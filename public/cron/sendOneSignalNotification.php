<?php

$servername = 'tempodb.cetiq3nrjtaw.us-west-2.rds.amazonaws.com';
$username = 'satempo';
$password = '$aTemp0db';
$dbname = 'tempoapi';

// $servername = 'localhost';
// $username = 'root';
// $password = 'mysql123';
// $dbname = 'tempodb';


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user_posts_to where is_notified = 0";
$curdate = date("Y-m-d H:i:s");
$result = $conn->query($sql);
$to_be_deleted_ids = '';
$i = 0;
$post_type = 'image';

if ($result->num_rows > 0) {
    echo "Notified following Ids<br>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $contentmsgText = '';
        $user_post_id = $row["user_post_id"];
        $user_posted_to = $row["posted_to"];
        $user_posts_to_id = $row["user_posts_to_id"];
        if (isNotificationEnable($user_posted_to, $conn)) {
            $ONESIGNAL_APP_ID = "cf5ddcd1-db11-46cf-96bd-500c1fac8b3b";
            $ONESIGNAL_REST_API_KEY =  "MzBkMThhNzktODk1NC00MGRmLWI5NWEtNGZmNGY4MGM0NWY3";
            $post_det = getPostDetails($user_post_id, $conn);
            $notiDetails = getPostNotification($user_post_id, $post_det[0]["user_post_by"], $user_posted_to, $conn);

            $contentmsgText = $notiDetails[0]['comment_text'];
            $content = array(
                "en" => $contentmsgText
            );
            $onesingaluserid = getOneSignalUserId($user_posted_to, $conn);
            // var_dump(isset($onesingaluserid) && count($onesingaluserid)>0);exit;
            if (isset($onesingaluserid) && count($onesingaluserid)>0) {
                $fields = array(
                    'app_id' => $ONESIGNAL_APP_ID,
                    'include_player_ids' => array($onesingaluserid[0]["one_signal_userid"]),
                    'data' => $notiDetails[0],
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
                echo "user_posts_to_id = ".$user_posts_to_id."<br>";
                
                //return $response;
                updatePostDetails($user_posts_to_id, $conn);
                $i++;
            }
        }
    }
}
if ($i == 0)
    echo "<br>0 results";
$conn->close();

function getPostDetails($user_post_id, $conn) {
    $sql123 = "SELECT * FROM user_posts where user_post_id = " . $user_post_id;
    $result123 = $conn->query($sql123);
    $user_post_type = "image";
    $retArr = array();
    if ($result123->num_rows > 0) {
        while ($row = $result123->fetch_assoc()) {
            array_push($retArr, $row);
        }
    }
    return $retArr;
}

function getPostNotification($user_post_id, $fromUserId = 0, $toUserId = 0, $conn) {
    $sql123 = "SELECT * FROM user_post_notifications where user_post_id = " . $user_post_id . " AND notification_type = 'posted' AND status = 'unread' AND from_user_id = $fromUserId AND to_user_id = $toUserId order by user_post_notification_id DESC limit 0, 1 ";
    $result123 = $conn->query($sql123);
    $retArr = array();
    if ($result123->num_rows > 0) {
        while ($row = $result123->fetch_assoc()) {
            array_push($retArr, $row);
        }
    }
    return $retArr;
}

function getOneSignalUserId($userId = 0, $conn) {
    $sql123 = "SELECT * FROM user_devices where user_id = " . $userId;
    $result123 = $conn->query($sql123);
    $retArr = array();
    if ($result123->num_rows > 0) {
        while ($row = $result123->fetch_assoc()) {
            array_push($retArr, $row);
        }
    }
    return $retArr;
}

function isNotificationEnable($userId = 0, $conn) {
    $sql123 = "SELECT IsNotification FROM users where user_id = " . $userId;
    $result123 = $conn->query($sql123);
    $IsNotification = 0;
    if ($result123->num_rows > 0) {
        while ($row = $result123->fetch_assoc()) {
            $IsNotification = $row["IsNotification"];
        }
    }
    return $IsNotification;
}
function updatePostDetails($user_posts_to_id, $conn){
    $sql123 = "Update user_posts_to set is_notified = 1 where user_posts_to_id = " . $user_posts_to_id;
    $result123 = $conn->query($sql123); 
    return 1;
}
?>
 

