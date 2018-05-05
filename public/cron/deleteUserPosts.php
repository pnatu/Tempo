<?php

$servername = 'tempodb.cetiq3nrjtaw.us-west-2.rds.amazonaws.com';
$username = 'satempo';
$password = '$aTemp0db';
$dbname = 'TempoProd';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_post_id,NOW() as crdate,created_at, TIMESTAMPDIFF(HOUR, created_at, NOW()) AS HRS from user_posts WHERE isdeleted = 0";
$curdate = date("Y-m-d H:i:s");
$result = $conn->query($sql);
$to_be_deleted_ids = '';$i = 0;
if ($result->num_rows > 0) {
    echo "Following ids have been deleted created before 24 hours.<br>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        if ((int) $row["HRS"] > 24) {
            echo "id -  " . $row["user_post_id"] . "; Hours: " . $row["HRS"] ."<br>";
            $to_be_deleted_ids .= $row["user_post_id"] . ",";
            $i++;
        }
    }
      
    if ($to_be_deleted_ids != "") {
        $to_be_deleted_ids = rtrim($to_be_deleted_ids, ","); 
        $delsql = "UPDATE user_posts SET isdeleted = 1, updated_at = '$curdate' where user_post_id IN ($to_be_deleted_ids)";  
        $result = $conn->query($delsql);
    }
} 
 if($i==0)
     echo "<br>0 results";
$conn->close();
?>
 

 