<?php

session_start();
include "connection.php";
$loginid = end($_SESSION[SESS_MEMBER_ID]);
$wid = $_POST['id'];

if ($loginid) {
    $cheklike_que = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$wid' AND user_id=$loginid");
    $cheklike = mysql_num_rows($cheklike_que);

    $web_que = mysql_query("SELECT * from freelancer_mmv_userimages where id='$wid' AND status=1");
    $web_res = mysql_fetch_array($web_que);
    $postedby = $web_res[userid];

    if ($cheklike <= 0) {
        $imgquery = mysql_query("INSERT INTO freelancer_mmv_member_like (like_id, user_id, workid, postedby, memberid, date) VALUES ('', '" . $loginid . "','" . $wid . "','" . $postedby . "','" . $postedby . "',NOW())");
    } else {
        $imgquery = mysql_query("DELETE FROM freelancer_mmv_member_like WHERE user_id='" . $loginid . "' AND workid='" . $wid . "'");
    }

    $like_que = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$wid'");
    $count = mysql_num_rows($like_que);

    if ($imgquery) {
        echo $count;
    }
} else {
    $like_que = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$wid'");
    $count = mysql_num_rows($like_que);

    if ($imgquery) {
        echo $count;
    }
}
?>