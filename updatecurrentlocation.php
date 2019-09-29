<?php
session_start();
include "connection.php";
$loginid = end($_SESSION[SESS_MEMBER_ID]);

$lats = $_COOKIE['mylatitude'];
$long = $_COOKIE['mylongitude'];
 
$exec = mysql_query("UPDATE freelancer_mmv_member_master SET loginlats='$lats', loginlong='$long' where member_id='$loginid'");	
if($exec==1){	
	header('Location: ' . $_SERVER['HTTP_REFERER'].'?status=success');
}
?>