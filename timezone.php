<?php
$ip = $_SERVER['REMOTE_ADDR'];  
//$ip = '78.159.160.0';  
$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);
$timezone = date_default_timezone_get();
$remotedate = date('Y-m-d H:i:s');

$currentlocationdate = new DateTime("now", new DateTimeZone($timezone));
$now = $currentlocationdate->format('Y-m-d H:i:s');
?>