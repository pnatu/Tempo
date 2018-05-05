<?php

namespace App\Controllers;

use Illuminate\Database\Query\Builder;
use App\Models\Event;
use App\Models\EventAssociation;
use App\Models\EventInvitations;
use App\Models\EventComments;
use App\Models\EventCoHost;
use App\Models\EventPost;
use App\Models\UsersRanking;
use App\Models\EventPostsTo;
use App\Models\EventMediaLike;
use App\Models\UserNotification;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class EventController {

    protected $ci;
    protected $_logger;
    protected $_db;
    protected $n;

    //Constructor
    public function __construct(\Slim\Container $ci) {
        $this->ci = $ci;
        $this->_logger = $this->ci->get('logger');
        $this->_db = $this->ci['db'];
        $this->n = $this->ci->get('n');
    }

    public function creatEvent($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');

        if (isset($postData['eventid'])) {
            //edit mode
            $updateData = [];

            if (isset($postData['eventhost']))
                $updateData['event_host'] = $postData['eventhost'];
            if (isset($postData['eventname']))
                $updateData['event_name'] = $postData['eventname'];
            if (isset($postData['description']))
                $updateData['event_description'] = $postData['description'];
            if (isset($postData['category']))
                $updateData['event_category'] = $postData['category'];
            if (isset($postData['startdatetime']))
                $updateData['event_start_datetime'] = date('Y-m-d H:i:s', strtotime($postData['startdatetime']));
            if (isset($postData['enddatetime']))
                $updateData['event_end_datetime'] = date('Y-m-d H:i:s', strtotime($postData['enddatetime']));

            if (isset($postData['ispublished']))
                $updateData['is_published'] = $postData['ispublished'];
            if (isset($postData['isprivate']))
                $updateData['is_private'] = $postData['isprivate'];
            if (isset($postData['keywords']))
                $updateData['key_words'] = $postData['keywords'];
            if (isset($postData['eventvenue']))
                $updateData['event_venu'] = $postData['eventvenue'];
            if (isset($postData['address']))
                $updateData['event_address'] = $postData['address'];
            if (isset($postData['zipcode']))
                $updateData['event_zipcode'] = $postData['zipcode'];
            if (isset($postData['city']))
                $updateData['event_city'] = $postData['city'];
            if (isset($postData['state']))
                $updateData['event_state'] = $postData['state'];
            if (isset($postData['country']))
                $updateData['event_country'] = $postData['country'];
            if (isset($postData['latitude']))
                $updateData['latitude'] = $postData['latitude'];
            if (isset($postData['longitude']))
                $updateData['longitude'] = $postData['longitude'];
            if (isset($postData['maxnoseat']))
                $updateData['max_no_of_seats'] = $postData['maxnoseat'];
            if (isset($postData['perseatcoast']))
                $updateData['per_seat_cost'] = $postData['perseatcoast'];
            if (isset($postData['userid']))
                $updateData['created_by_user'] = $postData['userid'];
            if (isset($postData['eventstatus']))
                $updateData['event_status'] = $postData['eventstatus'];
            if ($updateData['is_published'] == '1') {
                $updateData['event_publised_on'] = date('Y-m-d H:i:s');
            }
            if (isset($postData['guestcaninvitefriend']))
                $updateData['guest_can_invite_friend'] = $postData['guestcaninvitefriend'];
            try {
                // var_dump($updateData);exit;
                $eventDefination = $this->_db->table('events')
                        ->where('event_id', $postData['eventid'])
                        ->update($updateData);

                $result['success'] = 1;
                $result['message'] = "Event Updated Successfully!";
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {

            try {
                $event = new Event;
                $event->event_host = (isset($postData['eventhost'])) ? $postData['eventhost'] : '';
                $event->event_name = (isset($postData['eventname'])) ? $postData['eventname'] : '';
                $event->event_description = (isset($postData['description'])) ? $postData['description'] : '';
                $event->event_category = (isset($postData['category'])) ? $postData['category'] : '';
                if (isset($postData['startdatetime']))
                    $event->event_start_datetime = date('Y-m-d H:i:s', strtotime($postData['startdatetime']));
                else
                    $event->event_start_datetime = null;
                if (isset($postData['enddatetime']))
                    $event->event_end_datetime = date('Y-m-d H:i:s', strtotime($postData['enddatetime']));
                else
                    $event->event_end_datetime = null;
                $event->is_published = (isset($postData['ispublished'])) ? $postData['ispublished'] : 0;
                $event->is_private = (isset($postData['isprivate'])) ? $postData['isprivate'] : 0;
                $event->key_words = (isset($postData['keywords'])) ? $postData['keywords'] : '';
                $event->event_venu = (isset($postData['eventvenue'])) ? $postData['eventvenue'] : '';
                $event->event_address = (isset($postData['address'])) ? $postData['address'] : '';
                $event->event_zipcode = (isset($postData['zipcode'])) ? $postData['zipcode'] : '';
                $event->event_city = (isset($postData['city'])) ? $postData['city'] : '';
                $event->event_state = (isset($postData['state'])) ? $postData['state'] : '';
                $event->event_country = (isset($postData['country'])) ? $postData['country'] : '';
                $event->latitude = (isset($postData['latitude'])) ? $postData['latitude'] : 0;
                $event->longitude = (isset($postData['longitude'])) ? $postData['longitude'] : 0;
                $event->max_no_of_seats = (isset($postData['maxnoseat'])) ? $postData['maxnoseat'] : 0;
                $event->per_seat_cost = (isset($postData['perseatcoast'])) ? $postData['perseatcoast'] : 0;
                $event->created_by_user = (isset($postData['userid'])) ? $postData['userid'] : 0;
                $event->guest_can_invite_friend = (isset($postData['guestcaninvitefriend'])) ? $postData['guestcaninvitefriend'] : '';
                $event->event_status = (isset($postData['eventstatus'])) ? $postData['eventstatus'] : 'Drafts';
                if ($event->is_published == '1') {
                    $event->event_publised_on = date('Y-m-d H:i:s');
                }

                $event->save();

                $myevent = $this->_db->table('events')
                        ->where('isdeleted', 0)
                        ->take(1)
                        ->latest()
                        ->get();
                $evenew = array();
                foreach ($myevent as $eve) {
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
                    array_push($evenew, $eve);
                }
                //$event->eventid = $event->id;
                $result['success'] = 1;
                $result['message'] = $evenew;

                // posts of user in last 'n' days 
                $n = $this->n;
                $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
                $to_date = date('Y-m-d' . ' 22:00:40', time());

                $posts = $this->_db->table('user_posts')
                        ->where('user_post_by', $postData['userid'])
                        ->whereBetween('created_at', [$from_date, $to_date])
                        ->get();
                $posts_count = count($posts);
                $userevents = $this->_db->table('events')
                        ->where('isdeleted', 0)
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

                //event rating
                $eventid = $event->id;
                if (isset($eventid)) {
                    $event_rating = 0;
                    $event_rating = $this->creator_tempo_rating($eventid);

                    $event_creator = $this->_db->table('events')
                            ->where('isdeleted', 0)
                            ->where('created_by_user', $postData['userid'])
                            ->where('event_id', $eventid)
                            ->get();
                    if (count($event_creator) >= 1) {
                        $event_creator_rating = count($event_creator);
                        $event_rating += 1 * $event_creator_rating;
                        $last_rating = $this->last_event_rating($eventid);
                        $last_rating += 1 * $event_creator_rating;
                    }
                    $evetRankUpdate = $this->_db->table('events')
                            ->where('event_id', $eventid)
                            ->update([
                        'event_rating' => $event_rating,
                        'last_value' => $last_rating]);
                }
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }

        return $response->withJson($result);
    }

    /* public function eventList($request, $response) {
      $postData = $request->getParsedBody();
      date_default_timezone_set('UTC');
      $result['success'] = 0;
      //check  user  exists
      if (isset($postData['userid'])) {
      $listType = 'feeds';
      $myevent = $this->getMyEventsFromVarious($postData['userid'], $listType);
      if (isset($myevent) && count($myevent) >= 1) {
      $result['success'] = 1;
      $evenew = array();
      foreach ($myevent as $eve) {
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

      $cohost = array();
      $cohost = $this->getCohosts($eve->event_id);
      if (count($cohost) > 0)
      $eve->cohost = $cohost;
      else
      $eve->cohost = 0;

      $invitedGuests = array();
      $invitedGuests = $this->invitedGuests($eve->event_id, $postData['userid']);
      if (count($invitedGuests) > 0)
      $eve->invitedGuests = $invitedGuests;
      else
      $eve->invitedGuests = 0;


      array_push($evenew, $eve);
      }
      $result['myevent'] = $evenew;
      }
      }

      if (isset($postData['city'])) {
      $trending_in_city = $this->_db->table('events')
      ->where('isdeleted', 0)
      ->where('event_city', $postData['city'])
      ->take(10)
      ->latest()
      ->get();
      if (isset($trending_in_city) && count($trending_in_city) >= 1) {
      $result['success'] = 1;
      $trendingincitynew = array();
      foreach ($trending_in_city as $eve) {
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
      array_push($trendingincitynew, $eve);
      }

      $result['trendingincity'] = $trending_in_city;
      }
      }
      $from_date = date("Y-m-d");
      $to_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") + 10, date("Y")));
      if (isset($postData['city'])) {
      $upcoming_events = $this->_db->table('events')
      ->where('event_city', $postData['city'])
      ->where('isdeleted', 0)
      ->whereBetween('event_start_datetime', [$from_date, $to_date])
      ->where('is_published', 1)
      ->take(10)
      ->latest()
      ->get();
      }



      if (isset($upcoming_events) && count($upcoming_events) >= 1) {
      $result['success'] = 1;
      $evenewup = array();
      foreach ($upcoming_events as $eve) {
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
      array_push($evenewup, $eve);
      }
      $result['upcoming_events'] = $upcoming_events;
      }

      if (isset($postData['latitude']) && isset($postData['longitude'])) {
      $distance = 30;
      $radius_km = 100;
      $radius_mi = 62;
      $radius = $radius_mi;
      $latitude = $postData['latitude'];
      $longitude = $postData['longitude'];

      $nearby_events_distance = $this->_db->table('events')
      ->selectRaw('event_id, ( ' . $radius . ' * acos( cos( radians(' . $latitude . ') ) * cos(radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + '
      . 'sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) AS distance')
      ->where('is_published', 1)
      ->where('isdeleted', 0)
      ->having('distance', '<=', $distance)
      ->take(10)
      ->latest()
      ->get();

      if (isset($nearby_events_distance) && count($nearby_events_distance) >= 1) {
      foreach ($nearby_events_distance as $key => $value) {
      $event_ids[] = $value->event_id;
      }
      $nearby_events = $this->_db->table('events')
      ->where('isdeleted', 0)
      ->whereIn('event_id', $event_ids)
      ->get();
      $result['success'] = 1;
      $evenewnearby_events = array();
      foreach ($nearby_events as $eve) {
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
      array_push($evenewnearby_events, $eve);
      }

      $result['nearby_events'] = $nearby_events;
      }
      }
      if (isset($postData['search']) and ! empty($postData['search'])) {
      $searchevent = $this->_db->table('events')
      ->where('isdeleted', 0)
      ->where('event_name', 'LIKE', '%' . $postData['search'] . '%')
      ->orWhere('event_description', 'LIKE', '%' . $postData['search'] . '%')
      ->orWhere('event_city', 'LIKE', '%' . $postData['search'] . '%')
      ->take(10)
      ->latest()
      ->get();
      if (isset($searchevent) && count($searchevent) >= 1) {
      $result['success'] = 1;
      $evenewsearchevent = array();
      foreach ($searchevent as $eve) {
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
      array_push($evenewsearchevent, $eve);
      }

      $result['searchevent'] = $searchevent;
      }
      }
      return $response->withJson($result);
      } */

    public function eventList($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $result['success'] = 0;
        $curdate = date("YmdHis");
        //check  user  exists
        $userdatetime = isset($postData["userdatetime"]) ? $postData["userdatetime"] : '';
        if (isset($postData['userid'])) {
            $listType = 'feeds';
            $myevent = $this->getMyEventsFromVarious($postData['userid'], $listType);
            if (isset($myevent) && count($myevent) >= 1) {
                $result['success'] = 1;
                $evenew = array();
                foreach ($myevent as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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

                        $cohost = array();
                        $cohost = $this->getCohosts($eve->event_id);
                        if (count($cohost) > 0)
                            $eve->cohost = $cohost;
                        else
                            $eve->cohost = 0;

                        $invitedGuests = array();
                        $invitedGuests = $this->invitedGuests($eve->event_id, $postData['userid']);
                        if (count($invitedGuests) > 0)
                            $eve->invitedGuests = $invitedGuests;
                        else
                            $eve->invitedGuests = 0;


                        array_push($evenew, $eve);
                    }
                }
                $result['myevent'] = $evenew;
            } else
                $result['myevent'] = array();
        }

        if (isset($postData['city'])) {
            $trending_in_city = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('event_city', $postData['city'])
                    ->take(10)
                    ->latest()
                    ->get();
            if (isset($trending_in_city) && count($trending_in_city) >= 1) {
                $result['success'] = 1;
                $trendingincitynew = array();
                foreach ($trending_in_city as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                        array_push($trendingincitynew, $eve);
                    }
                }

                $result['trendingincity'] = $trendingincitynew;
            }
        }
        if ($userdatetime != "") {
            $userdatetime123 = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($userdatetime)));

            if (isset($postData['city'])) {
                $upcoming_events = $this->_db->table('events')
                        ->where('event_city', $postData['city'])
                        ->where('isdeleted', 0)
                        ->where('event_start_datetime', '>', $userdatetime123)
                        ->where('is_published', 1)
                        ->take(10)
                        ->latest()
                        ->get();
            }
        } else {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") + 10, date("Y")));
            if (isset($postData['city'])) {
                $upcoming_events = $this->_db->table('events')
                        ->where('event_city', $postData['city'])
                        ->where('isdeleted', 0)
                        ->whereBetween('event_start_datetime', [$from_date, $to_date])
                        ->where('is_published', 1)
                        ->take(10)
                        ->latest()
                        ->get();
            }
        }



        if (isset($upcoming_events) && count($upcoming_events) >= 1) {
            $result['success'] = 1;
            $evenewup = array();
            foreach ($upcoming_events as $eve) {
                $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                    array_push($evenewup, $eve);
                }
            }
            $result['upcoming_events'] = $evenewup;
        }

        if (isset($postData['latitude']) && isset($postData['longitude'])) {
            $distance = 30;
            $radius_km = 100;
            $radius_mi = 62;
            $radius = $radius_mi;
            $latitude = $postData['latitude'];
            $longitude = $postData['longitude'];

//            $nearby_events_distance = $this->_db->table('events')
//                    ->selectRaw('event_id, ( ' . $radius . ' * acos( cos( radians(' . $latitude . ') ) * cos(radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + '
//                            . 'sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) AS distance')
//                    ->where('is_published', 1)
//                    ->where('isdeleted', 0)
//                    ->having('distance', '<=', $distance)
//                    ->take(10)
//                    ->latest()
//                    ->get();
            $nearby_events_distance = $this->_db->table('events')
                    ->selectRaw('event_id, (3959 * ACOS( COS(RADIANS(' . $latitude . ')) * COS(RADIANS(latitude)) * COS(
                                                        RADIANS(longitude) - RADIANS(' . $longitude . ') ) + SIN(RADIANS(' . $latitude . ')) * SIN(RADIANS(latitude))
                                                    )
                                                  ) AS distance')
                    ->where('is_published', 1)
                    ->where('isdeleted', 0)
                    ->having('distance', '<=', $distance)
                    ->orderBy('distance', 'asc')
                    ->take(10)
                    ->latest()
                    ->get();

            if (isset($nearby_events_distance) && count($nearby_events_distance) >= 1) {
                foreach ($nearby_events_distance as $key => $value) {
                    $event_ids[] = $value->event_id;
                }
                $nearby_events = $this->_db->table('events')
                        ->where('isdeleted', 0)
                        ->whereIn('event_id', $event_ids)
                        ->get();
                $result['success'] = 1;
                $evenewnearby_events = array();
                foreach ($nearby_events as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                        array_push($evenewnearby_events, $eve);
                    }
                }

                $result['nearby_events'] = $evenewnearby_events;
            }
        }
        if (isset($postData['search']) and ! empty($postData['search'])) {
            $searchevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('event_name', 'LIKE', '%' . $postData['search'] . '%')
                    ->orWhere('event_description', 'LIKE', '%' . $postData['search'] . '%')
                    ->orWhere('event_city', 'LIKE', '%' . $postData['search'] . '%')
                    ->take(10)
                    ->latest()
                    ->get();
            if (isset($searchevent) && count($searchevent) >= 1) {
                $result['success'] = 1;
                $evenewsearchevent = array();
                foreach ($searchevent as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                        array_push($evenewsearchevent, $eve);
                    }
                }

                $result['searchevent'] = $evenewsearchevent;
            }
        }
        return $response->withJson($result);
    }

    public function setEventVenue($request, $response) {
        $postData = $request->getParsedBody();
        $eventDefination = $this->_db->table('events')
                ->where('event_id', $postData['eventid'])
                ->update([
            'event_address' => $postData['address'],
            'latitude' => $postData['latitude'],
            'longitude' => $postData['longitude'],
            'updated_by' => $postData['updatedby']
        ]);

        $result['success'] = 1;
        $result['message'] = "Event Venue Set Successfully!";

        return $response->withJson($result);
    }

    public function geteventInfo($request, $response) {
        $postData = $request->getParsedBody();
        $eventInfo = $this->_db->table('events')
                //->where('isdeleted', 0)
                ->where('event_id', $postData['eventid'])
                ->get();
        if (isset($eventInfo) && count($eventInfo) >= 1) {
            $result['success'] = 1;
            $new_eve = array();
            foreach ($eventInfo as $eve) {
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

                $cohost = array();
                $cohost = $this->getCohosts($eve->event_id, 'myevents');
                if (count($cohost) > 0)
                    $eve->cohost = $cohost;
                else
                    $eve->cohost = 0;

                $invitedGuests = array();
                $invitedGuests = $this->invitedGuests($eve->event_id, $eve->created_by_user, 'myevents');
                if (count($invitedGuests) > 0) {
                    $invitedGuestsarr = array();
                    foreach ($invitedGuests as $d) {
                        if (count($d) > 0)
                            array_push($invitedGuestsarr, $d);
                    }
                    $eve->invitedGuests = $invitedGuestsarr;
                } else
                    $eve->invitedGuests = 0;
                array_push($new_eve, $eve);
            }

            $result['eventinfo'] = $new_eve;
        } else {
            $result['success'] = 0;
            $result['eventinfo'] = "Event not found";
        }
        return $response->withJson($result);
    }

    public function setAssociatedevents($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $associationevent = $this->_db->table('event_association')
                ->where('event_id', $postData['eventid'])
                ->get();
        if (isset($associationevent) && count($associationevent) >= 1) {
            if (isset($postData['associationdatetime'])) {
                $associationdatetime = date('Y-m-d H:i:s', strtotime($postData['associationdatetime']));
                $updateEvent = $this->_db->table('event_association')
                        ->where('event_id', $postData['eventid'])
                        ->update([
                    'association_type' => $postData['associationtype'],
                    'associated_userid' => $postData['userid'],
                    'association_datetime' => $associationdatetime,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $updateEvent = $this->_db->table('event_association')
                        ->where('event_id', $postData['eventid'])
                        ->update([
                    'association_type' => $postData['associationtype'],
                    'associated_userid' => $postData['userid'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            $result['success'] = 1;
            $result['message'] = "Event Association Updated Successfully!";
        } else {
            $eventAssociation = new EventAssociation;
            $eventAssociation->event_id = (isset($postData['eventid'])) ? $postData['eventid'] : 0;
            $eventAssociation->associated_userid = (isset($postData['userid'])) ? $postData['userid'] : 0;
            $eventAssociation->association_type = (isset($postData['associationtype'])) ? $postData['associationtype'] : '';
            $eventAssociation->association_datetime = (isset($postData['associationdatetime'])) ? date('Y-m-d H:i:s', strtotime($postData['associationdatetime'])) : '';
            $eventAssociation->created_at = date('Y-m-d H:i:s');
            $eventAssociation->updated_at = null;
            $eventAssociation->save();
            $lastinserted = $eventAssociation->id;
            $result['success'] = 1;

            $eveassociation = $this->_db->table('event_association')
                    ->where('event_association_id', $lastinserted)
                    ->get();
            $eveinv = array();

            foreach ($eveassociation as $us) {
                if (isset($us->association_datetime))
                    $us->association_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->association_datetime)));
                if (isset($us->updated_at))
                    if (isset($us->created_at))
                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                if (isset($us->updated_at))
                    $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                array_push($eveinv, $us);
            }
            $result['eventassociationinfo'] = $eveinv;
        }
        return $response->withJson($result);
    }

    public function getAssociatedevents($request, $response) {
        $postData = $request->getParsedBody();
        $associationevent = $this->_db->table('event_association')
                ->where('event_id', $postData['eventid'])
                ->get();
        if (isset($associationevent) && count($associationevent) >= 1) {
            $result['success'] = 1;
            $eveinv = array();
            foreach ($associationevent as $us) {
                if (isset($us->association_datetime))
                    $us->association_datetime = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->association_datetime)));
                if (isset($us->created_at))
                    $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                if (isset($us->updated_at))
                    $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                array_push($eveinv, $us);
            }
            $result['message'] = $eveinv;
        } else {
            $result['success'] = 0;
            $result['message'] = 'Associated Events Not found';
        }
        return $response->withJson($result);
    }

    public function inviteToEvent($request, $response) {
        date_default_timezone_set('UTC');
        $postData = $request->getParsedBody();
        $postData['eventid'] = isset($postData['eventid']) ? $postData['eventid'] : 0;
        $pos = strpos($postData['invitedto'], ',');
        if ($pos === false) {
            $invitedto[] = $postData['invitedto'];
        } else {
            $invitedto = explode(',', $postData['invitedto']);
        }
        foreach ($invitedto as $invited_to) {
            $invitationsevent = $this->_db->table('event_invitations')
                    ->where('event_id', $postData['eventid'])
                    ->where('invited_to_userid', $invited_to)
                    ->where('invited_from_userid', $postData['invitedfrom'])
                    ->get();
            if (isset($invitationsevent) && count($invitationsevent) >= 1) {
                $result['success'] = 0;
                $result['message'][] = 'Event Invitations alredy sent to ' . $invited_to;
            } else {
                $eventInvitations = new EventInvitations;
                $eventInvitations->event_id = (isset($postData['eventid'])) ? $postData['eventid'] : 0;
                $eventInvitations->invited_to_userid = (isset($invited_to)) ? $invited_to : 0;
                $eventInvitations->invited_from_userid = (isset($postData['invitedfrom'])) ? $postData['invitedfrom'] : 0;
                $eventInvitations->invitation_status = (isset($postData['status'])) ? $postData['status'] : 'Invited';

                $eventInvitations->created_at = date("Y-m-d H:i:s");
                $eventInvitations->updated_at = null;
                $eventInvitations->save();
                $lastinserted = $eventInvitations->id;
                $result['success'] = 1;

                $evepost = $this->_db->table('event_invitations')
                        ->where('event_invitation_id', $lastinserted)
                        ->get();
                $eveinv = array();

                foreach ($evepost as $us) {
                    if (isset($us->created_at))
                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                    if (isset($us->updated_at))
                        $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                    array_push($eveinv, $us);
                }

                $result['eventinvitationsinfo'][] = $eveinv;

                ////Add User Notification
                $userNotification = new UserNotification;

                $userNotification->from_user_id = (isset($postData['invitedfrom'])) ? $postData['invitedfrom'] : 0;
                $userNotification->to_user_id = (isset($invited_to)) ? $invited_to : 0;

                $invitedfrom = (isset($postData['invitedfrom'])) ? $postData['invitedfrom'] : 0;
                $fromuser = $this->getUserName($invitedfrom);
                $touser = $this->getUserName($invited_to);

                $userNotification->event_id = $postData['eventid'];

                $userNotification->comment_text = $fromuser . ' has Invited on an event';
                $userNotification->notification_type = 'inviteonevent';
                $userNotification->status = 'unread';
                $userNotification->created_at = date('Y-m-d H:i:s');

                $userNotification->updated_at = null;
                $userNotification->save();
                $lastNotifyId = $userNotification->id;
                $getLatestNotification = $this->_db->table('user_notifications')
                        ->where('user_notification_id', $lastNotifyId)
                        ->get();
                $Notifydata = $getLatestNotification[0];
                $devInfo = $this->getDeviceInfo($invited_to);
                $contentmsg = $fromuser . ' has Invited on an event';
                if (count($devInfo) > 0) {
                    foreach ($devInfo as $dev123) {
                        if (isset($dev123->one_signal_userid)) {
                            $one_signal_userid = trim($dev123->one_signal_userid);
                            $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $invited_to);
                        }
                    }
                }
            }
        }
        //for useranking
        $n = $this->n;
        $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
        $to_date = date('Y-m-d' . ' 22:00:40', time());
        $share_events = $this->_db->table('event_invitations')
                ->join('events', 'events.event_id', '=', 'event_invitations.event_id')
                ->where('event_invitations.event_id', $postData['eventid'])
                ->whereBetween('event_invitations.created_at', [$from_date, $to_date])
                ->select('events.created_by_user')
                ->get();

        $created_by = $share_events[0]->created_by_user;
        $share_event_count = count($share_events);

        $share_posts = $this->_db->table('user_posts_to')
                ->join('user_posts', 'user_posts.user_post_id', '=', 'user_posts_to.user_post_id')
                ->where('user_posts.user_post_by', $created_by)
                ->whereBetween('user_posts_to.created_at', [$from_date, $to_date])
                ->get();

        $share_posts_count = count($share_posts);
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

        //update user ranking
        $this->usersRank($request, $response);

        //for event rating
        $event_rating = 0;
        $event_rating = $this->creator_tempo_rating($postData['eventid']);
        $share_user = $creator_tempo = $this->_db->table('users')
                ->where('user_id', $postData['invitedfrom'])
                ->select('tempo_user_rank')
                ->first();
        $share_user_tempo_rating = $share_user->tempo_user_rank;
        $event_rating += 5 * $share_user_tempo_rating;
        $last_rating = $this->last_event_rating($postData['eventid']);
        $last_rating += 5 * $share_user_tempo_rating;
        $evetRankUpdate = $this->_db->table('events')
                ->where('event_id', $postData['eventid'])
                ->update([
            'event_rating' => $event_rating,
            'last_value' => $last_rating]);

        return $response->withJson($result);
    }

    public function getInvitations($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $invitationsevent = $this->_db->table('event_invitations')
                //->where('event_id', $postData['eventid'])
                ->where('invited_to_userid', $postData['userid'])
                ->where('invitation_status', 'Invited')
                ->get();
        if (isset($invitationsevent) && count($invitationsevent) >= 1) {
            $result['success'] = 1;
            $eve_inv = array();
            foreach ($invitationsevent as $eve) {
                if (isset($eve->created_at))
                    $eve->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->created_at)));
                if (isset($eve->updated_at))
                    $eve->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->updated_at)));
                array_push($eve_inv, $eve);
            }
            $result['invitationsinfo'] = $eve_inv;
        } else {
            $result['success'] = 0;
            $result['invitationsinfo'] = 'No Invitations';
        }
        return $response->withJson($result);
    }

    public function updateInvitations($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $from_userid = 0;
        if ($postData['status'] == 2) {
            $postData['status'] = 'Accepted';
        } elseif ($postData['status'] == -1) {
            $postData['status'] = 'Rejected';
        } else {
            $postData['status'] = 'Invited';
        }
        $invitationseventexists = $this->_db->table('event_invitations')
                ->where('event_id', $postData['eventid'])
                ->where('invited_to_userid', $postData['userid'])
                ->where('invitation_status', "!=", "Accepted")
                ->get();
        if (isset($invitationseventexists[0])) {
            $from_userid_invitedBy = $invitationseventexists[0]->invited_from_userid;

            $invitationsevent = $this->_db->table('event_invitations')
                    ->where('event_id', $postData['eventid'])
                    ->where('invited_to_userid', $postData['userid'])
                    ->update(['invitation_status' => $postData['status'], 'updated_at' => date("Y-m-d H:i:s")]);
            if (isset($invitationsevent) && count($invitationsevent) >= 1) {
                $result['success'] = 1;
                $result['message'] = "Invitation " . $postData['status'] . " Successfully!";

                ////Add User Notification
                $userNotification = new UserNotification;

                $userNotification->from_user_id = (isset($postData['userid'])) ? $postData['userid'] : 0;
                $userNotification->to_user_id = (isset($from_userid_invitedBy)) ? $from_userid_invitedBy : 0;  // Reply to the requestor

                $fromuser = $this->getUserName($postData['userid']);
                $touser = $this->getUserName($from_userid_invitedBy);

                $userNotification->event_id = $postData['eventid'];
                $userNotification->comment_text = $fromuser . ' has ' . $postData['status'] . ' your invitation.';
                $userNotification->notification_type = 'updateinvitaionevent';
                $userNotification->status = 'unread';
                $userNotification->created_at = date('Y-m-d H:i:s');
                $userNotification->updated_at = null;
                $userNotification->save();
                $lastNotifyId = $userNotification->id;

                $getLatestNotification = $this->_db->table('user_notifications')
                        ->where('user_notification_id', $lastNotifyId)
                        ->get();
                $Notifydata = $getLatestNotification[0];
                $devInfo = $this->getDeviceInfo($from_userid_invitedBy);
                $contentmsg = $fromuser . ' has ' . $postData['status'] . ' your invitation.';
                if (count($devInfo) > 0) {
                    foreach ($devInfo as $dev123) {
                        if (isset($dev123->one_signal_userid)) {
                            $one_signal_userid = trim($dev123->one_signal_userid);
                            $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $from_userid_invitedBy);
                        }
                    }
                }
            } else {
                $result['success'] = 0;
                $result['invitationsinfo'] = 'No Invitations found';
            }
            $event_rating = 0;
            $event_rating = $this->creator_tempo_rating($postData['eventid']);
            $last_rating = $this->last_event_rating($postData['eventid']);
            //Calculation for attend
            $attendee_tempo = $this->_db->table('event_invitations')
                    ->where('event_id', $postData['eventid'])
                    ->where('invitation_status', 'Accepted')
                    ->get();
            if (count($attendee_tempo) >= 1) {
                $attendee_tempo_rating = count($attendee_tempo);
                $event_rating += 1 * $attendee_tempo_rating;
                $last_rating += 1 * $attendee_tempo_rating;
            }

            //Calculation for Leave
            $reject_tempo = $this->_db->table('event_invitations')
                    ->where('event_id', $postData['eventid'])
                    ->where('invitation_status', 'Rejected')
                    ->get();
            if (count($reject_tempo) >= 1) {
                $reject_tempo_rating = count($reject_tempo);
                $event_rating -= 1 * $reject_tempo_rating;
                $last_rating -= 1 * $reject_tempo_rating;
            }
            $evetRankUpdate = $this->_db->table('events')
                    ->where('event_id', $postData['eventid'])
                    ->update([
                'event_rating' => $event_rating,
                'last_value' => $last_rating]);
        } else {
            $result['success'] = 0;
        }
        return $response->withJson($result);
    }

    public function addCommentToEvent($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        try {
            $eventComments = new EventComments;
            $eventComments->event_post_id = (isset($postData['eventpostid'])) ? $postData['eventpostid'] : 0;

            $eventComments->comment_type = (isset($postData['commenttype'])) ? $postData['commenttype'] : '';
            //if ($postData['commenttype'] == 'image' OR $postData['commenttype'] == 'video') {
            if (isset($_FILES['commentdata'])) {
                $files = $_FILES['commentdata'];

                $file_name_expensions = explode('.', $files['name']);

                $new_file_name = $eventComments->event_post_id . '_' . time() . '.' . $file_name_expensions[1];
                $file_tmp = $files['tmp_name'];
                $contentType = $files['type'];
                $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($new_file_name, $file_tmp, $contentType, "event_comment_post");
                //$path = $basePath . 'user_post/';
                //move_uploaded_file($file_tmp, $path . $new_file_name);
                $imgsize = get_headers($uploadedimg, true);
                if (isset($imgsize['Content-Length'])) {
                    $sizeImage = (int) ($imgsize['Content-Length']);
                    if ($sizeImage < 1) {
                        $result123['success'] = 0;
                        $result123['message'] = "Unable to upload. Please try again.";
                        return $response->withJson($result123);
                    }
                }
                $eventComments->comment_data = $uploadedimg;
            } else
                $eventComments->comment_data = (isset($postData['commentdata'])) ? $postData['commentdata'] : '';
            $eventComments->commented_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
            $eventComments->comment_status = (isset($postData['commentstatus'])) ? $postData['commentstatus'] : '';
            $eventComments->save();
            $lastinserted = $eventComments->id;
            $result['success'] = 1;
            $retarray = array();
            $alleventComments = $this->_db->table('event_comments')
                    ->where('event_comment_id', $lastinserted)
                    ->get();
            foreach ($alleventComments as $eve) {
                if (isset($eve->created_at))
                    $eve->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->created_at)));
                if (isset($eve->updated_at))
                    $eve->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->updated_at)));

                array_push($retarray, $eve);
            }
            $result['eventcommentinfo'] = $retarray;
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }

        ////Add User Notification
        $EventId = $this->getPostEventId($postData['eventpostid']);
        $EventPostUserId = $this->getEventPostUserId($postData['eventpostid']);

        $userNotification = new UserNotification;

        $userNotification->from_user_id = (isset($postData['userid'])) ? $postData['userid'] : 0;
        $userNotification->to_user_id = (isset($EventPostUserId)) ? $EventPostUserId : 0;

        $fromuser = $this->getUserName($postData['userid']);
        $touser = $this->getUserName($EventPostUserId);

        $userNotification->event_id = $EventId;

        if ($EventPostUserId == $postData['userid'])
            $userNotification->comment_text = 'You have commented on your event';
        else
            $userNotification->comment_text = $fromuser . ' has commented on your event';
        $userNotification->notification_type = 'commentonevent';
        $userNotification->status = 'unread';
        $userNotification->created_at = date('Y-m-d H:i:s');

        $userNotification->updated_at = null;
        $userNotification->save();
        $lastNotifyId = $userNotification->id;

        $getLatestNotification = $this->_db->table('user_notifications')
                ->where('user_notification_id', $lastNotifyId)
                ->get();
        $Notifydata = $getLatestNotification[0];
        $devInfo = $this->getDeviceInfo($EventPostUserId);
        $contentmsg = $fromuser . ' has commented on your event';
        if (count($devInfo) > 0) {
            foreach ($devInfo as $dev123) {
                if (isset($dev123->one_signal_userid)) {
                    $one_signal_userid = trim($dev123->one_signal_userid);
                    $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $EventPostUserId);
                }
            }
        }
        //user ranking
        $n = $this->n;
        $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
        $to_date = date('Y-m-d' . ' 22:00:40', time());

        $event_comment = $this->_db->table('event_comments')
                ->join('event_posts', 'event_posts.event_post_id', '=', 'event_comments.event_post_id')
                ->join('events', 'events.event_id', '=', 'event_posts.event_id')
                ->where('event_posts.event_post_id', $postData['eventpostid'])
                ->whereBetween('event_comments.created_at', [$from_date, $to_date])
                ->select('events.created_by_user', 'events.event_id')
                ->get();

        $post_by = $event_comment[0]->created_by_user;
        $event_id = $event_comment[0]->event_id;
        $event_comment_count = count($event_comment);

        $posts_comment = $this->_db->table('userpost_comments')
                ->join('user_posts', 'user_posts.user_post_id', '=', 'userpost_comments.user_post_id')
                ->where('user_posts.user_post_by', $post_by)
                ->whereBetween('userpost_comments.created_at', [$from_date, $to_date])
                ->get();

        $post_comment_count = count($posts_comment);

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
        //update user ranking
        $this->usersRank($request, $response);

        //for event rating
        $postData['eventid'] = (isset($postData['eventid'])) ? $postData['eventid'] : $event_id;
        $event_rating = 0;
        $event_rating = $this->creator_tempo_rating($postData['eventid']);
        $commentor = $creator_tempo = $this->_db->table('users')
                ->where('user_id', $postData['userid'])
                ->select('tempo_user_rank')
                ->first();
        $commentor_tempo_rating = $commentor->tempo_user_rank;
        $event_rating += 2 * $commentor_tempo_rating;
        $last_rating = $this->last_event_rating($postData['eventid']);
        $last_rating += 2 * $commentor_tempo_rating;
        $evetRankUpdate = $this->_db->table('events')
                ->where('event_id', $postData['eventid'])
                ->update([
            'event_rating' => $event_rating,
            'last_value' => $last_rating]);
        return $response->withJson($result);
    }

    /* public function addCommentToEvent($request, $response) {
      $postData = $request->getParsedBody();
      try {
      $eventComments = new EventComments;
      $eventComments->event_post_id = (isset($postData['eventpostid'])) ? $postData['eventpostid'] : 0;
      $eventComments->comment_type = (isset($postData['commenttype'])) ? $postData['commenttype'] : '';
      $eventComments->comment_data = (isset($postData['commentdata'])) ? $postData['commentdata'] : '';
      $eventComments->commented_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
      $eventComments->comment_status = (isset($postData['commentstatus'])) ? $postData['commentstatus'] : '';
      $eventComments->save();
      $result['success'] = 1;
      $result['eventcommentinfo'] = $eventComments;
      } catch (Exception $e) {
      $result['success'] = 0;
      $result['message'] = $e->getMessage();
      }
      //user ranking
      $n = $this->n;
      $from_date = date('Y-m-d' . ' 00:00:00', strtotime('-' . $n . ' days'));
      $to_date = date('Y-m-d' . ' 22:00:40', time());

      $event_comment = $this->_db->table('event_comments')
      ->join('event_posts', 'event_posts.event_post_id', '=', 'event_comments.event_post_id')
      ->join('events', 'events.event_id', '=', 'event_posts.event_id')
      ->where('event_posts.event_post_id', $postData['eventpostid'])
      ->whereBetween('event_comments.created_at', [$from_date, $to_date])
      ->select('events.created_by_user', 'events.event_id')
      ->get();

      $post_by = $event_comment[0]->created_by_user;
      $event_id = $event_comment[0]->event_id;
      $event_comment_count = count($event_comment);

      $posts_comment = $this->_db->table('userpost_comments')
      ->join('user_posts', 'user_posts.user_post_id', '=', 'userpost_comments.user_post_id')
      ->where('user_posts.user_post_by', $post_by)
      ->whereBetween('userpost_comments.created_at', [$from_date, $to_date])
      ->get();

      $post_comment_count = count($posts_comment);

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
      //update user ranking
      $this->usersRank($request, $response);

      //for event rating
      $postData['eventid'] = (isset($postData['eventid'])) ? $postData['eventid'] : $event_id;
      $event_rating = 0;
      $event_rating = $this->creator_tempo_rating($postData['eventid']);
      $commentor = $creator_tempo = $this->_db->table('users')
      ->where('user_id', $postData['userid'])
      ->select('tempo_user_rank')
      ->first();
      $commentor_tempo_rating = $commentor->tempo_user_rank;
      $event_rating += 2 * $commentor_tempo_rating;
      $last_rating = $this->last_event_rating($postData['eventid']);
      $last_rating += 2 * $commentor_tempo_rating;
      $evetRankUpdate = $this->_db->table('events')
      ->where('event_id', $postData['eventid'])
      ->update([
      'event_rating' => $event_rating,
      'last_value' => $last_rating]);
      return $response->withJson($result);
      } */

    public function removeCommentEvent($request, $response) {
        $postData = $request->getQueryParams();
        if ($postData['eventcommentid'] != 0) {
            try {
                $varDefination = $this->_db->table('event_comments')
                        ->where('event_comment_id', $postData['eventcommentid'])
                        ->delete();
                $result['success'] = 1;
                $result['message'] = "Comment Deleted Successfully!";
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

    public function getEventComments($request, $response) {
        $postData = $request->getParsedBody();
        if (isset($postData['event_post_id'])) {
            $chkcomment = $this->_db->table('event_comments')
                    ->where('event_post_id', $postData['event_post_id'])
                    ->get();

            if (isset($chkcomment) && count($chkcomment) >= 1) {
                $result['success'] = 1;
                $evenewc = array();
                foreach ($chkcomment as $evec) {
                    if (isset($evec->created_at))
                        $evec->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($evec->created_at)));
                    if (isset($evec->updated_at))
                        $evec->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($evec->updated_at)));

                    array_push($evenewc, $evec);
                }
                $result['message'] = $evenewc;
            } else {
                $result['success'] = 0;
                $result['message'] = "Comment not found";
            }

            return $response->withJson($result);
        }
    }

    public function addCoHost($request, $response) {
        $postData = $request->getParsedBody();

        if ($postData['userid'] == -1) {    // All friends 
            $fromuserid = $this->getEventUserId($postData['eventid']);
            $allfriends = $this->getfriendsofUser($fromuserid);
            if (count($allfriends) > 0)
                $co_host = $allfriends;
            else {
                $result['success'] = 0;
                $result['message'][] = 'No friends available';
                return $response->withJson($result);
            }
        } else {
            $pos = strpos($postData['userid'], ',');
            if ($pos === false) {
                $co_host[] = $postData['userid'];
            } else {
                $co_host = explode(',', $postData['userid']);
            }
        }

        foreach ($co_host as $cohostval) {
            $chkcohost = $this->_db->table('event_co_host')
                    ->where('event_id', $postData['eventid'])
                    ->where('co_host_user_id', $cohostval)
                    ->first();

            if (count($chkcohost) == 0) {
                try {
                    $eventCoHost = new EventCoHost;
                    $eventCoHost->event_id = (isset($postData['eventid'])) ? $postData['eventid'] : '';

                    $eventCoHost->co_host_user_id = (isset($cohostval)) ? $cohostval : '';
                    $eventCoHost->status = (isset($postData['status'])) ? $postData['status'] : 'Invited';
                    $eventCoHost->save();
                    $result['success'] = 1;
                    $lastinserted = $eventCoHost->id;
                    $result['success'] = 1;
                    $dbpost = $this->_db->table('event_co_host')
                            ->where('event_co_host_id', $lastinserted)
                            ->get();
                    $dbpostarr = array();

                    foreach ($dbpost as $evec) {
                        if (isset($evec->created_at))
                            $evec->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($evec->created_at)));
                        if (isset($evec->updated_at))
                            $evec->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($evec->updated_at)));

                        array_push($dbpostarr, $evec);
                    }

                    $result['eventcohost'][] = $dbpostarr;
                } catch (Exception $e) {
                    $result['success'] = 0;
                    $result['message'] = $e->getMessage();
                }
                ///Add User Notification
                if ($postData['userid']) {
                    $fromuserid = $this->getEventUserId($postData['eventid']);
                    $status = (isset($postData['status'])) ? ucfirst(trim($postData['status'])) : 'Invited';
                    $this->addusernotificationtoCoHost($postData['eventid'], $fromuserid, $cohostval, $status);
                }
            } else {
                $result['success'] = 0;
                $result['message'][] = 'Co-Host ' . $cohostval . ' already added for Event ' . $postData['eventid'];
            }
        }
        return $response->withJson($result);
    }

    public function acceptRejectCoHostInvitation($request, $response) {
        $postData = $request->getParsedBody();
        if ($postData['status'] == 2) {
            $postData['status'] = 'Accepted';
        } elseif ($postData['status'] == -1) {
            $postData['status'] = 'Rejected';
        } else {
            $postData['status'] = 'Invited';
        }
        $updatecohost = $this->_db->table('event_co_host')
                ->where('event_id', $postData['eventid'])
                ->where('co_host_user_id', $postData['cohostuserid'])
                ->update(['status' => $postData['status']]);
        if (isset($updatecohost) && count($updatecohost) >= 1) {
            $result['success'] = 1;
            $result['message'] = "Co Host Update Successfully!";
            $status = $postData['status'];
            $touserid = $this->getEventUserId($postData['eventid']);
            $this->addusernotificationtoCoHost($postData['eventid'], $postData['cohostuserid'], $touserid, $status, $status);
        } else {
            $result['success'] = 0;
            $result['invitationsinfo'] = 'No Co Host found';
        }

        return $response->withJson($result);
    }

    public function removeCoHost($request, $response) {
        $postData = $request->getQueryParams();
        if (isset($postData['userid'])) {
            $chkcohost = $this->_db->table('event_co_host')
                    ->where('event_id', $postData['eventid'])
                    ->where('co_host_user_id', $postData['userid'])
                    ->first();

            if (isset($chkcohost) && count($chkcohost) >= 1) {
                try {
                    $eventhostid = $chkcohost->event_co_host_id;
                    $varDefination = $this->_db->table('event_co_host')
                            ->where('event_co_host_id', $eventhostid)
                            ->delete();
                    $result['success'] = 1;
                    $result['message'] = "Host Deleted Successfully!";
                } catch (Exception $e) {
                    $result['success'] = 0;
                    $result['message'] = $e->getMessage();
                }
            } else {
                $result['success'] = 0;
                $result['message'] = "Host Not Found";
            }

            return $response->withJson($result);
        }
    }

    public function removeEventGuest($request, $response) {
        $postData = $request->getQueryParams();
        if (isset($postData['userid'])) {
            $chkguest = $this->_db->table('event_invitations')
                    ->where('event_id', $postData['eventid'])
                    ->where('invited_to_userid', $postData['userid'])
                    ->first();

            if (isset($chkguest) && count($chkguest) >= 1) {
                try {
                    $invitedtouserid = $chkguest->invited_to_userid;
                    $varDefination = $this->_db->table('event_invitations')
                            ->where('invited_to_userid', $invitedtouserid)
                            ->delete();
                    $result['success'] = 1;
                    $result['message'] = "Invited Guest Deleted Successfully!";
                } catch (Exception $e) {
                    $result['success'] = 0;
                    $result['message'] = $e->getMessage();
                }
            } else {
                $result['success'] = 0;
                $result['message'] = "Guest Not Found";
            }

            return $response->withJson($result);
        }
    }

    public function setEventPosts($request, $response) {
        $postData = $request->getParsedBody();
        $filetype = "file";
        date_default_timezone_set('UTC');
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        if (isset($postData['eventpostid'])) {
            //edit mode
            $updateData = [];
            if (isset($postData['eventid']))
                $updateData['event_id'] = $postData['eventid'];
            if (isset($postData['posttype']))
                $updateData['post_type'] = $postData['posttype'];
            if (isset($postData['postdata']))
                $updateData['post_data'] = $postData['postdata'];
            if (isset($postData['userid']))
                $updateData['post_by'] = $postData['userid'];
            if (isset($postData['status']))
                $updateData['post_status'] = $postData['status'];
            try {
                $eventPost = $this->_db->table('event_posts')
                        ->where('event_post_id', $postData['eventpostid'])
                        ->update($updateData);

                $result['success'] = 1;
                $result['message'] = "Event Post Update Successfully!";
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {

            try {
                $eventPost = new EventPost;
                $eventPost->event_id = (isset($postData['eventid'])) ? $postData['eventid'] : 0;
                $eventPost->post_type = (isset($postData['posttype'])) ? $postData['posttype'] : '';
                $basePath = $request->getUri()->getBasePath();
                if ($postData['posttype'] == 'image' OR $postData['posttype'] == 'video') {

                    $files = $_FILES['postdata'];
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
                    $file_name_expensions = explode('.', $files['name']);

                    $getlastpost = $this->_db->table('event_posts')
                            ->where('isdeleted', 0)
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
                    // move_uploaded_file($file_tmp, $path . $new_file_name);
                    $uploadedimg = $awsS3Url . $bucket . "/" . $this->awsupload($new_file_name, $file_tmp, $contentType, "event_post");
                    $imgsize = get_headers($uploadedimg, true);
                    if (isset($imgsize['Content-Length'])) {
                        $sizeImage = (int) ($imgsize['Content-Length']);
                        if ($sizeImage < 1) {
                            $result123['success'] = 0;
                            $result123['message'] = "Unable to upload. Please try again.";
                            return $response->withJson($result123);
                        }
                    }
                    //   $eventPost->post_data = $path . $new_file_name;
                    $eventPost->post_data = $uploadedimg;
                } else {
                    $eventPost->post_data = (isset($postData['postdata'])) ? $postData['postdata'] : '';
                }
                //echo $eventPost->post_data;exit;
                $eventPost->event_id = (isset($postData['eventid'])) ? $postData['eventid'] : 0;
                $eventPost->post_by = (isset($postData['userid'])) ? $postData['userid'] : 0;
                $eventPost->post_status = (isset($postData['status'])) ? $postData['status'] : '';
                $eventPost->save();
                $lastinserted = $eventPost->id;
                $eventpostnew = array();
                $evepost = $this->_db->table('event_posts')
                        ->where('isdeleted', 0)
                        ->where('event_post_id', $lastinserted)
                        ->get();
                $devnew = array();

                foreach ($evepost as $us) {
                    if (isset($us->created_at))
                        $us->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->created_at)));
                    if (isset($us->updated_at))
                        $us->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($us->updated_at)));
                    array_push($eventpostnew, $us);
                }
                $result['success'] = 1;
                $result['eventPost'] = $eventpostnew;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }

        ////Add User Notification
        $EventUserId = $this->getEventUserId($postData['eventid']);

        $userNotification = new UserNotification;

        $userNotification->from_user_id = (isset($postData['userid'])) ? $postData['userid'] : 0;
        $userNotification->to_user_id = (isset($EventUserId)) ? $EventUserId : 0;
        $userid = (isset($postData['userid'])) ? $postData['userid'] : 0;
        $EventUserId = (isset($EventUserId)) ? $EventUserId : 0;

        $fromuser = $this->getUserName($userid);
        $touser = $this->getUserName($EventUserId);


        $userNotification->event_id = $postData['eventid'];

        if ($EventUserId == $postData['userid'])
            $userNotification->comment_text = 'You have uploaded post on your event';
        else {
            if ($filetype == "file")
                $userNotification->comment_text = $fromuser . ' has uploaded post on your event';
            else
                $userNotification->comment_text = $fromuser . ' has posted a ' . $filetype . ' on your event';
        }
        $userNotification->notification_type = 'uploadedpostonevent';
        $userNotification->status = 'processed';
        $userNotification->created_at = date('Y-m-d H:i:s');
        $userNotification->updated_at = null;
        $userNotification->save();
        $lastNotifyId = $userNotification->id;
        $getLatestNotification = $this->_db->table('user_notifications')
                ->where('user_notification_id', $lastNotifyId)
                ->get();
        $Notifydata = $getLatestNotification[0];
        $devInfo = $this->getDeviceInfo($EventUserId);
        $contentmsg = $fromuser . ' has uploaded post on your event';
        if ($filetype == "file")
            $contentmsg = $fromuser . ' has uploaded post on your event';
        else
            $contentmsg = $fromuser . ' has posted a ' . $filetype . ' on your event';
        if (count($devInfo) > 0) {
            foreach ($devInfo as $dev123) {
                if (isset($dev123->one_signal_userid)) {
                    $one_signal_userid = trim($dev123->one_signal_userid);
                    $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $EventUserId);
                }
            }
        }
        return $response->withJson($result);
    }

    public function uploadPromotionImages($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        if (isset($postData['event_id'])) {
            try {
                $event = new Event;
                $basePath = $request->getUri()->getBasePath();
                $filesarr1 = $filesarr2 = $filesarr3 = array();
                if (isset($_FILES['promotionalimages_1']))
                    $filesarr1 = $_FILES['promotionalimages_1'];
                if (isset($_FILES['promotionalimages_2']))
                    $filesarr2 = $_FILES['promotionalimages_2'];
                if (isset($_FILES['promotionalimages_3']))
                    $filesarr3 = $_FILES['promotionalimages_3'];

                $i = 0;
                $uploadedimg1 = $uploadedimg2 = $uploadedimg3 = '';
                $imagepath = $basePath . 'promo_images/';
                if (isset($filesarr1['name']) && $filesarr1['name'] != '') {
                    $file_name_expensions1 = pathinfo($filesarr1['name'], PATHINFO_EXTENSION);
                    $imgname1 = $postData['event_id'] . '_promo1_' . time() . '.' . $file_name_expensions1;
//                    if (move_uploaded_file($filesarr1['tmp_name'], $imagepath . $imgname1)) {   
//                    }
                    $file_tmp1 = $filesarr1['tmp_name'];
                    $contentType1 = $filesarr1['type'];
                    $uploadedimg1 = $awsS3Url . $bucket . "/" . $this->awsupload($imgname1, $file_tmp1, $contentType1, "promotionalimages");
                }
                if (isset($filesarr2['name']) && $filesarr2['name'] != '') {
                    $file_name_expensions2 = pathinfo($filesarr2['name'], PATHINFO_EXTENSION);
                    $imgname2 = $postData['event_id'] . '_promo2_' . time() . '.' . $file_name_expensions2;
//                    if (move_uploaded_file($filesarr2['tmp_name'], $imagepath . $imgname2)) {   
//                    }
                    $file_tmp2 = $filesarr2['tmp_name'];
                    $contentType2 = $filesarr2['type'];
                    $uploadedimg2 = $awsS3Url . $bucket . "/" . $this->awsupload($imgname2, $file_tmp2, $contentType2, "promotionalimages");
                }
                if (isset($filesarr3['name']) && $filesarr3['name'] != '') {
                    $file_name_expensions3 = pathinfo($filesarr3['name'], PATHINFO_EXTENSION);
                    $imgname3 = $postData['event_id'] . '_promo3_' . time() . '.' . $file_name_expensions3;
//                    if (move_uploaded_file($filesarr3['tmp_name'], $imagepath . $imgname3)) {   
//                    }
                    $file_tmp3 = $filesarr3['tmp_name'];
                    $contentType3 = $filesarr3['type'];
                    $uploadedimg3 = $awsS3Url . $bucket . "/" . $this->awsupload($imgname3, $file_tmp3, $contentType3, "promotionalimages");
                }

                $updateEvent = $this->_db->table('events')
                        ->where('event_id', $postData['event_id'])
                        ->update(array('promotion_image1' => $uploadedimg1, 'promotion_image2' => $uploadedimg2, 'promotion_image3' => $uploadedimg3));


                $result['success'] = 1;
                $result['message'] = array('event_id' => $postData['event_id'], 'promotion_image1' => $uploadedimg1, 'promotion_image2' => $uploadedimg2, 'promotion_image3' => $uploadedimg3);
                //$result['message'] = array('event_id'=>$postData['event_id'],'promotion_image1' => $uploadedimg1);
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        }
        return $response->withJson($result);
    }

    public function awsupload($image_name_actual, $tmp, $contentType, $path) {
        $public_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "aws_config" . DIRECTORY_SEPARATOR;
        include($public_path . 'config_s3.php');
        $key = $path . "/" . $image_name_actual;

        // $bucket = "tempoevent";
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

    public function getEventPosts($request, $response) {
        $postData = $request->getParsedBody();
        $offset = 0;
        if (isset($postData['pageno']))
            $offset = ($postData['pageno'] - 1) * 50;
        $geteventpost = $this->_db->table('event_posts')
                ->where('isdeleted', 0)
                ->where('event_id', $postData['eventid'])
                ->where('post_by', $postData['userid'])
                ->skip($offset)->take(50)
                ->get();
        if (isset($geteventpost) && count($geteventpost) >= 1) {
            try {

                $result['success'] = 1;

                $eventposts = array();
                foreach ($geteventpost as $eve) {
                    if (isset($eve->created_at))
                        $eve->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->created_at)));
                    if (isset($eve->updated_at))
                        $eve->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->updated_at)));
                    array_push($eventposts, $eve);
                }
                $result['eventpost'] = $eventposts;
                $result['totalrecords'] = count($geteventpost);
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "Post Event Not Found";
        }

        return $response->withJson($result);
    }

    public function getMyCreatedEvents($request, $response) {
        $postData = $request->getParsedBody();
        try {
            $myevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('created_by_user', $postData['userid'])
                    ->get();
            if (isset($myevent) && count($myevent) >= 1) {
                $result['success'] = 1;
                $result['myevent'] = $myevent;
            } else {
                $result['success'] = 0;
                $result['message'] = "Event Not Found";
            }
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $response->withJson($result);
    }

    public function getUserEvents($request, $response) {
        $postData = $request->getParsedBody();

        $getusertpostevent = $this->_db->table('event_posts')
                ->where('isdeleted', 0)
                ->where('post_by', $postData['userid'])
                ->take(20)
                ->latest()
                ->get();
        if (isset($getusertpostevent) && count($getusertpostevent) >= 1) {
            try {

                $result['success'] = 1;
                $result['userpostevent'] = $getusertpostevent;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "User Events Not Found";
        }

        return $response->withJson($result);
    }

    /* public function getFeedListFilter($request, $response) {
      $postData = $request->getParsedBody();
      $result['success'] = 0;

      if (isset($postData['search']) and ! empty($postData['search'])) {
      $searchevent = $this->_db->table('events')
      ->where('event_name', 'LIKE', '%' . $postData['search'] . '%')
      ->orWhere('event_description', 'LIKE', '%' . $postData['search'] . '%')
      ->orWhere('event_city', 'LIKE', '%' . $postData['search'] . '%')
      ->get();
      if (isset($searchevent) && count($searchevent) >= 1) {
      $result['success'] = 1;
      $result['searchevent'] = $searchevent;
      }
      }
      if ($postData['categoryid'] == "1") {
      $myevent = $this->_db->table('events')
      ->where('created_by_user', $postData['userid'])
      ->get();
      if (isset($myevent) && count($myevent) >= 1) {
      $result['success'] = 1;
      $result['myevent'] = $myevent;
      }
      }

      if ($postData['categoryid'] == "2") {
      $trending_in_city = $this->_db->table('events')
      ->where('event_city', $postData['city'])
      ->get();
      if (isset($trending_in_city) && count($trending_in_city) >= 1) {
      $result['success'] = 1;
      $result['trendingincity'] = $trending_in_city;
      }
      }
      $from_date = date("Y-m-d");
      $to_date = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + 10, date("Y")));
      $upcoming_events = $this->_db->table('events')
      ->whereBetween('event_start_datetime', [$from_date, $to_date])
      ->where('is_published', 1)
      ->get();

      if ($postData['categoryid'] == "3") {
      $result['success'] = 1;
      $result['upcoming_events'] = $upcoming_events;
      }

      if ($postData['categoryid'] == "4") {
      $distance = 30;
      $radius_km = 100;
      $radius_mi = 62;
      $radius = $radius_mi;
      $latitude = $postData['latitude'];
      $longitude = $postData['longitude'];
      $nearby_events_distance = $this->_db->table('events')
      ->selectRaw('event_id, ( ' . $radius . ' * acos( cos( radians(' . $latitude . ') ) * cos(radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + '
      . 'sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) AS distance')
      ->where('is_published', 1)
      ->having('distance', '<=', $distance)
      ->get();

      if (isset($nearby_events_distance) && count($nearby_events_distance) >= 1) {
      foreach ($nearby_events_distance as $key => $value) {
      $event_ids[] = $value->event_id;
      }
      $nearby_events = $this->_db->table('events')
      ->whereIn('event_id', $event_ids)
      ->get();
      $result['success'] = 1;
      $result['nearby_events'] = $nearby_events;
      }
      }
      return $response->withJson($result);
      } */

    public function getFeedListFilter($request, $response) {
        $postData = $request->getParsedBody();
        $result['success'] = 0;
        date_default_timezone_set('UTC');
        $curdate = date("YmdHis");
        $userdatetime = isset($postData["userdatetime"]) ? $postData["userdatetime"] : '';
        $categoryid = isset($postData['categoryid']) ? $postData['categoryid'] : 0;
        if (isset($postData['search']) and ! empty($postData['search'])) {
            $searchevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('event_name', 'LIKE', '%' . $postData['search'] . '%')
                    ->orWhere('event_description', 'LIKE', '%' . $postData['search'] . '%')
                    ->orWhere('event_city', 'LIKE', '%' . $postData['search'] . '%')
                    ->get();
            if (isset($searchevent) && count($searchevent) >= 1) {
                $result['success'] = 1;
                $evenewsear = array();
                foreach ($searchevent as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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


                        array_push($evenewsear, $eve);
                    }
                }

                $result['searchevent'] = $evenewsear;
            }
        }

        if ($categoryid == "1") {

            $listType = 'feedslistfilter';
            $myevent = $this->getMyEventsFromVarious($postData['userid'], $listType);
            if (isset($myevent) && count($myevent) >= 1) {
                $result['success'] = 1;
                $evenew = array();
                foreach ($myevent as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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

                        $cohost = array();
                        $cohost = $this->getCohosts($eve->event_id);
                        if (count($cohost) > 0)
                            $eve->cohost = $cohost;
                        else
                            $eve->cohost = 0;

                        $invitedGuests = array();
                        $invitedGuests = $this->invitedGuests($eve->event_id, $postData['userid']);
                        if (count($invitedGuests) > 0)
                            $eve->invitedGuests = $invitedGuests;
                        else
                            $eve->invitedGuests = 0;

                        array_push($evenew, $eve);
                    }
                }

                $result['myevent'] = $evenew;
            }
        }

        if ($categoryid == "2") {
            $trending_in_city = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('event_city', $postData['city'])
                    ->get();
            if (isset($trending_in_city) && count($trending_in_city) >= 1) {
                $result['success'] = 1;

                $evenew_city = array();
                foreach ($trending_in_city as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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


                        array_push($evenew_city, $eve);
                    }
                }


                $result['trendingincity'] = $evenew_city;
            }
        }
        if ($userdatetime != "") {
            $userdatetime123 = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($userdatetime)));
            if (isset($postData['city'])) {
                $upcoming_events = $this->_db->table('events')
                        ->where('event_city', $postData['city'])
                        ->where('isdeleted', 0)
                        ->where('event_start_datetime', '>', $userdatetime123)
                        ->where('is_published', 1)
                        ->take(10)
                        ->latest()
                        ->get();
            }
        } else {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + 10, date("Y")));
//        $upcoming_events = $this->_db->table('events')
//                ->where('isdeleted', 0)
//                ->whereBetween('event_start_datetime', [$from_date, $to_date])
//                ->where('is_published', 1)
//                ->get();

            if (isset($postData['city'])) {
                $upcoming_events = $this->_db->table('events')
                        ->where('event_city', $postData['city'])
                        ->where('isdeleted', 0)
                        ->whereBetween('event_start_datetime', [$from_date, $to_date])
                        ->where('is_published', 1)
                        ->take(10)
                        ->latest()
                        ->get();
            }
        }
//        else{
//            $upcoming_events = $this->_db->table('events')
//                ->where('isdeleted', 0)
//                ->whereBetween('event_start_datetime', [$from_date, $to_date])
//                ->where('is_published', 1)
//                ->take(10)
//                ->latest()
//                ->get();
//        }

        if ($categoryid == "3") {
            $result['success'] = 1;
            $event_up = array();
            foreach ($upcoming_events as $eve) {
                $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                    array_push($event_up, $eve);
                }
            }
            $result['upcoming_events'] = $event_up;
        }

        if ($categoryid == "4") {
            $distance = 30;
            $radius_km = 100;
            $radius_mi = 62;
            $radius = $radius_mi;
            $latitude = $postData['latitude'];
            $longitude = $postData['longitude'];
            $nearby_events_distance = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->selectRaw('event_id, ( ' . $radius . ' * acos( cos( radians(' . $latitude . ') ) * cos(radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + '
                            . 'sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) AS distance')
                    ->where('is_published', 1)
                    ->having('distance', '<=', $distance)
                    ->orderBy('distance', 'asc')
                    ->get();

            if (isset($nearby_events_distance) && count($nearby_events_distance) >= 1) {
                foreach ($nearby_events_distance as $key => $value) {
                    $event_ids[] = $value->event_id;
                }
                $nearby_events = $this->_db->table('events')
                        ->where('isdeleted', 0)
                        ->whereIn('event_id', $event_ids)
                        ->get();
                $result['success'] = 1;
                $event_near = array();
                foreach ($nearby_events as $eve) {
                    $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                    if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                        array_push($event_near, $eve);
                    }
                }
                $result['nearby_events'] = $event_near;
            }
        }
        if ($categoryid == 0) {
            if (isset($postData['search']) and ! empty($postData['search'])) {
                $searchevent = $this->_db->table('events')
                        ->where('isdeleted', 0)
                        ->where('event_name', 'LIKE', '%' . $postData['search'] . '%')
                        ->orWhere('event_description', 'LIKE', '%' . $postData['search'] . '%')
                        ->orWhere('event_city', 'LIKE', '%' . $postData['search'] . '%')
                        ->get();
                if (isset($searchevent) && count($searchevent) >= 1) {
                    $result['success'] = 1;
                    $event_sear = array();
                    foreach ($searchevent as $eve) {
                        $enddate = isset($eve->event_end_datetime) ? $eve->event_end_datetime : 0;
                        if ($curdate - date('YmdHis', strtotime($enddate)) <= 1000000) {
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
                            array_push($event_sear, $eve);
                        }
                    }
                    $result['searchevent'] = $event_sear;
                }
            }
        }
        return $response->withJson($result);
    }

    public function creator_tempo_rating($eventid) {
        $creator_tempo_rating = 0;
        $event = $this->_db->table('events')
                ->where('isdeleted', 0)
                ->where('event_id', $eventid)
                ->first();
        $event_rating = isset($event->event_rating) ? $event->event_rating : '';
        if ($event_rating != '') {
            $creator_tempo_rating = $event_rating;
        } else {
            $creator_tempo = $this->_db->table('users')
                    ->join('events', 'events.created_by_user', '=', 'users.user_id')
                    ->where('events.event_id', $eventid)
                    ->select('users.tempo_user_rank')
                    ->first();
            if (isset($creator_tempo) && count($creator_tempo) >= 1) {
                $creator_tempo_rating = $creator_tempo->tempo_user_rank;
            }
        }

        return $creator_tempo_rating;
    }

    public function last_event_rating($eventid) {
        $last_event_rating = 0;
        $event = $this->_db->table('events')
                ->where('isdeleted', 0)
                ->where('event_id', $eventid)
                ->select('last_value')
                ->first();
        $event_rating = isset($event->last_value) ? $event->last_value : '';
        if (isset($event_rating) and $event_rating != '') {
            $last_event_rating = $event_rating;
            if ($last_event_rating < 0) {
                $last_event_rating = 0;
            }
        } else {
            $creator_tempo = $this->_db->table('users')
                    ->join('events', 'events.created_by_user', '=', 'users.user_id')
                    ->where('events.event_id', $eventid)
                    ->select('users.tempo_user_rank')
                    ->first();
            if (isset($creator_tempo) && count($creator_tempo) >= 1) {
                $last_event_rating = $creator_tempo->tempo_user_rank;
            }
        }

        return $last_event_rating;
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

    public function coolingDowneventsRank($request, $response) {
        $postData = $request->getParsedBody();
        $current_date = date('Y-m-d');
        $from_date = date('Y-m-d' . ' 00:00:00');
        $to_date = date('Y-m-d' . ' 22:00:40', time());
        $event = $this->_db->table('events')
                ->where('isdeleted', 0)
                ->select('event_id', 'event_rating', 'last_value')
                ->whereBetween('updated_at', [$from_date, $to_date])
                ->get();
        foreach ($event as $rankval) {
            $event_rating = $rankval->event_rating;
            $last_value = $rankval->last_value;
            $event_id = $rankval->event_id;
            if (isset($event_rating)) {
                if (isset($event_rating)) {
                    $slap = $this->_db->table('cooling_down_slap')
                            ->select('percentage')
                            ->where('max_rating_value', '>=', $last_value)
                            ->first();
                    $percentage = $slap->percentage;
                    $percent_last_value = ($percentage * $last_value) / 100;
                    $final_rating = $event_rating - $percent_last_value;
                    if ($final_rating < 0) {
                        $final_rating = 0;
                    }
                    //update event rating
                    $evetRankUpdate = $this->_db->table('events')
                            ->where('event_id', $event_id)
                            ->update([
                        'event_rating' => $final_rating,
                        'last_value' => 0]);
                }
            }
        }
    }

    public function eventPostsTo($request, $response) {
        $postData = $request->getParsedBody();
        try {
            $pos = strpos($postData['userid'], ',');
            if ($pos === false) {
                $eventPoststo = new EventPostsTo;
                $eventPoststo->event_post_id = (isset($postData['eventpostid'])) ? $postData['eventpostid'] : 0;
                $eventPoststo->posted_to = (isset($postData['userid'])) ? $postData['userid'] : 0;
                $eventPoststo->save();
                $result['success'] = 1;
                $result['eventPoststo'] = $eventPoststo;
            } else {
                $userid = explode(',', $postData['userid']);
                foreach ($userid as $value) {
                    $eventPoststo = new EventPostsTo;
                    $eventPoststo->event_post_id = (isset($postData['eventpostid'])) ? $postData['eventpostid'] : 0;
                    $eventPoststo->posted_to = $value;
                    $eventPoststo->save();
                    $result['success'] = 1;
                    $result['eventPostto'][] = $eventPoststo;
                }
            }
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }

        return $response->withJson($result);
    }

    /* public function GetEventRecords($request, $response) {
      $postData = $request->getParsedBody();
      try {
      $events = $this->_db->table('event_posts')
      //->join('events', 'events.event_id', '=', 'event_posts.event_id')
      ->where('event_posts.event_id', $postData['eventid'])
      ->select('event_posts.event_id', 'event_posts.event_post_id','post_type', 'event_posts.post_data')
      ->get();
      if (isset($events) && count($events) >= 1) {
      foreach ($events as $eventpostval) {

      $commentEventPost = $this->_db->table('event_comments')
      ->where('event_post_id', $eventpostval->event_post_id)
      ->groupBy('event_post_id')
      ->count();
      $eventRecords[] = array('eventPostId' => $eventpostval->event_post_id, 'mediaType'=>$eventpostval->post_type,
      'postData' => $eventpostval->post_data,
      'likeCount' => 0,
      'commentCount' => $commentEventPost
      );
      }
      $result['success'] = 1;
      $result['eventRecords'] = $eventRecords;
      } else {
      $result['success'] = 0;
      $result['message'] = 'Event post data not found';
      }
      } catch (Exception $e) {
      $result['success'] = 0;
      $result['message'] = $e->getMessage();
      }

      return $response->withJson($result);
      } */

    public function GetEventRecords($request, $response) {
        $postData = $request->getParsedBody();
        $user_id = isset($postData['userid']) ? (int) $postData['userid'] : 0;
        try {

            $events = $this->_db->table('event_posts')
                    //->join('events', 'events.event_id', '=', 'event_posts.event_id')
                    ->where('event_posts.event_id', $postData['eventid'])
                    ->where('isdeleted', 0)
                    ->select('event_posts.event_id', 'event_posts.event_post_id', 'post_type', 'event_posts.post_data', 'event_posts.like_count', 'event_posts.created_at')
                    ->get();
            if (isset($events) && count($events) >= 1) {
                foreach ($events as $eventpostval) {
                    if ($user_id > 0) {
                        $commentEventPost = $this->_db->table('event_comments')
                                ->where('event_post_id', $eventpostval->event_post_id)
                                ->groupBy('event_post_id')
                                ->count();
                        $eventRecords[] = array('eventPostId' => $eventpostval->event_post_id, 'mediaType' => $eventpostval->post_type,
                            'postData' => $eventpostval->post_data,
                            'post_created_at' => str_replace('+00:00', 'Z', gmdate('c', strtotime($eventpostval->created_at))),
                            'isUserLiked' => $this->getisUserLiked($postData['eventid'], $eventpostval->event_post_id, $user_id),
                            'likeCount' => $eventpostval->like_count,
                            'commentCount' => $commentEventPost
                        );
                    } else {
                        $commentEventPost = $this->_db->table('event_comments')
                                ->where('event_post_id', $eventpostval->event_post_id)
                                ->groupBy('event_post_id')
                                ->count();
                        $eventRecords[] = array('eventPostId' => $eventpostval->event_post_id, 'mediaType' => $eventpostval->post_type,
                            'postData' => $eventpostval->post_data,
                            'post_created_at' => str_replace('+00:00', 'Z', gmdate('c', strtotime($eventpostval->created_at))),
                            'likeCount' => $eventpostval->like_count,
                            'commentCount' => $commentEventPost
                        );
                    }
                    /*  $cohost = array();
                      $cohost = $this->getCohosts($postData['eventid']);
                      if (count($cohost) > 0)
                      $eventRecords['cohosts'] = $cohost;
                      else
                      $eventRecords['cohosts'] = 0;

                      $invitedGuests = array();
                      $invitedGuests = $this->invitedGuests($postData['eventid'], $user_id);
                      if (count($invitedGuests) > 0)
                      $eventRecords['invitedGuests'] = $invitedGuests;
                      else
                      $eventRecords['invitedGuests'] = 0;
                     */
                }
                $result['success'] = 1;
                $result['eventRecords'] = $eventRecords;
            } else {
                $result['success'] = 0;
                $result['message'] = 'Event post data not found';
            }
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }

        return $response->withJson($result);
    }

    public function getisUserLiked($eventid, $postid, $user_id) {

        $isLike = $this->_db->table('event_media_like')
                ->where('event_id', $eventid)
                ->where('event_post_id', $postid)
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

    public function getmyevents($request, $response) {
        $postData = $request->getParsedBody();
        $userid = isset($postData['userid']) ? (int) $postData['userid'] : 0;
        $result['success'] = 0;
        $listType = 'myevents';
        $myevent = $this->getMyEventsFromVarious($userid, $listType);
        if (isset($myevent) && count($myevent) >= 1) {
            $result['success'] = 1;
            $notnew = array();
            $repArr = array();
            foreach ($myevent as $eve) {
                if (!in_array($eve->event_id, $repArr)) {
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

                    $cohost = array();
                    $cohost = $this->getCohosts($eve->event_id, $listType);
                    if (count($cohost) > 0)
                        $eve->cohost = $cohost;
                    else
                        $eve->cohost = 0;

                    $invitedGuests = array();
                    $invitedGuests = $this->invitedGuests($eve->event_id, $userid, $listType);
                    if (count($invitedGuests) > 0)
                        $eve->invitedGuests = $invitedGuests;
                    else
                        $eve->invitedGuests = 0;
                    array_push($notnew, $eve);
                    array_push($repArr, $eve->event_id);
                }
                $result['myevent'] = $notnew;
            }
        }


        return $response->withJson($result);
    }

    public function getCohosts($event_id, $listType = null) {
        $comnew = array();
        if ($listType == "myevents") {
            $co_hosts = $this->_db->table('event_co_host')
                    ->where('event_id', '=', $event_id)
                    ->get();
        } else {
            $co_hosts = $this->_db->table('event_co_host')
                    ->where('event_id', '=', $event_id)
                    ->where('status', '=', 'Accepted')
                    ->get();
        }

        if (count($co_hosts) > 0) {
            foreach ($co_hosts as $eve) {
                $co_host_user_id = 0;
                $user = array();
                $co_host_user_id = $eve->co_host_user_id;
                $user = $this->getUserdetails($co_host_user_id);
                array_push($comnew, $user);
            }
        }
        return $comnew;
    }

    public function invitedGuests($event_id, $userid, $listType = null) {
        $comnew = array();
        if ($listType = "myevents") {
            $co_hosts = $this->_db->table('event_invitations')
                    ->where('event_id', '=', $event_id)
                    ->where('invited_from_userid', '=', $userid)
                    ->get();
        } else {
            $co_hosts = $this->_db->table('event_invitations')
                    ->where('event_id', '=', $event_id)
                    ->where('invited_from_userid', '=', $userid)
                    ->where('invitation_status', '=', 'Accepted')
                    ->get();
        }

        if (count($co_hosts) > 0) {
            foreach ($co_hosts as $eve) {
                $co_host_user_id = 0;
                $user = array();
                $co_host_user_id = $eve->invited_to_userid;
                $user = $this->getUserdetails($co_host_user_id);
                array_push($comnew, $user);
            }
        }
        return $comnew;
    }

    public function getUserdetails($userid) {
        $usersarr = array();
        $users = $this->_db->table('users')
                ->where('user_id', '=', $userid)
                ->get();

        if (count($users) > 0) {
            foreach ($users as $u) {
                if (isset($u->user_id))
                    $usersarr["user_id"] = $u->user_id;
                $usersarr["user_name"] = $u->user_name;
                if (isset($u->first_name))
                    $usersarr["first_name"] = $u->first_name;
                else
                    $usersarr["first_name"] = '';
                if (isset($u->last_name))
                    $usersarr["last_name"] = $u->last_name;
                else
                    $usersarr["last_name"] = '';
                
              $usersarr["user_avatar"] = (isset($u->user_avatar)) ? $u->user_avatar : 'https://s3-us-west-2.amazonaws.com/tempoevent/avatar/default.png';

                /* $usersarr["display_name"] = $u->display_name;
                  $usersarr["user_avatar"] = $u->user_avatar;
                  $usersarr["tempo_user_rank"] = $u->tempo_user_rank; */
            }
        }

        return $usersarr;
    }

    public function update_event_media_like($request, $response) {
        $postData = $request->getParsedBody();
        $userid = $postData['userid'];
        $eventid = $postData['eventid'];
        $event_post_id = $postData['event_post_id'];
        $islike = (int) $postData['islike'];   /// Unlike => 0; Like => 1 
        date_default_timezone_set('UTC');
        $check_post_exists = $this->_db->table('event_posts')
                ->selectRaw('event_post_id')->where(array('event_id' => $eventid, 'event_post_id' => $event_post_id))
                ->where('isdeleted', 0)
                ->get();
        if (count($check_post_exists) == 0) {
            $result['success'] = 0;
            $result['message'] = "Post does not exists";
        } else {
            $check_exists = $this->_db->table('event_media_like')
                            ->selectRaw('event_media_like_id')->where(array('user_id' => $userid, 'event_id' => $eventid, 'event_post_id' => $event_post_id))->get();

            if (count($check_exists) == 0) {

                $eventmedialike = new EventMediaLike;

                $eventmedialike->user_id = $userid;
                $eventmedialike->event_id = $eventid;
                $eventmedialike->event_post_id = $event_post_id;
                $eventmedialike->islike = $islike;

                $eventmedialike->created_at = date('Y-m-d H:i:s');
                $eventmedialike->updated_at = null;
                $eventmedialike->save();
                $getlastlike_count = $this->_db->table('event_media_like')
                        ->select('event_media_like_id')->where(array('event_post_id' => $event_post_id, 'event_id' => $eventid, 'islike' => 1)) // <<== Like Count Only
                        ->count();
                $this->_db->table('event_posts')
                        ->where('event_post_id', $event_post_id)
                        ->update([
                            'like_count' => $getlastlike_count]);

                $result['success'] = 1;
                $result['event_post_id'] = $event_post_id;
                $result['eventid'] = $eventid;
                $result['like_count'] = $getlastlike_count;
            } else {
                $eventmedialike = new EventMediaLike;

                $eventmedialike->user_id = $userid;
                $eventmedialike->event_post_id = $event_post_id;
                $eventmedialike->islike = $islike;
                $eventmedialike->updated_at = date("Y-m-d H:i:s");
                $eventmedialike->update();
                $this->_db->table('event_media_like')
                        ->where((array('user_id' => $userid, 'event_post_id' => $event_post_id)))
                        ->update([
                            'islike' => $islike]);

                $getlastlike_count = $this->_db->table('event_media_like')
                        ->select('event_media_like_id')->where(array('event_post_id' => $event_post_id, 'islike' => 1))// <<== Like Count Only
                        ->count();
                $this->_db->table('event_posts')
                        ->where('event_post_id', $event_post_id)
                        ->update([
                            'like_count' => $getlastlike_count]);

                $result['success'] = 1;
                $result['event_post_id'] = $event_post_id;
                $result['eventid'] = $eventid;
                $result['like_count'] = $getlastlike_count;
            }

            ////Add User Notification
            if ($islike != 0) {
                $EventId = $this->getPostEventId($event_post_id);
                $EventPostUserId = $this->getEventPostUserId($event_post_id);

                $userNotification = new UserNotification;

                $userNotification->from_user_id = $userid;
                $userNotification->to_user_id = (isset($EventPostUserId)) ? $EventPostUserId : 0;

                $EventPostUserId = (isset($EventPostUserId)) ? $EventPostUserId : 0;
                $fromuser = $this->getUserName($userid);
                $touser = $this->getUserName($EventPostUserId);


                $userNotification->event_id = $EventId;
                if ($EventPostUserId == $postData['userid'])
                    $userNotification->comment_text = 'You have liked your post';
                else
                    $userNotification->comment_text = $fromuser . ' has liked your post';

                $userNotification->notification_type = 'eventpostlike';
                $userNotification->status = 'unread';
                $userNotification->created_at = date('Y-m-d H:i:s');
                $userNotification->updated_at = null;
                $userNotification->save();

                $lastNotifyId = $userNotification->id;
                $getLatestNotification = $this->_db->table('user_notifications')
                        ->where('user_notification_id', $lastNotifyId)
                        ->get();
                $Notifydata = $getLatestNotification[0];
                $devInfo = $this->getDeviceInfo($EventPostUserId);
                $contentmsg = $fromuser . ' has liked your post';
                if (count($devInfo) > 0) {
                    foreach ($devInfo as $dev123) {
                        if (isset($dev123->one_signal_userid)) {
                            $one_signal_userid = trim($dev123->one_signal_userid);
                            $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $EventPostUserId);
                        }
                    }
                }
            }
        }

        return $response->withJson($result);
    }

    public function getComments($request, $response) {
        $postData = $request->getParsedBody();
        date_default_timezone_set('UTC');
        // $user_id = isset($postData['userid']) ? (int) $postData['userid'] : 0;
        $event_post_id = isset($postData['eventpostid']) ? (int) $postData['eventpostid'] : 0;
        $offset = isset($postData['offset']) ? (int) $postData['offset'] : 0;

        if ($offset > 0) {
            $get_comments = $this->_db->table('event_comments')
                    // ->where('commented_by', '=', $user_id)
                    ->where('event_post_id', '=', $event_post_id)
                    ->where('event_comment_id', '>', $offset)
                    ->get();
        } else {
            $get_comments = $this->_db->table('event_comments')
                    //  ->where('commented_by', '=', $user_id)
                    ->where('event_post_id', '=', $event_post_id)
                    ->get();
        }

        if (count($get_comments) > 0) {
            $result['success'] = 1;
            $comnew = array();

            foreach ($get_comments as $eve) {
                if (isset($eve->created_at))
                    $eve->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->created_at)));
                if (isset($eve->updated_at))
                    $eve->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($eve->updated_at)));

                $eve->commented_byuserid = $eve->commented_by;
                $eve->commented_byname = $this->getUserName($eve->commented_by);
                array_push($comnew, $eve);
            }
            $result['comments'] = $comnew;
        } else {
            $result['success'] = 0;
        }
        return $response->withJson($result);
    }

    public function getPostEventId($event_post_id) {
        $get_eve = $this->_db->table('event_posts')
                ->where('isdeleted', 0)
                ->where('event_post_id', '=', $event_post_id)
                ->select('event_id')
                ->get();
        if (isset($get_eve[0])) {
            return $get_eve[0]->event_id;
        } else {
            return 0;
        }
    }

    public function getEventUserId($eventid) {
        $get_user = $this->_db->table('events')
                ->where('isdeleted', 0)
                ->where('event_id', '=', $eventid)
                ->select('created_by_user')
                ->get();
        if (isset($get_user[0])) {
            return $get_user[0]->created_by_user;
        } else {
            return '';
        }
    }

    public function getEventPostUserId($event_post_id) {
        $get_user = $this->_db->table('event_posts')
                ->where('isdeleted', 0)
                ->where('event_post_id', '=', $event_post_id)
                ->select('post_by')
                ->get();
        if (isset($get_user[0])) {
            return $get_user[0]->post_by;
        } else {
            return '';
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

    public function getfriendsofUser($userid) {
        $userarr = array();
        if (isset($userid)) {
            $user = $this->_db->table('user_network')
                    ->select('network_user_id')
                    ->where('primary_user_id', $userid)
                    ->where('association_type', 'follow')
                    ->where('network_status', 'Accepted')
                    ->get();
            foreach ($user as $us) {
                array_push($userarr, $us->network_user_id);
            }
        }
        return $userarr;
    }

    public function addusernotificationtoCoHost($eventid, $fromuserid, $co_host, $status) {

        date_default_timezone_set('UTC');
        $lastNotifyIdarr = array();
        if (is_array($co_host)) {
            foreach ($co_host as $ho) {
                $fromuser = $this->getUserName($fromuserid);
                $userNotification = new UserNotification;
                $userNotification->event_id = $eventid;
                $userNotification->from_user_id = $fromuserid;
                $userNotification->to_user_id = $ho;
                $userNotification->comment_text = $fromuser . ' has invited you as Host for an event';
                $userNotification->notification_type = 'hostinvitaion';
                $userNotification->status = 'unread';
                $userNotification->created_at = date('Y-m-d H:i:s');
                $userNotification->updated_at = null;
                $userNotification->save();
                array_push($lastNotifyIdarr, $userNotification->id);
            }
        } else {
            $fromuser = $this->getUserName($fromuserid);
            $userNotification = new UserNotification;
            $userNotification->event_id = $eventid;
            $userNotification->from_user_id = $fromuserid;
            $userNotification->to_user_id = $co_host;
            if ($status == "Accepted") {
                $userNotification->comment_text = $fromuser . ' has accepted your request for Host for an event';
                $userNotification->notification_type = 'accepthostinvitation';
            }
            if ($status == "Invited") {
                $userNotification->comment_text = $fromuser . ' has invited you as Host for an event';
                $userNotification->notification_type = 'hostinvitation';
            }
            if ($status == "Rejected") {
                $userNotification->comment_text = $fromuser . ' has rejected your request for Host for an event';
                $userNotification->notification_type = 'rejectthostinvitation';
            }

            $userNotification->status = 'unread';
            $userNotification->created_at = date('Y-m-d H:i:s');
            $userNotification->updated_at = null;
            $userNotification->save();
            array_push($lastNotifyIdarr, $userNotification->id);
        }

        foreach ($lastNotifyIdarr as $lastNotifyId) {
            $getLatestNotification = $this->_db->table('user_notifications')
                    ->where('user_notification_id', $lastNotifyId)
                    ->get();
            $Notifydata = $this->getUserNotificationDetails($lastNotifyId);
            $devInfo = $this->getDeviceInfo($Notifydata->to_user_id);

            if ($status == "Accepted")
                $contentmsg = $fromuser . ' has accepted your request for Host for an event';
            if ($status == "Invited")
                $contentmsg = $fromuser . ' has invited you as Host for an event';
            if ($status == "Rejected")
                $contentmsg = $fromuser . ' has rejected your request for Host for an event';

            if (count($devInfo) > 0) {
                foreach ($devInfo as $dev123) {
                    if (isset($dev123->one_signal_userid)) {
                        $one_signal_userid = trim($dev123->one_signal_userid);
                        $this->sendOneSignalNotify($one_signal_userid, $Notifydata, $contentmsg, $Notifydata->to_user_id);
                    }
                }
            }
        }
    }

    public function getUserNotificationDetails($lastinserted) {
        $getLatestNotification = $this->_db->table('user_notifications')
                ->where('user_notification_id', $lastinserted)
                ->get();
        return $getLatestNotification[0];
    }

    public function getMyEventsFromVarious($userid, $listType = null) {
        $arrCo_event_id = array();
        $arrInv_event_id = array();
        $myevent = array();
        if ($listType == 'feeds' || $listType == 'feedslistfilter') {
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
        }
        if ($listType == "myevents") {
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
        }


        if (count($arrCo_event_id) > 0 && count($arrInv_event_id) > 0) {
            $myevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('created_by_user', $userid)
                    ->where('is_published', 1)
                    ->orwhereIn('event_id', $arrCo_event_id)
                    ->orwhereIn('event_id', $arrInv_event_id)
                    ->get();
        } else if (count($arrInv_event_id) > 0) {
            $myevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('created_by_user', $userid)
                    ->where('is_published', 1)
                    ->orwhereIn('event_id', $arrInv_event_id)
                    ->get();
        } else if (count($arrCo_event_id) > 0) {
            $myevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('created_by_user', $userid)
                    ->where('is_published', 1)
                    ->orwhereIn('event_id', $arrCo_event_id)
                    ->get();
        } else {
            $myevent = $this->_db->table('events')
                    ->where('isdeleted', 0)
                    ->where('created_by_user', $userid)
                    ->get();
        }
        $retarray = array();
        if (count($myevent) > 0) {
            foreach ($myevent as $ev) {
                if ($ev->isdeleted != 1) {
                    if (isset($ev->created_at))
                        $ev->created_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($ev->created_at)));
                    if (isset($eve->updated_at))
                        $ev->updated_at = str_replace('+00:00', 'Z', gmdate('c', strtotime($ev->updated_at)));
                    array_push($retarray, $ev);
                }
            }
        }
        return $retarray;
    }

    public function deleteeventpost($request, $response) {
        $postData = $request->getParsedBody();
        $event_post_id = isset($postData['postid']) ? $postData['postid'] : 0;

        $result['success'] = 0;
        $result['message'] = "No Post available";

        $exists_event_posts = $this->_db->table('event_posts')
                ->where('event_post_id', $event_post_id)
                ->where('isdeleted', 0)
                ->select('event_post_id')
                ->get();
        if (isset($exists_event_posts[0])) {
            $this->_db->table('event_posts')
                    ->where('event_post_id', $event_post_id)
                    ->update([
                        'isdeleted' => 1]);

            $result['success'] = 1;
            $result['message'] = "Post deleted successfully";
        }
        return $response->withJson($result);
    }

    public function deleteevent($request, $response) {
        $postData = $request->getParsedBody();
        $event_id = isset($postData['eventid']) ? $postData['eventid'] : 0;

        $result['success'] = 0;
        $result['message'] = "No Event available";

        $exists_events = $this->_db->table('events')
                ->where('event_id', $event_id)
                ->where('isdeleted', 0)
                ->select('event_id')
                ->get();
        if (isset($exists_events[0])) {
            $this->_db->table('events')
                    ->where('event_id', $event_id)
                    ->update([
                        'isdeleted' => 1]);

            $result['success'] = 1;
            $result['message'] = "Event deleted successfully";
        }
        return $response->withJson($result);
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

}
