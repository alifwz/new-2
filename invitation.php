<?php
session_start();
include "connection.php";
$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
// checks if profile is updated
$login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$user_id'");
$login_result = mysql_fetch_array($login_que);
if ($login_result['first_name'] == '' || $login_result['last_name'] == '' || $login_result['country'] == '' || $login_result['nationality'] == '' || $login_result['expsectornew'] == '' || $login_result['hobby'] == '' || $login_result['mbti'] == '' || $login_result['area'] == '') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/make-profile.php?status=updateprof");
    die;
}
include "headerinvitation.php";
include "functions.php";
include "what3wordsapp.php";
include "what3wordsmapp.php";
include "auth.php";

mysql_query("UPDATE freelancer_mmv_member_invitation SET userreadstatus='1' WHERE user_id='$loginid'");
mysql_query("UPDATE freelancer_mmv_member_invitation SET invitedreadstatus='1' WHERE invited_userid='$loginid'");
add_member_notification(0, $loginid, 'meet_request',1);
add_member_notification(0, $loginid, 'meet_accepted',1);
add_member_notification(0, $loginid, 'meet_rejected',1);
add_member_notification(0, $loginid, 'meet_edited',1);
add_member_notification(0, $loginid, 'invite_request',1);
add_member_notification(0, $loginid, 'invite_accepted',1);
add_member_notification(0, $loginid, 'invite_rejected',1);
?>
<style>
    .job-dtl h3 {
        font-size: 17px;
        padding: 3px 0px;
    }
</style>
<!--start main-->
<div class="main">	
    <div id="sticktabs" class="notification-main">
        <!--<div class="notify-title">Notification</div>-->
        <table class="notification-links tabs">
            <tr>
                <td>
                    <a href="invitation.php" class="active">
                        <span class="tabs-title">Invitation</span>
                        <?php
                        $nowdate = date('Y-m-d H:i:s');
                        $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND userreadstatus=0 AND date>='$nowdate'");
                        $banner_result = mysql_num_rows($banner_que);
                        if ($banner_result != 0) {
                            ?>
                            <span class="bell-notify"></span>
                        <?php } ?>
                    </a>
                </td>
                <td>
                    <a href="calendar.php">
                        <span class="tabs-title"><img src="images/icon-calendar.png" alt="Calendar" /></span>
                        <?php
                        $cal_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND acceptedstatus=1 AND calreadstatus=0");
                        $cal_result = mysql_num_rows($cal_que);
                        if ($cal_result != 0) {
                            ?>
                            <span class="bell-notify"></span>
                        <?php } ?>
                    </a>
                </td>
                <td>
                    <a href="favourite.php">
                        <span class="tabs-title"><img src="images/icon-favourite.png" alt="Favourite" /></span>
                        <?php
                        /* $fav_que = mysql_query("SELECT * from freelancer_mmv_favourites where userid='$loginid' AND favstatus=0");
                          $fav_result = mysql_num_rows($fav_que); */
                        $fav_que1 = mysql_query("SELECT * from freelancer_mmv_favourites where memberid='$loginid' AND favstatus=0");
                        $fav_result1 = mysql_num_rows($fav_que1);
//                        $subcat_query = mysql_query("SELECT * FROM freelancer_mmv_member_like WHERE memberid='$loginid' AND postedby='$loginid' AND readstatus=0");
//                        $cal_result1 = mysql_num_rows($subcat_query);
//                        if (($fav_result1 != 0) || ($cal_result1 != 0)) {
                        if (($fav_result1 != 0)) {
                            ?>
                            <span class="bell-notify"></span>
                        <?php } ?>
                    </a>
                </td>
                <td>
                    <a href="messages.php">
                        <span class="tabs-title">Messages</span>
                        <?php
                        $mes_que = mysql_query("SELECT * from freelancer_mmv_chatmsgs where userid='$loginid' AND readstatus=0");
                        $mes_result = mysql_num_rows($mes_que);
                        if ($mes_result != 0) {
                            ?>
                            <span class="bell-notify"></span>
                        <?php } ?>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="notification-dtl">
        <div class="notification">
            <div id="header" class="update-location-div">
                <a href="updatecurrentlocation.php" class="update-location"><img src="images/update-location.png" alt="update location"/> Update Current Location</a>
            </div>	
            <?php
            $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND invited_userid='$loginid' ORDER BY invitation_id DESC");
            while ($banner_result = mysql_fetch_array($banner_que)) {
                if ($banner_result[invited_userid] != '0') {
                    $invitedid = $banner_result[user_id];
                    $invinfo = getUserinfo($invitedid);

                    $dbtimezone = $banner_result[timezone];
                    $meetdate = $banner_result[meetingdate];
                    $converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);

                    $appdate = strtotime($converted_datetime);
                    $startdate = date('Y-m-d H:i', $appdate);
                    $strtTime = strtotime("+180 minutes", $appdate);
                    $realdate = $appdate + $ttt;
                    $appointdate = date('Y-m-d', strtotime($meetdate));
                    $currentdate = date('Y-m-d');

                    if ($currentdate <= $appointdate) {
                        ?>			
                        <div class="job-thumb cursor-pointer" >
                            <div class="job-row">				
                                <div class="job-thumb-holder setSize" onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">

                                    <div class="photograph">	
                                        <?php if ($invinfo[11] == "") { ?>
                                            <img src="images/user.png" alt=""/>
                                        <?php } else { ?>
                                            <img src="uploads/users/<?php echo $invinfo[11] ?>"/>
                                        <?php } ?>
                                    </div>

                                </div>					
                                <?php
                                $what3data = Getwhat3words($banner_result[what3word]);
                                $split = explode(',', $what3data);

                                //print_r($what3data);

                                $apilats = $split[0];
                                $apilongs = $split[1];

                                $memberid = $banner_result[invited_userid];
                                /* Get Distance */
                                $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                $login_result = mysql_fetch_array($login_que);
                                $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
                                $login_result1 = mysql_fetch_array($login_que1);
                                if ($banner_result[what3word] != '') {
                                    $latitudeTo = $apilats;
                                    $longitudeTo = $apilongs;
                                } else {
                                    $latitudeTo = $login_result1['loginlats'];
                                    $longitudeTo = $login_result1['loginlong'];
                                }

                                $latitudeFrom = $login_result['loginlats'];
                                $longitudeFrom = $login_result['loginlong'];

                                $distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
                                $finaldist = $distt;
                                /* End */
                                ?>
                                <?php
                                if ($banner_result[invitationtype] == 1) {
                                    $meettype = "Meeting";
                                } else {
                                    $meettype = "Invitation";
                                }
                                ?>
                                <div class="job-dtl">
                                    <div class="meeting-row"> 
                                        <?php if ($banner_result[edited] == 1 && $banner_result[acceptedstatus] == 0) { ?>
                                            <span class="red">Edited <?php echo $meettype; ?></span> with 
                                        <?php } else if ($banner_result[acceptedstatus] == 1) { ?>
                                            <span class="green">Accepted <?php echo $meettype; ?></span> with 
                                        <?php } else if ($banner_result[acceptedstatus] == 2) { ?>
                                            <span class="red"><?php echo $meettype; ?> Rejected </span> 
                                        <?php } else { ?>
                                            <span class="green"><?php
                                            if ($banner_result[invitationtype] == 1) {
                                                echo "Meeting Request";
                                            } else {
                                                echo "Invitation request";
                                            }
                                            ?></span> from 
                                            <!--<p><span class="km"><?php echo number_format($finaldist, 1) . ' km' ?></span></p>-->
                                        <?php } ?>	
                                    </div>						
                                    <h3><a href="viewclient.php?id=<?php echo $invitedid ?>"><?php echo $invinfo[3] . ' ' . $invinfo[4]; ?></a></h3>
                                    <?php if ($banner_result[invitationtype] == 1) { ?>
                                        <a onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <?php } else { ?>
                                            <a onClick="location.href = 'invitation-from.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                            <?php } ?>						
                                            <div class="date-time">
                                                <span>Date Time: </span> <?php echo $meetdate; // echo date('Y-m-d h:i A', strtotime($converted_datetime));         ?>							
                                                <p align="right"> 							
                                                    <!-- <span class="km" style="color:#000;font-weight:400;font-size:20px;"><?php echo number_format($finaldist, 1) . ' km' ?></span> -->
                                                </p>	
                                            </div>

                                        </a>
                                </div>
                            </div>				
                            <div class="job-row location-main">
                                <span class="location-pin">
                                    <?php if ($banner_result[what3word] != "") { ?>
                                        <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><img src="images/location-pin.png" alt="location"/></a>
                                    <?php } else { ?>
                                        <img src="images/location-pin.png" alt="location"/>
                                    <?php } ?>
                                </span>
                                <?php
                                if ($banner_result[acceptedstatus] == 1) {
                                    if ($currentdate <= $appointdate) {
                                        ?>
                                        <a class="send-mesage" href="messages-detail2.php?id=<?php echo $banner_result[user_id]; ?>&inv=<?php echo $banner_result[invitation_id]; ?>">Send<br/>Message</a>
                                        <?php
                                    }
                                }
                                ?>
                                <?php if ($banner_result[invitationtype] == 1) { ?>
                                    <a onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                    <?php } else { ?>
                                        <a onClick="location.href = 'invitation-from.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <?php } ?>

                                        <div class="location-address">
                                            <?php if ($banner_result[what3word] != "") { ?>
                                                <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><p><?php echo $banner_result[location]; ?></p></a>
                                            <?php } else { ?>
                                                <p><?php echo $banner_result[location]; ?></p>
                                            <?php } ?>
                                        </div>
                                        <!--<div class="wordaapp">Exact Location using What3words</div>-->
                                        <div align="right">
                                            <?php
                                            if ($banner_result[acceptedstatus] == 1) {
                                                if ($currentdate <= $appointdate) {
                                                    echo "<br><br>";
                                                }
                                            }
                                            ?>
                                            <span style="font-size:13px" class="km-num">Click here for details..</span></div>

                                        <?php /* if($banner_result[what3word]!=""){ ?>
                                          <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><div class="butchers"><?php echo $banner_result[what3word] ?></div></a>
                                          <?php } else { ?>
                                          <div class="butchers">eg: butchers.flattening.padded</div>
                                          <?php } */ ?>

                                    </a>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>

            <?php
            $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND acceptedstatus =1 ORDER BY invitation_id DESC");
            while ($banner_result = mysql_fetch_array($banner_que)) {
                if ($banner_result[invited_userid] != '0') {
                    $invitedid = $banner_result[invited_userid];
                    $invinfo = getUserinfo($invitedid);

                    $dbtimezone = $banner_result[timezone];
                    $meetdate = $banner_result[meetingdate];
                    $converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);

                    $appdate = strtotime($converted_datetime);
                    $startdate = date('Y-m-d H:i', $appdate);
                    $strtTime = strtotime("+180 minutes", $appdate);
                    $realdate = $appdate + $ttt;
                    $appointdate = date('Y-m-d', strtotime($meetdate));
                    $currentdate = date('Y-m-d');

                    if ($currentdate <= $appointdate) {
                        ?>			
                        <div class="job-thumb cursor-pointer">
                            <div class="job-row">				
                            <!--<a onClick="location.href='hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">-->			

                                <div class="job-thumb-holder setSize" 
                                <?php if ($banner_result[invitationtype] == 1) { ?>
                                         onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'"
                                     <?php } else { ?>
                                         onClick="location.href = 'invitation-to.php?id=<?php echo $banner_result[invitation_id]; ?>'"
                                     <?php } ?>
                                     >				
                                    <div class="photograph">
                                        <?php if ($invinfo[11] == "") { ?>
                                            <img src="images/user.png" alt=""/>
                                        <?php } else { ?>
                                            <img src="uploads/users/<?php echo $invinfo[11] ?>"/>
                                        <?php } ?>
                                    </div>

                                </div>

                                <?php
                                $what3data = Getwhat3words($banner_result[what3word]);
                                $split = explode(',', $what3data);

                                $apilats = $split[0];
                                $apilongs = $split[1];

                                $memberid = $banner_result[invited_userid];
                                /* Get Distance */
                                $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                $login_result = mysql_fetch_array($login_que);
                                $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
                                $login_result1 = mysql_fetch_array($login_que1);

                                $latitudeFrom = $login_result['loginlats'];
                                $longitudeFrom = $login_result['loginlong'];

                                if ($banner_result[what3word] != '') {
                                    $latitudeTo = $apilats;
                                    $longitudeTo = $apilongs;
                                } else {
                                    $latitudeTo = $login_result1['loginlats'];
                                    $longitudeTo = $login_result1['loginlong'];
                                }
                                $distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
                                $finaldist = $distt;
                                /* End */
                                ?>
                                <?php
                                if ($banner_result[invitationtype] == 1) {
                                    $meettype = "Meeting";
                                } else {
                                    $meettype = "Invitation";
                                }
                                ?>
                                <div class="job-dtl">						
                                    <h3><a href="viewclient.php?id=<?php echo $invitedid ?>"><?php echo $invinfo[3] . ' ' . $invinfo[4]; ?></a></h3>
                                    <!--<a onClick="location.href='hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">-->
                                    <?php if ($banner_result[invitationtype] == 1) { ?>
                                        <a onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <?php } else { ?>
                                            <a onClick="location.href = 'invitation-to.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                            <?php } ?>

                                            <?php if ($banner_result[acceptedstatus] == 1) { ?>
                                                <div class="meeting-row">
                                                    <span class="green">Accepted <?php echo $meettype; ?></span>
                                                </div>
                                            <?php } else if ($banner_result[acceptedstatus] == 2) { ?>
                                                <div class="meeting-row">
                                                    <span class="red">Rejected <?php echo $meettype; ?></span> 
                                                </div>
                                            <?php } ?>
                                            <div class="date-time">
                                                <span>Date Time: </span> <?php echo date('Y-m-d h:i A', strtotime($converted_datetime)); ?>
                                                <!-- <p align="right"> 							
                                                    <span class="km" style="color:#000;font-weight:400;font-size:20px;"><?php echo number_format($finaldist, 1) . ' km' ?></span>
                                                </p> -->
                                            </div>	
                                        </a>	
                                </div>
                            </div>									
                            <div class="job-row location-main">
                                <span class="location-pin">
                                    <?php if ($banner_result[what3word] != "") { ?>
                                        <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><img src="images/location-pin.png" alt="location"/></a>
                                    <?php } else { ?>
                                        <img src="images/location-pin.png" alt="location"/>
                                    <?php } ?>

                                </span>
                                <?php
                                if ($banner_result[acceptedstatus] == 1) {
                                    if ($currentdate <= $appointdate) {
                                        ?>
                                        <a class="send-mesage" href="messages-detail2.php?id=<?php echo $banner_result[invited_userid]; ?>&inv=<?php echo $banner_result[invitation_id]; ?>">Send<br />Message</a>
                                        <?php
                                    }
                                }
                                ?>

                                                                                                                                                                        <!--<a onClick="location.href='hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">-->
                                <?php if ($banner_result[invitationtype] == 1) { ?>
                                    <a onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                    <?php } else { ?>
                                        <a onClick="location.href = 'invitation-to.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <?php } ?>

                                        <div class="location-address">						
                                            <?php if ($banner_result[what3word] != "") { ?>
                                                <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><p><?php echo $banner_result[location]; ?></p></a>
                                            <?php } else { ?>
                                                <p><?php echo $banner_result[location]; ?></p>
                                            <?php } ?>
                                        </div>
                                        <!--<div class="wordaapp">Exact Location using What3words</div>-->
                                        <div align="right">
                                            <?php
                                            if ($banner_result[acceptedstatus] == 1) {
                                                if ($currentdate <= $appointdate) {
                                                    echo "<br><br>";
                                                }
                                            }
                                            ?>
                                            <span style="font-size:13px" class="km-num">Click here for details..</span></div>
                                        <?php /* if($banner_result[what3word]!=""){ ?>
                                          <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><div class="butchers"><?php echo $banner_result[what3word] ?></div></a>
                                          <?php } else { ?>
                                          <div class="butchers">eg: butchers.flattening.padded</div>
                                          <?php } */ ?>
                                    </a>
                            </div>				
                        </div>
                        <?php
                    }
                }
            }
            ?>

            <?php
            $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND acceptedstatus =2 ORDER BY invitation_id DESC");
            while ($banner_result = mysql_fetch_array($banner_que)) {
                if ($banner_result[invited_userid] != '0') {
                    $invitedid = $banner_result[invited_userid];
                    $invinfo = getUserinfo($invitedid);

                    $dbtimezone = $banner_result[timezone];
                    $meetdate = $banner_result[meetingdate];
                    $converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);

                    $appdate = strtotime($converted_datetime);
                    $startdate = date('Y-m-d H:i', $appdate);
                    $strtTime = strtotime("+180 minutes", $appdate);
                    $realdate = $appdate + $ttt;
                    $appointdate = date('Y-m-d', strtotime($meetdate));
                    $currentdate = date('Y-m-d');

                    if ($currentdate <= $appointdate) {
                        ?>			
                        <div class="job-thumb cursor-pointer">
                            <div class="job-row">

                                <div class="job-thumb-holder setSize" onClick="location.href = 'invitation-from-rejected.php?id=<?php echo $banner_result[invitation_id]; ?>'">						
                                    <div class="photograph">
                                        <?php if ($invinfo[11] == "") { ?>
                                            <img src="images/user.png" alt=""/>
                                        <?php } else { ?>
                                            <img src="uploads/users/<?php echo $invinfo[11] ?>"/>
                                        <?php } ?>
                                    </div>						 
                                </div>

                                <?php
                                $what3data = Getwhat3words($banner_result[what3word]);
                                $split = explode(',', $what3data);

                                $apilats = $split[0];
                                $apilongs = $split[1];

                                $memberid = $banner_result[invited_userid];
                                /* Get Distance */
                                $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                $login_result = mysql_fetch_array($login_que);
                                $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
                                $login_result1 = mysql_fetch_array($login_que1);

                                $latitudeFrom = $login_result['loginlats'];
                                $longitudeFrom = $login_result['loginlong'];

                                if ($banner_result[what3word] != '') {
                                    $latitudeTo = $apilats;
                                    $longitudeTo = $apilongs;
                                } else {
                                    $latitudeTo = $login_result1['loginlats'];
                                    $longitudeTo = $login_result1['loginlong'];
                                }
                                $distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
                                $finaldist = $distt;
                                /* End */
                                ?>
                                <?php
                                if ($banner_result[invitationtype] == 1) {
                                    $meettype = "Meeting";
                                } else {
                                    $meettype = "Invitation";
                                }
                                ?>
                                <div class="job-dtl">						
                                    <h3><a href="viewclient.php?id=<?php echo $invitedid ?>"><?php echo $invinfo[3] . ' ' . $invinfo[4]; ?></a></h3>
                                    <a onClick="location.href = 'invitation-from-rejected.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <?php if ($banner_result[acceptedstatus] == 1) { ?>
                                            <div class="meeting-row">
                                                <span class="green">Accepted <?php echo $meettype; ?></span>
                                            </div>
                                        <?php } else if ($banner_result[acceptedstatus] == 2) { ?>
                                            <div class="meeting-row">
                                                <span class="red">Rejected <?php echo $meettype; ?></span>
                                            </div>
                                        <?php } ?>
                                        <div class="date-time">
                                            <span>Date Time: </span> <?php echo date('Y-m-d h:i A', strtotime($converted_datetime)); ?>
                                            <!-- <p align="right"> 							
                                                <span class="km" style="color:#000;font-weight:400;font-size:20px;"><?php echo number_format($finaldist, 1) . ' km' ?></span>
                                            </p> -->
                                        </div>	
                                    </a>	
                                </div>
                            </div>									
                            <div class="job-row location-main">
                                <span class="location-pin">
                                    <?php if ($banner_result[what3word] != "") { ?>
                                        <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><img src="images/location-pin.png" alt="location"/></a>
                                    <?php } else { ?>
                                        <img src="images/location-pin.png" alt="location"/>
                                    <?php } ?>
                                </span>
                                <?php
                                if ($banner_result[acceptedstatus] == 1) {
                                    if ($currentdate <= $appointdate) {
                                        ?>
                                        <a class="send-mesage" href="messages-detail2.php?id=<?php echo $banner_result[invited_userid]; ?>&inv=<?php echo $banner_result[invitation_id]; ?>">Send<br />Message</a>
                                        <?php
                                    }
                                }
                                ?>
                                <a onClick="location.href = 'invitation-from-rejected.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                    <div class="location-address">
                                        <?php if ($banner_result[what3word] != "") { ?>
                                            <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><p><?php echo $banner_result[location]; ?></p></a>
                                        <?php } else { ?>
                                            <p><?php echo $banner_result[location]; ?></p>
                                        <?php } ?>
                                    </div>
                                    <!--<div class="wordaapp">Exact Location using What3words</div>-->
                                    <div align="right">
                                        <?php
                                        if ($banner_result[acceptedstatus] == 1) {
                                            if ($currentdate <= $appointdate) {
                                                echo "<br><br>";
                                            }
                                        }
                                        ?>
                                        <span style="font-size:13px" class="km-num">Click here for details..</span></div>
                                    <?php /* if($banner_result[what3word]!=""){ ?>
                                      <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><div class="butchers"><?php echo $banner_result[what3word] ?></div></a>
                                      <?php } else { ?>
                                      <div class="butchers">eg: butchers.flattening.padded</div>
                                      <?php } */ ?>
                                </a>
                            </div>				
                        </div>
                        <?php
                    }
                }
            }
            ?>


            <?php
            $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND edited='1' AND acceptedstatus='0' ORDER BY invitation_id DESC ");
            while ($banner_result = mysql_fetch_array($banner_que)) {
                if ($banner_result[invited_userid] != '0') {
                    $invitedid = $banner_result[invited_userid];
                    $invinfo = getUserinfo($invitedid);

                    $dbtimezone = $banner_result[timezone];
                    $meetdate = $banner_result[meetingdate];
                    $converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);

                    $appdate = strtotime($converted_datetime);
                    $startdate = date('Y-m-d H:i', $appdate);
                    $strtTime = strtotime("+180 minutes", $appdate);
                    $realdate = $appdate + $ttt;
                    $appointdate = date('Y-m-d', strtotime($meetdate));
                    $currentdate = date('Y-m-d');
                    if ($currentdate <= $appointdate) {
                        ?>			
                        <div class="job-thumb cursor-pointer">
                            <div class="job-row">

                                <div class="job-thumb-holder setSize" onClick="location.href = 'hire-detail-sender.php?id=<?php echo $banner_result[invitation_id]; ?>'">					
                                    <div class="photograph">
                                        <?php if ($invinfo[11] == "") { ?>
                                            <img src="images/user.png" alt=""/>
                                        <?php } else { ?>
                                            <img src="uploads/users/<?php echo $invinfo[11] ?>" />
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php
                                $what3data = Getwhat3words($banner_result[what3word]);
                                $split = explode(',', $what3data);

                                $apilats = $split[0];
                                $apilongs = $split[1];

                                $memberid = $banner_result[invited_userid];
                                /* Get Distance */
                                $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                $login_result = mysql_fetch_array($login_que);
                                $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
                                $login_result1 = mysql_fetch_array($login_que1);

                                $latitudeFrom = $login_result['loginlats'];
                                $longitudeFrom = $login_result['loginlong'];

                                if ($banner_result[what3word] != '') {
                                    $latitudeTo = $apilats;
                                    $longitudeTo = $apilongs;
                                } else {
                                    $latitudeTo = $login_result1['loginlats'];
                                    $longitudeTo = $login_result1['loginlong'];
                                }
                                $distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
                                $finaldist = $distt;
                                /* End */
                                ?>
                                <?php
                                if ($banner_result[invitationtype] == 1) {
                                    $meettype = "Meeting";
                                } else {
                                    $meettype = "Invitation";
                                }
                                ?>
                                <div class="job-dtl">
                                    <div class="meeting-row">
                                        <span class="red">Edited <?php echo $meettype; ?></span> with 
                                    </div>
                                    <h3><a href="viewclient.php?id=<?php echo $invitedid ?>"><?php echo $invinfo[3] . ' ' . $invinfo[4]; ?></a></h3>
                                    <a onClick="location.href = 'hire-detail-sender.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <div class="date-time">
                                            <span>Date Time: </span> <?php echo date('Y-m-d h:i A', strtotime($converted_datetime)); ?>							
                                            <!-- <p align="right"> 							
                                                <span class="km" style="color:#000;font-weight:400;font-size:20px;"><?php echo number_format($finaldist, 1) . ' km' ?></span>
                                            </p> -->						
                                        </div>
                                    </a>						
                                </div>
                            </div>				
                            <div class="job-row location-main">
                                <span class="location-pin">
                                    <?php if ($banner_result[what3word] != "") { ?>
                                        <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><img src="images/location-pin.png" alt="location"/></a>
                                    <?php } else { ?>
                                        <img src="images/location-pin.png" alt="location"/>
                                    <?php } ?>
                                </span>
                                <?php
                                if ($banner_result[acceptedstatus] == 1) {
                                    if ($currentdate <= $appointdate) {
                                        ?>
                                        <a class="send-mesage" href="messages-detail2.php?id=<?php echo $banner_result[invited_userid]; ?>&inv=<?php echo $banner_result[invitation_id]; ?>">Send<br />Message</a>
                                        <?php
                                    }
                                }
                                ?>
                                <a onClick="location.href = 'hire-detail-sender.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                    <div class="location-address">
                                        <?php if ($banner_result[what3word] != "") { ?>
                                            <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><p><?php echo $banner_result[location]; ?></p></a>
                                        <?php } else { ?>
                                            <p><?php echo $banner_result[location]; ?></p>
                                        <?php } ?>
                                    </div>
                                    <!--<div class="wordaapp">Exact Location using What3words</div>-->
                                    <div align="right">
                                        <?php
                                        if ($banner_result[acceptedstatus] == 1) {
                                            if ($currentdate <= $appointdate) {
                                                echo "<br><br>";
                                            }
                                        }
                                        ?>
                                        <span style="font-size:13px" class="km-num">Click here for details..</span></div>
                                    <?php /* if($banner_result[what3word]!=""){ ?>
                                      <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><div class="butchers"><?php echo $banner_result[what3word] ?></div></a>
                                      <?php } else { ?>
                                      <div class="butchers">eg: butchers.flattening.padded</div>
                                      <?php } */ ?>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>

            <?php
            $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND edited='0' AND acceptedstatus='0' ORDER BY invitation_id DESC ");
            while ($banner_result = mysql_fetch_array($banner_que)) {
                if ($banner_result[invited_userid] != '0') {
                    $invitedid = $banner_result[invited_userid];
                    $invinfo = getUserinfo($invitedid);

                    $dbtimezone = $banner_result[timezone];
                    $meetdate = $banner_result[meetingdate];
                    $converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);

                    $appdate = strtotime($converted_datetime);
                    $startdate = date('Y-m-d H:i', $appdate);
                    $strtTime = strtotime("+180 minutes", $appdate);
                    $realdate = $appdate + $ttt;
                    $appointdate = date('Y-m-d', strtotime($meetdate));
                    $currentdate = date('Y-m-d');
                    if ($currentdate <= $appointdate) {
                        ?>
                        <?php if ($banner_result[invitationtype] == 1) { ?>
                                                                                                                                                                                                                                                                <!--<a onClick="location.href='hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">-->
                            <div class="job-thumb cursor-pointer" onClick="location.href = 'hire-detail-sender.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                            <?php } else { ?>
                            <!--<a onClick="location.href='invitation-from.php?id=<?php echo $banner_result[invitation_id]; ?>'">-->
                                <div class="job-thumb cursor-pointer" onClick="location.href = 'invitation-to.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                <?php } ?>	


                                <div class="job-row">				
                                    <div class="job-thumb-holder setSize" onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <div class="photograph">	
                                            <?php if ($invinfo[11] == "") { ?>
                                                <img src="images/user.png" alt=""/>
                                            <?php } else { ?>
                                                <img src="uploads/users/<?php echo $invinfo[11] ?>"/>
                                            <?php } ?>
                                        </div>						
                                    </div>

                                    <?php
                                    $what3data = Getwhat3words($banner_result[what3word]);
                                    $split = explode(',', $what3data);

                                    $apilats = $split[0];
                                    $apilongs = $split[1];

                                    $memberid = $banner_result[invited_userid];
                                    /* Get Distance */
                                    $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                    $login_result = mysql_fetch_array($login_que);
                                    $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
                                    $login_result1 = mysql_fetch_array($login_que1);

                                    $latitudeFrom = $login_result['loginlats'];
                                    $longitudeFrom = $login_result['loginlong'];

                                    if ($banner_result[what3word] != '') {
                                        $latitudeTo = $apilats;
                                        $longitudeTo = $apilongs;
                                    } else {
                                        $latitudeTo = $login_result1['loginlats'];
                                        $longitudeTo = $login_result1['loginlong'];
                                    }
                                    $distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
                                    $finaldist = $distt;
                                    /* End */
                                    ?>
                                    <?php
                                    if ($banner_result[invitationtype] == 1) {
                                        $meettype = "Meeting";
                                    } else {
                                        $meettype = "Invitation";
                                    }
                                    ?>
                                    <div class="job-dtl">
                                        <div class="meeting-row">
                                            <span class="red"><?php echo $meettype; ?> </span>request with 
                                        </div>
                                        <h3><a href="viewclient.php?id=<?php echo $invitedid ?>"><?php echo $invinfo[3] . ' ' . $invinfo[4]; ?></a></h3>
                                        <div class="date-time">
                                            <span>Date Time: </span> <?php echo date('Y-m-d h:i A', strtotime($converted_datetime)); ?>
                                            <!-- <p align="right"> 							
                                                <span class="km" style="color:#000;font-weight:400;font-size:20px;"><?php echo number_format($finaldist, 1) . ' km' ?></span>
                                            </p> -->
                                        </div>						
                                    </div>
                                </div>				
                                <div class="job-row location-main">
                                    <span class="location-pin">
                                        <?php if ($banner_result[what3word] != "") { ?>
                                            <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>">
                                                <img src="images/location-pin.png" alt="location"/></a>
                                        <?php } else { ?>
                                            <img src="images/location-pin.png" alt="location"/>
                                        <?php } ?>
                                    </span>
                                    <?php
                                    if ($banner_result[acceptedstatus] == 1) {
                                        if ($currentdate <= $appointdate) {
                                            ?>
                                            <a class="send-mesage" href="messages-detail2.php?id=<?php echo $banner_result[invited_userid]; ?>&inv=<?php echo $banner_result[invitation_id]; ?>">Send<br />Message</a>
                                            <?php
                                        }
                                    }
                                    ?>

                                                                                                                                                                        <!--<a onClick="location.href='hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">-->
                                    <?php if ($banner_result[invitationtype] == 1) { ?>
                                        <a onClick="location.href = 'hire-detail-receiver.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                        <?php } else { ?>
                                            <a onClick="location.href = 'invitation-to.php?id=<?php echo $banner_result[invitation_id]; ?>'">
                                            <?php } ?>

                                            <div class="location-address">						
                                                <?php if ($banner_result[what3word] != "") { ?>
                                                    <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><p><?php echo $banner_result[location]; ?></p></a>
                                                <?php } else { ?>
                                                    <p><?php echo $banner_result[location]; ?></p>
                                                <?php } ?>
                                            </div>
                                            <!--<div class="wordaapp">Exact Location using What3words</div>-->
                                            <div align="right">
                                                <?php
                                                if ($banner_result[acceptedstatus] == 1) {
                                                    if ($currentdate <= $appointdate) {
                                                        echo "<br><br>";
                                                    }
                                                }
                                                ?>
                                                <span style="font-size:13px" class="km-num">Click here for details..</span></div>
                                                <?php /* if($banner_result[what3word]!=""){ ?>
                                                  <a target="blank" href="<?php Getwhat3wordsmap($banner_result[what3word]) ?>"><div class="butchers"><?php echo $banner_result[what3word] ?></div></a>
                                                  <?php } else { ?>
                                                  <div class="butchers">eg: butchers.flattening.padded</div>
                                                  <?php } */ ?>
                                        </a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>

            </div>
        </div>
    </div>
    <!--end main-->


    <?php include "footer.php"; ?>
    <script type="text/javascript" src="js/jquery.sticky.js"></script>
    <script type="text/javascript">
                                                $(window).load(function () {
                                                    $("#header").sticky({topSpacing: $('header').innerHeight() + $('#sticktabs').innerHeight()});
                                                    $("#sticktabs").sticky({topSpacing: $('header').height()});
                                                });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script>
                                                $(window).on("scroll", function () {
                                                    $.cookie("invScrollTop", $(window).scrollTop());
                                                    $.cookie("invclicks", 1);
                                                });
                                                $(function () {
                                                    if ($.cookie("invScrollTop")) {
                                                        $(window).scrollTop($.cookie("invScrollTop"));
                                                    }
                                                });

                                                function removecookies() {
                                                    $.cookie('invScrollTop', 0);
                                                }
    </script>

    <script>
        $("#tabstitle").click(function () {
            //alert();
            var $btn = $(this);
            var count = ($.cookie("invclicks", 2) || 0) + 1;
            //alert(count);
            $.cookie("invclicks", count);
            if (count == 1)
                alert();
            else if (count == 2)
                alert();
            else {
                removecookies();
                return true;
            }
            return false;
        });

    </script>