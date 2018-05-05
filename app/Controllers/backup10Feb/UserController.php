<?php

namespace App\Controllers;

use Illuminate\Database\Query\Builder;
use App\Models\Users;
use App\Models\UserPost;
use App\Models\UserPostComments;
use App\Models\UserPostsTo;
use App\Models\UsersRanking;
use App\Models\EventPost;
use App\Models\UserDevices;
use App\Models\UserPostNotification;
use App\Models\UserpostMediaLike;
use App\Models\TwitterAPIExchange;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class UserController {

    protected $ci;
    protected $_logger;
    protected $_db;
    protected $n;

    //Constructor
    public function __construct(\Slim\Container $ci) {
        $this->ci = $ci;
        $this->view = $ci->view;
        $this->_logger = $this->ci->get('logger');
        $this->_db = $this->ci['db'];
        $this->n = $this->ci->get('n');
    }

    public function creatUser($request, $response) {
        $postData = $request->getParsedBody();
        $error = false;
        date_default_timezone_set('UTC');
        $error_fields = "";
        $userUniqId = "";
        $already = "";
        $alreadyTwitter = 0;

        if (isset($postData['usertype']) and ! empty($postData['usertype'])) {

            if ($postData['usertype'] == 'tempo') {
                if (isset($postData['email']) and ! empty($postData['email'])) {
                    $chkemail = $this->_db->table('users')
                            ->Where('user_email', $postData['email'])
                            ->Where('user_type', 'tempo')
                            ->get();
                    if (isset($chkemail) && count($chkemail) >= 1) {
                        $already .= 'email, ';
                    } else {
                        $userUniqId = 't-' . md5($postData['email']);
                    }
                } else {
                    $error = true;
                    $error_fields .= 'email, ';
                }
            } else {
                $userUniqId = trim($postData['useruniqueid']);
                if ($postData['usertype'] == 'twitter') {
                    $chkusertwuniq = $this->_db->table('users')
                            ->Where('user_unique_id', $userUniqId)
                            ->Where('user_type', 'twitter')
                            ->get();
                    if (isset($chkusertwuniq) && count($chkusertwuniq) >= 1) {
                        $usernewuniq = array();
                        foreach ($chkusertwuniq as $us) {
                            if (isset($us->otp_datetime))
                                $us->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->otp_datetime)));
                            if (isset($us->created_at))
                                $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                            if (isset($us->last_usage))
                                $us->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->last_usage)));
                            array_push($usernewuniq, $us);
                        }
                        $result['success'] = 1;
                        $result['userinfo'] = $usernewuniq;
                        return $response->withJson($result);
                    }
                }
            }
        } else {
            $error = true;
            $error_fields .= 'usertype, ';
        }
        if (isset($postData['username']) and ! empty($postData['username'])) {
            $otp = $this->randomKey(6);
            if ($postData['usertype'] == 'tempo') {
                $chkusername = $this->_db->table('users')
                        ->Where('user_name', $postData['username'])
                        ->Where('user_type', 'tempo')
                        ->get();
                if (isset($postData['phone']) and ! empty($postData['phone'])) {
                    $chkphone = $this->_db->table('users')
                            ->Where('user_phone', $postData['phone'])
                            ->Where('user_type', 'tempo')
                            ->get();
                    if (isset($chkphone) && count($chkphone) >= 1) {
                        $already .= 'phone, ';
                    }
                }
            } else if ($postData['usertype'] == 'twitter') {
                $chkusername = $this->_db->table('users')
                        ->Where('user_name', $postData['username'])
                        ->Where('user_type', 'twitter')
                        ->get();
                if (isset($chkusername) && count($chkusername) >= 1) {
                    $usernew = array();
                    foreach ($chkusername as $us) {
                        if (isset($us->otp_datetime))
                            $us->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->otp_datetime)));
                        if (isset($us->created_at))
                            $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                        if (isset($us->last_usage))
                            $us->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->last_usage)));
                        array_push($usernew, $us);
                    }
                    $result['success'] = 1;
                    $result['userinfo'] = $usernew;
                    return $response->withJson($result);
                }
            } else {
                $chkusername = $this->_db->table('users')
                        ->Where('user_name', $postData['username'])
                        ->get();
            }
            if (isset($chkusername) && count($chkusername) >= 1) {
                $already .= 'username, ';
            }
        } else {
            $error = true;
            $error_fields .= 'username, ';
        }
        if (isset($error) and ! empty($error)) {
            // Required field(s) are missing or empty
            $result['success'] = 0;
            if ($alreadyTwitter == 0)
                $result['message'] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            else {
                
            }
        } elseif (isset($already) and ! empty($already)) {
            $result['success'] = 0;
            $result['message'] = substr($already, 0, -2) . " already exists!";
        } else {
            try {
                if (isset($userUniqId) and ! empty($userUniqId)) {
                    $user = new Users;
                    $user->user_name = (isset($postData['username'])) ? $postData['username'] : '';
                    $user->first_name = (isset($postData['firstname'])) ? $postData['firstname'] : '';
                    $user->last_name = (isset($postData['lastname'])) ? $postData['lastname'] : '';
                    $user->user_password = (isset($postData['password'])) ? $postData['password'] : '';
                    $user->display_name = (isset($postData['displayname'])) ? $postData['displayname'] : '';
                    $user->user_email = (isset($postData['email'])) ? $postData['email'] : '';
                    $user->user_phone = (isset($postData['phone'])) ? $postData['phone'] : 0;
                    $user->user_dob = (isset($postData['dob'])) ? $postData['dob'] : null;
                    $user->user_gender = (isset($postData['gender'])) ? $postData['gender'] : '';
                    $user->user_address = (isset($postData['address'])) ? $postData['address'] : '';
                    $user->user_city = (isset($postData['city'])) ? $postData['city'] : '';
                    $user->user_state = (isset($postData['state'])) ? $postData['state'] : '';
                    $user->user_zipcode = (isset($postData['zipcode'])) ? $postData['zipcode'] : '';
                    $user->user_country = (isset($postData['country'])) ? $postData['country'] : '';
                    $user->user_avatar = (isset($postData['avatar'])) ? $postData['avatar'] : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                    $user->user_unique_id = $userUniqId;
                    $user->user_created_from = (isset($postData['createdfrom'])) ? $postData['createdfrom'] : '';
                    $user->user_type = (isset($postData['usertype'])) ? $postData['usertype'] : '';
                    $user->account_type = (isset($postData['accounttype'])) ? $postData['accounttype'] : '';
                    $user->IsNotification = 1;
                    $user->otp = $otp;
                    $user->otp_datetime = date("Y-m-d H:i:s");
                    $user->last_usage = date("Y-m-d H:i:s");
                    $user->created_at = date("Y-m-d H:i:s");
                    $user->updated_at = null;
                    $user->save();
                    $lastinserted = $user->id;
                    $user = $this->_db->table('users')
                            ->where('user_id', $lastinserted)
                            ->get();
                    $usernew = array();
                    foreach ($user as $us) {
                        if (isset($us->otp_datetime))
                            $us->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->otp_datetime)));
                        if (isset($us->created_at))
                            $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                        if (isset($us->last_usage))
                            $us->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->last_usage)));
                        if (isset($us->otp_datetime))
                            $us->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->otp_datetime)));
                        array_push($usernew, $us);
                    }
                    $result['success'] = 1;
                    $result['userinfo'] = $usernew;
                    if ($postData['usertype'] == 'tempo') {
                        if (isset($postData['firstname']) && isset($postData['lastname']))
                            $name = ucfirst(trim($postData['firstname'])) . ' ' . ucfirst(trim($postData['lastname']));
                        if (isset($postData['firstname']))
                            $name = ucfirst(trim($postData['firstname']));
                        if (isset($postData['lastname']))
                            $name = ucfirst(trim($postData['lastname']));
                        $data = array('email' => trim($postData['email']), 'name' => $name,
                            'subject' => trim("OTP from Tempo"), 'otp' => trim($otp),);
                        $hostAddr = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
                        $url = $hostAddr . "/mailsetting/mail.php";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $output = curl_exec($ch);
                        curl_close($ch);
                        $result['message'] = 'OTP has been sent to your Email.';
                        $smsarr = array();
                        $smsarr['msg'] = 'Your OTP for Tempo is ' . trim($otp);
                        //$smsarr['to'] = (isset($postData['phone'])) ? $postData['phone'] : '';
                        if (isset($postData['phone'])) {
                            if (strpos($postData['phone'], "+1") == false)
                                $postData['phone'] = "+1" . $postData['phone'];
                            $smsarr['to'] = $postData['phone'];
                        } else
                            $smsarr['to'] = '';
                        $smsText = $this->SendSms($smsarr);
                    }
                }
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }
        return $response->withJson($result);
    }

    public function loginUser($request, $response) {
        $postData = $request->getParsedBody();
        require __DIR__ . "/../../vendor/encdec/Aes.class.inc.php";
        require __DIR__ . "/../../vendor/encdec/AesCtr.class.inc.php";
        $AesCtr = new \AesCtr();
        $encryption_key = 'sdwnmt';
        $userpr = trim($postData['password']);
        $usertype = trim($postData['usertype']);
        if ($usertype == '') {
            $result['success'] = 0;
            $result['message'] = "Missing parameters";
            return $response->withJson($result);
        }
        // echo $userpr;
        //$userpr_encr = $AesCtr->encrypt($userpr, $encryption_key, 128);
        //$userpr_encr = base64_encode($userpr);
        //$postData['password'] = $userpr_encr;
        $userpr_dec = $AesCtr->decrypt($userpr, $encryption_key, 128);
        // echo "userpr_dec == ".$userpr_dec;  
        //check  user  exists
        $user = $this->_db->table('users')
                ->where('user_name', $postData['username'])
                ->where('user_type', $usertype)
                ->first();

        if (isset($user) && count($user) >= 1) {
            $dbpass_dec = $AesCtr->decrypt(trim($user->user_password), $encryption_key, 128);
            //  echo "dbpass_dec == ".$dbpass_dec; exit;
            //$userpr_dec = $this->cleanstring($userpr_dec);
            // $dbpass_dec = $this->cleanstring($dbpass_dec);
            if (trim($userpr_dec) === trim($dbpass_dec)) {
                if (isset($user->last_usage))
                    $user->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($user->last_usage)));
                if (isset($user->created_at))
                    $user->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($user->created_at)));
                if (isset($user->updated_at))
                    $user->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($user->updated_at)));
                if (isset($user->otp_datetime))
                    $user->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($user->otp_datetime)));

                $result['success'] = 1;
                $result['userInfo'] = ['user_id' => $user->user_id,
                    'user_name' => $user->user_name,
                    'display_name' => $user->display_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'user_dob' => $user->user_dob,
                    'user_email' => $user->user_email,
                    'user_phone' => $user->user_phone,
                    'user_gender' => $user->user_gender,
                    'user_address' => $user->user_address,
                    'user_city' => $user->user_city,
                    'user_state' => $user->user_state,
                    'user_country' => $user->user_country,
                    'user_zipcode' => $user->user_zipcode,
                    'user_avatar' => $user->user_avatar,
                    'user_unique_id' => $user->user_unique_id,
                    'user_created_from' => $user->user_created_from,
                    'user_type' => $user->user_type,
                    'account_type' => $user->account_type,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'last_usage' => $user->last_usage,
                    'otp' => $user->otp,
                    'IsNotification' => $user->IsNotification,
                    'otp_datetime' => $user->otp_datetime,
                    'tempo_user_rank' => $user->tempo_user_rank,
                ];
            } else {
                $result['success'] = 0;
                $result['message'] = "Invalid Password!";
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "Invalid username or password!";
        }
        return $response->withJson($result);
    }

    public function verfyUserName($request, $response) {
        $postData = $request->getParsedBody();
        //check  username exists
        $user = $this->_db->table('users')
                ->where('user_name', $postData['username'])
                ->first();


        if (isset($user) && count($user) >= 1) {
            $result['success'] = 1;
            $result['message'] = "Username not available.";
        } else {
            $result['success'] = 0;
            $result['message'] = "Username available.";
        }
        return $response->withJson($result);
    }

    public function verfyUserEmail($request, $response) {
        $postData = $request->getParsedBody();
        //check  username exists
        $user = $this->_db->table('users')
                ->where('user_email', $postData['email'])
                ->first();

        if (isset($user) && count($user) >= 1) {
            $result['success'] = 1;
            $result['message'] = "User already exists with this email.";
        } else {
            $result['success'] = 0;
            $result['message'] = "No user registered with this email.";
        }
        return $response->withJson($result);
    }

    public function resendOTP($request, $response) {
        $postData = $request->getParsedBody();
        $otp = $this->randomKey(6);
        $user = $this->_db->table('users')
                ->where('user_id', $postData['userid'])
                ->pluck('otp')
                ->first();
        if (isset($user) && count($user) >= 1) {
            $this->_db->table('users')
                    ->where('user_id', $postData['userid'])
                    ->update(['otp' => $otp]);
            $user = $this->_db->table('users')
                    ->where('user_id', $postData['userid'])
                    //->pluck('otp')
                    ->first();
            $result['success'] = 1;
            if (isset($user->first_name) && isset($user->last_name))
                $name = ucfirst(trim($user->first_name)) . ' ' . ucfirst(trim($user->last_name));
            if (isset($user->first_name))
                $name = ucfirst(trim($user->first_name));
            if (isset($user->last_name))
                $name = ucfirst(trim($user->last_name));
            if (isset($user->otp))
                $otp = trim($user->otp);

            $result['message'] = 'OTP has been sent to your Email.';
            $result['OTP'] = $otp;
            $data = array('email' => trim($user->user_email), 'name' => $name,
                'subject' => trim("OTP from Tempo"), 'otp' => trim($otp),);
            $hostAddr = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
            $url = $hostAddr . "/mailsetting/mail.php";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $output = curl_exec($ch);
            curl_close($ch);
        } else {
            $result['success'] = 0;
            $result['message'] = 'user not exists!';
        }
        return $response->withJson($result);
    }

    public function verifyOTP($request, $response) {
        $postData = $request->getParsedBody();
        $user = $this->_db->table('users')
                ->where('user_id', $postData['userid'])
                ->where('otp', $postData['otp'])
                ->get();
        if (isset($user) && count($user) >= 1) {
            $result['success'] = 1;
        } else {
            $result['success'] = 0;
        }
        return $response->withJson($result);
    }

    public function searchUser($request, $response) {
        $postData = $request->getParsedBody();
        $result['success'] = 0;
        $search = (isset($postData['search'])) ? $postData['search'] : '';
        if (isset($postData['userid'])) {
            $userId = $postData['userid'];
        }

        $searchscope = (isset($postData['searchscope'])) ? $postData['searchscope'] : 0;

        if (isset($search) and ! empty($search)) {
            if (isset($userId) and ! empty($userId)) {

                if ($searchscope == 1) {

                    $user = $this->_db->table('user_network')
                            ->where('primary_user_id', $postData['userid'])
                            ->where('network_status', 'Accepted')
                            ->select('user_network.network_user_id')
                            ->get();

                    if (isset($user) && count($user) >= 1) {
                        foreach ($user as $networkUser) {
                            $network_user_id = $networkUser->network_user_id;

                            $networkUserData[] = $this->_db->table('users')
                                    ->leftJoin('user_network', 'users.user_id', '=', 'user_network.primary_user_id')
                                    ->where('user_id', $network_user_id)
                                    ->where('user_id', '!=', $postData['userid'])
                                    ->where(function($query) use ($search) {
                                        $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                                        $query->orWhere('first_name', 'LIKE', '%' . $search . '%');
                                        $query->orWhere('last_name', 'LIKE', '%' . $search . '%');
                                    })
                                    ->get();
                        }
                        $arr = array();
                        foreach ($networkUserData as $e) {
                            if (isset($e[0])) {
                                $arr1 = array();
                                $arr1['user_id'] = $e[0]->user_id;
                                $arr1['user_name'] = $e[0]->user_name;
                                $arr1['first_name'] = $e[0]->first_name;
                                $arr1['last_name'] = $e[0]->last_name;
                                $arr1['user_avatar'] = (isset($e[0]->user_avatar)) ? $e[0]->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                                $arr1['association_type'] = $this->getAssociationtype($e[0]->user_id, (int) $postData['userid']); //isset($e[0]->association_type) ? trim(strtolower($e[0]->association_type)) : 'invited';
                                array_push($arr, $arr1);
                            }
                        }
                        if (count($arr) > 0) {
                            $result['success'] = 1;
                            $result['userinfo'] = $arr;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = 'friends not found';
                        }
                    } else {
                        $result['success'] = 0;
                        $result['message'] = 'friends not found';
                    }
                } else if ($searchscope == 2) {

                    $networkUserData = $this->_db->table('users')
                            ->where('user_id', '!=', $postData['userid'])
                            ->where(function($query) use ($search) {
                                $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                                $query->orWhere('first_name', 'LIKE', '%' . $search . '%');
                                $query->orWhere('last_name', 'LIKE', '%' . $search . '%');
                            })
                            ->get();
                    $arr = array();
                    $id = 0;
                    foreach ($networkUserData as $e) {
                        $arr1 = array();
                        $arr1['user_id'] = $e->user_id;
                        $arr1['user_name'] = $e->user_name;
                        $arr1['first_name'] = $e->first_name;
                        $arr1['last_name'] = $e->last_name;
                        $arr1['user_avatar'] = (isset($e->user_avatar)) ? $e->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                        $arr1['association_type'] = $this->getAssociationtype($e->user_id, (int) $postData['userid']); // isset($e->association_type) ? trim(strtolower($e->association_type)) : 0;
                        array_push($arr, $arr1);
                    }
                    if (count($arr) > 0) {
                        $result['success'] = 1;
                        $result['userinfo'] = $arr;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = 'friends not found';
                    }
                } else {
                    $UserData = $this->_db->table('users')
                            ->where('user_id', '!=', $postData['userid'])
                            ->where(function($query) use ($search) {
                                $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                                $query->orWhere('first_name', 'LIKE', '%' . $search . '%');
                                $query->orWhere('last_name', 'LIKE', '%' . $search . '%');
                            })
                            ->get();
                    if (isset($UserData) && count($UserData) >= 1) {
                        $arr = array();
                        $id = 0;
                        foreach ($UserData as $e) {
                            if (isset($e)) {
                                if ($id != $e->user_id) {
                                    $arr1 = array();
                                    $arr1['user_id'] = $e->user_id;
                                    $arr1['user_name'] = $e->user_name;
                                    $arr1['first_name'] = $e->first_name;
                                    $arr1['last_name'] = $e->last_name;
                                    $arr1['user_avatar'] = (isset($e->user_avatar)) ? $e->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                                    $arr1['association_type'] = isset($e->association_type) ? trim(strtolower($e->association_type)) : 'invited';
                                    array_push($arr, $arr1);
                                    $id = $e->user_id;
                                }
                            }
                        }
                        $result['success'] = 1;
                        $result['userinfo'] = $arr;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = 'user not found.';
                    }
                }
            } else {
                $user = $this->_db->table('users')
                        ->where(function($query) use ($search) {
                            $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                            $query->orWhere('first_name', 'LIKE', '%' . $search . '%');
                            $query->orWhere('last_name', 'LIKE', '%' . $search . '%');
                        })
                        ->get();
                if (isset($user) && count($user) >= 1) {
                    $arr = array();
                    $id = 0;
                    foreach ($user as $e) {
                        if (isset($e)) {
                            if ($id != $e->user_id) {
                                $arr1 = array();
                                $arr1['user_id'] = $e->user_id;
                                $arr1['user_name'] = $e->user_name;
                                $arr1['first_name'] = $e->first_name;
                                $arr1['last_name'] = $e->last_name;
                                $arr1['user_avatar'] = (isset($e->user_avatar)) ? $e->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                                $arr1['association_type'] = isset($e->association_type) ? trim(strtolower($e->association_type)) : 'invited';
                                array_push($arr, $arr1);
                                $id = $e->user_id;
                            }
                        }
                    }

                    $result['success'] = 1;
                    $result['userinfo'] = $arr;
                } else {
                    $result['success'] = 0;
                }
            }
        } else {

            $userid = isset($postData['userid']) ? (int) $postData['userid'] : 0;
            if ($searchscope == 1) {

                $user = $this->_db->table('user_network')
                        ->where('primary_user_id', $postData['userid'])
                        ->where('network_status', 'Accepted')
                        ->where('association_type', 'follow')
                        ->select('user_network.network_user_id')
                        ->get();

                if (isset($user) && count($user) >= 1) {
                    foreach ($user as $networkUser) {
                        $network_user_id = $networkUser->network_user_id;

                        $networkUserData[] = $this->_db->table('users')
                                ->leftJoin('user_network', 'users.user_id', '=', 'user_network.primary_user_id')
                                ->where('user_id', $network_user_id)
                                ->where('user_id', '!=', $postData['userid'])
                                ->get();
                    }
                    $arr = array();
                    foreach ($networkUserData as $e) {
                        if (isset($e[0])) {
                            $arr1 = array();
                            $arr1['user_id'] = $e[0]->user_id;
                            $arr1['user_name'] = $e[0]->user_name;
                            $arr1['first_name'] = $e[0]->first_name;
                            $arr1['last_name'] = $e[0]->last_name;
                            $arr1['user_avatar'] = (isset($e[0]->user_avatar)) ? $e[0]->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                            $arr1['association_type'] = $this->getAssociationtype($e[0]->user_id, $userid); //isset($e[0]->association_type) ? trim(strtolower($e[0]->association_type)) : 0;
                            array_push($arr, $arr1);
                        }
                    }
                    if (count($arr) > 0) {
                        $result['success'] = 1;
                        $result['userinfo'] = $arr;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = 'friends not found';
                    }
                } else {
                    $result['success'] = 0;
                    $result['message'] = 'friends not found';
                }
            } else if ($searchscope == 2) {

                if ($userid == 0) {
                    $networkUserData = $this->_db->table('users')->get();
                } else {
                    $networkUserData = $this->_db->table('users')
                            //->leftJoin('user_network', 'users.user_id', '=', 'user_network.primary_user_id')
                            ->where('user_id', '!=', $postData['userid'])
                            ->get();
                }
                $arr = array();
                $id = 0;
                $k = 0;
                foreach ($networkUserData as $e) {
                    $arr1 = array();
                    $arr1['user_id'] = $e->user_id;
                    $arr1['user_name'] = $e->user_name;
                    $arr1['first_name'] = $e->first_name;
                    $arr1['last_name'] = $e->last_name;
                    $arr1['user_avatar'] = (isset($e->user_avatar)) ? $e->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';
                    $arr1['association_type'] = $this->getAssociationtype($e->user_id, (int) $postData['userid']); // isset($e->association_type) ? trim(strtolower($e->association_type)) : 0;
                    array_push($arr, $arr1);
                }

                if (count($arr) > 0) {
                    $result['success'] = 1;
                    $result['userinfo'] = $arr;
                } else {
                    $result['success'] = 0;
                    $result['message'] = 'No user Found';
                }
            }
        }
        return $response->withJson($result);
    }

    /* public function uploadAvatar($request, $response) {
      $postData = $request->getParsedBody();
      $basePath = $request->getUri()->getBasePath();
      $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
      include($public_path . 'config_s3.php');
      if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
      $files = $_FILES['avatar'];
      $file_name_expensions = explode('.', $files['name']);
      $imgname = $postData['userid'] . '.' . $file_name_expensions[1];
      $imagepath = $basePath . 'avatar/';
      if (move_uploaded_file($files['tmp_name'], $imagepath . $imgname)) {
      $certificate_newname = $imgname;

      $tmp = $files['tmp_name'];
      $contentType = $files['type'];
      $uploadedimg = $awsS3Url . "tempoevent/" . $this->awsupload($certificate_newname, $tmp, $contentType, "avatar");
      if (isset($postData['userid'])) {
      $userDefination = $this->_db->table('users')
      ->where('user_id', $postData['userid'])
      ->update(['user_avatar' => $uploadedimg]);
      }
      $result['success'] = 1;
      // $result['imagepath'] = $imagepath . $imgname;
      $result['imagepath'] = $uploadedimg;
      } else {
      $result['success'] = 0;
      $result['message'] = "Image Upload failed";
      }
      return $response->withJson($result);
      }
      } */

    public function uploadAvatar($request, $response) {
        $postData = $request->getParsedBody();
        $basePath = $request->getUri()->getBasePath();
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');

        if ($_FILES['avatar']['error'] === 0) {
            $files = $_FILES['avatar'];
            $newfilename = time() + rand(1, 999);
            $imgname = $postData['userid'] . '_' . $newfilename;
            $certificate_ext = pathinfo($files['name'], PATHINFO_EXTENSION);
            $certificate_newname = $imgname . '.' . $certificate_ext;

            $tmp = $files['tmp_name'];
            $contentType = $files['type'];
            $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($certificate_newname, $tmp, $contentType, "avatar");

            if ($uploadedimg) {
                if (isset($postData['userid'])) {
                    $userDefination = $this->_db->table('users')
                            ->where('user_id', $postData['userid'])
                            ->update(['user_avatar' => $uploadedimg]);
                }
                $result['success'] = 1;
                $result['imagepath'] = $uploadedimg;
            } else {
                $result['success'] = 0;
                $result['message'] = "Image Upload failed";
            }
            /* $imagepath = $basePath . 'avatar/';
              if (move_uploaded_file($files['tmp_name'], $imagepath . $imgname)) {
              if (isset($postData['userid'])) {
              $userDefination = $this->_db->table('users')
              ->where('user_id', $postData['userid'])
              ->update(['user_avatar' => $imgname]);
              }
              $result['success'] = 1;
              $result['imagepath'] = $imagepath . $imgname;
              } else {
              $result['success'] = 0;
              $result['message'] = "Image Upload failed";
              } */
            return $response->withJson($result);
        }
    }

    function getFriendList($request, $response) {
        $postData = $request->getParsedBody();
        if (isset($postData['phone']) && isset($postData['email'])) {
            $user = $this->_db->table('users')
                    ->orWhereIn('user_phone', explode(',', $postData['phone']))
                    ->orWhereIn('user_email', explode(',', $postData['email']))
                    ->get();
        }if (isset($postData['phone']) && empty($postData['email'])) {
            $user = $this->_db->table('users')
                    ->WhereIn('user_phone', explode(',', $postData['phone']))
                    ->get();
        }if (isset($postData['email']) && empty($postData['phone'])) {
            $user = $this->_db->table('users')
                    ->WhereIn('user_email', explode(',', $postData['email']))
                    ->get();
        }
        if (isset($user) && count($user) >= 1) {
            //$userData = $user->get();
            $result['success'] = '1';
            $result['data'] = $user;
        } else {
            $result['success'] = 0;
            $result['message'] = "Friend List not found!";
        }
        return $response->withJson($result);
    }

    function getUserFriendList($request, $response) {
        $postData = $request->getParsedBody();
        $user = $this->_db->table('users')
                ->join('user_network', 'users.user_id', '=', 'user_network.primary_user_id')
                ->select('user_network.network_user_id')
                ->where('users.user_id', $postData['userid'])
                ->get();
        if (isset($user) && count($user) >= 1) {
            foreach ($user as $networkUser) {
                $network_user_id = $networkUser->network_user_id;

                $networkUserData[] = $this->_db->table('users')
                        ->where('user_id', $network_user_id)
                        ->get();
            }
        }
        if (isset($networkUserData) && count($networkUserData) >= 1) {
            //$userData = $user->get();
            $result['success'] = '1';
            $result['data'] = $networkUserData;
        } else {
            $result['success'] = 0;
            $result['message'] = "User Friend List not found!";
        }
        return $response->withJson($result);
    }

    function randomKey($length) {
        $pool = range(0, 9);
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }

    public function chkloginUser($request, $response) {
        $postData = $request->getParsedBody();

        //$result = $this->loginUser($request, $response);
        /* require __DIR__ . "/../../vendor/encdec/Aes.class.inc.php";
          require __DIR__ . "/../../vendor/encdec/AesCtr.class.inc.php";
          $AesCtr = new \AesCtr();
          $encryption_key = 'sdwnmt';
          $userpr = $postData['password'];
          $userpr_encr = $AesCtr->encrypt($userpr, $encryption_key, 128);
          //$userpr_encr = base64_encode($userpr);
          $postData['password'] = $userpr_encr;
          $userpr_dec = $AesCtr->decrypt($postData['password'], $encryption_key, 128);

          //check  user  exists
          $user = $this->_db->table('users')
          ->where('user_name', $postData['username'])
          ->first();
         */

        if (isset($postData['username']) && isset($postData['password'])) {
            //$dbpass_dec = $AesCtr->decrypt($user->user_password, $encryption_key, 128);
            if ($postData['username'] == 'admin' && $postData['password'] == 'Adm!n@123') {
                $result['success'] = 1;
//                $user = $this->_db->table('users')
//          ->where('user_name', $postData['username'])
//          ->first();
//                var_dump($user);exit;
                $result['userInfo'] = ['first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'user_avatar' => $user->user_avatar];
                $_SESSION["userInfo"] = $result['userInfo'];
                $_SESSION['loggedIn'] = 'loggedIn';
                return $this->view->render($response, 'profile.phtml');
            } else {
                return $response->withRedirect('/login');
            }
        } else {
            return $response->withRedirect('/login');
        }
    }

    public function usersList($request, $response) {

        $basePath = $request->getUri()->getBasePath();

        $imagepath = $basePath . 'avatar/';
        return $this->view->render($response, 'userslist.phtml', array('image_path' => $imagepath));
    }

    public function eventsList($request, $response) {

        return $this->view->render($response, 'eventslist.phtml');
    }

    public function coolingSlap($request, $response) {

        return $this->view->render($response, 'cooling_down_slap.phtml');
    }

    public function usersInfo($request, $response) {
        $postData = $request->getQueryParams();
        $user = $this->_db->table('users')
                ->where('user_id', $postData['id'])
                ->get();
        $userInfo = (array) $user[0];
        return $this->view->render($response, 'userinfo.phtml', array('userInfo' => $userInfo));
        // var_dump($array);
    }

    /* public function setUserPosts($request, $response) {
      $postData = $request->getParsedBody();
      $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
      include($public_path . 'config_s3.php');
      if (isset($postData['userpostid'])) {
      //edit mode
      $updateData = [];
      if (isset($postData['posttype']))
      $updateData['user_post_type'] = $postData['posttype'];
      if ($postData['posttype'] == 'image' OR $postData['posttype'] == 'video') {

      $files = $_FILES['postdata'];

      $file_name_expensions = explode('.', $files['name']);

      $new_file_name = $postData['userpostid'] . '.' . $file_name_expensions[1];
      $file_tmp = $files['tmp_name'];
      $contentType = $files['type'];
      $uploadedimg = $awsS3Url . "tempoevent/" . $this->awsupload($new_file_name, $file_tmp, $contentType, "user_post");
      //$path = $basePath . 'user_post/';
      //move_uploaded_file($file_tmp, $path . $new_file_name);

      $updateData['user_post_data'] = $uploadedimg;
      //$updateData['user_post_data'] = $path . $new_file_name;
      } else {
      $updateData['user_post_data'] = $postData['postdata'];
      }
      if (isset($postData['userid']))
      $updateData['user_post_by'] = $postData['userid'];
      if (isset($postData['status']))
      $updateData['user_post_status'] = $postData['status'];

      try {
      $userPost = $this->_db->table('user_posts')
      ->where('user_post_id', $postData['userpostid'])
      ->update($updateData);

      $result['success'] = 1;
      $result['message'] = "User Post Update Sucessfully!";
      } catch (Exception $e) {
      $result['success'] = 0;
      $result['message'] = $e->getMessage();
      }
      } else {

      try {
      $userPost = new UserPost;
      $userPost->user_post_type = (isset($postData['posttype'])) ? $postData['posttype'] : '';
      $basePath = $request->getUri()->getBasePath();
      if ($postData['posttype'] == 'image' OR $postData['posttype'] == 'video') {

      $files = $_FILES['postdata'];

      $file_name_expensions = explode('.', $files['name']);

      $getlastpost = $this->_db->table('user_posts')
      ->selectRaw('user_post_id')
      ->latest()
      ->first();
      if (count($getlastpost) == 0) {
      $getlastpostId = 0;
      } else {
      $getlastpostId = $getlastpost->user_post_id;
      }
      $new_file_name = ($getlastpostId + 1) . '.' . $file_name_expensions[1];
      $file_tmp = $files['tmp_name'];
      //$path = $basePath . 'user_post/';
      //move_uploaded_file($file_tmp, $path . $new_file_name);
      $contentType = $files['type'];
      $uploadedimg = $awsS3Url . "tempoevent/" . $this->awsupload($new_file_name, $file_tmp, $contentType, "user_post");

      //$userPost->user_post_data = $path . $new_file_name;
      $userPost->user_post_data = $uploadedimg;
      } else {
      $userPost->user_post_data = (isset($postData['postdata'])) ? $postData['postdata'] : '';
      }

      $userPost->user_post_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
      $userPost->user_post_status = (isset($postData['status'])) ? $postData['status'] : '';
      $userPost->save();
      $result['success'] = 1;
      $result['userPost'] = $userPost;
      } catch (Exception $e) {
      $result['success'] = 0;
      $result['message'] = $e->getMessage();
      }
      }
      // posts of user in last 'n' days
      $n = 30;
      $from_date = date('Y-m-d', strtotime('-' . $n . ' days'));
      $to_date = date('Y-m-d');
      $posts = $this->_db->table('user_posts')
      ->where('user_post_by', $postData['userid'])
      ->whereBetween('created_at', [$from_date, $to_date])
      ->get();
      // echo $posts_count = count($posts);
      return $response->withJson($result);
      } */

    public function setUserPosts($request, $response) {
        $postData = $request->getParsedBody();
        $basePath = $request->getUri()->getBasePath();
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        $filetype = "file";

        //send post to users
        if (isset($postData['postedtouserid'])and ! empty($postData['postedtouserid'])) {

            try {
                $userPost = new UserPost;
                $userPost->user_post_type = (isset($postData['posttype'])) ? $postData['posttype'] : '';

                // if (isset($postData['posttype'])) {
                if (isset($_FILES['postdata'])) {

                    $files = $_FILES['postdata'];
                    $file_name_expensions = explode('.', $files['name']);
                    $imageOnlyname = (time() + 100) . '_';
                    $imgname = $imageOnlyname . '.' . $file_name_expensions[1];
                    $imagepath = $basePath . 'user_post/';
                    $file_tmp = $files['tmp_name'];
                    $contentType = $files['type'];
                    $thumbnail = "thumbs/thumb_" . $imgname;

                    $tmp = $files['tmp_name'];
                    if (isset($_FILES['postdata'])) {
                        $mime = $_FILES['postdata']['type'];
                        if (strstr($mime, "video/")) {
                            $filetype = "video";
                        } else if (strstr($mime, "image/")) {
                            $filetype = "image";
                        } else if (strstr($mime, "audio/")) {
                            $filetype = "audio";
                        }
                    }

                    $getlastpost = $this->_db->table('user_posts')
                            ->selectRaw('user_post_id')
                            ->where('isdeleted', 0)
                            ->latest()
                            ->first();
                    if (count($getlastpost) == 0) {
                        $getlastpostId = 0;
                    } else {
                        $getlastpostId = $getlastpost->user_post_id;
                    }
                    $new_file_name = ($getlastpostId + 1) . '.' . $file_name_expensions[1];
                    $certificate_newname = (time() + 100) . '_' . $new_file_name;

                    $file_name_expensions = explode('.', $files['name']);

                    if ($filetype == 'image') {
                        move_uploaded_file($files['tmp_name'], $imagepath . $imgname);
                        $this->generateThumbnail($imagepath . $imgname, $imageOnlyname);
                        $originalimage = $imagepath . $imgname;

                        $orgarr = explode(".", $originalimage);
                        $thumimage = $orgarr[0] . "." . $orgarr[1];
                        $thmbarr = explode("/", $thumimage);

                        $thu = explode(".", $thmbarr[1]);
                        $thumbImageName = "thumb_" . $thu[0] . ".jpg";
                        $thumbpath = "user_post/" . $thumbImageName;
                        $thumbcontentType = "image/jpg";
                        $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($imgname, $originalimage, $contentType, "user_post");

                        $uploadedThumbimg = $awsS3Url . $bucket . "/" . $this->awsupload($thumbImageName, $thumbpath, $thumbcontentType, "user_post_thumbs");

                        if (file_exists($originalimage))
                            unlink($originalimage);
                        if (file_exists($thumbpath))
                            unlink($thumbpath);

                        $userPost->user_post_data = $uploadedimg;
                        $userPost->user_post_thumb = $uploadedThumbimg;
                        $userPost->thumbnailDone = 1;
                    }
                    else {
                        $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($certificate_newname, $tmp, $contentType, "user_post");
                        $userPost->user_post_data = $uploadedimg;
                        $userPost->user_post_thumb = null;
                        $userPost->thumbnailDone = 0;
                    }


                    $userPost->user_post_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
                    $userPost->user_post_status = (isset($postData['status'])) ? $postData['status'] : '';
                    $userPost->save();
                    $postid = $userPost->id;
                }
                // posts of user in last 'n' days 
                $n = $this->n;
                $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
                $to_date = date('Y-m-d' . ' 22:00:40', time());

                $posts = $this->_db->table('user_posts')
                        ->where('isdeleted', 0)
                        ->where('user_post_by', $postData['userid'])
                        ->whereBetween('created_at', [$from_date, $to_date])
                        ->get();
                $posts_count = count($posts);
                $userevents = $this->_db->table('events')
                        ->where('created_by_user', $postData['userid'])
                        ->whereBetween('event_publised_on', [$from_date, $to_date])
                        ->get();
                $userevents_count = count($userevents);
                $recent_posts_count = ($posts_count) + ($userevents_count);
                $check = $this->_db->table('user_ranking')
                        ->where('user_id', $postData['userid'])
                        ->get();
                if (count($check) == 0) {
                    try {
                        $userRank = new UsersRanking;

                        $userRank->user_id = $postData['userid'];
                        $userRank->recent_posts_count = $recent_posts_count;
                        $userRank->save();
                    } catch (Exception $e) {
                        $result['success'] = 0;
                        $result['message'] = $e->getMessage();
                    }
                } else {
                    $userRankUpdate = $this->_db->table('user_ranking')
                            ->where('user_id', $postData['userid'])
                            ->update([
                        'recent_posts_count' => $recent_posts_count]);
                }
                $this->usersRank($request, $response);

                if (isset($postid)) {
                    if ($postData['postedtouserid'] == -1) {
                        //get user friend list
                        $userfriend = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->get();
                        if (isset($userfriend) && count($userfriend) >= 1) {
                            foreach ($userfriend as $networkUser) {
                                $network_user_id = $networkUser->network_user_id;
                                $userPoststo = new UserPostsTo;
                                $userPoststo->user_post_id = $postid;
                                $userPoststo->posted_to = $network_user_id;
                                $userPoststo->status = 'unread';
                                $userPoststo->created_at = date("Y-m-d H:i:s");
                                $userPoststo->updated_at = null;
                                $userPoststo->save();
                                $lastinserted = $userPoststo->id;
                                $result['success'] = 1;
                                $result['user_post_id'] = $lastinserted;
                                $dbpost = $this->_db->table('user_posts_to')
                                        ->where('user_posts_to_id', $lastinserted)
                                        ->get();
                                $dbpostarr = array();

                                foreach ($dbpost as $us) {
                                    if (isset($us->created_at))
                                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                                    if (isset($us->updated_at))
                                        $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                                    array_push($dbpostarr, $us);
                                }
                                $result['userPostto'][] = $dbpostarr;
                                $result['user_post_id'] = $postid;
                                $notitype = 'posted';
                                $notistatus = 'unread';
                                $Notifydata = $this->addUserPostNotification($postData['userid'], $network_user_id, $postid, $notitype, $notistatus, $filetype);
                                $devInfo = $this->getDeviceInfo($network_user_id);
                                $contentmsg = isset($Notifydata->comment_text) ? $Notifydata->comment_text : "";
                                if (count($devInfo) > 0) {
                                    foreach ($devInfo as $dev123) {
                                        if (isset($dev123->one_signal_userid)) {
                                            $one_signal_userid = trim($dev123->one_signal_userid);
                                            $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $network_user_id);
                                        }
                                    }
                                }
                            }
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "User friends not Found";
                        }
                    } else {
                        $pos = strpos($postData['postedtouserid'], ',');
                        if ($pos === false) {
                            $userPoststo = new UserPostsTo;
                            $userPoststo->user_post_id = $postid;
                            $userPoststo->posted_to = (isset($postData['postedtouserid'])) ? $postData['postedtouserid'] : 0;
                            $userPoststo->created_at = date("Y-m-d H:i:s");
                            $userPoststo->updated_at = null;
                            $userPoststo->save();
                            $lastinserted = $userPoststo->id;

                            $result['success'] = 1;
                            $dbpost = $this->_db->table('user_posts_to')
                                    ->where('user_posts_to_id', $lastinserted)
                                    ->get();
                            $dbpostarr = array();

                            foreach ($dbpost as $us) {
                                if (isset($us->created_at))
                                    $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                                if (isset($us->updated_at))
                                    $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                                array_push($dbpostarr, $us);
                            }
                            $result['userPostto'] = $dbpostarr;
                            $result['user_post_id'] = $postid;
                            $notitype = 'posted';
                            $notistatus = 'unread';
                            $Notifydata = $this->addUserPostNotification($postData['userid'], $postData['postedtouserid'], $postid, $notitype, $notistatus, $filetype);
                            $devInfo = $this->getDeviceInfo($postData['postedtouserid']);
                            $contentmsg = isset($Notifydata->comment_text) ? $Notifydata->comment_text : "";
                            if (count($devInfo) > 0) {
                                foreach ($devInfo as $dev123) {
                                    if (isset($dev123->one_signal_userid)) {
                                        $one_signal_userid = trim($dev123->one_signal_userid);
                                        $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $postData['postedtouserid']);
                                    }
                                }
                            }
                        } else {
                            $postedtouserid = explode(',', $postData['postedtouserid']);
                            foreach ($postedtouserid as $value) {
                                $lastinserted = 0;
                                $userPoststo = new UserPostsTo;
                                $userPoststo->user_post_id = $postid;
                                $userPoststo->posted_to = $value;
                                $userPoststo->created_at = date("Y-m-d H:i:s");
                                $userPoststo->updated_at = null;
                                $userPoststo->save();
                                $lastinserted = $userPoststo->id;
                                $result['success'] = 1;
                                $dbpost = $this->_db->table('user_posts_to')
                                        ->where('user_posts_to_id', $lastinserted)
                                        ->get();
                                $dbpostarr = array();

                                foreach ($dbpost as $us) {
                                    if (isset($us->created_at))
                                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                                    if (isset($us->updated_at))
                                        $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                                    array_push($dbpostarr, $us);
                                }
                                $result['userPostto'][] = $dbpostarr;
                                $result['user_post_id'] = $postid;
                                $notitype = 'posted';
                                $notistatus = 'unread';
                                $Notifydata = $this->addUserPostNotification($postData['userid'], $value, $postid, $notitype, $notistatus, $filetype);

                                $devInfo = $this->getDeviceInfo($postData['postedtouserid']);
                                $contentmsg = isset($Notifydata->comment_text) ? $Notifydata->comment_text : "";
                                if (count($devInfo) > 0) {
                                    foreach ($devInfo as $dev123) {
                                        if (isset($dev123->one_signal_userid)) {
                                            $one_signal_userid = trim($dev123->one_signal_userid);
                                            $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $postData['postedtouserid']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //share post
                $n = $this->n;
                $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
                $to_date = date('Y-m-d' . ' 22:00:40', time());
                $share_posts = $this->_db->table('user_posts_to')
                        ->join('user_posts', 'user_posts.user_post_id', '=', 'user_posts_to.user_post_id')
                        ->where('user_posts_to.user_post_id', $postid)
                        ->whereBetween('user_posts_to.created_at', [$from_date, $to_date])
                        ->select('user_posts.user_post_by')
                        ->get();
                if (isset($share_posts[0])) {
                    $created_by = $share_posts[0]->user_post_by;
                    $share_posts_count = count($share_posts);

                    $share_event = $this->_db->table('event_invitations')
                            ->join('events', 'events.event_id', '=', 'event_invitations.event_id')
                            ->where('events.created_by_user', $created_by)
                            ->whereBetween('event_invitations.created_at', [$from_date, $to_date])
                            ->get();
                    $share_event_count = count($share_event);
                    $recent_share_count = ($share_posts_count) + ($share_event_count);
                    $check = $this->_db->table('user_ranking')
                            ->where('user_id', $created_by)
                            ->get();
                    if (count($check) == 0) {
                        try {
                            $userRank = new UsersRanking;

                            $userRank->user_id = $created_by;
                            $userRank->recent_share_count = $recent_share_count;
                            $userRank->save();
                        } catch (Exception $e) {
                            $result['success'] = 0;
                            $result['message'] = $e->getMessage();
                        }
                    } else {
                        $userRankUpdate = $this->_db->table('user_ranking')
                                ->where('user_id', $created_by)
                                ->update([
                            'recent_share_count' => $recent_share_count]);
                    }
                }
                $this->usersRank($request, $response);

                //$result['success'] = 1;
                //$result['userPost'] = $userPost;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }

        if (isset($postData['eventid']) and ! empty($postData['eventid'])) {

            try {
                $eventidpos = strpos($postData['eventid'], ',');
                if ($eventidpos === false) {

                    $eventPost = new EventPost;
                    $eventPost->event_id = (isset($postData['eventid'])) ? $postData['eventid'] : 0;
                    $eventPost->post_type = (isset($postData['posttype'])) ? $postData['posttype'] : '';
                    $eventPost->post_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
                    $eventPost->post_status = (isset($postData['status'])) ? $postData['status'] : '';
                    if (isset($_FILES['postdata'])) {
                        $files = $_FILES['postdata'];

                        $file_name_expensions = explode('.', $files['name']);

                        $getlastpost = $this->_db->table('event_posts')
                                ->selectRaw('event_post_id')
                                ->latest()
                                ->first();
                        if (count($getlastpost) == 0) {
                            $getlastpostId = 0;
                        } else {
                            $getlastpostId = $getlastpost->event_post_id;
                        }
                        $new_file_name = ($getlastpostId + 1) . '.' . $file_name_expensions[1];
                        $file_tmp = $files['tmp_name'];

                        $path = $basePath . 'event_post/';
                        $contentType = $files['type'];
                        $certificate_newname = $new_file_name;
                        $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($certificate_newname, $file_tmp, $contentType, "event_post");

                        $eventPost->post_data = $uploadedimg;
                    } else
                        $eventPost->post_data = (isset($postData['postdata'])) ? $postData['postdata'] : '';
                    $eventPost->save();
                    $lastinserted = $eventPost->id;
                    $result['success'] = 1;
                    $result['user_post_id'] = $lastinserted;
                    $result['eventPost'] = $eventPost;
                    /// Add Notification 
                } else {
                    $eventidslist = explode(',', $postData['eventid']);

                    foreach ($eventidslist as $eventidvalue) {
                        $eventPost = new EventPost;
                        $eventPost->event_id = (isset($eventidvalue)) ? $eventidvalue : 0;
                        $eventPost->post_type = (isset($postData['posttype'])) ? $postData['posttype'] : '';
                        $eventPost->post_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
                        $eventPost->post_status = (isset($postData['status'])) ? $postData['status'] : '';
                        $basePath = $request->getUri()->getBasePath();
                        if (isset($_FILES['postdata'])) {
                            $files = $_FILES['postdata'];

                            $file_name_expensions = explode('.', $files['name']);

                            $getlastpost = $this->_db->table('event_posts')
                                    ->selectRaw('event_post_id')
                                    ->latest()
                                    ->first();
                            if (count($getlastpost) == 0) {
                                $getlastpostId = 0;
                            } else {
                                $getlastpostId = $getlastpost->event_post_id;
                            }
                            $new_file_name = ($getlastpostId + 1) . '.' . $file_name_expensions[1];
                            $file_tmp = $files['tmp_name'];
                            $path = $basePath . 'event_post/';
                            $contentType = $files['type'];
                            $certificate_newname = $new_file_name;
                            $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($certificate_newname, $file_tmp, $contentType, "event_post");

                            $eventPost->post_data = $uploadedimg;
                        } else
                            $eventPost->post_data = (isset($postData['postdata'])) ? $postData['postdata'] : '';
                        $eventPost->save();
                        $result['success'] = 1;
                        $result['eventPost'][] = $eventPost;
                    }
                }
                // $eventpostid = $eventPost->id;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }
        return $response->withJson($result);
    }

    public function addUserPostNotification($fromuserid, $touserid, $postid, $type, $status, $userpostmsg = null) {
        ////Add User POST Notification 

        date_default_timezone_set('UTC');
        $UserPostNotification = new UserPostNotification;

        $UserPostNotification->from_user_id = $fromuserid;
        $UserPostNotification->to_user_id = $touserid;

        $fromuser = $this->getUserName($fromuserid);
        $UserPostNotification->user_post_id = $postid;

        if ($userpostmsg == null)
            $UserPostNotification->comment_text = $fromuser . ' has posted to you';
        else {
            if ($userpostmsg == "like")
                $UserPostNotification->comment_text = $fromuser . ' has liked your post';
            else if ($userpostmsg == "commentpost")
                $UserPostNotification->comment_text = $fromuser . ' has commented on your post';
            else if ($userpostmsg == "video" || $userpostmsg == "audio" || $userpostmsg == "image")
                $UserPostNotification->comment_text = $fromuser . ' has posted a new ' . $userpostmsg;
            else
                $UserPostNotification->comment_text = $fromuser . ' has unliked your post';
        }
        $UserPostNotification->notification_type = $type;
        $UserPostNotification->status = $status;
        $UserPostNotification->created_at = date('Y-m-d H:i:s');
        $UserPostNotification->updated_at = null;
        $UserPostNotification->save();
        $lastinserted = $UserPostNotification->id;

        $getLatestNotification = $this->_db->table('user_post_notifications')
                ->where('user_post_notification_id', $lastinserted)
                ->get();
        return $getLatestNotification[0];
    }

    public function getUserPosts($request, $response) {
        $postData = $request->getParsedBody();

        $getusertpost = $this->_db->table('user_posts')
                ->where('isdeleted', 0)
                ->where('user_post_by', $postData['userid'])
                ->take(20)
                ->latest()
                ->get();
        if (isset($getusertpost) && count($getusertpost) >= 1) {
            try {

                $result['success'] = 1;
                $result['userpost'] = $getusertpost;
                $result['totalrecords'] = count($getusertpost);
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "User Post Not Found";
        }

        return $response->withJson($result);
    }

    public function setPostComments($request, $response) {
        $postData = $request->getParsedBody();
        try {
            $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
            include($public_path . 'config_s3.php');
            $userPostComments = new UserPostComments;
            $userPostComments->user_post_id = (isset($postData['postid'])) ? $postData['postid'] : 0;
            $basePath = $request->getUri()->getBasePath();
            if ($postData['commenttype'] == 'image' OR $postData['commenttype'] == 'video') {

                $files = $_FILES['commentdata'];

                $file_name_expensions = explode('.', $files['name']);

                $getlastpost = $this->_db->table('userpost_comments')
                        ->selectRaw('userpost_comment_id')
                        ->latest()
                        ->first();
                if (count($getlastpost) == 0) {
                    $getlastpostId = 0;
                } else {
                    $getlastpostId = $getlastpost->userpost_comment_id;
                }

                $new_file_name = ($getlastpostId + 1) . '.' . $file_name_expensions[1];
                $file_tmp = $files['tmp_name'];
                $contentType = $files['type'];
                $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($new_file_name, $file_tmp, $contentType, "post_comment");


                //$path = $basePath . 'post_comment/';
                // move_uploaded_file($file_tmp, $path . $new_file_name);
                //$userPostComments->comment_data = $path . $new_file_name;
                $userPostComments->comment_data = $uploadedimg;
            } else {
                $userPostComments->comment_data = (isset($postData['commentdata'])) ? $postData['commentdata'] : '';
            }

            $userPostComments->commented_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
            $userPostComments->comment_status = (isset($postData['status'])) ? $postData['status'] : '';
            $userPostComments->save();
            $result['success'] = 1;
            $result['userPostComment'] = $userPostComments;
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }

        return $response->withJson($result);
    }

    /* public function setPostComments($request, $response) {
      $postData = $request->getParsedBody();
      try {
      $userPostComments = new UserPostComments;
      $userPostComments->user_post_id = (isset($postData['postid'])) ? $postData['postid'] : 0;
      $userPostComments->comment_data = (isset($postData['commentdata'])) ? $postData['commentdata'] : '';
      $userPostComments->commented_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
      $userPostComments->comment_status = (isset($postData['status'])) ? $postData['status'] : '';
      $userPostComments->save();
      $result['success'] = 1;
      $result['userPostComment'] = $userPostComments;
      } catch (Exception $e) {
      $result['success'] = 0;
      $result['message'] = $e->getMessage();
      }
      // posts of user in last 'n' days
      $n = $this->n;
      $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
      $to_date = date('Y-m-d' . ' 22:00:40', time());
      $posts_comment = $this->_db->table('userpost_comments')
      ->join('user_posts', 'user_posts.user_post_id', '=', 'userpost_comments.user_post_id')
      ->where('userpost_comments.user_post_id', $postData['postid'])
      ->whereBetween('userpost_comments.created_at', [$from_date, $to_date])
      ->select('user_posts.user_post_by')
      ->get();
      $post_by = $posts_comment[0]->user_post_by;
      $post_comment_count = count($posts_comment);
      $event_comment = $this->_db->table('event_comments')
      ->join('events', 'events.event_id', '=', 'event_comments.event_post_id')
      ->where('events.created_by_user', $post_by)
      ->whereBetween('event_comments.created_at', [$from_date, $to_date])
      ->get();
      $event_comment_count = count($event_comment);
      $recent_comment_count = ($post_comment_count) + ($event_comment_count);
      $check = $this->_db->table('user_ranking')
      ->where('user_id', $post_by)
      ->get();
      if (count($check) == 0) {
      try {
      $userRank = new UsersRanking;

      $userRank->user_id = $post_by;
      $userRank->recent_comment_count = $recent_comment_count;
      $userRank->save();
      } catch (Exception $e) {
      $result['success'] = 0;
      $result['message'] = $e->getMessage();
      }
      } else {
      $userRankUpdate = $this->_db->table('user_ranking')
      ->where('user_id', $post_by)
      ->update([
      'recent_comment_count' => $recent_comment_count]);
      }

      $this->usersRank($request, $response);

      return $response->withJson($result);
      } */

    public function getPostComments($request, $response) {
        $postData = $request->getParsedBody();
        $offset = isset($postData['offset']) ? (int) $postData['offset'] : 0;
        if ($offset > 0) {
            $getusertpost = $this->_db->table('userpost_comments')
                    ->where('user_post_id', $postData['postid'])
                    //->where('commented_by', $postData['userid'])
                    ->where('userpost_comment_id', '>', $offset)
                    ->get();
        } else {
            $getusertpost = $this->_db->table('userpost_comments')
                    ->where('user_post_id', $postData['postid'])
                    //  ->where('commented_by', $postData['userid'])
                    ->get();
        }
        if (isset($getusertpost) && count($getusertpost) >= 1) {
            try {

                $result['success'] = 1;
                $comnew = array();

                foreach ($getusertpost as $uc) {
                    if (isset($uc->created_at))
                        $uc->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($uc->created_at)));
                    if (isset($uc->updated_at))
                        $uc->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($uc->updated_at)));

                    $uc->commented_byuserid = $uc->commented_by;
                    $uc->commented_byname = $this->getUserName($uc->commented_by);

                    array_push($comnew, $uc);
                }
                $result['comments'] = $comnew;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "Comment Not Found";
        }

        return $response->withJson($result);
    }

    public function postTo($request, $response) {
        $postData = $request->getParsedBody();
        try {
            $pos = strpos($postData['userid'], ',');
            if ($pos === false) {
                $userPoststo = new UserPostsTo;
                $userPoststo->user_post_id = (isset($postData['postid'])) ? $postData['postid'] : 0;
                $userPoststo->posted_to = (isset($postData['userid'])) ? $postData['userid'] : 0;
                $userPoststo->save();
                $result['success'] = 1;
                $result['userPostto'] = $userPoststo;
            } else {
                $userid = explode(',', $postData['userid']);
                foreach ($userid as $value) {
                    $userPoststo = new UserPostsTo;
                    $userPoststo->user_post_id = (isset($postData['postid'])) ? $postData['postid'] : 0;
                    $userPoststo->posted_to = $value;
                    $userPoststo->save();
                    $result['success'] = 1;
                    $result['userPostto'][] = $userPoststo;
                }
            }
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }

        $n = $this->n;
        $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
        $to_date = date('Y-m-d' . ' 22:00:40', time());
        $share_posts = $this->_db->table('user_posts_to')
                ->join('user_posts', 'user_posts.user_post_id', '=', 'user_posts_to.user_post_id')
                ->where('user_posts_to.user_post_id', $postData['postid'])
                ->whereBetween('user_posts_to.created_at', [$from_date, $to_date])
                ->select('user_posts.user_post_by')
                ->get();
        $created_by = $share_posts[0]->user_post_by;
        $share_posts_count = count($share_posts);

        $share_event = $this->_db->table('event_invitations')
                ->join('events', 'events.event_id', '=', 'event_invitations.event_id')
                ->where('events.created_by_user', $created_by)
                ->whereBetween('event_invitations.created_at', [$from_date, $to_date])
                ->get();
        $share_event_count = count($share_event);
        $recent_share_count = ($share_posts_count) + ($share_event_count);
        $check = $this->_db->table('user_ranking')
                ->where('user_id', $created_by)
                ->get();
        if (count($check) == 0) {
            try {
                $userRank = new UsersRanking;

                $userRank->user_id = $created_by;
                $userRank->recent_share_count = $recent_share_count;
                $userRank->save();
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $userRankUpdate = $this->_db->table('user_ranking')
                    ->where('user_id', $created_by)
                    ->update([
                'recent_share_count' => $recent_share_count]);
        }
        $this->usersRank($request, $response);
        return $response->withJson($result);
    }

    public function getUserMesssages($request, $response) {
        $postData = $request->getParsedBody();
        $getusertpost = $this->_db->table('user_posts')
                ->where('isdeleted', 0)
                ->where('user_post_by', $postData['userid'])
                ->get();
        if (isset($getusertpost) && count($getusertpost) >= 1) {
            try {

                $result['success'] = 1;
                $result['userMesssages'] = $getusertpost;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "User Messsage Not Found";
        }

        return $response->withJson($result);
    }

    public function editProfile($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $chkuser = $this->_db->table('users')
                ->where('user_id', $postData['userid'])
                ->first();
        if (isset($chkuser) && count($chkuser) >= 1) {
            //edit mode
            $updateData = [];
            if (isset($postData['firstname'])) {
                $updateData['first_name'] = $postData['firstname'];
            }if (isset($postData['lastname'])) {
                $updateData['last_name'] = $postData['lastname'];
            } if (isset($postData['email'])) {
                $checkEmail = $this->checEmailExists(trim($postData['email']), $postData['userid']);
                if ($checkEmail == 0)
                    $updateData['user_email'] = $postData['email'];
                else {
                    $result['success'] = 0;
                    $result['message'] = "Email aready Exists";
                    return $response->withJson($result);
                }
            }if (isset($postData['phone'])) {
                $checkEmail = $this->checPhoneExists(trim($postData['phone']), $postData['userid']);
                if ($checkEmail == 0)
                    $updateData['user_phone'] = $postData['phone'];
                else {
                    $result['success'] = 0;
                    $result['message'] = "Phone aready Exists";
                    return $response->withJson($result);
                }
            }if (isset($postData['dob'])) {
                $updateData['user_dob'] = $postData['dob'];
            }if (isset($postData['gender'])) {
                $updateData['user_gender'] = $postData['gender'];
            }if (isset($postData['address'])) {
                $updateData['user_address'] = $postData['address'];
            }if (isset($postData['city'])) {
                $updateData['user_city'] = $postData['city'];
            }if (isset($postData['state'])) {
                $updateData['user_state'] = $postData['state'];
            }if (isset($postData['zipcode'])) {
                $updateData['user_zipcode'] = $postData['zipcode'];
            }if (isset($postData['country'])) {
                $updateData['user_country'] = $postData['country'];
            }if (isset($postData['createdfrom'])) {
                $updateData['user_created_from'] = $postData['createdfrom'];
            }
            $updateData['updated_at'] = date("Y-m-d H:i:s");
            try {
                $userDefination = $this->_db->table('users')
                        ->where('user_id', $postData['userid'])
                        ->update($updateData);
                $user = $this->_db->table('users')
                        ->where('user_id', $postData['userid'])
                        ->get();
                $usernew = array();
                foreach ($user as $us) {
                    if (isset($us->otp_datetime))
                        $us->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->otp_datetime)));
                    if (isset($us->created_at))
                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                    if (isset($us->last_usage))
                        $us->last_usage = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->last_usage)));
                    if (isset($us->otp_datetime))
                        $us->otp_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->otp_datetime)));
                    array_push($usernew, $us);
                }
                $result['userinfo'] = $usernew;
                $result['success'] = 1;
                $result['message'] = "Profile Update Successfully!";
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "User Not Found";
        }

        return $response->withJson($result);
    }

    // Commented on 05 Dec.2017
    /* public function usersRank($request, $response) {
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
      if (isset($user_id)) {
      $updateUserRank = $this->_db->table('users')
      ->where('user_id', $user_id)
      ->update([
      'tempo_user_rank' => $tempo_user_rank]);
      }
      }
      } */
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

    public function getUserProfile($request, $response) {
        $postData = $request->getParsedBody();
        $chkuser = $this->_db->table('users')
                ->where('user_id', $postData['userid'])
                ->first();
        if (isset($chkuser) && count($chkuser) >= 1) {
            $myevent = $this->_db->table('events')
                    ->where('created_by_user', $chkuser->user_id)
                    ->get();

            $friend = $this->_db->table('user_network')
                    ->where('network_user_id', $postData['calledbyUserID'])
                    ->where('primary_user_id', $postData['userid'])
                    ->where('network_status', 'Accepted')
                    ->get();
            if (isset($friend) && count($friend) >= 1) {
                $networkFriend = 1;
            } else {
                $networkFriend = 0;
            }
            $result['success'] = 1;
            $result['userInfo'] = ['userid' => $chkuser->user_id,
                'name' => $chkuser->first_name . ' ' . $chkuser->last_name,
                'avatar' => $chkuser->user_avatar,
                'tempoRating' => $chkuser->tempo_user_rank,
                'IsNotification' => $chkuser->IsNotification,
                'events' => count($myevent),
                'networkFriend' => $networkFriend
            ];
        } else {
            $result['success'] = 0;
            $result['message'] = "User Not Found";
        }

        return $response->withJson($result);
    }

    public function getUserPostEventFriend($request, $response) {
        $postData = $request->getParsedBody();
        $userid = $postData['userid'];
        $calledbyUserID = $postData['calledbyUserID'];
        $type = 0;
        if (isset($postData['type']))
            $type = (int) $postData['type'];
        $maxid = isset($postData['maxid']) ? $postData['maxid'] : 0;
        $minid = isset($postData['minid']) ? $postData['minid'] : 0;

        if ($userid == $calledbyUserID) {

            switch ($type) {
                case '0':  //All
                    //get Events
                    if ($maxid == 0 && $minid == 0) {
                        $myevent = $this->_db->table('events')
                                ->select('event_id', 'event_name', 'event_description', 'event_rating', 'promotion_image1', 'promotion_image2', 'promotion_image3')
                                ->where('created_by_user', $postData['userid'])
                                ->where('isdeleted', 0)
                                ->get();
                    }
                    if ($maxid > 0 && $minid == 0) {
                        $myevent = $this->_db->table('events')
                                ->select('event_id', 'event_name', 'event_description', 'event_rating', 'promotion_image1', 'promotion_image2', 'promotion_image3')
                                ->where('created_by_user', $postData['userid'])
                                ->where('event_id', '>', $maxid)
                                ->where('isdeleted', 0)
                                ->get();
                    }
                    if ($maxid == 0 && $minid > 0) {
                        $myevent = $this->_db->table('events')
                                ->select('event_id', 'event_name', 'event_description', 'event_rating', 'promotion_image1', 'promotion_image2', 'promotion_image3')
                                ->where('created_by_user', $postData['userid'])
                                ->where('event_id', '<', $minid)
                                ->where('isdeleted', 0)
                                ->get();
                    }
                    //get Posts
                    if ($maxid == 0 && $minid == 0) {
                        $getusertpost = $this->_db->table('user_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('user_post_by', $postData['userid'])
                               // ->where('isdeleted', 0)
                                ->get();
                    }
                    if ($maxid > 0 && $minid == 0) {
                        $getusertpost = $this->_db->table('user_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('user_post_by', $postData['userid'])
                                ->where('user_post_id', '>', $maxid)
                              //  ->where('isdeleted', 0)
                                ->get();
                    }
                    if ($maxid == 0 && $minid > 0) {
                        $getusertpost = $this->_db->table('user_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('user_post_by', $postData['userid'])
                                ->where('user_post_id', '<', $minid)
                              //  ->where('isdeleted', 0)
                                ->get();
                    }
                    //get users friend
                    if ($maxid == 0 && $minid == 0) {
                        $getuserfriends = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->get();
                    }
                    if ($maxid > 0 && $minid == 0) {
                        $getuserfriends = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->where('user_network_id', '>', $maxid)
                                ->get();
                    }
                    if ($maxid == 0 && $minid > 0) {
                        $getuserfriends = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->where('user_network_id', '<', $minid)
                                ->get();
                    }

                    if (isset($getusertpost) && count($getusertpost) >= 1) {
                        $result['success'] = 1;
                        $arrRet = array();
                        foreach ($getusertpost as $p) {
                            if (isset($p->user_post_by)) {
                                $p->user_rating = $this->getUserRating($p->user_post_by);
                            }
                            array_push($arrRet, $p);
                        }
                        $result['posts'] = $arrRet;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = "User's Post Not Found";
                    }
                    if (isset($myevent) && count($myevent) >= 1) {
                        $result['success'] = 1;
                        $result['events'] = $myevent;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = "User's event Not Found";
                    }
                    if (isset($getuserfriends) && count($getuserfriends) >= 1) {
                        foreach ($getuserfriends as $networkUser) {
                            $network_user_id = $networkUser->network_user_id;
                            $networkUserData[] = $this->_db->table('users')
                                    ->select('user_id', 'first_name', 'last_name')
                                    ->where('user_id', $network_user_id)
                                    ->get();
                        }
                        $result['success'] = 1;
                        $result['friends'] = $networkUserData;
                        if (isset($getuserfriends) && count($getuserfriends) >= 1) {
                            foreach ($getuserfriends as $networkUser) {
                                $network_user_id = $networkUser->network_user_id;
                                $networkUserData[] = $this->_db->table('users')
                                        ->select('user_id', 'first_name', 'last_name')
                                        ->where('user_id', $network_user_id)
                                        ->get();
                            }
                            $result['success'] = 1;
                            $result['friends'] = $networkUserData;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "No friend Found";
                        }
                    } else {
                        $result['success'] = 0;
                        $result['message'] = "No friend Found";
                    }
                    break;
                case '1':  //get Posts
                    if ($maxid == 0 && $minid == 0) {
                        $getusertpost = $this->_db->table('user_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('user_post_by', $postData['userid'])
                              //  ->where('isdeleted', 0)
                                ->orderBy('user_post_id', 'desc')
                                ->get();
                        $geteventposts = $this->_db->table('event_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('post_by', $postData['userid'])
                                ->where('isdeleted', 0)
                                ->orderBy('event_post_id', 'desc')
                                ->get();
                    }
                    if ($maxid > 0 && $minid == 0) {
                        $getusertpost = $this->_db->table('user_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('user_post_by', $postData['userid'])
                                ->where('user_post_id', '>' . $maxid)
                               // ->where('isdeleted', 0)
                                ->orderBy('user_post_id', 'desc')
                                ->get();
                        $geteventposts = $this->_db->table('event_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('post_by', $postData['userid'])
                                ->where('event_post_id', '>' . $maxid)
                                ->where('isdeleted', 0)
                                ->orderBy('event_post_id', 'desc')
                                ->get();
                    }
                    if ($maxid == 0 && $minid > 0) {
                        $getusertpost = $this->_db->table('user_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('user_post_by', $postData['userid'])
                                ->where('user_post_id', '<' . $minid)
                              //  ->where('isdeleted', 0)
                                ->orderBy('user_post_id', 'desc')
                                ->get();
                        $geteventposts = $this->_db->table('event_posts')
                                // ->where('isdeleted', 0)  /// Commented for the owner
                                ->where('post_by', $postData['userid'])
                                ->where('event_post_id', '<' . $minid)
                                ->where('isdeleted', 0)
                                ->orderBy('event_post_id', 'desc')
                                ->get();
                    }

                    if (isset($getusertpost) && count($getusertpost) >= 1) {
                        $result['success'] = 1;
                        $finalarr = array();
                        foreach ($getusertpost as $posts) {
                            $arr = array();
                            $arr["user_post_id"] = $posts->user_post_id;
                            $arr["user_post_type"] = $posts->user_post_type;
                            $arr["user_post_data"] = $posts->user_post_data;
                            $arr["user_post_thumb"] = $posts->user_post_thumb;
                            $arr["thumbnailDone"] = $posts->thumbnailDone;
                            $arr["user_post_status"] = 'read';
                            $arr["user_post_by"] = $posts->user_post_by;
                            $arr["user_rating"] = $this->getUserRating($posts->user_post_by);
                            $arr["like_count"] = $posts->like_count;
                            $arr["comment_count"] = $posts->comment_count;
                            $arr["created_at"] = isset($posts->created_at) ? str_replace('+00:00', 'Z', gmdate('c', strtotime($posts->created_at))) : null;
                            $arr["updated_at"] = isset($posts->updated_at) ? str_replace('+00:00', 'Z', gmdate('c', strtotime($posts->updated_at))) : null;
                            array_push($finalarr, $arr);
                        }
                        $result['posts'] = $finalarr;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = "User's Post Not Found";
                    }
                    if (isset($geteventposts) && count($geteventposts) >= 1) {
                        $result['success'] = 1;
                        $finalarrEvent = array();
                        foreach ($geteventposts as $posts111) {
                            $arr122 = array();
                            $arr122["event_post_id"] = $posts111->event_post_id;
                            $arr122["event_id"] = $posts111->event_id;
                            $arr122["post_type"] = $posts111->post_type;
                            $arr122["post_data"] = $posts111->post_data;
                            $arr122["post_status"] = 'read';
                            $arr122["post_by"] = $posts111->post_by;
                            $arr122["like_count"] = $posts111->like_count;
                            $arr122["created_at"] = isset($posts111->created_at) ? str_replace('+00:00', 'Z', gmdate('c', strtotime($posts111->created_at))) : null;
                            $arr122["updated_at"] = isset($posts111->updated_at) ? str_replace('+00:00', 'Z', gmdate('c', strtotime($posts111->updated_at))) : null;
                            array_push($finalarrEvent, $arr122);
                        }
                        $result['eventposts'] = $finalarrEvent;
                    } else {
                        if (isset($result['success']) && $result['success'] != 1)
                            $result['success'] = 0;
                        $result['message'] = "User's Event Post Not Found";
                    }
                    break;
                case '2': //get Event 
                    $myevent = $this->getMyEventsFromVarious($postData['userid'], $maxid, $minid);
                    if (isset($myevent) && count($myevent) >= 1) {
                        $result['success'] = 1;
                        $result['events'] = $myevent;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = "User's event Not Found";
                    }
                    break;
                case '3': //get Friends
                    //get users friend
                    if ($maxid == 0 && $minid == 0) {
                        $getuserfriends = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->get();
                    }
                    if ($maxid > 0 && $minid == 0) {
                        $getuserfriends = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->where('user_network_id', '>', $maxid)
                                ->get();
                    }
                    if ($maxid == 0 && $minid > 0) {
                        $getuserfriends = $this->_db->table('user_network')
                                ->select('network_user_id')
                                ->where('primary_user_id', $postData['userid'])
                                ->where('network_status', 'Accepted')
                                ->where('user_network_id', '<', $minid)
                                ->get();
                    }

                    if (isset($getuserfriends) && count($getuserfriends) >= 1) {
                        foreach ($getuserfriends as $networkUser) {
                            $network_user_id = $networkUser->network_user_id;
                            $networkUserData[] = $this->_db->table('users')
                                    ->select('user_id', 'first_name', 'last_name')
                                    ->where('user_id', $network_user_id)
                                    ->get();
                        }
                        $result['success'] = 1;
                        $result['friends'] = $networkUserData;
                    } else {
                        $result['success'] = 0;
                        $result['message'] = "No friend Found";
                    }
                    break;
            }
        }
        if ($userid != $calledbyUserID) {
            $chkuserfriend = $this->_db->table('user_network')
                    ->where('primary_user_id', $postData['calledbyUserID'])
                    ->where('network_user_id', $postData['userid'])
                    ->where('network_status', 'Accepted')
                    ->get();
            if (count($chkuserfriend) == 0) {
                $result['success'] = 0;
                $result['message'] = "You are not following the User";
                $result['userinfo'] = $this->getUserBasicInfo($userid);
                return $response->withJson($result);
            } else {
                switch ($type) {
                    case '0':  //All
                        if ($maxid == 0 && $minid == 0) {
                            $getusertpost = $this->_db->table('user_posts')
                                    ->where('user_post_by', $postData['userid'])
                                    ->where('isdeleted', 0)
                                    ->get();
                        }
                        if ($maxid > 0 && $minid == 0) {
                            $getusertpost = $this->_db->table('user_posts')
                                    ->where('isdeleted', 0)
                                    ->where('user_post_by', $postData['userid'])
                                    ->where('user_post_id', '>', $maxid)
                                    ->get();
                        }
                        if ($maxid == 0 && $minid > 0) {
                            $getusertpost = $this->_db->table('user_posts')
                                    ->where('isdeleted', 0)
                                    ->where('user_post_by', $postData['userid'])
                                    ->where('user_post_id', '<', $minid)
                                    ->get();
                        }
                        if (isset($getusertpost) && count($getusertpost) >= 1) {
                            $result['success'] = 1;
                            $arrRet = array();
                            foreach ($getusertpost as $p) {
                                if (isset($p->user_post_by)) {
                                    $p->user_rating = $this->getUserRating($p->user_post_by);
                                }
                                array_push($arrRet, $p);
                            }
                            $result['posts'] = $arrRet;
                            //    $result['posts'] = $getusertpost;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "User's Post Not Found";
                        }

                        $myevent = $this->getMyEventsFromVarious($postData['userid'], $maxid, $minid);
                        if (isset($myevent) && count($myevent) >= 1) {
                            $result['success'] = 1;
                            $result['events'] = $myevent;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "User's event Not Found";
                        }
                        if (isset($getuserfriends) && count($getuserfriends) >= 1) {
                            foreach ($getuserfriends as $networkUser) {
                                $network_user_id = $networkUser->network_user_id;
                                $networkUserData[] = $this->_db->table('users')
                                        ->select('user_id', 'first_name', 'last_name')
                                        ->where('user_id', $network_user_id)
                                        ->get();
                            }
                            $result['success'] = 1;
                            $result['friends'] = $networkUserData;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "No friend Found";
                        }

                        break;
                    case '1':  //Post
                        if ($maxid == 0 && $minid == 0) {
                            $getSharedusertpostIds = $this->_db->table('user_posts_to')
                                    ->select('user_post_id')
                                     
                                    ->where('posted_to', $postData['userid'])
                                    ->get();
                        }
                        if ($maxid > 0 && $minid == 0) {
                            $getSharedusertpostIds = $this->_db->table('user_posts_to')
                                    ->select('user_post_id')
                                    
                                    ->where('posted_to', $postData['userid'])
                                    ->where('user_post_to_id', '>', $maxid)
                                    ->get();
                        }
                        if ($maxid == 0 && $minid > 0) {
                            $getSharedusertpostIds = $this->_db->table('user_posts_to')
                                    ->select('user_post_id')
                                    
                                    ->where('posted_to', $postData['userid'])
                                    ->where('user_post_to_id', '<', $minid)
                                    ->get();
                        }

                        $sharedPostsids = array();
                        if (count($getSharedusertpostIds) > 0) {
                            foreach ($getSharedusertpostIds as $id) {
                                array_push($sharedPostsids, $id->user_post_id);
                            }
                        }
                        array_push($sharedPostsids, (int) $postData['userid']);
                        $getusertpost = $this->_db->table('user_posts')
                                ->where('isdeleted', 0)
                                ->where('user_post_by', $postData['userid'])
                                ->where('isdeleted', 0)
                                ->orwhereIn('user_post_id', $sharedPostsids)
                                ->get();
                        if (isset($getusertpost) && count($getusertpost) >= 1) {
                            $finalarr = array();
                            foreach ($getusertpost as $posts) {
                                $arr = array();
                                $arr["user_post_id"] = $posts->user_post_id;
                                $arr["user_post_type"] = $posts->user_post_type;
                                $arr["user_post_data"] = $posts->user_post_data;
                                $arr["user_post_thumb"] = $posts->user_post_thumb;
                                $arr["thumbnailDone"] = $posts->thumbnailDone;
                                $arr["user_post_status"] = $this->getPostStatus($posts->user_post_id, $postData['userid']);
                                $arr["user_post_by"] = $posts->user_post_by;
                                $arr["user_rating"] = $this->getUserRating($posts->user_post_by);
                                $arr["like_count"] = $posts->like_count;
                                $arr["comment_count"] = $posts->comment_count;
                                $arr["created_at"] = isset($posts->created_at) ? str_replace('+00:00', 'Z', gmdate('c', strtotime($posts->created_at))) : null;
                                $arr["updated_at"] = isset($posts->updated_at) ? str_replace('+00:00', 'Z', gmdate('c', strtotime($posts->updated_at))) : null;
                                array_push($finalarr, $arr);
                            }
                            $result['success'] = 1;
                            $result['posts'] = $finalarr;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "User's Post Not Found";
                        }
                        break;
                    case '2':  //Event 
                        $myevent = $this->getMyEventsFromVarious($postData['userid'], $maxid, $minid);
                        if (isset($myevent) && count($myevent) >= 1) {
                            $result['success'] = 1;
                            $result['events'] = $myevent;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "User's event Not Found";
                        }

                        break;
                    case '3':  //Friends 
                        //get users friend
                        if ($maxid == 0 && $minid == 0) {
                            $getuserfriends = $this->_db->table('user_network')
                                    ->select('network_user_id')
                                    ->where('primary_user_id', $postData['userid'])
                                    ->where('network_status', 'Accepted')
                                    ->get();
                        }
                        if ($maxid > 0 && $minid == 0) {
                            $getuserfriends = $this->_db->table('user_network')
                                    ->select('network_user_id')
                                    ->where('primary_user_id', $postData['userid'])
                                    ->where('network_status', 'Accepted')
                                    ->where('user_network', '>', $maxid)
                                    ->get();
                        }
                        if ($maxid == 0 && $minid > 0) {
                            $getuserfriends = $this->_db->table('user_network')
                                    ->select('network_user_id')
                                    ->where('primary_user_id', $postData['userid'])
                                    ->where('network_status', 'Accepted')
                                    ->where('user_network', '<', $minid)
                                    ->get();
                        }

                        if (isset($getuserfriends) && count($getuserfriends) >= 1) {
                            foreach ($getuserfriends as $networkUser) {
                                $network_user_id = $networkUser->network_user_id;
                                $networkUserData[] = $this->_db->table('users')
                                        ->select('user_id', 'first_name', 'last_name', 'user_avatar')
                                        ->where('user_id', $network_user_id)
                                        ->get();
                            }
                            $result['success'] = 1;
                            $result['friends'] = $networkUserData;
                        } else {
                            $result['success'] = 0;
                            $result['message'] = "No friend Found";
                        }

                        break;
                }
            }
        }
        if ($type > 3) {
            $result['success'] = 0;
            $result['messsage'] = "Invalid argument";
        }
        return $response->withJson($result);
    }

    public function getUserRating($userid) {
        $tempo_user_rank = "";
        if (isset($userid)) {
            $users = $this->_db->table('users')
                    ->select('tempo_user_rank')
                    ->where('user_id', $userid)
                    ->get();
            if (isset($users[0]->tempo_user_rank)) {
                $tempo_user_rank = $users[0]->tempo_user_rank;
            }
        }
        return $tempo_user_rank;
    }

    /* public function getUserPostEventFriend($request, $response) {
      $postData = $request->getParsedBody();
      $userid = $postData['userid'];
      $calledbyUserID = $postData['calledbyUserID'];
      $type = 0;
      if (isset($postData['type']))
      $type = (int) $postData['type'];

      //get uses friend
      $getuserfriends = $this->_db->table('user_network')
      ->select('network_user_id')
      ->where('primary_user_id', $postData['userid'])
      ->where('network_status', 'Accepted')
      ->get();
      //get events
      $myevent = $this->_db->table('events')
      ->select('event_id', 'event_name', 'event_description', 'promotion_image1', 'promotion_image2', 'promotion_image3')
      ->where('created_by_user', $postData['userid'])
      ->get();

      if ($userid == $calledbyUserID) {

      switch ($type) {
      case '0':  //All
      $getusertpost = $this->_db->table('user_posts')
      ->where('user_post_by', $postData['userid'])
      ->get();
      if (isset($getusertpost) && count($getusertpost) >= 1) {
      $result['success'] = 1;
      $result['posts'] = $getusertpost;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's Post Not Found";
      }
      if (isset($myevent) && count($myevent) >= 1) {
      $result['success'] = 1;
      $result['events'] = $myevent;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's event Not Found";
      }
      if (isset($getuserfriends) && count($getuserfriends) >= 1) {
      foreach ($getuserfriends as $networkUser) {
      $network_user_id = $networkUser->network_user_id;
      $networkUserData[] = $this->_db->table('users')
      ->select('user_id', 'first_name', 'last_name')
      ->where('user_id', $network_user_id)
      ->get();
      }
      $result['success'] = 1;
      $result['friends'] = $networkUserData;
      if (isset($getuserfriends) && count($getuserfriends) >= 1) {
      foreach ($getuserfriends as $networkUser) {
      $network_user_id = $networkUser->network_user_id;
      $networkUserData[] = $this->_db->table('users')
      ->select('user_id', 'first_name', 'last_name')
      ->where('user_id', $network_user_id)
      ->get();
      }
      $result['success'] = 1;
      $result['friends'] = $networkUserData;
      } else {
      $result['success'] = 0;
      $result['message'] = "No friend Found";
      }
      } else {
      $result['success'] = 0;
      $result['message'] = "No friend Found";
      }
      break;
      case '1':  //get Posts
      $getusertpost = $this->_db->table('user_posts')
      ->where('user_post_by', $postData['userid'])
      ->get();
      if (isset($getusertpost) && count($getusertpost) >= 1) {
      $result['success'] = 1;
      $result['posts'] = $getusertpost;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's Post Not Found";
      }
      break;
      case '2': //get Event
      if (isset($myevent) && count($myevent) >= 1) {
      $result['success'] = 1;
      $result['events'] = $myevent;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's event Not Found";
      }
      break;
      case '3': //get Friends
      if (isset($getuserfriends) && count($getuserfriends) >= 1) {
      foreach ($getuserfriends as $networkUser) {
      $network_user_id = $networkUser->network_user_id;
      $networkUserData[] = $this->_db->table('users')
      ->select('user_id', 'first_name', 'last_name')
      ->where('user_id', $network_user_id)
      ->get();
      }
      $result['success'] = 1;
      $result['friends'] = $networkUserData;
      } else {
      $result['success'] = 0;
      $result['message'] = "No friend Found";
      }
      break;
      }
      }
      if ($userid != $calledbyUserID) {
      $chkuserfriend = $this->_db->table('user_network')
      ->where('primary_user_id', $postData['userid'])
      ->where('network_user_id', $postData['calledbyUserID'])
      ->where('network_status', 'Accepted')
      ->get();
      switch ($type) {
      case '0':  //All
      $getusertpost = $this->_db->table('user_posts')
      ->where('user_post_by', $postData['userid'])
      ->get();
      if (isset($getusertpost) && count($getusertpost) >= 1) {
      $result['success'] = 1;
      $result['posts'] = $getusertpost;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's Post Not Found";
      }
      if (isset($chkuserfriend) && count($chkuserfriend) >= 1) {
      if (isset($myevent) && count($myevent) >= 1) {
      $result['success'] = 1;
      $result['events'] = $myevent;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's event Not Found";
      }
      if (isset($getuserfriends) && count($getuserfriends) >= 1) {
      foreach ($getuserfriends as $networkUser) {
      $network_user_id = $networkUser->network_user_id;
      $networkUserData[] = $this->_db->table('users')
      ->select('user_id', 'first_name', 'last_name')
      ->where('user_id', $network_user_id)
      ->get();
      }
      $result['success'] = 1;
      $result['friends'] = $networkUserData;
      } else {
      $result['success'] = 0;
      $result['message'] = "No friend Found";
      }
      } else {
      $result['success'] = 0;
      $result['message'] = "This is a private account";
      }
      break;
      case '1':  //Post
      $getusertpost = $this->_db->table('user_posts')
      ->where('user_post_by', $postData['userid'])
      ->get();
      if (isset($getusertpost) && count($getusertpost) >= 1) {
      $result['success'] = 1;
      $result['posts'] = $getusertpost;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's Post Not Found";
      }
      break;
      case '2':  //Event
      if (isset($chkuserfriend) && count($chkuserfriend) >= 1) {
      if (isset($myevent) && count($myevent) >= 1) {
      $result['success'] = 1;
      $result['events'] = $myevent;
      } else {
      $result['success'] = 0;
      $result['message'] = "User's event Not Found";
      }
      } else {
      $result['success'] = 0;
      $result['message'] = "This is a private account";
      }
      break;
      case '3':  //Friends
      if (isset($chkuserfriend) && count($chkuserfriend) >= 1) {
      if (isset($getuserfriends) && count($getuserfriends) >= 1) {
      foreach ($getuserfriends as $networkUser) {
      $network_user_id = $networkUser->network_user_id;
      $networkUserData[] = $this->_db->table('users')
      ->select('user_id', 'first_name', 'last_name')
      ->where('user_id', $network_user_id)
      ->get();
      }
      $result['success'] = 1;
      $result['friends'] = $networkUserData;
      } else {
      $result['success'] = 0;
      $result['message'] = "No friend Found";
      }
      } else {
      $result['success'] = 0;
      $result['message'] = "This is a private account";
      }
      break;
      }
      }
      if ($type > 3) {
      $result['success'] = 0;
      $result['messsage'] = "Invalid argument";
      }
      return $response->withJson($result);
      } */

    public function getSharedPost($request, $response) {
        $postData = $request->getParsedBody();
        $share_posts = $this->_db->table('user_posts')
                ->join('user_posts_to', 'user_posts_to.user_post_id', '=', 'user_posts.user_post_id')
                ->where('user_posts.user_post_by', $postData['userid'])
                ->where('user_posts_to.posted_to', $postData['postedtouserid'])
                ->where('user_posts.isdeleted', 0)
                // ->select('user_posts.user_post_by')
                ->get();
        if (isset($share_posts) && count($share_posts) >= 1) {
            $result['success'] = 1;
            $post = array();
            foreach ($share_posts as $po) {
                if (isset($po->created_at))
                    $po->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($po->created_at)));
                if (isset($po->updated_at))
                    $po->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($po->updated_at)));
                array_push($post, $po);
            }
            $result['share_posts'] = $share_posts;
        } else {
            $result['success'] = 0;
            $result['message'] = "No post shared";
        }

        return $response->withJson($result);
    }

    public function cleanstring($dirtystring) {
        $cleanstring = '';
        $cleanstring = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $dirtystring);
        $cleanstring = preg_replace('/[\x00-\x1F\x7F]/', '', $cleanstring);
        $cleanstring = preg_replace('/[\x00-\x1F\x7F]/u', '', $cleanstring);
        $cleanstring = str_replace("\r", "\n", $cleanstring);
        $cleanstring = preg_replace('~\R~u', "\r\n", $cleanstring);
        return $cleanstring;
    }

    public function getAssociationtype($userid, $posteduserId = 0) {

        if ($posteduserId != 0) {
            $userassociation_type = $this->_db->table('user_network')
                    ->where('primary_user_id', $posteduserId)
                    ->where('network_user_id', $userid)
                    // ->where('network_status', 'Accepted')
                    ->select('user_network.association_type', 'user_network.network_status')
                    ->first();
        } else {
            $userassociation_type = $this->_db->table('user_network')
                    ->where('network_user_id', $userid)
                    ->where('network_status', 'Accepted')
                    ->select('user_network.association_type', 'user_network.network_status')
                    ->first();
        }


        if (isset($userassociation_type->association_type)) {

            if (($userassociation_type->network_status) == "Invited")
                return "Requested";
            else
            if (strtolower($userassociation_type->association_type) == "unfollow")
                return 0;
            else
                return "Follow";
        } else
            return 0;
    }

    public function awsupload($image_name_actual, $tmp, $contentType, $path) {
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        $key = $path . "/" . $image_name_actual;

        //$bucket = "tempoevent";
        try {
            $client->putObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                'SourceFile' => $tmp,
                'StorageClass' => 'STANDARD',
                'ACL' => 'public-read-write',
                'Body' => '',
                'ContentType' => $contentType,
            ));
            $message = "S3 Upload Successful.";

            return $key;
        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            echo $e->getMessage();
        }
    }

    public function getcities($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $cities = $this->_db->table('city')
                ->get();
        if (isset($cities) && count($cities) >= 1) {
            $result['success'] = 1;
            $result['cities'] = $cities;
        } else {
            $result['success'] = 0;
            $result['message'] = "No city available";
        }

        return $response->withJson($result);
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

    public function getnotifications($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $maxid = isset($postData['maxid']) ? $postData['maxid'] : 0;
        $minid = isset($postData['minid']) ? $postData['minid'] : 0;
        if ($maxid == 0 && $minid == 0) {
            $notifications = $this->_db->table('user_notifications')
                    ->leftJoin('users', 'user_notifications.from_user_id', '=', 'users.user_id')
                    ->where('to_user_id', $userid)
                    ->orderBy('user_notification_id', 'desc')
                    ->take(200)
                    ->latest()
                    ->select(['user_notifications.user_notification_id', 'from_user_id', 'users.user_avatar as from_user_avatar', 'user_notifications.to_user_id', 'user_notifications.event_id', 'user_notifications.photo_id', 'user_notifications.network_id',
                        'user_notifications.comment_text', 'user_notifications.notification_type', 'user_notifications.status', 'user_notifications.created_at'])
                    ->get();
        }
        if ($maxid > 0 && $minid == 0) {
            $notifications = $this->_db->table('user_notifications')
                    ->leftJoin('users', 'user_notifications.from_user_id', '=', 'users.user_id')
                    ->where('to_user_id', $userid)
                    ->where('user_notification_id', '>', $maxid)
                    ->orderBy('user_notification_id', 'asc')
                    ->take($maxid)
                    //->latest()
                    ->select(['user_notifications.user_notification_id', 'from_user_id', 'users.user_avatar as from_user_avatar', 'user_notifications.to_user_id', 'user_notifications.event_id', 'user_notifications.photo_id', 'user_notifications.network_id',
                        'user_notifications.comment_text', 'user_notifications.notification_type', 'user_notifications.status', 'user_notifications.created_at'])
                    ->get();
        }
        if ($maxid == 0 && $minid > 0) {
            $notifications = $this->_db->table('user_notifications')
                    ->leftJoin('users', 'user_notifications.from_user_id', '=', 'users.user_id')
                    ->where('to_user_id', $userid)
                    ->where('user_notification_id', '<', $minid)
                    ->orderBy('user_notification_id', 'asc')
                    ->take($minid)
                    ->select(['user_notifications.user_notification_id', 'from_user_id', 'users.user_avatar as from_user_avatar', 'user_notifications.to_user_id', 'user_notifications.event_id', 'user_notifications.photo_id', 'user_notifications.network_id',
                        'user_notifications.comment_text', 'user_notifications.notification_type', 'user_notifications.status', 'user_notifications.created_at'])
                    ->get();
        }
        if (isset($notifications) && count($notifications) >= 1) {
            $result['success'] = 1;

            $notnew = $arrRep = array();

            foreach ($notifications as $us) {
                if (!in_array($us->user_notification_id, $arrRep)) {
                    if (isset($us->created_at))
                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                    if (isset($us->updated_at))
                        $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                    array_push($notnew, $us);
                    array_push($arrRep, $us->user_notification_id);
                }
            }
            $result['success'] = 1;

            $result['notifications'] = $notnew;
        } else {
            $result['success'] = 0;
            $result['message'] = "No Notification available";
        }

        return $response->withJson($result);
    }

    public function forgotPassword($request, $response) {
        $postData = $request->getParsedBody();
        $otp = $this->randomKey(6);
        $emailOrPhone = isset($postData['emailOrPhone']) ? $postData['emailOrPhone'] : '';
        if (strpos($emailOrPhone, "@") !== false) {
            $user = $this->_db->table('users')
                    ->where('user_email', $emailOrPhone)
                    ->first();
            if (isset($user) && count($user) >= 1) {
                $result['success'] = 1;
                if (isset($user->first_name) && isset($user->last_name))
                    $name = ucfirst(trim($user->first_name)) . ' ' . ucfirst(trim($user->last_name));
                if (isset($user->first_name))
                    $name = ucfirst(trim($user->first_name));
                if (isset($user->last_name))
                    $name = ucfirst(trim($user->last_name));
                if (isset($user->user_password)) {
                    $postData = $request->getParsedBody();
                    require __DIR__ . "/../../vendor/encdec/Aes.class.inc.php";
                    require __DIR__ . "/../../vendor/encdec/AesCtr.class.inc.php";
                    $AesCtr = new \AesCtr();
                    $encryption_key = 'sdwnmt';
                    $password = trim($user->user_password);
                    $dbpass_dec = $AesCtr->decrypt(trim($password), $encryption_key, 128);
                }
                $result['message'] = 'Password has been sent to your Email.';
                $data = array('email' => trim($user->user_email), 'name' => $name,
                    'subject' => trim("Password from Tempo"), 'password' => trim($dbpass_dec),);
                $hostAddr = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];

                $url = $hostAddr . "/mailsetting/mail.php";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $output = curl_exec($ch);
                curl_close($ch);
            } else {
                $result['success'] = 0;
                $result['message'] = 'user not exists!';
            }
        } else {
            $user = $this->_db->table('users')
                    ->where('user_phone', $emailOrPhone)
                    ->first();
            if (isset($user) && count($user) >= 1) {
                $result['success'] = 1;
                if (isset($user->first_name) && isset($user->last_name))
                    $name = ucfirst(trim($user->first_name)) . ' ' . ucfirst(trim($user->last_name));
                if (isset($user->first_name))
                    $name = ucfirst(trim($user->first_name));
                if (isset($user->last_name))
                    $name = ucfirst(trim($user->last_name));
                if (isset($user->user_password)) {
                    $postData = $request->getParsedBody();
                    require __DIR__ . "/../../vendor/encdec/Aes.class.inc.php";
                    require __DIR__ . "/../../vendor/encdec/AesCtr.class.inc.php";
                    $AesCtr = new \AesCtr();
                    $encryption_key = 'sdwnmt';
                    $password = trim($user->user_password);
                    $dbpass_dec = $AesCtr->decrypt(trim($password), $encryption_key, 128);
                }
                $result['message'] = 'Password has been sent to your Phone.';
                $smsarr = array();
                $smsarr['msg'] = 'Your Password for Tempo is ' . trim($dbpass_dec);
                if (strpos($emailOrPhone, "+1") == false)
                    $emailOrPhone = "+1" . $emailOrPhone;
                $smsarr['to'] = $emailOrPhone;
                $smsText = $this->SendSms($smsarr);
            } else {
                $result['success'] = 0;
                $result['message'] = 'user not exists!';
            }
        }
        return $response->withJson($result);
    }

    public function markNotificationAsRead($request, $response) {
        $postData = $request->getParsedBody();
        $user_notification_id = isset($postData['user_notification_id']) ? $postData['user_notification_id'] : 0;
        $result['success'] = 0;
        $result['message'] = "No Notification available";

        $exists_notifications = $this->_db->table('user_notifications')
                ->where('user_notification_id', $user_notification_id)
                ->select('user_notification_id')
                ->get();
        if (isset($exists_notifications[0])) {
            $notifications = $this->_db->table('user_notifications')
                    ->where('user_notification_id', $user_notification_id)
                    ->update([
                'status' => 'read']);
            $result['success'] = 1;
            $result['message'] = "Notification updated successfully";
        }

        return $response->withJson($result);
    }

    public function deleteNotification($request, $response) {
        $postData = $request->getParsedBody();
        $user_notification_id = isset($postData['user_notification_id']) ? $postData['user_notification_id'] : 0;
        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $result['success'] = 0;
        $result['message'] = "No Notification available";

        $exists_notifications = $this->_db->table('user_notifications')
                ->where('user_notification_id', $user_notification_id)
                ->where('to_user_id', $user_id)
                ->select('user_notification_id')
                ->get();
        if (isset($exists_notifications[0])) {
            $notifications = $this->_db->table('user_notifications')
                    ->where('user_notification_id', $user_notification_id)
                    ->where('to_user_id', $user_id)
                    ->delete();

            $result['success'] = 1;
            $result['message'] = "Notification deleted successfully";
        }
        return $response->withJson($result);
    }

    public function deleteAllUserNotification($request, $response) {
        $postData = $request->getParsedBody();
        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $result['success'] = 0;
        $result['message'] = "No Notification available";

        $exists_notifications = $this->_db->table('user_notifications')
                ->where('to_user_id', $user_id)
                ->select('user_notification_id')
                ->get();
        if (count($exists_notifications) > 0) {
            $notifications = $this->_db->table('user_notifications')
                    ->where('to_user_id', $user_id)
                    ->delete();

            $result['success'] = 1;
            $result['message'] = "All notifications deleted successfully";
        }
        return $response->withJson($result);
    }

    /// User Post Notificaions 

    public function getUserPostnotifications($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $maxid = isset($postData['maxid']) ? $postData['maxid'] : 0;
        $minid = isset($postData['minid']) ? $postData['minid'] : 0;
        if ($maxid == 0 && $minid == 0) {
            $notifications = $this->_db->table('user_post_notifications')
                    ->leftJoin('users', 'user_post_notifications.from_user_id', '=', 'users.user_id')
                    ->where('to_user_id', $userid)
                    ->orderBy('user_post_notification_id', 'desc')
                    ->take(200)
                    ->latest()
                    ->select(['user_post_notifications.user_post_notification_id', 'from_user_id', 'users.user_avatar as from_user_avatar', 'user_post_notifications.to_user_id', 'user_post_notifications.user_post_id',
                        'user_post_notifications.comment_text', 'user_post_notifications.notification_type', 'user_post_notifications.status', 'user_post_notifications.created_at'])
                    ->get();
        }
        if ($maxid > 0 && $minid == 0) {
            $notifications = $this->_db->table('user_post_notifications')
                    ->leftJoin('users', 'user_post_notifications.from_user_id', '=', 'users.user_id')
                    ->where('to_user_id', $userid)
                    ->where('user_post_notification_id', '>', $maxid)
                    ->orderBy('user_post_notification_id', 'asc')
                    ->take($maxid)
                    //->latest()
                    ->select(['user_post_notifications.user_post_notification_id', 'from_user_id', 'users.user_avatar as from_user_avatar', 'user_post_notifications.to_user_id', 'user_post_notifications.user_post_id',
                        'user_post_notifications.comment_text', 'user_post_notifications.notification_type', 'user_post_notifications.status', 'user_post_notifications.created_at'])
                    ->get();
        }
        if ($maxid == 0 && $minid > 0) {
            $notifications = $this->_db->table('user_post_notifications')
                    ->leftJoin('users', 'user_post_notifications.from_user_id', '=', 'users.user_id')
                    ->where('to_user_id', $userid)
                    ->where('user_post_notification_id', '<', $minid)
                    ->orderBy('user_post_notification_id', 'asc')
                    ->take($minid)
                    ->select(['user_post_notifications.user_post_notification_id', 'from_user_id', 'users.user_avatar as from_user_avatar', 'user_post_notifications.to_user_id', 'user_post_notifications.user_post_id',
                        'user_post_notifications.comment_text', 'user_post_notifications.notification_type', 'user_post_notifications.status', 'user_post_notifications.created_at'])
                    ->get();
        }
        if (isset($notifications) && count($notifications) >= 1) {
            $result['success'] = 1;

            $notnew = array();

            foreach ($notifications as $us) {
                if (isset($us->user_post_id)) {
                    $retPosdata = $this->getPostUrl($us->user_post_id);
                    if ($retPosdata != "") {
                        $us->user_post_url = $retPosdata->user_post_data;
                        $us->user_post_type = $retPosdata->user_post_type;
                        $us->user_post_thumb = $retPosdata->user_post_thumb;
                        $us->thumbnailDone = $retPosdata->thumbnailDone;
                    }
                }
                if (isset($us->created_at))
                    $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                if (isset($us->updated_at))
                    $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                array_push($notnew, $us);
            }
            $result['success'] = 1;

            $result['notifications'] = $notnew;
        } else {
            $result['success'] = 0;
            $result['message'] = "No Notification available";
        }

        return $response->withJson($result);
    }

    public function getPostUrl($user_post_id) {
        $user_post_data = '';
        $users = $this->_db->table('user_posts')
                ->select('user_post_data', 'user_post_type', 'user_post_thumb', 'thumbnailDone')
                ->where('user_post_id', $user_post_id)
                ->get();

        if (isset($users[0])) {

            return $users[0];
        }

        return $user_post_data;
    }

    public function markpostNotificationAsRead($request, $response) {
        $postData = $request->getParsedBody();
        $user_post_notification_id = isset($postData['user_post_notification_id']) ? $postData['user_post_notification_id'] : 0;
        $result['success'] = 0;
        $result['message'] = "No Post notification  available";

        $exists_notifications = $this->_db->table('user_post_notifications')
                ->where('user_post_notification_id', $user_post_notification_id)
                ->select('user_post_notification_id')
                ->get();
        if (isset($exists_notifications[0])) {
            $notifications = $this->_db->table('user_post_notifications')
                    ->where('user_post_notification_id', $user_post_notification_id)
                    ->update([
                'status' => 'read']);
            $result['success'] = 1;
            $result['message'] = "Post notification  updated successfully";
        }

        return $response->withJson($result);
    }

    public function deletepostNotification($request, $response) {
        $postData = $request->getParsedBody();
        $user_post_notification_id = isset($postData['user_post_notification_id']) ? $postData['user_post_notification_id'] : 0;

        $result['success'] = 0;
        $result['message'] = "No Post notification  available";

        $exists_notifications = $this->_db->table('user_post_notifications')
                ->where('user_post_notification_id', $user_post_notification_id)
                ->select('user_post_notification_id')
                ->get();
        if (isset($exists_notifications[0])) {
            $notifications = $this->_db->table('user_post_notifications')
                    ->where('user_post_notification_id', $user_post_notification_id)
                    ->delete();

            $result['success'] = 1;
            $result['message'] = "Post notification  deleted successfully";
        }
        return $response->withJson($result);
    }

    public function deleteAllUserpostNotification($request, $response) {
        $postData = $request->getParsedBody();
        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $result['success'] = 0;
        $result['message'] = "No Post notification  available";

        $exists_notifications = $this->_db->table('user_post_notifications')
                ->where('to_user_id', $user_id)
                ->select('user_post_notification_id')
                ->get();
        if (count($exists_notifications) > 0) {
            $notifications = $this->_db->table('user_post_notifications')
                    ->where('to_user_id', $user_id)
                    ->delete();

            $result['success'] = 1;
            $result['message'] = "All notifications deleted successfully";
        }
        return $response->withJson($result);
    }

    public function addUserDevice($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $devicetype = isset($postData['devicetype']) ? $postData['devicetype'] : '';
        $devicepushtoken = isset($postData['devicepushtoken']) ? trim($postData['devicepushtoken']) : '';
        $one_signal_userid = isset($postData['one_signal_userid']) ? $postData['one_signal_userid'] : '';
        $result['success'] = 0;

        if ($user_id == 0 || $devicetype == '' || $one_signal_userid == '') {
            $result['success'] = 0;
            $result['message'] = "Invalid parameters";
            return $response->withJson($result);
        }
        try {
            $exists_one_signal_userid = $this->_db->table('user_devices')
                    ->where('user_id', "!=", $user_id)
                    ->where('one_signal_userid', $one_signal_userid)
                    ->where('devicetype', $devicetype)
                    ->select('user_device_id')
                    ->get();
            if (count($exists_one_signal_userid) > 0) {

                $devices = $this->_db->table('user_devices')
                        ->where('one_signal_userid', $one_signal_userid)
                        ->where('devicetype', $devicetype)
                        ->where('user_id', "!=", $user_id)
                        ->delete();
                $userDev = new UserDevices;

                $userDev->user_id = $user_id;
                $userDev->devicepushtoken = $devicepushtoken;
                $userDev->devicetype = $devicetype;
                $userDev->one_signal_userid = $one_signal_userid;
                $userDev->created_at = date("Y-m-d H:i:s");
                $userDev->updated_at = null;
                $userDev->save();
                $lastinserted = $userDev->id;
                $userdev = $this->_db->table('user_devices')
                        ->where('user_device_id', $lastinserted)
                        ->get();
            } else {
                $exists_one_signal_userid_this_user_id = $this->_db->table('user_devices')
                        ->where('user_id', $user_id)
                        ->where('devicetype', $devicetype)
                        ->select('user_device_id')
                        ->get();

                if (count($exists_one_signal_userid_this_user_id) == 0) {
                    $userDev = new UserDevices;

                    $userDev->user_id = $user_id;
                    $userDev->devicepushtoken = $devicepushtoken;
                    $userDev->devicetype = $devicetype;
                    $userDev->one_signal_userid = $one_signal_userid;
                    $userDev->created_at = date("Y-m-d H:i:s");
                    $userDev->updated_at = null;
                    $userDev->save();
                    $lastinserted = $userDev->id;
                    $userdev = $this->_db->table('user_devices')
                            ->where('user_device_id', $lastinserted)
                            ->get();
                } else {
                    if ($devicepushtoken == "") {
                        $user_devices = $this->_db->table('user_devices')
                                ->where('user_id', $user_id)
                                ->where('devicetype', $devicetype)
                                ->update(['devicetype' => $devicetype,
                            'one_signal_userid' => $one_signal_userid, 'updated_at' => date("Y-m-d H:i:s")]);
                    } else {
                        $user_devices = $this->_db->table('user_devices')
                                ->where('user_id', $user_id)
                                ->where('devicetype', $devicetype)
                                ->update(['devicetype' => $devicetype, 'devicepushtoken' => $devicepushtoken,
                            'one_signal_userid' => $one_signal_userid, 'updated_at' => date("Y-m-d H:i:s")]);
                    }
                    $userdev = $this->_db->table('user_devices')
                            ->where('user_id', $user_id)
                            ->where('devicetype', $devicetype)
                            ->where('one_signal_userid', $one_signal_userid)
                            ->get();
                }
            }
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }
        $devnew = array();


        foreach ($userdev as $us) {
            if (isset($us->created_at))
                $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
            if (isset($us->updated_at))
                $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
            array_push($devnew, $us);
        }
        $result['success'] = 1;
        $result['deviceinfo'] = $devnew;

        return $response->withJson($result);
    }

    public function removeUserDevice($request, $response) {
        $postData = $request->getParsedBody();
        $result['success'] = 0;
        $one_signal_userid = isset($postData['one_signal_userid']) ? $postData['one_signal_userid'] : '';
        $devicetype = isset($postData['devicetype']) ? $postData['devicetype'] : '';
        $user_id = isset($postData['userid']) ? $postData['userid'] : '';

        $exists_one_signal_userid = $this->_db->table('user_devices')
                ->where('user_id', "=", $user_id)
                ->where('one_signal_userid', $one_signal_userid)
                ->where('devicetype', $devicetype)
                ->select('user_device_id')
                ->get();
        if (count($exists_one_signal_userid) > 0) {

            $devices = $this->_db->table('user_devices')
                    ->where('one_signal_userid', $one_signal_userid)
                    ->where('devicetype', $devicetype)
                    ->where('user_id', "=", $user_id)
                    ->delete();

            $result['success'] = 1;
            $result['message'] = "Device deleted successfully";
        }
        return $response->withJson($result);
    }

    public function GetFriendsLatestPosts($request, $response) {
        $postData = $request->getParsedBody();
        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $maxid = isset($postData['maxid']) ? $postData['maxid'] : 0;
        $minid = isset($postData['minid']) ? $postData['minid'] : 0;
        if ($user_id == 0) {
            $result['success'] = 0;
            $result['message'] = "Missing parameters";
            return $response->withJson($result);
        }
        $getFrindsUserIds = $this->getAllfrinedIds($user_id); //var_dump(implode(",",$getFrindsUserIds));exit;
        $finalarr = array();
        $result['success'] = 0;
        $arrRep = array();
        if ($maxid == 0 && $minid == 0) {
            $postdetails = $this->_db->table('user_posts')
                    ->leftJoin('user_posts_to', 'user_posts_to.user_post_id', '=', 'user_posts.user_post_id')
                    ->select('user_posts.user_post_id', 'user_posts.user_post_type', 'user_posts.user_post_data', 'user_posts.user_post_thumb', 'user_posts.thumbnailDone', 'user_posts.like_count', 'user_posts.comment_count', 'user_posts.created_at', 'users.user_id', 'users.user_name', 'users.first_name', 'users.last_name', 'users.user_avatar', 'users.tempo_user_rank')
                    ->leftJoin('users', 'user_posts.user_post_by', '=', 'users.user_id')
                    //->leftJoin('user_post_notifications', 'user_posts.user_post_id', '=', 'user_post_notifications.user_post_id')
                    ->where('user_posts_to.posted_to', $user_id)
                    ->where('user_posts.isdeleted', 0)
                    ->orderBy('user_posts.user_post_id', 'desc')
                    ->get();
            if (count($postdetails) > 0) {

                foreach ($postdetails as $pos12) {
                    if ($pos12->user_id != $user_id && !in_array($pos12->user_id, $arrRep)) {
                        $arr = array();
                        $arr["user_post_id"] = $pos12->user_post_id;
                        $arr["user_post_type"] = $pos12->user_post_type;
                        $arr["user_post_data"] = isset($pos12->user_post_data) ? $pos12->user_post_data : '';
                        $arr["user_post_thumb"] = isset($pos12->user_post_thumb) ? $pos12->user_post_thumb : '';
                        $arr["thumbnailDone"] = $pos12->thumbnailDone;
                        $arr["status"] = $this->getPostStatus($pos12->user_post_id, $user_id);
                        $arr["post_created_at"] = str_replace('+00:00', 'Z', gmdate('c', strtotime($pos12->created_at)));
                        $arr["user_id"] = $pos12->user_id;
                        $arr["user_name"] = $pos12->user_name;
                        if (isset($pos12->first_name) && isset($pos12->last_name))
                            $arr["posted_by"] = $pos12->first_name . ' ' . $pos12->last_name;
                        else if (isset($pos12->first_name))
                            $arr["posted_by"] = $pos12->first_name;
                        else if (isset($pos12->last_name))
                            $arr["posted_by"] = $pos12->last_name;
                        $arr["user_avatar"] = $pos12->user_avatar;
                        $arr["comment_count"] = $pos12->comment_count;
                        $arr["like_count"] = $pos12->like_count;
                        $arr["tempo_user_rank"] = $pos12->tempo_user_rank;
                        array_push($finalarr, $arr);
                        array_push($arrRep, $pos12->user_id);
                    }
                }
            }
        }
        if ($maxid > 0 && $minid == 0) {

            $postdetails = $this->_db->table('user_posts')
                    ->leftJoin('user_posts_to', 'user_posts_to.user_post_id', '=', 'user_posts.user_post_id')
                    ->select('user_posts.user_post_id', 'user_posts.user_post_type', 'user_posts.user_post_data', 'user_posts.thumbnailDone', 'user_posts.user_post_thumb', 'user_posts.like_count', 'user_posts.comment_count', 'user_posts.created_at', 'users.user_id', 'users.user_name', 'users.first_name', 'users.last_name', 'users.user_avatar', 'users.tempo_user_rank')
                    ->leftJoin('users', 'user_posts.user_post_by', '=', 'users.user_id')
                    //->leftJoin('user_post_notifications', 'user_posts.user_post_id', '=', 'user_post_notifications.user_post_id')
                    ->where('user_posts_to.posted_to', $user_id)
                    ->where('user_posts.isdeleted', 0)
                    ->where('user_posts.user_post_id', '>', $maxid)
                    ->orderBy('user_posts.user_post_id', 'desc')
                    ->get();
            if (count($postdetails) > 0) {

                foreach ($postdetails as $pos12) {
                    if ($pos12->user_id != $user_id && !in_array($pos12->user_id, $arrRep)) {
                        $arr = array();
                        $arr["user_post_id"] = $pos12->user_post_id;
                        $arr["user_post_type"] = $pos12->user_post_type;
                        $arr["user_post_data"] = isset($pos12->user_post_data) ? $pos12->user_post_data : '';
                        $arr["user_post_thumb"] = $pos12->user_post_thumb;
                        $arr["thumbnailDone"] = $pos12->thumbnailDone;
                        $arr["status"] = $this->getPostStatus($pos12->user_post_id, $user_id);
                        $arr["post_created_at"] = str_replace('+00:00', 'Z', gmdate('c', strtotime($pos12->created_at)));
                        $arr["user_id"] = $pos12->user_id;
                        $arr["user_name"] = $pos12->user_name;
                        if (isset($pos12->first_name) && isset($pos12->last_name))
                            $arr["posted_by"] = $pos12->first_name . ' ' . $pos12->last_name;
                        else if (isset($pos12->first_name))
                            $arr["posted_by"] = $pos12->first_name;
                        else if (isset($pos12->last_name))
                            $arr["posted_by"] = $pos12->last_name;
                        $arr["user_avatar"] = $pos12->user_avatar;
                        $arr["comment_count"] = $pos12->comment_count;
                        $arr["like_count"] = $pos12->like_count;
                        $arr["tempo_user_rank"] = $pos12->tempo_user_rank;
                        array_push($finalarr, $arr);
                        array_push($arrRep, $pos12->user_id);
                    }
                }
            }
        }

        if ($maxid == 0 && $minid > 0) {
            $postdetails = $this->_db->table('user_posts')
                    ->leftJoin('user_posts_to', 'user_posts_to.user_post_id', '=', 'user_posts.user_post_id')
                    ->select('user_posts.user_post_id', 'user_posts.user_post_type', 'user_posts.user_post_data', 'user_posts.thumbnailDone', 'user_posts.user_post_thumb', 'user_posts.like_count', 'user_posts.comment_count', 'user_posts.created_at', 'users.user_id', 'users.user_name', 'users.first_name', 'users.last_name', 'users.user_avatar', 'users.tempo_user_rank')
                    ->leftJoin('users', 'user_posts.user_post_by', '=', 'users.user_id')
                    //->leftJoin('user_post_notifications', 'user_posts.user_post_id', '=', 'user_post_notifications.user_post_id')
                    ->where('user_posts_to.posted_to', $user_id)
                    ->where('user_posts.isdeleted', 0)
                    ->where('user_posts.user_post_id', '<', $minid)
                    ->orderBy('user_posts.user_post_id', 'desc')
                    ->get();
            if (count($postdetails) > 0) {

                foreach ($postdetails as $pos12) {
                    if ($pos12->user_id != $user_id && !in_array($pos12->user_id, $arrRep)) {
                        $arr = array();
                        $arr["user_post_id"] = $pos12->user_post_id;
                        $arr["user_post_type"] = $pos12->user_post_type;
                        $arr["user_post_data"] = isset($pos12->user_post_data) ? $pos12->user_post_data : '';
                        $arr["user_post_thumb"] = $pos12->user_post_thumb;
                        $arr["thumbnailDone"] = $pos12->thumbnailDone;
                        $arr["status"] = $this->getPostStatus($pos12->user_post_id, $user_id);
                        $arr["post_created_at"] = str_replace('+00:00', 'Z', gmdate('c', strtotime($pos12->created_at)));
                        $arr["user_id"] = $pos12->user_id;
                        $arr["user_name"] = $pos12->user_name;
                        if (isset($pos12->first_name) && isset($pos12->last_name))
                            $arr["posted_by"] = $pos12->first_name . ' ' . $pos12->last_name;
                        else if (isset($pos12->first_name))
                            $arr["posted_by"] = $pos12->first_name;
                        else if (isset($pos12->last_name))
                            $arr["posted_by"] = $pos12->last_name;
                        $arr["user_avatar"] = $pos12->user_avatar;
                        $arr["comment_count"] = $pos12->comment_count;
                        $arr["like_count"] = $pos12->like_count;
                        $arr["tempo_user_rank"] = $pos12->tempo_user_rank;
                        array_push($finalarr, $arr);
                        array_push($arrRep, $pos12->user_id);
                    }
                }
            }
        }
        if (count($finalarr) > 0) {
            $listSorted = $this->array_sort($finalarr, 'posted_by', SORT_ASC);
            $newArr = array();
            foreach ($listSorted as $s) {
                array_push($newArr, $s);
            }

            $result['success'] = 1;
            $result['posts'] = $newArr;
        } else {
            $result['success'] = 0;
            $result['message'] = "No friends posts";
            return $response->withJson($result);
        }

        return $response->withJson($result);
    }

    function array_sort($array, $on, $order = SORT_ASC) {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function getTopUserPost($pos) {
        $finalarr = array();
        $postdetails = $this->_db->table('user_posts')
                ->leftJoin('users', 'user_posts.user_post_by', '=', 'users.user_id')
                ->leftJoin('user_post_notifications', 'user_posts.user_post_id', '=', 'user_post_notifications.user_post_id')
                ->where('user_posts.user_post_id', $pos->user_post_id)
                ->where('user_posts.isdeleted', 0)
                ->where('user_post_notifications.to_user_id', $pos->posted_to)
                ->get();
        $chkrep = array();
        if (count($postdetails) > 0) {
            foreach ($postdetails as $pos12) {
                $arr = array();
                if (!in_array($pos12->user_id, $chkrep)) {
                    $arr["user_post_id"] = $pos12->user_post_id;
                    $arr["user_post_type"] = $pos12->user_post_type;
                    $arr["status"] = $pos12->status;
                    $arr["post_created_at"] = str_replace('+00:00', 'Z', gmdate('c', strtotime($pos12->created_at)));
                    $arr["user_id"] = $pos12->user_id;
                    $arr["user_name"] = $pos12->user_name;
                    if (isset($pos12->first_name) && isset($pos12->last_name))
                        $arr["posted_by"] = $pos12->first_name . ' ' . $pos12->last_name;
                    else if (isset($pos12->first_name))
                        $arr["posted_by"] = $pos12->first_name;
                    else if (isset($pos12->last_name))
                        $arr["posted_by"] = $pos12->last_name;
                    $arr["user_avatar"] = $pos12->user_avatar;
                    $arr["like_count"] = $pos12->like_count;
                    $arr["tempo_user_rank"] = $pos12->tempo_user_rank;
                    array_push($chkrep, $pos12->user_id);
                    array_push($finalarr, $arr);
                }
            }
        }
        return $finalarr;
    }

    public function GetFriendPost($request, $response) {
        $postData = $request->getParsedBody();

        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $friendUserID = isset($postData['friendUserID']) ? $postData['friendUserID'] : 0;
        $maxid = isset($postData['maxid']) ? $postData['maxid'] : 0;
        $minid = isset($postData['minid']) ? $postData['minid'] : 0;
        if ($user_id == 0) {
            $result['success'] = 0;
            $result['message'] = "Missing parameters";
            return $response->withJson($result);
        }
        $finalarr = array();
        $result['success'] = 0;
        $AllPostIds = 0;
        // $AllPostIds = $this->getAllPostId($user_id, $friendUserID);

        $postdetails = $this->_db->table('user_posts')
                ->leftJoin('user_posts_to', 'user_posts_to.user_post_id', '=', 'user_posts.user_post_id')
                ->select('user_posts.user_post_id', 'user_posts.user_post_type', 'user_posts.user_post_data', 'user_posts.user_post_thumb', 'user_posts.thumbnailDone', 'user_posts.like_count', 'user_posts.comment_count', 'user_posts.created_at', 'users.user_id', 'users.user_name', 'users.first_name', 'users.last_name', 'users.user_avatar', 'users.tempo_user_rank')
                ->leftJoin('users', 'user_posts.user_post_by', '=', 'users.user_id')
                //->leftJoin('user_post_notifications', 'user_posts.user_post_id', '=', 'user_post_notifications.user_post_id')
                ->where('user_posts_to.posted_to', $user_id)
                ->where('user_posts.user_post_by', $friendUserID)
                ->where('user_posts.isdeleted', 0)
                ->orderBy('user_posts.user_post_id', 'desc')
                ->get();
        if (count($postdetails) > 0) {

            foreach ($postdetails as $pos12) {
                if ($pos12->user_id != $user_id) {
                    $arr = array();
                    $arr["user_post_id"] = $pos12->user_post_id;
                    $arr["user_post_type"] = $pos12->user_post_type;
                    $arr["user_post_data"] = isset($pos12->user_post_data) ? $pos12->user_post_data : '';
                    $arr["user_post_thumb"] = $pos12->user_post_thumb;
                    $arr["thumbnailDone"] = $pos12->thumbnailDone;
                    $arr["status"] = $this->getPostStatus($pos12->user_post_id, $user_id);
                    $arr["post_created_at"] = str_replace('+00:00', 'Z', gmdate('c', strtotime($pos12->created_at)));
                    $arr["user_id"] = $pos12->user_id;
                    $arr["user_name"] = $pos12->user_name;
                    if (isset($pos12->first_name) && isset($pos12->last_name))
                        $arr["posted_by"] = $pos12->first_name . ' ' . $pos12->last_name;
                    else if (isset($pos12->first_name))
                        $arr["posted_by"] = $pos12->first_name;
                    else if (isset($pos12->last_name))
                        $arr["posted_by"] = $pos12->last_name;
                    $arr["user_avatar"] = $pos12->user_avatar;
                    $arr["comment_count"] = $pos12->comment_count;
                    $arr["like_count"] = $pos12->like_count;
                    $arr["tempo_user_rank"] = $pos12->tempo_user_rank;
                    array_push($finalarr, $arr);
                }
            }
        }


        if (count($finalarr) > 0) {
            $result['success'] = 1;
            $result['posts'] = $finalarr;
        } else {
            $result['success'] = 0;
            $result['message'] = "No posts available";
            return $response->withJson($result);
        }

        return $response->withJson($result);
    }

    public function update_post_media_like($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $user_post_id = isset($postData['postid']) ? $postData['postid'] : 0;

        $islike = (int) $postData['islike'];   /// Unlike => 0; Like => 1 

        date_default_timezone_set('UTC');
        $check_post_exists = $this->_db->table('user_posts')
                ->selectRaw('user_post_id')->where(array('user_post_id' => $user_post_id))
                ->where('isdeleted', 0)
                ->get();
        if (count($check_post_exists) == 0) {
            $result['success'] = 0;
            $result['message'] = "Post does not exists";
        } else {
            $check_exists = $this->_db->table('userpost_media_like')
                            ->selectRaw('userpost_media_like_id')
                            ->where(array('user_id' => $userid, 'user_post_id' => $user_post_id))->get();

            if (count($check_exists) == 0) {

                $postmedialike = new UserpostMediaLike;

                $postmedialike->user_id = $userid;
                $postmedialike->user_post_id = $user_post_id;
                $postmedialike->islike = $islike;

                $postmedialike->created_at = date('Y-m-d H:i:s');
                $postmedialike->updated_at = null;
                $postmedialike->save();
                $getlastlike_count = $this->_db->table('userpost_media_like')
                        ->select('userpost_media_like_id')->where(array('user_post_id' => $user_post_id, 'islike' => 1)) // <<== Like Count Only
                        ->count();
                $this->_db->table('user_posts')
                        ->where('user_post_id', $user_post_id)
                        ->update([
                            'like_count' => $getlastlike_count]);

                $result['success'] = 1;
                $result['user_post_id'] = $user_post_id;
                $result['like_count'] = $getlastlike_count;
            } else {
                $postmedialike = new UserpostMediaLike;

                $postmedialike->user_id = $userid;
                $postmedialike->user_post_id = $user_post_id;
                $postmedialike->islike = $islike;
                $postmedialike->updated_at = date("Y-m-d H:i:s");
                $postmedialike->update();
                $this->_db->table('userpost_media_like')
                        ->where((array('user_id' => $userid, 'user_post_id' => $user_post_id)))
                        ->update([
                            'islike' => $islike]);

                $getlastlike_count = $this->_db->table('userpost_media_like')
                        ->select('userpost_media_like_id')->where(array('user_post_id' => $user_post_id, 'islike' => 1))// <<== Like Count Only
                        ->count();
                $this->_db->table('user_posts')
                        ->where('user_post_id', $user_post_id)
                        ->update([
                            'like_count' => $getlastlike_count]);

                $result['success'] = 1;
                $result['user_post_id'] = $user_post_id;
                $result['like_count'] = $getlastlike_count;
            }
            ////Add User POST Notification
            if ($islike == 1) {
                $islikemsg = "like";
                $notitype = 'likepost';

                $notistatus = 'processed';
                $touserid = $this->getUserIdByPost($user_post_id);
                $Notifydata = $this->addUserPostNotification($userid, $touserid, $user_post_id, $notitype, $notistatus, $islikemsg);
                $devInfo = $this->getDeviceInfo($touserid);
                $contentmsg = isset($Notifydata->comment_text) ? $Notifydata->comment_text : "";
                if (count($devInfo) > 0) {
                    foreach ($devInfo as $dev123) {
                        if (isset($dev123->one_signal_userid)) {
                            $one_signal_userid = trim($dev123->one_signal_userid);
                            $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $touserid);
                        }
                    }
                }
            } else {
                $islikemsg = "unlike";
                $notitype = 'unlikepost';
            }
        }

        return $response->withJson($result);
    }

    public function addCommentToPost($request, $response) {
        $postData = $request->getParsedBody();
        $filetype = "";
        date_default_timezone_set('UTC');
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $user_post_id = isset($postData['postid']) ? $postData['postid'] : 0;
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        try {
            $userComments = new UserPostComments;
            $userComments->user_post_id = $user_post_id;

            $userComments->comment_type = (isset($postData['commenttype'])) ? $postData['commenttype'] : '';
            // if ($postData['commenttype'] == 'image' OR $postData['commenttype'] == 'video') {
            if (isset($_FILES['commentdata'])) {
                $files = $_FILES['commentdata'];

                $file_name_expensions = explode('.', $files['name']);

                $new_file_name = $userComments->user_post_id . '_' . time() . '.' . $file_name_expensions[1];
                $file_tmp = $files['tmp_name'];
                if (isset($_FILES['commentdata'])) {
                    $mime = $_FILES['commentdata']['type'];
                    if (strstr($mime, "video/")) {
                        $filetype = "video";
                    } else if (strstr($mime, "image/")) {
                        $filetype = "image";
                    } else if (strstr($mime, "audio/")) {
                        $filetype = "audio";
                    }
                }
                $contentType = $files['type'];
                $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($new_file_name, $file_tmp, $contentType, "comment_post");
                $userComments->comment_data = $uploadedimg;
            } else
                $userComments->comment_data = (isset($postData['commentdata'])) ? $postData['commentdata'] : '';
            $userComments->commented_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
            $userComments->comment_status = (isset($postData['commentstatus'])) ? $postData['commentstatus'] : '';
            $userComments->created_at = date("Y-m-d H:i:s");
            $userComments->updated_at = null;
            $userComments->save();
            $lastinserted = $userComments->id;
            $result['success'] = 1;
            $retarray = array();

            $alluserComments = $this->_db->table('userpost_comments')
                    ->where('userpost_comment_id', $lastinserted)
                    ->get();
            foreach ($alluserComments as $ucom) {
                if (isset($ucom->created_at))
                    $ucom->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($ucom->created_at)));
                if (isset($eve->updated_at))
                    $ucom->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($ucom->updated_at)));

                array_push($retarray, $ucom);
            }
            $result['postcommentinfo'] = $retarray;
            $CountcommentOnPost = 0;
            $CountcommentOnPost = $this->_db->table('userpost_comments')
                    ->where('user_post_id', $user_post_id)
                    ->count();
            $updateposts = $this->_db->table('user_posts')
                    ->where('user_post_id', $user_post_id)
                    ->update([
                'comment_count' => $CountcommentOnPost]);
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }

        ////Add User Notification

        $notitype = 'commentpost';
        $notistatus = 'unread';
        $touserid = $this->getUserIdByPost($user_post_id);
        $userpostmsg = ($filetype == "") ? "commentpost" : $filetype;

        $Notifydata = $this->addUserPostNotification($userid, $touserid, $user_post_id, $notitype, $notistatus, $userpostmsg);
        $devInfo = $this->getDeviceInfo($touserid);
        $contentmsg = isset($Notifydata->comment_text) ? $Notifydata->comment_text : "";
        if (count($devInfo) > 0) {
            foreach ($devInfo as $dev123) {
                if (isset($dev123->one_signal_userid)) {
                    $one_signal_userid = trim($dev123->one_signal_userid);
                    $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $touserid);
                }
            }
        }
        return $response->withJson($result);
    }

    public function markReadOnPost($request, $response) {
        $postData = $request->getParsedBody();
        $user_post_id = isset($postData['postid']) ? $postData['postid'] : 0;
        $user_id = isset($postData['userid']) ? $postData['userid'] : 0;
        $result['success'] = 0;
        $result['message'] = "No Post available";

        $exists_posts = $this->_db->table('user_posts_to')
                ->where('user_post_id', $user_post_id)
                ->where('posted_to', $user_id)
                ->select('user_posts_to_id')
                ->get();
        if (isset($exists_posts[0])) {
            $posts = $this->_db->table('user_posts_to')
                    ->where('user_post_id', $user_post_id)
                    ->where('posted_to', $user_id)
                    ->update([
                'status' => 'read']);
            $result['success'] = 1;
            $result['message'] = "Post updated successfully";
        }

        return $response->withJson($result);
    }

    public function getUserIdByPost($user_post_id) {
        $id = 0;
        $users = $this->_db->table('user_posts')
                ->select('user_post_by')
                ->where('user_post_id', $user_post_id)
                ->get();
        if (isset($users[0]->user_post_by)) {

            $id = $users[0]->user_post_by;
        }

        return $id;
    }

    public function getAllfrinedIds($user_id) {
        $idsarr = array();
        $allids = '';
        $users = $this->_db->table('user_network')
                ->select('network_user_id')
                ->where('primary_user_id', $user_id)
                ->get();
        if (count($users) > 0) {
            foreach ($users as $n)
                array_push($idsarr, $n->network_user_id);
        }

        return $idsarr;
    }

    public function getPostStatus($user_post_id, $user_id) {
        $status = 'unread';
        $users = $this->_db->table('user_posts_to')
                ->select('status')
                ->where('user_post_id', $user_post_id)
                ->where('posted_to', $user_id)
                ->get();

        if (isset($users[0]->status)) {

            $status = $users[0]->status;
        }
//echo $user_post_id." ".$user_id;
        return $status;
    }

    public function getLatestPostId($user_id) {
        $idsarr = array();
        $lastid = 0;
        $users = $this->_db->table('user_posts')
                ->select('user_post_id')
                ->where('isdeleted', 0)
                ->where('user_post_by', $user_id)
                ->get();

        if (count($users) > 0) {
            foreach ($users as $n)
                array_push($idsarr, $n->user_post_id);
        }
        $lastid = end($idsarr);
        return $lastid;
    }

    public function getAllPostId($user_id, $friendUserID) {
        $idsarr = array();
        $users = $this->_db->table('user_posts_to')
                ->select('user_post_id')
                ->where('isdeleted', 0)
                ->where('user_post_by', $user_id)
                ->get();

        if (count($users) > 0) {
            foreach ($users as $n)
                array_push($idsarr, $n->user_post_id);
        }

        return $idsarr;
    }

    public function checEmailExists($user_email, $user_id) {
        $id = 0;
        $users = $this->_db->table('users')
                ->select('user_id')
                ->where('user_email', $user_email)
                ->where('user_id', '!=', $user_id)
                ->get();
        if (isset($users[0]->user_id)) {
            $id = $users[0]->user_id;
        }

        return $id;
    }

    public function checPhoneExists($user_phone, $user_id) {
        $id = 0;
        $users = $this->_db->table('users')
                ->select('user_id')
                ->where('user_phone', $user_phone)
                ->where('user_id', '!=', $user_id)
                ->get();
        if (isset($users[0]->user_id)) {
            $id = $users[0]->user_id;
        }

        return $id;
    }

    public function getisUserLikedPost($user_post_id, $user_id) {

        $isLike = $this->_db->table('userpost_media_like')
                ->where('user_post_id', $user_post_id)
                ->where('user_id', $user_id)
                ->select('islike')
                ->get();
        if (count($isLike) > 0) {
            if ($isLike[0]->islike == 1)
                return true;
            else
                return false;
        } else
            return false;
    }

    public function getMyEventsFromVarious($userid, $maxid = 0, $minid = 0) {
        $arrCo_event_id = array();
        $arrInv_event_id = array();
        $myevent = array();

        $myeventfromInvi = $this->_db->table('event_invitations')
                ->select('event_id')
                ->where('invited_to_userid', $userid)
                ->where('invitation_status', 'Accepted')
                ->get();

        if (isset($myeventfromInvi) && count($myeventfromInvi) > 0) {
            foreach ($myeventfromInvi as $Inv) {
                array_push($arrInv_event_id, $Inv->event_id);
            }
        }
        $myeventfromCo = $this->_db->table('event_co_host')
                ->select('event_id')
                ->where('co_host_user_id', $userid)
                ->where('status', 'Accepted')
                ->get();

        if (isset($myeventfromCo) && count($myeventfromCo) > 0) {
            foreach ($myeventfromCo as $co) {
                array_push($arrCo_event_id, $co->event_id);
            }
        }

        if (count($arrCo_event_id) > 0 && count($arrInv_event_id) > 0) {
            $arrCo_event_id = array_reverse($arrCo_event_id);
            $arrInv_event_id = array_reverse($arrInv_event_id);

            if ($maxid == 0 && $minid == 0) {
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->orwhereIn('event_id', $arrCo_event_id)
                        ->orwhereIn('event_id', $arrInv_event_id)
                        ->get();
            }
            if ($maxid > 0 && $minid == 0) {
                $arrCo_event_id = array_slice($arrCo_event_id, 0, $maxid);
                $arrInv_event_id = array_slice($arrInv_event_id, 0, $maxid);
                $myevent = $this->_db->table('events')
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->where('event_id', '>', $maxid)
                        ->whereIn('event_id', $arrCo_event_id)
                        ->orwhereIn('event_id', $arrInv_event_id)
                        ->orwhere('created_by_user', $userid)
                        ->get();
            }
            if ($maxid == 0 && $minid > 0) {

                $arrCo_event_id = array_slice($arrCo_event_id, $minid);
                $arrInv_event_id = array_slice($arrInv_event_id, $minid);

                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->whereIn('event_id', $arrCo_event_id)
                        ->orwhereIn('event_id', $arrInv_event_id)
                        ->get();
            }
        } else if (count($arrInv_event_id) > 0) {
            $arrInv_event_id = array_reverse($arrInv_event_id);

            if ($maxid == 0 && $minid == 0) {
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->orwhereIn('event_id', $arrInv_event_id)
                        ->get();
            }
            if ($maxid > 0 && $minid == 0) {
                $arrInv_event_id = array_slice($arrInv_event_id, 0, $maxid);
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->whereIn('event_id', $arrInv_event_id)
                        ->orwhere('created_by_user', $userid)
                        ->get();
            }
            if ($maxid == 0 && $minid > 0) {
                $arrInv_event_id = array_slice($arrInv_event_id, $minid);
                $myevent = $this->_db->table('events')
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->whereIn('event_id', $arrInv_event_id)
                        ->orwhere('created_by_user', $userid)
                        ->get();
            }
        } else if (count($arrCo_event_id) > 0) {
            $arrCo_event_id = array_reverse($arrCo_event_id);
            if ($maxid == 0 && $minid == 0) {
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->orwhereIn('event_id', $arrCo_event_id)
                        ->get();
            }
            if ($maxid > 0 && $minid == 0) {
                $arrCo_event_id = array_slice($arrCo_event_id, 0, $maxid);
                $myevent = $this->_db->table('events')
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->where('event_id', $arrCo_event_id)
                        ->orwhereIn('created_by_user', $userid)
                        ->get();
            }
            if ($maxid == 0 && $minid > 0) {
                $arrCo_event_id = array_slice($arrCo_event_id, $minid);
                $myevent = $this->_db->table('events')
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->where('event_id', $arrCo_event_id)
                        ->orwhereIn('created_by_user', $userid)
                        ->get();
            }
        } else {
            if ($maxid == 0 && $minid == 0) {
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->get();
            }
            if ($maxid > 0 && $minid == 0) {
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->where('event_id', '>', $maxid)
                        ->get();
            }
            if ($maxid == 0 && $minid > 0) {
                $myevent = $this->_db->table('events')
                        ->where('created_by_user', $userid)
                        ->where('is_published', 1)
                        ->where('isdeleted', 0)
                        ->where('event_id', '>', $minid)
                        ->get();
            }
        }
        $retarray = array();
        if (count($myevent) > 0) {
            foreach ($myevent as $eve) {
                if ($eve->isdeleted != 1) {
                    if (isset($eve->event_start_datetime))
                        $eve->event_start_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->event_start_datetime)));
                    if (isset($eve->event_end_datetime))
                        $eve->event_end_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->event_end_datetime)));

                    if (isset($eve->event_publised_on))
                        $eve->event_publised_on = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->event_publised_on)));
                    if (isset($eve->created_at))
                        $eve->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->created_at)));
                    if (isset($eve->updated_at))
                        $eve->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->updated_at)));
                    array_push($retarray, $eve);
                }
            }
        }
        return $retarray;
    }

    public function getUserBasicInfo($userid) {
        $name = "";
        $arr = array();
        if (isset($userid)) {
            $users = $this->_db->table('users')
                    ->select('first_name', 'last_name', 'user_avatar')
                    ->where('user_id', $userid)
                    ->get();
            $arr["user_id"] = $userid;
            $arr["first_name"] = (isset($users[0]->first_name)) ? $users[0]->first_name : "";
            $arr["last_name"] = (isset($users[0]->last_name)) ? $users[0]->last_name : "";
            $arr["user_avatar"] = (isset($users[0]->user_avatar)) ? $users[0]->user_avatar : "https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png";
        }
        return $arr;
    }

    public function deleteuserpost($request, $response) {
        $postData = $request->getParsedBody();
        $user_post_id = isset($postData['postid']) ? $postData['postid'] : 0;

        $result['success'] = 0;
        $result['message'] = "No Post available";

        $exists_user_post = $this->_db->table('user_posts')
                ->where('user_post_id', $user_post_id)
                ->select('user_post_id')
                ->get();
        if (isset($exists_user_post[0])) {
            $this->_db->table('user_posts')
                    ->where('user_post_id', $user_post_id)->delete();
            $this->_db->table('userpost_media_like')
                    ->where('user_post_id', $user_post_id)->delete();
            $this->_db->table('userpost_comments')
                    ->where('user_post_id', $user_post_id)->delete();
//                    ->update([
//                        'isdeleted' => 1]);

            $result['success'] = 1;
            $result['message'] = "Post deleted successfully";
        }
        return $response->withJson($result);
    }

    public function userTwitterInfo($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $user = $this->_db->table('users')
                ->select('user_unique_id')
                ->where('user_id', $userid)
                ->where('user_type', 'twitter')
                ->get();
        if (isset($user[0])) {
            $user_unique_id = trim($user->user_unique_id);
        }
        // require_once('TwitterAPIExchange.php'); //https://github.com/J7mbo/twitter-api-php

        /** Access token (https://dev.twitter.com/apps/) * */
        $access_token_settings = array(
            'oauth_access_token' => "YOUR_OAUTH_ACCESS_TOKEN",
            'oauth_access_token_secret' => "YOUR_OAUTH_ACCESS_TOKEN_SECRET",
            'consumer_key' => "5NAVUjm9uhzK2aKxiiloa1k1S",
            'consumer_secret' => "UMZ23zRA2AZQZOXMLSdsDKpJO5JoPUAWoOsWYQXgk2LaQZhUVD"
        );

        $twitter_api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name=prafullanatu';
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($access_token_settings);
        $follow_count = $twitter->setGetfield($getfield)->buildOauth($twitter_api_url, $requestMethod)->performRequest();
        $data = json_decode($follow_count, true);
        $followers_count = $data[0]['user']['followers_count'];
        echo $followers_count;
        /*
          $user = $connection->get("https://api.twitter.com/1.1/users/show.json?screen_name=".$twitteruser);
          $following = intval($user->friends_count);
          $followers = intval($user->followers_count);
          $tweets = intval($user->statuses_count);
         */
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

    public function SendSms($aMsgData) {
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        if ($aMsgData['to'] != '') {
            $result = file_get_contents('http://api.clickatell.com/http/sendmsg?api_id=' . $CLICKATELL_API .
                    '&user=' . $CLICKATELL_USERNAME . '&password=' . $CLICKATELL_PASSWORD . '&to=' . $aMsgData['to'] .
                    '&text=' . substr(urlencode($aMsgData['msg']), 0, 160));
            return $result;
        }
    }

    function generateThumbnail($img, $imgname, $width = 200, $height = 200, $quality = 90) {
        if (is_file($img)) {
            $imagick = new \Imagick($img);
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($quality);
            $imagick->thumbnailImage($width, $height, false, false);
            $filename_no_ext = "user_post/thumb_" . $imgname;
            if (file_put_contents($filename_no_ext . '.jpg', $imagick) === false) {
                throw new Exception("Could not put contents.");
            }
            return true;
        } else {
            throw new Exception("No valid image provided with {$img}.");
        }
    }

    function UploadVideoThumbnail($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        ini_set('session.gc_maxlifetime', 10800);    # 3 hours
        $basePath = $request->getUri()->getBasePath();
        $user_post_id = $postData['mediaid'];
        $uploadedThumbimg = $uploadedimg = $thumbnail = "";
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');

        if (isset($_FILES['videothumbnail']) && $_FILES['videothumbnail']['error'] === 0) {
            $files = $_FILES['videothumbnail'];
            $file_name_expensions = explode('.', $files['name']);
            $imgname = (time() + 100) . '.' . $file_name_expensions[1];
            $file_tmp = $files['tmp_name'];
            $contentType = $files['type'];
            $uploadedThumbimg = $awsS3Url . $bucket . "/" . $this->awsupload("thumb_" . $imgname, $file_tmp, $contentType, "user_post_thumbs");
            if (isset($postData['mediaid'])) {
                $this->_db->table('user_posts')
                        ->where('user_post_id', $user_post_id)
                        ->update([
                            'user_post_thumb' => $uploadedThumbimg, 'thumbnailDone' => 1, 'updated_at' => date("Y-m-d H:i:s")]);
            }
            $result['success'] = 1;
            $result['user_post_thumb'] = $uploadedThumbimg;

            return $response->withJson($result);
        }
    }

    public function updateOneSignalNotifyFlag($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? $postData['userid'] : 0;
        $isNotify = isset($postData['isNotify']) ? $postData['isNotify'] : 0;
        $result['success'] = 0;
        $result['message'] = "No User exists";

        $exists_user_post = $this->_db->table('users')
                ->select('IsNotification')
                ->where('user_id', $userid)
                ->get();
        if (isset($exists_user_post[0])) {
            $updateposts = $this->_db->table('users')
                    ->where('user_id', $userid)
                    ->update(['IsNotification' => $isNotify]);

            $result['success'] = 1;
            if ($isNotify == 0)
                $result['message'] = "One Signal Notification is disabled.";
            else
                $result['message'] = "One Signal Notification is enabled.";
        }
        return $response->withJson($result);
    }

}
