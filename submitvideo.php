<?php

session_start();
include "connection.php";
?>
<style>
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('images/wait.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>
<div class="loader"></div>
<?php

$loginid = end($_SESSION[SESS_MEMBER_ID]);
if (!$loginid) {
    header("Location: index.php#login");
    die;
}
if (isset($_POST['submitvideo'])) {
    $description = $_POST[description];
//    $videolink = $_POST[videolink];
    $pcountry = $_POST[pcountry];
    $freelanceserviceid = $_POST[freelanceserviceid];

    $name = $_FILES['videoimg']['name'];
    $size = $_FILES['videoimg']['size'];
    $tmpe = $_FILES['videoimg']['tmp_name'];

    $ppquery = mysql_query("SELECT * FROM freelancer_mmv_paypalsettings WHERE id='1'");
    $ppres = mysql_fetch_array($ppquery);
    $ppamount = $ppres[video];
    if ($ppamount == '0' || $ppamount == '0.00') {
        $status = '1';
    } else {
        $status = '0';
    }

    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $ext1 = strtolower(pathinfo($_FILES['videoimg']['name'], PATHINFO_EXTENSION));

    if (($ext1 == "mp4") || ($ext1 == "mov")) {
        if ($size < (3145728000)) {
            $actual_image_name = time() . substr($name, 0, 5) . "." . $ext;
            $actual_image = time() . substr($name, 0, 5) . ".png";
            //die;
            $tmp = $tmpe;
            $blocation = "uploads/live/" . $actual_image_name;
            $live_dir = 'uploads/live/';
            $uploadfile = "uploads/live/" . $name;
            $base = $blocation;
            //echo "<br>";
            $new_file = $base;
            $new_flv = $live_dir . $actual_image_name;
            //die;
            //shell_exec('/usr/local/bin/ffmpeg -i '.$uploadfile.' -f mp4 -s 320x240 '.$new_flv.'');	

            if (move_uploaded_file($tmp, $new_flv)) {
                $video = $blocation;
                $thumbnail = 'uploads/live/' . $actual_image;
                $thumbSize = '625x350';

                // shell command [highly simplified, please don't run it plain on your script!]
                shell_exec("/usr/local/bin/ffmpeg -i $new_flv -deinterlace -an -ss 1 -t 00:00:01 -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");

                $imgquery = mysql_query("INSERT INTO freelancer_mmv_userimages (id, userid, image, type, countryid, description, date, freelanceserviceid, status) VALUES ('', '" . $loginid . "','" . $new_flv . "','2','$pcountry','$description',NOW(),'$freelanceserviceid','$status')");
                $imid = mysql_insert_id();

                //max 9 post code commented by dev
                /*
                  $countquery = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$loginid'");
                  $count_res = mysql_num_rows($countquery);
                  if($count_res>9){
                  mysql_query("DELETE FROM freelancer_mmv_userimages WHERE userid='$loginid' ORDER BY id ASC LIMIT 1");
                  } */
                if ($ppamount == '0' || $ppamount == '0.00') {
                    echo '<script type="text/javascript">window.location = "index.php?status=success"</script>';
                } else {
                    echo '<script type="text/javascript">window.location = "paypal.php?type=2&imgid=' . $imid . '&uid=' . $loginid . '"</script>';
                }
            } else {
                echo '<script type="text/javascript">window.location = "index.php?status=uploadfail"</script>';
            }
        } else {
            echo '<script type="text/javascript">window.location = "index.php?status=maxsize"</script>';
        }
    } else {
        echo '<script type="text/javascript">window.location = "index.php?status=invalidfile"</script>';
    }

    if ($imgquery) {
        echo '<script type="text/javascript">window.location = "index.php"</script>';
    }
}
?>