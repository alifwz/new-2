<?php

include 'connection.php';
session_start();
if (!isset($_SESSION['SESS_MEMBER_ID']) || empty($_SESSION['SESS_MEMBER_ID'])) {
    echo "<script>window.location='index.php?status=failed'</script>";
    die;
}
$uid = 0;
if (isset($_SESSION['SESS_MEMBER_ID'][0]) and ! empty($_SESSION['SESS_MEMBER_ID'][0])) {
    $uid = $_SESSION['SESS_MEMBER_ID'][0];
}
$id = $_POST['id'];
$idd = $_REQUEST['id'];
$type = $_REQUEST['type'];
if ($type == 'uploads') {
    $query = mysql_query("DELETE FROM freelancer_mmv_userimages WHERE id='{$id}' and userid='{$uid}' ", $con);
} else if ($type == 'delpost') {
    $query = mysql_query("DELETE FROM freelancer_mmv_userimages WHERE id='{$idd}' and userid='{$uid}' ", $con);
    echo "<script>window.location='index.php?status=success'</script>";
}
if ($query) {
    echo "done";
}
?>