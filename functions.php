<?php
include "connection.php";

function getCountry($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_countries WHERE countries_id=$gid");
	$res = mysql_fetch_array($query);
	return $res[countries_name];	
}
function getNationality($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_nationalities WHERE nationality_id=$gid");
	$res = mysql_fetch_array($query);
	return $res[nationality_name];	
}
function getArea($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_area WHERE area_id=$gid");
	$res = mysql_fetch_array($query);
	return $res[area];	
}
function getEducation($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_education WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getDegree($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_degree WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getExperience($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='0' AND filter_id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}

function getSubExperience($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id!='0' AND filter_id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}

function getJob($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_jobtitle WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getHobby($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_hobby WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getSport($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_sport WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getFaith($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_faith WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getMBTI($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_mbti WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getFreelanceServices($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_service WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getFreelanceTiming($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_timings WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getUserinfo($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id=$gid");
	$res = mysql_fetch_array($query);
	return $res;	
}
function getBudget($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_expsector WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function getDuration($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_duration WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[durange];	
}
function getExperienceSector($gid){
	$query = mysql_query("SELECT * FROM freelancer_mmv_expsectornew WHERE id=$gid");
	$res = mysql_fetch_array($query);
	return $res[title];	
}
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

function converToTz($time="",$toTz='',$fromTz=''){
	$date = new DateTime($time, new DateTimeZone($fromTz));
	$date->setTimezone(new DateTimeZone($toTz));
	$time= $date->format('Y-m-d H:i');	
	return $time;
	
}

function add_member_notification($from, $to, $action, $status = 0) {
    //If status not sent then it will Enter new entry in freelancer_mmv_member_notifications table...
    // If Status = 1 sent then it will Update row and mark as notification read. Do send $from=0
    $nowdate = date('Y-m-d H:i:s');
    if ($status == 0) {
        $query_res = mysql_query("INSERT INTO `freelancer_mmv_member_notifications` (`from_user_id`, `to_user_id`, `action`, `status`, `added_on`, `modified_on`) "
                . "VALUES ('" . $from . "', '" . $to . "', '" . $action . "', '" . $status . "', '" . $nowdate . "', '" . $nowdate . "');");
    } else {
        $query_res = mysql_query("UPDATE `freelancer_mmv_member_notifications` SET `status` = '1',`modified_on`='" . $nowdate . "' WHERE `to_user_id`='" . $to . "' AND `action`='" . $action . "';");
    }
    return $query_res;
}

?>