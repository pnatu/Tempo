<?php

use Aws\S3\S3Client;

// Bucket Name
$bucket = "tempoevent"; 
//AWS access info
if (!defined('awsAccessKey')) 
    define('awsAccessKey', 'AKIAITUC3Y42BGAJW53A');
if (!defined('awsSecretKey')) 
    define('awsSecretKey', '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'); 
$client = S3Client::factory(
                array(
                    'credentials' => array(
                        'key' => awsAccessKey,
                        'secret' => awsSecretKey
                    ), 
                    'region' => 'us-west-2',
                    'version' => 'latest',
                    'scheme' => 'http',
                    'signature'=>'v4'
                )
);
 

$awsS3Url = "https://s3-us-west-2.amazonaws.com/";
//$awsS3Url = "http://content.alivetempo.com/";
$ONESIGNAL_APP_ID = "cf5ddcd1-db11-46cf-96bd-500c1fac8b3b";
$ONESIGNAL_REST_API_KEY =  "MzBkMThhNzktODk1NC00MGRmLWI5NWEtNGZmNGY4MGM0NWY3";
 
$CLICKATELL_API = 'sMn38Z2nSw-J5oxCb1ypJQ==';
$CLICKATELL_USERNAME = 'carl@tempoevent.com';
$CLICKATELL_PASSWORD = 'Tempo2017';

?>
	