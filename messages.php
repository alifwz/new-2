<?php
session_start();

$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: index.php#login");
    die;
}
include "connection.php";
include "headerinvitation.php";
include "functions.php";
include "auth.php";

$clientid = $_REQUEST[id];
$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$clientid'");
$about_res = mysql_fetch_array($query);
?>

<!--start main-->
<div class="main" style="min-height:950px;">	
    <div id="sticktabs" class="notification-main">
        <!--<div class="notify-title">Notification</div>-->
        <table class="notification-links tabs">
            <tr>
                <td>
                    <a href="invitation.php">
                        <span class="tabs-title">Invitation</span>
                        <?php
                        $nowdate = date('Y-m-d H:i:s');
                        $banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where status=1 AND user_id='$loginid' AND readstatus=0 AND date>='$nowdate'");
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
                        $cal_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' AND acceptedstatus=1 AND calreadstatus=0");
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
                        $subcat_query = mysql_query("SELECT * FROM freelancer_mmv_member_like WHERE memberid='$loginid' AND postedby='$loginid' AND readstatus=0");
                        $cal_result1 = mysql_num_rows($subcat_query);
                        if (($fav_result1 != 0) || ($cal_result1 != 0)) {
                            ?>
                            <span class="bell-notify"></span>
                        <?php } ?>
                    </a>
                </td>
                <td>
                    <a href="messages.php" class="active">
                        <span class="tabs-title">Messages</span>
                        <span class="bell-notify"></span>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="notification-dtl">
        <div class="notification">
            <div class="messages-main">
                <div id="header" class="update-location-div">
                    <a href="updatecurrentlocation.php" class="update-location"><img src="images/update-location.png" alt="update location"/> Update Current Location</a>
                </div>
                <?php
                $chat_que = mysql_query("SELECT * from freelancer_mmv_chatmsgs where (userid='$loginid') AND parent_id='0' ORDER BY date DESC");
                while ($chat_result = mysql_fetch_array($chat_que)) {
                    $invitedid = getUserinfo($chat_result[receiverid]);
                    $userid = $chat_result[userid];
                    $receiverid = $chat_result[receiverid];
                    $receiverinfo = getUserinfo($receiverid);
                    $chatid = $chat_result[id];
                    if ($loginid == $userid) {

                        $latestdate_que = mysql_query("SELECT * from freelancer_mmv_chatmsgs where (userid='$loginid') AND (receiverid='$receiverid') ORDER BY date DESC");
                        $latestdate_res = mysql_fetch_array($latestdate_que);

                        $rescount = mysql_query("SELECT * from freelancer_mmv_chatmsgs where (userid='$loginid') AND (receiverid='$receiverid') AND readstatus=0");
                        $count = mysql_num_rows($rescount);

                        //Invitation data
                        $invid = $chat_result[invitationid];
                        $inv_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where invitation_id='$invid'");
                        $inv_count = mysql_num_rows($inv_que);
                        if ($inv_count > 0) {
                            $inv_result = mysql_fetch_array($inv_que);
                            $appdate = strtotime($inv_result[meetingdate]);
                            $startdate = date('Y-m-d H:i', $appdate);
                            $strtTime = strtotime("+180 minutes", $appdate);
                            $realdate = $appdate + $ttt;
                            $appointdate = date('Y-m-d H:i', $strtTime);
                            $currentdate = date('Y-m-d H:i');
                            $dbtimezone = $inv_result[timezone];

                            $converted_appdate = converToTz($appointdate, $timezone, $dbtimezone);
                            $converted_startdate = converToTz($startdate, $timezone, $dbtimezone);
                            if ($currentdate <= $converted_appdate) {
                                ?>

                                <div class="messages-box" id="the_function">
                                    <div class="job-row msgdtl" rol e="<?php echo $receiverid ?>">
                                        <a href="messages-detail3.php?id=<?php echo $receiverid ?>" >
                                        <!--<a onClick="viewclient.php?id=<?php echo $receiverid ?>">-->
                                            <div class="msgimg-holder">
                                                <?php if ($invitedid[11] == "") { ?>
                                                    <img src="images/user.png" alt=""/>
                                                <?php } else { ?>
                                                    <img src="uploads/users/<?php echo $invitedid[11] ?>" alt=""/>
                                                <?php } ?>
                                            </div>
                                            <h3><?php echo $invitedid[3] . ' ' . substr($invitedid[4], 0, 8); ?><span><?php echo getSubExperience($invitedid[16]); ?></span></h3>
                                        </a>					
                                        <?php
                                        $dbtimezone = $latestdate_res[timezone];
                                        $chatdate1 = $latestdate_res[date];
                                        $chatdate = converToTz($chatdate1, $timezone, $dbtimezone);

                                        if ($chatdate > date('Y-m-d 00:00:00', strtotime("-1 days")) && $chatdate < date('Y-m-d 23:59:59', strtotime("-1 days"))) {
                                            $chatday = "Yesterday";
                                        } else if ($chatdate > date('Y-m-d 00:00:00') && $chatdate < date('Y-m-d 23:59:59')) {
                                            $chatday = date('H:i A', strtotime($chatdate));
                                        } else {
                                            $now = time();
                                            $your_date = strtotime($chatdate);
                                            $datediff = $now - $your_date;
                                            $chatday = round($datediff / (60 * 60 * 24)) . ' day(s) ago';
                                        }
                                        ?>
                                        <div class="msg-dtl"><?php echo $chatday ?> <div><span><?php echo $count ?></span></div></div>
                                    </div>
                                </div> <!--</a>-->
                            <?php
                            }
                        }
                    }
                }
                ?>				
            </div> 
        </div>

    </div>
</div>
<!--end main-->
<div class="messages-detail-main">	 
    <?php
    $chat_que = mysql_query("SELECT * from freelancer_mmv_chatmsgs where userid='$loginid' ORDER BY date DESC");
    while ($chat_result = mysql_fetch_array($chat_que)) {
        $invitedid = getUserinfo($chat_result[receiverid]);
        $userid = $chat_result[userid];
        $receiverid = $chat_result[receiverid];
        $receiverinfo = getUserinfo($receiverid);
        $chatid = $chat_result[id];
        if ($loginid == $userid) {
            ?>
            <div class="messages-dtl" id="<?php echo $receiverid ?>">
                <div class="messages-header">
                    <div class="job-row">
                        <div class="arrow-left"><img src="images/arrow-left.png" alt="Balck Wing"/></div>
                        <div class="msgimg-holder-div">
                            <a href="viewclient.php?id=<?php echo $receiverid ?>">
                                <div class="msgimg-holder">
                                    <?php if ($invitedid[11] == "") { ?>
                                        <img src="images/user.png" alt=""/>
                                    <?php } else { ?>
                                        <img src="uploads/users/<?php echo $invitedid[11] ?>" alt=""/>
        <?php } ?>
                                </div>
                                <h3><?php echo $invitedid[3] . ' ' . $invitedid[4]; ?> <span><?php echo getJob($invitedid[16]); ?></span></h3>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="notification" style="max-height: 735px; scroll:auto;scrollTop:0">
                    <div class="messages-chat">
                        <?php
                        $chat_que3 = mysql_query("SELECT * from freelancer_mmv_chatmsgs where (userid='$loginid') AND receiverid='$receiverid' ORDER BY date DESC");
                        while ($chat_result3 = mysql_fetch_array($chat_que3)) {
                            if ($loginid != $chat_result3[msgpostedby]) {
                                ?>	
                                <div class="chat">
                                    <div class="bubble me">
                                        <p><?php echo $chat_result3[message]; ?></p>
                                        <!--<div class="msg-dt-tm text-right">12:44 PM</div>-->
                                    </div>
                                </div>
            <?php } else { ?>
                                <div class="chat">
                                    <div class="bubble you">
                                        <p><?php echo $chat_result3[message]; ?></p>
                                        <!--<div class="msg-dt-tm text-right">12:44 PM</div>-->
                                    </div>
                                </div>

            <?php }
        }
        ?>

                        <form name="myForm" id="myForm" action="chatsubmit.php?id=<?php echo $receiverid ?>" method="post">
                            <input type="hidden" name="timezone" value="<?php echo $timezone ?>">
                            <div class="type-message-main">
                                <div class="container clearfix">
                                    <button type="submit" name="submit" class="send-btn"><img src="images/send-btn.png" alt="send"/></button>
                                    <div class="type-message">
                                            <!--<a href="javascript:void(0);" class="camera-link"><img src="images/camera-icon.png" alt="camera"/></a>
                                            <a href="javascript:void(0);" class="attach-link"><img src="images/attach-icon.png" alt="attachment"/></a>-->
                                        <div class="type-input-div"><input type="text" required name="msg" id="msg" class="input-msg" value="" placeholder="Type a message" /></div>			
                                    </div>
                                </div>
                            </div>				
                        </form>			

                    </div> 
                </div>
            </div>
    <?php }
}
?>					
</div>

<?php include "footer.php"; ?>
<script type="text/javascript" src="js/jquery.sticky.js"></script>
<script type="text/javascript">
    $(window).load(function () {
        $("#header").sticky({topSpacing: $('header').innerHeight() + $('#sticktabs').innerHeight()});
        $("#sticktabs").sticky({topSpacing: $('header').height()});
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#the_function").click(function () {
            $.ajax({
                type: 'POST',
                url: 'messageread.php',
                success: function (data) {

                }
            });
        });
    });
</script>