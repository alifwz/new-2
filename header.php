<?php
ob_start();
session_start();
include "connection.php";
include "smtpfunction.php";
include "timezone.php";

if ($_SESSION[countryid] == "") {
    $countryid = "";
} else {
    $countryid = $_SESSION[countryid];
}

$loginid = end($_SESSION[SESS_MEMBER_ID]);
$pagename = basename($_SERVER['REQUEST_URI']);

if ($loginid != "") {
    mysql_query("UPDATE freelancer_mmv_work SET lastseen='$now', timezone='$timezone' where member_id='$loginid'");
    mysql_query("UPDATE freelancer_mmv_member_master SET lastseeen='$now', timezone='$timezone' where member_id='$loginid'");
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Freelancer</title>
        <!--<meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=1.0" />-->
        <meta name="viewport" content="width=device-width,user-scalable=no" />
        <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png" />
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png" />
        <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png" />
        <link rel="manifest" href="favicon/manifest.json" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet" />
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link href="css/selectric.css" rel="stylesheet" type="text/css" media="all" />
        <link href="lightbox/jquery.fancybox.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/responsive.css" rel="stylesheet" type="text/css" media="all" />
        <link rel="stylesheet" type="text/css" href="date-time/anypicker.css" />
        <link rel="stylesheet" type="text/css" href="date-time/anypicker-ios.css" />
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    </head>
    <body>
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
        <?php
        $basename = basename($_SERVER['REQUEST_URI']);
        if ($basename != 'index.php') {
            ?>
            <!--<div class="loader"></div>-->
        <?php } ?>
        <!--start header-->
        <div class="wrapper">
            <header>
                <div class="container clearfix">
                    <div class="sub-header">
                        <div class="left-select bgnone">
                            <div class="select-box select-style">
                                <select name="selectcountry" id="selectcountry" class="form-control country_header" style="font-weight:lighter">			
                                    <option value="" selected>All Countries</option>
                                    <?php
                                    $country_query = mysql_query("SELECT * FROM `freelancer_mmv_countries` ORDER BY `freelancer_mmv_countries`.`countries_id` ASC");
                                    while ($country_res = mysql_fetch_array($country_query)) {
                                        $selcountryid = $country_res[countries_id];
                                        ?>
                                        <option value="<?php echo $selcountryid ?>" <?php
                                        if ($selcountryid == $countryid) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $country_res[countries_name]; ?></option>
                                            <?php } ?>				
                                </select>
                            </div>
                        </div>
                        <?php
                        if ($loginid != "") {
                            $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                            $login_result = mysql_fetch_array($login_que);
                            if ($login_result['first_name'] == '' || $login_result['last_name'] == '' || $login_result['country'] == '' || $login_result['nationality'] == '' || $login_result['expsectornew'] == '' || $login_result['hobby'] == '' || $login_result['mbti'] == '' || $login_result['area'] == '') {
                                ?>
                                <a data-fancybox data-type="inline" data-src="#profilefillPopup" href="javascript:void(0);" class="bell-div">
                                <?php } else {
                                    ?>
                                    <a href="invitation.php" class="bell-div">
                                    <?php }
                                    ?>
                                <?php } else { ?>
                                    <a href="#" data-fancybox data-type="inline" data-src="#loginPopup" class="bell-div">
                                    <?php } ?>
                                    <img src="images/bell-icon.png" alt="Bell"/>
                                    <?php
                                    $nowdate = date('Y-m-d H:i:s');
                                    
                                    $getNoti = "SELECT * FROM `freelancer_mmv_member_notifications` WHERE `to_user_id` = '".$loginid."' AND `status` = 0";
                                    $notiRes = mysql_query($getNoti);
                                    $notiRow = mysql_num_rows($notiRes);

//                                echo "SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' AND readstatus=0";
                                    /* $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' AND readstatus=0 AND status = 1 AND date>='$nowdate'");
                                    $banner_result = mysql_num_rows($banner_que);
//                                echo $banner_result;
                                    $cal_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where invited_userid='$loginid' AND invitedreadstatus=0 AND status = 1 AND date>='$nowdate'");
                                    $cal_result = mysql_num_rows($cal_que);

//                                echo $cal_result;
                                    $cal_que1 = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND acceptedstatus=1 AND calreadstatus=0 AND date>='$nowdate'");
                                    $cal_result1 = mysql_num_rows($cal_que1);
//                                echo $cal_result1;
                                    $cal_que1 = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND invited_userid='$loginid' AND acceptedstatus=1 AND calreadstatus=0 AND date>='$nowdate'");
                                    $cal_result2 = mysql_num_rows($cal_que1);

                                    $cal_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' AND calreadstatus=0 AND date>='$nowdate'");
                                    $inviread_result = mysql_num_rows($cal_que); 
//echo $inviread_result;
                                    $fav_que = mysql_query("SELECT * from freelancer_mmv_favourites where userid='$loginid' AND favstatus=0");
                                    $fav_result = mysql_num_rows($fav_que);
//                                echo $fav_result;
                                    $fav_que1 = mysql_query("SELECT * from freelancer_mmv_favourites where memberid='$loginid' AND favstatus=0");
                                    $fav_result1 = mysql_num_rows($fav_que1);*/
//                                echo $fav_result1;
                                    $like_que1 = mysql_query("SELECT * from freelancer_mmv_member_like where memberid='$loginid' AND readstatus=0");
                                    $like_result1 = mysql_num_rows($like_que1);
//                                echo $like_result1;
                                    $mes_que = mysql_query("SELECT * from freelancer_mmv_chatmsgs where userid='$loginid' AND readstatus=0");
                                    $mes_result = mysql_num_rows($mes_que);
//                                echo $mes_result;
//                                if ($mes_result != 0 || $banner_result != 0 || $fav_result != 0 || $fav_result1 != 0 || $mes_result != 0 || $like_result1 != 0 || $cal_result != 0 || $cal_result1 != 0 || $cal_result2 != 0) {
                                   

                                    if ($fav_result != 0 || $fav_result1 != 0 || $like_result1 != 0 || $mes_result != 0 || $notiRow !=0) {
//                                    echo 'herere';
                                        ?>
                                        <div class="<?php
                                        if ($loginid != "") {
                                            echo "bell-notify red";
                                        }
                                        ?>"><span></span></div>
                                         <?php } ?>
                                </a>
                                <div class="right-select category_header">
                                    <a href="javascript:void(0);" class="filter-link">
                                        <?php
                                        if ($_REQUEST[cid] != "") {
                                            $idd = $_REQUEST[cid];
                                            $web_que = mysql_query("SELECT * from freelancer_mmv_filter where filter_id='$idd' AND status='1'");
                                            $web_res = mysql_fetch_array($web_que);
                                            $catid = $web_res[parent_id];
                                            $web_que1 = mysql_query("SELECT * from freelancer_mmv_filter where filter_id='$catid' AND status='1'");
                                            $web_res1 = mysql_fetch_array($web_que1);

                                            if ($catid == "0") {
                                                $cattitle = $web_res[title];
                                            } else {
                                                $cattitle = $web_res1[title];
                                            }
                                            ?>
                                            <span id="jqval" style="color:red !important; font-weight:bold;"><?php echo $cattitle; ?></span>
                                            <?php
                                        } else if ($_SESSION[SESS_SUBCAT_ID] != "") {
                                            $cc = $_SESSION[SESS_SUBCAT_ID];
                                            $cat_query1 = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND filter_id=$cc");
                                            $cat_res1 = mysql_fetch_array($cat_query1);
                                            $parent_id = $cat_res1[parent_id];

                                            $web_que1 = mysql_query("SELECT * from freelancer_mmv_filter where filter_id='$parent_id' AND status='1'");
                                            $web_res1 = mysql_fetch_array($web_que1);

                                            if ($parent_id == "0") {
                                                $cattitle = $cat_res1[title];
                                            } else {
                                                $cattitle = $web_res1[title];
                                            }
                                            ?>
                                            <span id="jqval" ><?php echo $cattitle; ?></span>
                                        <?php } else { ?>
                                            <span id="jqval" >All Categories</span>
                                        <?php } ?>
                                    </a>		
                                </div>
                                <div class="filter-main">
                                    <div class="filter-div">

                                        <div class="triangle-div"><span><img src="images/triangle.png" alt="triangle" /></span></div>

                                        <div class="filter-sub">
                                            <form name="reset" method="post">					
                                                <button name="destroy" style="width:100%; font-size: 15px !important; background: #f7f7f7; border:0px; font-weight:300;" type="submit" class="button reset-button">Reset All Categories</button>
                                            </form>
                                            <br>
                                            <div class="filter-scroll">
                                                <ul>
                                                    <?php
                                                    $cat_query = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='0'");
                                                    while ($cat_res = mysql_fetch_array($cat_query)) {
                                                        $filterid = $cat_res[filter_id];
                                                        ?>
                                                        <li>

                                                            <div data-fancybox data-type="inline" data-src="#<?php echo $filterid; ?>" class="filter-box">
                                                                <div class="fltr-icon"><img src="uploads/filter/<?php echo $cat_res[image]; ?>" alt="<?php echo $filterid; ?>" title="" /></div>
                                                                <div class="fltr-title"><?php echo $cat_res[title]; ?></div>
                                                                <div class="sub-link-main" id="<?php echo $filterid; ?>">
                                                                    <div class="sub-link-title">
                                                                        <?php if (strpos($pagename, 'index.php') == 'index.php') { ?>	
                                                                            <a id="<?php echo $cat_res[filter_id]; ?>" href="index.php?cid=<?php echo $cat_res[filter_id]; ?>&type=cat"><?php echo $cat_res[title]; ?></a>
                                                                        <?php } else if (strpos($pagename, 'listing.php') == 'listing.php') { ?>
                                                                            <a id="<?php echo $cat_res[filter_id]; ?>" href="listing.php?cid=<?php echo $cat_res[filter_id]; ?>&type=cat"><?php echo $cat_res[title]; ?></a>
                                                                        <?php } else if (strpos($pagename, 'hire.php') == 'hire.php') { ?>
                                                                            <a id="<?php echo $cat_res[filter_id]; ?>" href="hire.php?cid=<?php echo $cat_res[filter_id]; ?>&type=cat"><?php echo $cat_res[title]; ?></a>
                                                                        <?php } else { ?>-->
                                                                            <a id="<?php echo $cat_res[filter_id]; ?>" href="index.php?cid=<?php echo $cat_res[filter_id]; ?>&type=cat"><?php echo $cat_res[title]; ?></a>
                                                                        <?php } ?>									
                                                                    </div>
                                                                    <div class="sub-link-list">
                                                                        <?php
                                                                        $subcat_query = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='$filterid'");
                                                                        while ($subcat_res = mysql_fetch_array($subcat_query)) {
                                                                            if (strpos($pagename, 'index.php') == 'index.php') {
                                                                                ?>						
                                                                                <a id="<?php echo $subcat_res[filter_id]; ?>" href="index.php?cid=<?php echo $subcat_res[filter_id]; ?>"><?php echo $subcat_res[title]; ?></a>
                                                                            <?php } else if (strpos($pagename, 'listing.php') == 'listing.php') { ?>
                                                                                <a id="<?php echo $subcat_res[filter_id]; ?>" href="listing.php?cid=<?php echo $subcat_res[filter_id]; ?>"><?php echo $subcat_res[title]; ?></a>
                                                                            <?php } else if (strpos($pagename, 'hire.php') == 'hire.php') { ?>
                                                                                <a id="<?php echo $subcat_res[filter_id]; ?>" href="hire.php?cid=<?php echo $subcat_res[filter_id]; ?>"><?php echo $subcat_res[title]; ?></a>
                                                                            <?php } else { ?>
                                                                                <a id="<?php echo $subcat_res[filter_id]; ?>" href="index.php?cid=<?php echo $subcat_res[filter_id]; ?>"><?php echo $subcat_res[title]; ?></a>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>	
                                                                    </div>
                                                                </div>
                                                            </div>	
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                                </header>

                                <!--end header-->
                                <script>
                                    /*$('.fltr-icon').click(function(e) {
                                     var alt = $(this).children("img").attr("alt");      
                                     $.ajax({
                                     url: "getfilterval.php",
                                     type: "POST",
                                     data: "categoryId="+alt,
                                     success: function (response) {		   
                                     $("#jqval").html(response);					
                                     },
                                     });
                                     });*/

                                    /*$('.sub-link-list a').click(function(e) {
                                     var sid = $(this).attr('id');	
                                     $.ajax({
                                     url: "getsubfilterval.php",
                                     type: "POST",
                                     data: "subcategoryId="+sid,
                                     success: function (response) {
                                     //alert(response);
                                     $("#jqval").html(response);					
                                     },
                                     });	
                                     });*/
                                </script>

