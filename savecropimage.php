<?php

session_start();
include "connection.php";
$loginid = end($_SESSION[SESS_MEMBER_ID]);

function compress_image($source_url, $destination_url, $quality) {

    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$loginid'");
$res = mysql_fetch_array($query);
$freelanceserviceid = $res[16];

$file_to_upload = $_FILES['croppedImage']['tmp_name'];
$ext = end((explode(".", $name)));
$file_name = time() . '.png';
$blocation = "uploads/images/" . $file_name;
//move_uploaded_file($file_to_upload, 'uploads/images/'.$file_name);
compress_image($file_to_upload, 'uploads/images/' . $file_name, 40);

$imgquery = mysql_query("INSERT INTO freelancer_mmv_userimages (id, userid, image, type, countryid, website, date, freelanceserviceid, status) VALUES ('', '" . $loginid . "','" . $blocation . "','1','','', NOW(),'$freelanceserviceid','0')");
$imid = mysql_insert_id();

//$countquery = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$loginid' and status = 1");
//$count_res = mysql_num_rows($countquery);
//if ($count_res > 9) {
//    mysql_query("DELETE FROM freelancer_mmv_userimages WHERE userid='$loginid' and status = 1 ORDER BY id ASC LIMIT 1");
//}

if ($imgquery == 1) {
    echo $imid;
} else {
    echo 'failed';
}
?>