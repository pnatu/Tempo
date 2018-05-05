<?php
$default = array( 'username' => 'root', 'password' => 'mysql123', 'host' => 'localhost', 'dbname' => 'tempodb');
$ApiCronDBCofig= require_once('../../config/autoload/global.php');
$db = $default;

$mysqlhost     = $db['host'];
$mysqlUserName = $db['username'];
$mysqlPassword = $db['password'];
$mysqlcatalog  = $db['dbname'];
date_default_timezone_set('UTC');
$GLOBALS['_SERVER']['CUSTOM']['MY_TIMEZONE'] = date('e T');
//$_SERVER['DOCUMENT_ROOT']=$_SERVER['DOCUMENT_ROOT'].'/pcfy';
$env = "Debug"; // Live OR Debug change this if do not want to log the messages
if(!function_exists('mysqlconnect')) 
{
    function mysqlconnect()
    {
        global $mysqlhost;
        global $mysqlUserName;
        global $mysqlPassword;
        global $mysqlcatalog;
        global $linknew;
		if(is_resource($linknew) && get_resource_type($linknew) === 'mysql link')
        {
					return $linknew;
         }else{
			$linknew=mysqli_connect($mysqlhost, $mysqlUserName, $mysqlPassword,$mysqlcatalog);
			return $linknew;
		}
    }
}
if(!function_exists('mysqlclose')) 
{
    function mysqlclose($linknew)
    {
        mysqli_close($linknew);
    }
}
if(!function_exists('generateLog')) 
{
 function generateLog($msg, $iscompulsory='false', $file='log.txt')
 {
     global $env; 
     
     
  } 
}
?>
