<?php

namespace App\Controllers;

use Illuminate\Database\Query\Builder;
use App\Models\Users;
use App\Models\UserNetwork;
use App\Models\UsersRanking;
use App\Models\UserNotification;

class UserNetworkController {

    protected $ci;
    protected $_logger;
    protected $_db;
    protected $n;

    //Constructor
    public function __construct(\Slim\Container $ci) {
        $this->ci = $ci;
        $this->_logger = $this->ci->get('logger');
        $this->_db = $this->ci['db'];
    }

    public function addToUserNetwork($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $lastinserted = 0;
        $user = $this->_db->table('user_network')
                ->where('primary_user_id', $postData['primaryuserid'])
                ->where('network_user_id', $postData['networkuserid'])
                ->select('network_user_id')
                ->first();

        if (count($user) > 0) {

            $updateuser_network = $this->_db->table('user_network')
                    ->where('primary_user_id', $postData['primaryuserid'])
                    ->where('network_user_id', $postData['networkuserid'])
                    ->update(['association_type' => $postData['associationtype'], 'association_status_date' => date('Y-m-d H:i:s')]);
            $userNetwork = $this->_db->table('user_network')
                    ->where('primary_user_id', $postData['primaryuserid'])
                    ->where('network_user_id', $postData['networkuserid'])
                    ->get();
            $result['success'] = 1;
            $netnew = array();
            foreach ($userNetwork as $net) {
                if (isset($net->association_status_date))
                    $net->association_status_date = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->association_status_date)));
                if (isset($net->created_at))
                    $net->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->created_at)));
                if (isset($net->updated_at))
                    $net->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->updated_at)));
                array_push($netnew, $net);
            }
            $result['networkuserinfo'] = $netnew;
        } else {
            try {
                $userNetwork = new UserNetwork;

                $userNetwork->primary_user_id = (isset($postData['primaryuserid'])) ? $postData['primaryuserid'] : 0;
                $userNetwork->network_user_id = (isset($postData['networkuserid'])) ? $postData['networkuserid'] : 0;
                $userNetwork->association_type = (isset($postData['associationtype'])) ? $postData['associationtype'] : '';
                $userNetwork->network_status = 'Invited';
                $userNetwork->association_status_date = date("Y-m-d H:i:s");
                $userNetwork->network_initiated_by = $postData['primaryuserid'];
                $userNetwork->save();
                $lastinserted = $userNetwork->id;

                $result['success'] = 1;

                $userNetworknew = $this->_db->table('user_network')
                        ->where('user_network_id', $lastinserted)
                        ->get();
                $netnew = array();

                foreach ($userNetworknew as $net) {
                    if (isset($net->association_status_date))
                        $net->association_status_date = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->association_status_date)));
                    if (isset($net->created_at))
                        $net->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->created_at)));
                    if (isset($net->updated_at))
                        $net->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->updated_at)));
                    array_push($netnew, $net);
                }
                $result['networkuserinfo'] = $netnew;

                ////Add User Notification
                /*  $userNotification = new UserNotification;

                  $userNotification->from_user_id = (isset($postData['primaryuserid'])) ? $postData['primaryuserid'] : 0;
                  $userNotification->to_user_id = (isset($postData['networkuserid'])) ? $postData['networkuserid'] : 0;

                  $primaryuserid = (isset($postData['primaryuserid'])) ? $postData['primaryuserid'] : 0;
                  $networkuserid = (isset($postData['networkuserid'])) ? $postData['networkuserid'] : 0;
                  $fromuser = $this->getUserName($primaryuserid);
                  $touser = $this->getUserName($networkuserid);


                  $userNotification->network_id = $lastinserted;

                  $userNotification->comment_text = $fromuser . ' wants to follow you';
                  $userNotification->notification_type = 'followrequest';
                  $userNotification->status = 'unread';
                  $userNotification->created_at = date("Y-m-d H:i:s");
                  $userNotification->updated_at = null;
                  $userNotification->save();
                  $lastNotifyId = $userNotification->id;

                  $getLatestNotification = $this->_db->table('user_notifications')
                  ->where('user_notification_id', $lastNotifyId)
                  ->get();
                  $Notifydata = $getLatestNotification[0];
                  $devInfo = $this->getDeviceInfo($networkuserid);
                  $contentmsg = $fromuser . ' wants to follow you';
                  if (count($devInfo) > 0) {
                  foreach ($devInfo as $dev123) {
                  if (isset($dev123->one_signal_userid)) {
                  $one_signal_userid = trim($dev123->one_signal_userid);
                  $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $networkuserid);
                  }
                  }
                  } */
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }

        //for base ratio:
        $followers_query = $this->_db->table('user_network')
                //->where('network_initiated_by', $postData['networkuserid'])   //05 Dec.2017
                //->where('network_user_id', '!=', $postData['networkuserid'])  //05 Dec.2017 
                ->where('network_user_id',  $postData['primaryuserid'])
                ->where('network_status', 'Accepted')
                ->get();
        $followers = count($followers_query);

        $following_query = $this->_db->table('user_network')
                ->where('network_user_id', $postData['networkuserid'])
                ->get();
        $following = count($following_query);

        if ($following == 0) {
            $following = 1;
        }
        $base_ratio = $followers / $following;

        $check = $this->_db->table('user_ranking')
                ->where('user_id', $postData['primaryuserid'])
                ->get();

        if (count($check) == 0) {
            try {
                $userRank = new UsersRanking;

                $userRank->user_id = $postData['primaryuserid'];
                $userRank->base_ratio = $base_ratio;
                $userRank->save();
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $userRankUpdate = $this->_db->table('user_ranking')
                    ->where('user_id', $postData['primaryuserid'])
                    ->update([
                'base_ratio' => $base_ratio]);
        }
        $this->usersRank($request, $response);
        return $response->withJson($result);
    }

    public function networkRequestList($request, $response) {
        $postData = $request->getParsedBody();
        $result['success'] = 0;
        //check if the user network already exists
        $userNetworkDefination = $this->_db->table('user_network')
                ->where('network_user_id', $postData['networkuserid'])
                ->where('network_status', 'Invited')
                ->select('primary_user_id')
                ->get();
        if (isset($userNetworkDefination) && count($userNetworkDefination) >= 1) {
            foreach ($userNetworkDefination as $requestList) {
                $user_id = $requestList->primary_user_id;

                $requestListData = $this->_db->table('users')
                        ->where('user_id', $user_id)
                        ->get();
            }
            $netnew = array();
            foreach ($requestListData as $net) {
                if (isset($net->otp_datetime))
                    $net->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->otp_datetime)));
                if (isset($net->created_at))
                    $net->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->created_at)));
                if (isset($net->updated_at))
                    $net->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($net->updated_at)));
                array_push($netnew, $net);
            }
            $result['success'] = 1;
            $result['networkuserinfo'] = $netnew;
        }

        return $response->withJson($result);
    }

    public function changeNetworkStatus($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        if ($postData['status'] == 2) {
            $postData['status'] = 'Accepted';
        } elseif ($postData['status'] == -1) {
            $postData['status'] = 'Rejected';
        } else {
            $postData['status'] = 'Invited';
        }
        //check if the user network already exists
        $userNetworkDefination = $this->_db->table('user_network')
                ->where('network_user_id', $postData['primaryuserid'])
                ->where('primary_user_id', $postData['networkuserid'])
                ->first();

        if (isset($userNetworkDefination) && count($userNetworkDefination) >= 1) {
            try {
                if ($postData['status'] != 'Accepted') {
                    $userNetworkUpdate = $this->_db->table('user_network')
                            ->where('network_user_id', $postData['primaryuserid'])
                            ->where('primary_user_id', $postData['networkuserid'])
                            ->update([
                        'network_status' => $postData['status'], 'association_type' => 'unfollow',
                        'association_status_date' => date("Y-m-d H:i:s"),
                    ]);
                } else {
                    $userNetworkUpdate = $this->_db->table('user_network')
                            ->where('network_user_id', $postData['primaryuserid'])
                            ->where('primary_user_id', $postData['networkuserid'])
                            ->update([
                        'network_status' => $postData['status'], 'association_type' => 'follow',
                        'association_status_date' => date("Y-m-d H:i:s"),
                    ]);
                }
                ////Add User Notification
                if ($postData['status'] == 2) {
                    $userNotification = new UserNotification;

                    $userNotification->from_user_id = (isset($postData['primaryuserid'])) ? $postData['primaryuserid'] : 0;
                    $userNotification->to_user_id = (isset($postData['networkuserid'])) ? $postData['networkuserid'] : 0;

                    $primaryuserid = (isset($postData['primaryuserid'])) ? $postData['primaryuserid'] : 0;
                    $networkuserid = (isset($postData['networkuserid'])) ? $postData['networkuserid'] : 0;
                    $fromuser = $this->getUserName($primaryuserid);
                    $touser = $this->getUserName($networkuserid);

                    $userNotification->network_id = isset($userNetworkDefination->user_network_id) ? $userNetworkDefination->user_network_id : 0;
                    if ($postData['status'] == 'Accepted')
                        $st = "followaccepted";
                    if ($postData['status'] == 'Rejected')
                        $st = "followrejected";
                    if ($postData['status'] == 'Invited')
                        $st = "followrequest";
                    $userNotification->comment_text = $fromuser . ' has ' . $postData['status'] . ' follow request';
                    $userNotification->notification_type = $st;
                    $userNotification->status = 'processed';
                    $userNotification->created_at = date("Y-m-d H:i:s");
                    $userNotification->updated_at = null;

                    $userNotification->save();

                    $lastNotifyId = $userNotification->id;

                    $getLatestNotification = $this->_db->table('user_notifications')
                            ->where('user_notification_id', $lastNotifyId)
                            ->get();
                    $Notifydata = $getLatestNotification[0];
                    $devInfo = $this->getDeviceInfo($networkuserid);
                    $contentmsg = $fromuser . ' has ' . $postData['status'] . ' follow request';
                    if (count($devInfo) > 0) {
                        foreach ($devInfo as $dev123) {
                            if (isset($dev123->one_signal_userid)) {
                                $one_signal_userid = trim($dev123->one_signal_userid);
                                $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $networkuserid);
                            }
                        }
                    }
                }
                $result['success'] = 1;
                $result['message'] = "User Network Update Successfully!";
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "User Network not exists!";
        }
        return $response->withJson($result);
    }

    public function getMyFollows($request, $response) {
        $postData = $request->getParsedBody();
        $userId = (isset($postData['userid'])) ? $postData['userid'] : 0;
        $myFollowingsarr = $myFollowersarr = array();
        $myFollowers = $this->_db->table('user_network')
                ->where('user_network.network_user_id', $userId)
                ->where('user_network.network_status', 'Accepted')
                // ->where('user_network.association_type', 'follow')
                ->orderBy('user_network.user_network_id', 'desc')
                ->limit(200, 0)
                ->get();
        $myFollowings = $this->_db->table('user_network')
                ->where('user_network.primary_user_id', $userId)
                ->where('user_network.network_status', 'Accepted')
                ->where('user_network.association_type', 'follow')
                ->orderBy('user_network.user_network_id', 'desc')
                ->limit(200, 0)
                ->get();
        if (count($myFollowings) > 0) {
            $i = 0;
            $full_name_arr = array();
            foreach ($myFollowings as $us) {
                $full_name = '';
                $userdet = $this->getUserdetails($us->network_user_id);
                if (!empty($userdet)) {
                    $userdet->user_avatar = isset($userdet->user_avatar) ? $userdet->user_avatar : "https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png";
                    if (isset($userdet->created_at))
                        $userdet->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($userdet->created_at)));
                    if (isset($userdet->last_usage))
                        $userdet->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($userdet->last_usage)));
                    $userdet->association_type = 'follow';
                    if (isset($userdet->first_name)) {
                        if (isset($userdet->last_name)) {
                            $full_name = $userdet->first_name . ' ' . $userdet->last_name;
                        } else
                            $full_name = $userdet->first_name;
                    }
                    else if (isset($userdet->last_name)) {
                        $full_name = $userdet->last_name;
                    } else
                        $full_name = '';

                    $full_name_arr[$i] = $full_name; 
                    $i++;
                    array_push($myFollowingsarr, $userdet);
                }
            }
             array_multisort($full_name_arr, SORT_ASC, $myFollowingsarr);
        } else
            $myFollowingsarr = 0;
        if (count($myFollowers) > 0) {
            $i = 0;
            $full_name_arr123 = array();
            foreach ($myFollowers as $us) {
                $full_name = '';

                $userdet123 = $this->getUserdetails($us->primary_user_id);
                if (!empty($userdet123)) {
                    $userdet123->user_avatar = isset($userdet123->user_avatar) ? $userdet123->user_avatar : "https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png";
                    if (isset($userdet123->created_at))
                        $userdet123->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($userdet123->created_at)));
                    if (isset($userdet123->last_usage))
                        $userdet123->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($userdet123->last_usage)));

                    $isFollowingTo = $this->isFollowingTo($us->primary_user_id, $userId);
                    $userdet123->association_type = $isFollowingTo;
                    if (isset($userdet123->first_name)) {
                        if (isset($userdet123->last_name)) {
                            $full_name = $userdet123->first_name . ' ' . $userdet123->last_name;
                        } else
                            $full_name = $userdet123->first_name;
                    }
                    else if (isset($userdet123->last_name)) {
                        $full_name = $userdet123->last_name;
                    } else
                        $full_name = '';

                    $full_name_arr123[$i] = $full_name;
                    array_push($myFollowersarr, $userdet123);
                    $i++;
                }
            }
            array_multisort($full_name_arr123, SORT_ASC, $myFollowersarr);
        } else
            $myFollowersarr = 0;
        $result['success'] = 1;
        $result['myFollowing'] = $myFollowingsarr;
        $result['myFollowers'] = $myFollowersarr;

        return $response->withJson($result);
    }

    public function isFollowingTo($networkid, $user_id) {
        $isFollowingTo = $this->_db->table('user_network')
                ->where('user_network.primary_user_id', $user_id)
                ->where('user_network.network_user_id', $networkid)
                ->where('user_network.association_type', 'follow')
                ->where('user_network.network_status', 'Accepted')
                ->get();
        if (count($isFollowingTo) > 0)
            return 'follow';
        else
            return 0;
    }

    /* // Commented on 05 Dec.2017
        public function usersRank($request, $response) {
        $postData = $request->getParsedBody();
        $n = $this->n;
        $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
        $to_date = date('Y-m-d' . ' 22:00:40', time());
        $userrank = $this->_db->table('user_ranking')
                ->whereBetween('created_at', [$from_date, $to_date])
                ->get();
        $user_id = 0;
        $base_ratio = 0;
        $hate_ratio = 0;
        $user_ratio = 0;
        $recent_posts_count = 0;
        $recent_share_count = 0;
        $recent_comment_count = 0;
        $tempo_user_rank = 0;

        foreach ($userrank as $rankval) {
            $user_id = $rankval->user_id;
            $base_ratio = $rankval->base_ratio;
            $hate_ratio = $rankval->hate_ratio;
            $user_ratio = ($base_ratio) - ($hate_ratio);
            $recent_posts_count = $rankval->recent_posts_count;
            $recent_share_count = $rankval->recent_share_count;
            $recent_comment_count = $rankval->recent_comment_count;
            $tempo_user_rank = abs($user_ratio) * ($recent_posts_count + $recent_share_count * 2 + $recent_comment_count * 4);

            $updateUserRank = $this->_db->table('users')
                    ->where('user_id', $user_id)
                    ->update([
                'tempo_user_rank' => $tempo_user_rank]);
        }
    }*/
    public function usersRank($request, $response) {
        $new_date_frm_cr = $new_date_frm_up = '';
        $n = $this->n;
        $from_date = date('Ymd' . ' 00:00:00', strtotime('-' . $n . ' days'));
        $to_date = date('Ymd' . ' 22:00:40', time());
        $userrank = $this->_db->table('user_ranking')
                ->get();
        $arrUserank = array();
        foreach ($userrank as $r) {
            if (isset($r->updated_at)) {
                $new_date_frm_up = date('Ymd', strtotime($r->updated_at));
                if ($from_date < $new_date_frm_up && $new_date_frm_up <= $to_date) {
                    array_push($arrUserank, $r);
                }
            } else {
                $new_date_frm_cr = date('Ymd', strtotime($r->created_at));
                if ($from_date < $new_date_frm_cr && $new_date_frm_cr <= $to_date) {
                    array_push($arrUserank, $r);
                }
            }
        } 
        $user_id = 0;
        $base_ratio = 0;
        $hate_ratio = 0;
        $user_ratio = 0;
        $recent_posts_count = 0;
        $recent_share_count = 0;
        $recent_comment_count = 0;
        $tempo_user_rank = 0;

        for ($i = 0; $i < count($arrUserank); $i++) {
            if ($arrUserank[$i]->user_id ) {
                $user_id = $arrUserank[$i]->user_id;
                $base_ratio = $arrUserank[$i]->base_ratio;
                $hate_ratio = $arrUserank[$i]->hate_ratio;
                $user_ratio = ($base_ratio) - ($hate_ratio);
                $recent_posts_count = $arrUserank[$i]->recent_posts_count;
                $recent_share_count = $arrUserank[$i]->recent_share_count;
                $recent_comment_count = $arrUserank[$i]->recent_comment_count;
//                var_dump($user_ratio); 
//                var_dump($recent_posts_count);
//                var_dump($recent_share_count);
//                var_dump($recent_comment_count);
                $tempo_user_rank = abs($user_ratio) * ($recent_posts_count + $recent_share_count * 2 + $recent_comment_count * 4);
                
                if (isset($user_id)) {
                    $updateUserRank = $this->_db->table('users')
                            ->where('user_id', $user_id)
                            ->update([
                        'tempo_user_rank' => $tempo_user_rank]);
                    // echo $user_id." = ".($recent_posts_count + $recent_share_count * 2 + $recent_comment_count * 4)."<br>";
                }
            }
        }
    }
    public function getUserName($userid) {
        $name = "";
        if (isset($userid)) {
            $users = $this->_db->table('users')
                    ->select('first_name', 'last_name')
                    ->where('user_id', $userid)
                    ->get();
            if (isset($users[0]->first_name) && isset($users[0]->last_name)) {
                $name = $users[0]->first_name . ' ' . $users[0]->last_name;
            } else if (isset($users[0]->first_name)) {
                $name = $users[0]->first_name;
            } else if (isset($users[0]->last_name)) {
                $name = $users[0]->last_name;
            }
        }
        return $name;
    }

    public function getDeviceInfo($user_id) {
        $pos = strpos($user_id, ',');
        $devarr = array();
        if ($pos == 1) {
            $user_id_arr = explode(',', $user_id);
            foreach ($user_id_arr as $us) {
                $existsUserDevice = $this->_db->table('user_devices')
                        ->where('user_id', $us)
                        ->get();

                if (count($existsUserDevice) > 0) {
                    foreach ($existsUserDevice as $dev) {
                        array_push($devarr, $dev);
                    }
                }
            }
        } else {
            $existsUserDevice = $this->_db->table('user_devices')
                    ->where('user_id', $user_id)
                    ->get();
            if (count($existsUserDevice) > 0) {
                foreach ($existsUserDevice as $dev) {
                    array_push($devarr, $dev);
                }
            }
            return $devarr;
        }
        return $devarr;
    }

    function sendOneSignalNotify($onesingaluseridarr, $data, $contentmsg, $userid = 0) {
        $user_IsNotification = 1;
        if ($userid != 0) {
            $user123 = $this->_db->table('users')
                    ->select('IsNotification')
                    ->where('user_id', $userid)
                    ->get();
            if (isset($user123[0])) {
                $user_IsNotification = trim($user123[0]->IsNotification);
            }
        }
        if ($user_IsNotification == 1) {
            $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
            include($public_path . 'config_s3.php');

            $content = array(
                "en" => $contentmsg
            );

            $fields = array(
                'app_id' => $ONESIGNAL_APP_ID,
                'include_player_ids' => array($onesingaluseridarr),
                'data' => $data,
                'contents' => $content
            );

            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . $ONESIGNAL_REST_API_KEY));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }
    }

    public function getUserdetails($user_id) {
        $details = $this->_db->table('users')
                ->select('users.user_id', 'users.user_name', 'users.first_name', 'users.last_name', 'users.display_name', 'users.user_avatar', 'users.created_at', 'users.last_usage', 'users.tempo_user_rank')
                ->where(array('users.user_id' => $user_id))
                ->get();
        $arr = array();
        if (count($details) > 0) {
            $arr = $details[0];
        }
        return $arr;
    }

}
