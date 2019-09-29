<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";
include "auth.php";

if (isset($_POST['submit'])) {
    $clientid = $_POST[invitehire];
    $count = count($_POST[invitehire]);
    $inval = implode("-", $clientid);
    echo "<script>window.location='meeting-request.php?clientid=$inval&num=$count'</script>";
}

$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: index.php#login");
    die;
}


$id = $_POST[invitehire];
$inval = implode(",", $id);
$_SESSION['INV_IDS'] = $_POST[invitehire];
$count = count($_POST[invitehire]);

if (isset($_POST[submitinv])) {

    $datetime = $_POST[datetime];
    $location = $_POST[location];
    $invtitedid = $_POST[invvals];
    $meetingtopics = $_POST[meetingtopics];
    $invcount = $_POST[incount];
    $what3word = "";

    $ppquery = mysql_query("SELECT * FROM freelancer_mmv_paypalsettings WHERE id='1'");
    $ppres = mysql_fetch_array($ppquery);
    $ppamount = $ppres[invite];
    if ($ppamount == '0' || $ppamount == '0.00') {
        $status = '1';
    } else {
        $status = '0';
    }

    $resul = explode(",", $invtitedid);
    foreach ($resul as $keys) {

        $clieinfo = getUserinfo($keys);
        $userinfo = getUserinfo($loginid);

        $query = mysql_query("INSERT INTO freelancer_mmv_member_invitation (invitation_id, user_id, invited_userid, hours_minutes, meeting_topics, photoshooting, offeredprice, date, location, meetingdate, invitationtype, status, what3word, timezone, invitation_return_reviewto, invitation_return_reviewby) VALUES ('', '$loginid','$keys','','$meetingtopics','','',NOW(),'$location','$datetime','2','$status','$what3word','$timezone','$keys','$loginid')");
        $inserid[] = mysql_insert_id();

        $totainv = implode('-', $inserid);
        add_member_notification($loginid, $keys, 'invite_request');
    }

    if ($query) {
        if ($ppamount == '0' || $ppamount == '0.00') {
            //Send Mail
            foreach ($resul as $keys) {
                $clieinfo = getUserinfo($keys);
                $userinfo = getUserinfo($loginid);

                $tou = $clieinfo[1];
                $fullname = $clieinfo[3] . ' ' . $clieinfo[4];
                $subjectu = "Freelancer Invite Invitation";
                $messageu = '<html>
					<head>
					<meta charset="utf-8">
					<title>Freelancer</title>
					<style type="text/css">
						a:hover{background:#ac5e2a!important;border:1px solid #ac5e2a!important }
					</style>
					</head>

					<body style="margin: 0px;padding: 0px">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr><td style="padding: 25px;background:#eee ">
					<table cellpadding="0" cellspacing="0" border="0" style="background: #ffffff">
						<tr>
							<td style="padding:10px 20px;"><div style="border-bottom:1px solid #d1b555;padding-bottom:15px "><img src="" alt="Freelancer" /></div></td>
						</tr>
						<tr>
							<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #000;border-collapse: collapse;padding: 15px;padding:10px 20px">Welcome to Freelancer</td>
						</tr>
						<tr>
							<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Hello ' . $clieinfo[3] . ' ' . $clieinfo[4] . ',</td>
						</tr>
						<tr>		
							<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
								You have received an Invite invitation request from <h3>' . $userinfo[3] . ' ' . $userinfo[4] . '</h3> please login to your freelancer account and check more information.
							</td>
						</tr>
						<tr>		
							<td style="padding:10px 20px 15px 20px">
								<a target="_blank" href="' . $urlpath . 'invitation.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Invite invitation link</a>
							</td>
						</tr>						
						<tr>		
							<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px 10px 20px">
								Thanks,<br>Freelancer.
							</td>
						</tr>	
					</table>
					</td></tr>
					</table>
					</body>
					</html>';

                // Always set content-type when sending HTML email
                //$headersu = "MIME-Version: 1.0" . "\r\n";
                //$headersu .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                //$headersu .= 'From: <notifications@meetfreelancers.com>' . "\r\n".
                //"Reply-To: noreply@meetfreelancers.com" . "\r\n" ;
                //$headers .= 'Cc: myboss@example.com' . "\r\n";
                smtpmailer($tou, $fullname, $from, $from_name, $subjectu, $messageu);
                //mail($tou,$subjectu,$messageu,$headersu);
            }
            echo "<script>window.location='hire.php?status=invited'</script>";
        } else {
            echo "<script>window.location='paypalinvite.php?id=$totainv&counts=$invcount'</script>";
        }
    }
}
?>
<!--start main-->
<form name="" action="" method="post" >
    <input type="hidden" name="invvals" value="<?php echo $inval; ?>">
    <input type="hidden" name="incount" value="<?php echo $count; ?>">
    <div class="main" style="min-height:850px">		
        <section class="contenets-main">
            <!--start post job -->
            <div class="contenets invite-main">
                <h2>Invitation</h2>			
                <div class="job-thumb favourite-box for-rating invite-box">
                    <div class="job-row location-text">
                        <p class="grey">Location</p>
                        <textarea placeholder="Enter your Meeting Location" id="text" required name="location" class="form-control"></textarea>	
                    </div>
                </div>
                <!--<div class="exact-location"><p>(Exact Location using What3worda ap)</p></div>
                <div class="job-thumb favourite-box for-rating invite-box">
                        <div class="job-row butchers-flattening-div">					
                                <input type="text" placeholder="Eg: Butchers.flattening.paded" id="what3word" name="what3word" class="form-control">
                        </div>
                </div>-->
                <!--<div class="job-thumb favourite-box for-rating invite-box">
                        <div class="input-group date form_datetime" data-date="" data-date-format="dd MM yyyy HH:ii p" data-link-field="dtp_input1">
            <input class="form-control" size="16" required="required" name="datetime" placeholder="Select Date and Time" type="text" value="" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        </div>
                        <div class="job-row date-time-div">
                                Fri - Oct 20 - 2017 20:00
                        </div>
                </div>-->
                <div class="job-thumb favourite-box for-rating invite-box bluebdr">
                    <div class="input-group"><input class="form-control" name="datetime" required="required" type="text" id="ip-ios" placeholder="Select Date and Time" readonly></div>				
                </div>
                <div class="meeting-topics">
                    <p class="grey">Event Description</p>
                    <div class="box-shadow share-experience">
                        <textarea placeholder="Enter your word here..." id="texts" required name="meetingtopics" class="form-control"></textarea>
                    </div>
                </div>

                <div class="row intrested-edit-cancel">
                    <div class="col-6">
                        <input type="hidden" id="textss">
                        <button type="submit" onclick="return check_val();" name="submitinv" style="width:183px;height:23" class="button">Send</button>
                    </div>
                    <div class="col-6">
                        <a href="invitation.php" class="button light-pink">Cancel</a>
                    </div>
                </div>				
            </div>
            <!--end post job -->
        </section>
    </div>
</form>
<!--end main-->

<div class="popbox">
    <div class="review-popup" id="reviewPopup">
        <?php
        $comm_query = mysql_query("SELECT * FROM freelancer_mmv_member_invitation WHERE user_id='$loginid' AND invited_userid='$clientid' AND acceptedstatus=1");
        $comm_res = mysql_num_rows($comm_query);
        $comm_result = mysql_fetch_array($comm_query);
        $appdate = strtotime($comm_result[meetingdate]);
        $hourmin = $comm_result[hours_minutes];

        $time = $hourmin;
        $timesplit = explode(':', $time);
        $min = ($timesplit[0] * 60) + ($timesplit[1]) + ($timesplit[2] > 30 ? 1 : 0) . ' minutes';
        $strtTime = strtotime("+" . $min, $appdate);
        $totaltime = $strtTime;
        $startdate = date('Y-m-d H:i', ($totaltime));
        $currentdate = date('Y-m-d H:i');

        if ($comm_res >= '1' && $currentdate >= $startdate) {
            ?>
            <form name="ratings" method="post" action="">
                <div class="write-comment-main">
                    <h2>Add Your Comments</h2>
                    <div class="rating-stars text-center">
                        <ul id="stars">
                            <li class="star" title="Poor" data-value="1"><i class="fa fa-star fa-fw"></i></li>
                            <li class="star" title="Fair" data-value="2"><i class="fa fa-star fa-fw"></i></li>
                            <li class="star" title="Good" data-value="3"><i class="fa fa-star fa-fw"></i></li>
                            <li class="star" title="Excellent" data-value="4"><i class="fa fa-star fa-fw"></i></li>
                            <li class="star" title="WOW!!!" data-value="5"><i class="fa fa-star fa-fw"></i></li>
                        </ul>
                    </div>
                    <div class="success-box">			
                        <div class="text-message"></div>
                        <input type="hidden" name="ratingnum" id="ratingnum" value="">
                        <div class="clearfix"></div>
                    </div>
                    <div class="row post-a-job-row">
                        <div class="col-12">
                            <div class="job-inputbox">
                                <textarea required name="review" class="job-input review-textarea" placeholder="Write Your Comment Here..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row post-a-job-row text-align-center">
                        <div class="col-12 paddingtop10">
                            <div class="rating-review rating-submit"><button class="button" name="submitrating" type="submit">Submit</button></div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
        <div class="review-main">
            <h2>Review Comments</h2>
            <?php
            $revquery = mysql_query("SELECT * FROM freelancer_mmv_reviewratings WHERE reviewto='$id' ORDER BY date DESC");
            while ($revres = mysql_fetch_array($revquery)) {
                $givenby = $revres[reviewby];
                $myrat = $revres[ratings];
                $resquery = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$givenby'");
                $rev_res = mysql_fetch_array($resquery);
                ?>
                <div class="job-thumb favourite-box for-rating invite-box">
                    <div class="job-row">
                        <div class="favourite-holder">
                            <?php if ($rev_res[image] == "") { ?>
                                <img src="images/user.png" alt="user"/>
                            <?php } else { ?>
                                <img src="uploads/users/<?php echo $rev_res[image] ?>" alt="user"/>
                            <?php } ?>
                        </div>
                        <div class="favourite-dtl">
                            <h3>
                                <?php if ($givenby != $loginid) { ?>
                                    <a href="viewclient.php?id=<?php echo $givenby ?>"><?php echo $rev_res[first_name] . ' ' . $rev_res[last_name] ?></a>
                                <?php } else { ?>
                                    <a href="#"><?php echo $rev_res[first_name] . ' ' . $rev_res[last_name] ?></a>
                                <?php } ?>
                                <div class="review-rating">	
                                    <?php
                                    if ($rateval >= '0.5' && $rateval < '1.5') {
                                        echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                    } else if ($rateval >= '1.5' && $rateval < '2.5') {
                                        echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                    } else if ($rateval >= '2.5' && $rateval < '3.5') {
                                        echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                    } else if ($rateval >= '3.5' && $rateval < '4.5') {
                                        echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                    } else if ($rateval >= '4.5' && $rateval <= '5') {
                                        echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>';
                                    } else {
                                        echo '<img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                    }
                                    ?>	
                                                <!--<span class="number">( 18 )</span>-->
                                </div>
                            </h3>					 
                        </div>
                    </div>
                    <p><?php echo $revres[reviewdesc] ?></p>				
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<script type="text/javascript">
    $(function ()
    {
        $('.sendBtn,.cancelBtn').hide();
        $('[name="allowed"]').change(function ()
        {
            if ($(this).is(':checked')) {
                $('.acceptBtn,.rejectBtn').hide();
                $('.sendBtn,.cancelBtn').show();
            } else {
                $('.acceptBtn,.rejectBtn').show();
                $('.sendBtn,.cancelBtn').hide();
            }
            ;
        });

    });
</script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
</script>	
