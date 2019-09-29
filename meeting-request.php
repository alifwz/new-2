<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";
include "auth.php";

$clientid = $_REQUEST[clientid];
$clientssid = $_REQUEST[clientid];
$countmet = $_REQUEST[num];

$splitmeeters = explode('-', $clientid);

$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$clientid'");
$about_res = mysql_fetch_array($query);
//Ratings
$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$clientid'");
$noofrev_res = mysql_fetch_array($noofrev_que);
$ratingsum = $noofrev_res[ratingsum];
$con = $noofrev_res[con];
$rateval = $ratingsum / $con;

$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: index.php#login");
    die;
}


if (isset($_POST[submit])) {

    $datechoosen = $_POST[datechoosen];
    $photoshoot = $_POST[photoshoot];
    //$hoour1 		= $_POST[hoour1];
    //$hoour2 		= $_POST[hoour2];

    $hour = $_POST[hour];
    //$min1			= $_POST[min1];
    //$min2 			= $_POST[min2];

    $min = $_POST[minute];

    $offerpay = $_POST[offerpay];

    //$offerpay1 		= $_POST[offerpay1];
    //$offerpay2 		= $_POST[offerpay2];
    //$offerpay3 		= $_POST[offerpay3];
    //$offerpay4 		= $_POST[offerpay4];
    //$offerpay5 		= $_POST[offerpay5];

    $meetingtopics = $_POST[meetingtopics];
    $location = $_POST[location];
    $meetingdate = $_POST[datechoosen];
    $what3word = "";

    //$hoursmin = $hoour1.''.$hoour2.':'.$min1.''.$min2;
    //$offeredprice = $offerpay1.''.$offerpay2.''.$offerpay3.''.$offerpay4.''.$offerpay5;

    $hoursmin = $hour . ':' . $min;
    $offeredprice = $offerpay;

    $ppquery = mysql_query("SELECT * FROM freelancer_mmv_paypalsettings WHERE id='1'");
    $ppres = mysql_fetch_array($ppquery);
    $ppamount = $ppres[hire];
    if ($ppamount == '0' || $ppamount == '0.00') {
        $status = '1';
    } else {
        $status = '0';
    }

    foreach ($splitmeeters as $invikeys) {

        $query2 = mysql_query("INSERT INTO freelancer_mmv_member_invitation (invitation_id, user_id, invited_userid, hours_minutes, meeting_topics, photoshooting, offeredprice, date, 	location, meetingdate, status, invitationtype, senderedit, receiveredit, what3word, timezone, invitation_return_reviewto, invitation_return_reviewby) VALUES ('', '$loginid','$invikeys','$hoursmin','$meetingtopics','$photoshoot','$offeredprice',NOW(),'$location','$meetingdate','$status','1','0','1','$what3word','$timezone','$invikeys','$loginid')");
        $inserid[] = mysql_insert_id();
        $totainv = implode('-', $inserid);
        add_member_notification($loginid, $invikeys, 'meet_request');
    }

    if ($query2 == 1) {
        if ($ppamount == '0' || $ppamount == '0.00') {
            echo "<script>window.location='hire.php?status=invited'</script>";
            //Send Mail
            foreach ($splitmeeters as $invikeys) {
                $clieinfo = getUserinfo($invikeys);
                $userinfo = getUserinfo($loginid);

                $tou = $clieinfo[1];
                $fullname = $clieinfo[3] . ' ' . $clieinfo[4];
                $subjectu = "Freelancer Meet Invitation";
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
									You have received an Meet invitation request from <h3>' . $userinfo[3] . ' ' . $userinfo[4] . '</h3> please login to your freelancer account and check more information.
								</td>
							</tr>
							<tr>		
								<td style="padding:10px 20px 15px 20px">
									<a target="_blank" href="' . $urlpath . 'invitation.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Meet Invitation link</a>
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
        } else {
            echo "<script>window.location='paypalinvite.php?id=$totainv&counts=$countmet'</script>";
        }
    }
}

if (isset($_POST[submitrating])) {
    $ratingnum = $_POST[ratingnum];
    $reviewdesc = $_POST[review];

    $query3 = mysql_query("INSERT INTO freelancer_mmv_reviewratings (id, reviewto, reviewby, ratings, reviewdesc, date) VALUES ('', '$clientid','$loginid','$ratingnum','$reviewdesc',NOW())");

    if ($query3 == 1) {
        echo "<script>window.location='meeting-request.php?status=posted'</script>";
    }
    echo "<script>window.location='meeting-request.php?clientid=$clientid'</script>";
}
?>
<!--start main-->
<div class="main">		
    <section class="contenets-main">
        <!--start post job -->
        <form name="" method="post" action="">
            <div class="contenets invite-main">
                <h2>Meeting Request</h2>
                <!--<div class="job-thumb favourite-box for-rating invite-box">
                        <div class="job-row">
                                <div class="favourite-holder">
                <?php if ($about_res[image] == "") { ?>
                                                                                                            <img src="images/user.png" alt="user"/>
                <?php } else { ?>
                                                                                                            <img src="uploads/users/<?php echo $about_res[image] ?>" alt="user"/>
                <?php } ?>
                                
                                </div>
                                <div class="favourite-dtl">
                                <h3><?php echo $about_res[first_name] . ' ' . $about_res[last_name] ?>
                                                <p><span class="grey">Country:</span> <?php echo getCountry($about_res[country]) ?></p>
                                </h3>
                                </div>
                                <div class="favourite-dtl" style="margin-bottom:60px;">											
                                        <div class="rating-review">
                                                <div class="rating-div">							
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
                                                         <span class="number">( <?php echo $con; ?> )</span>
                                                </div>
                                                <a href="javascript:void(0);" class="button reviewPopupBtn">Reviews</a>
                                        </div>					
                                </div>					
                        </div>				
                </div>-->
                <div class="job-thumb favourite-box for-rating invite-box">
                    <div class="job-row location-text">
                        <p class="grey">Location</p>
                        <textarea placeholder="Enter your Meeting Location" id="text" required name="location" class="form-control"></textarea>					
                    </div>
                </div>
                <!--<div class="exact-location"><p>(Exact Location using What3words)</p></div>
                <div class="job-thumb favourite-box for-rating invite-box">
                        <div class="job-row butchers-flattening-div">					
                                <input type="text" placeholder="Eg: Butchers.flattening.paded" id="what3word" name="what3word" class="form-control">
                        </div>
                </div>-->
                <!--	<div class="job-thumb favourite-box for-rating invite-box">
                                <div class="input-group date form_datetime" data-date="" data-date-format="dd MM yyyy HH:ii p" data-link-field="dtp_input1">
                    <input class="form-control" size="16" placeholder="Select Date and Time" type="text" value="" name="datechoosen" required>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>-->
                <div class="job-thumb favourite-box for-rating invite-box bluebdr">
                    <div class="input-group"><input class="form-control" name="datechoosen" type="text" id="ip-ios" placeholder="Select Date and Time" readonly></div>				
                </div>
                <div class="job-thumb favourite-box for-rating invite-box">
                    <div class="job-row photo-shooting-div">
                        <div class="photo-shooting">Photo Shooting</div>
                        <div class="allow-div">
                            <label class="control control--checkbox">Allowed
                                <input type="checkbox" id="allowed" class="allowed" name="allowed" />
                                <input type="hidden" name="photoshoot" id="photoshoot" value="1">
                                <div class="adt_width control__not_alowed">NOT Allowed</div>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <!--<div class="allow-div"><a href="javascript:void(0);" class="allowBtn">Alowed <span></span></a></div>-->
                    </div>
                </div>
                <div class="job-row hours-minutes">
                    <div class="box-shadow">
                        <p>Number of Hours</p>					
                        <div class="job-row input-numbox">
                                <!--<div class="num-box"><input type="text" maxlength="1" name="hoour1" id="" value="0"></div>
                                <div class="num-box"><input type="text" maxlength="1" name="hoour2" id="" value="0"></div> -->

                            <select id="hour"  name="hour" class="form-control">
                                <?php for ($hour = 0; $hour <= 12; $hour++) { ?>
                                    <option value="<?php echo str_pad($hour, 2, "0", STR_PAD_LEFT); ?>"><?php echo $hour; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-shadow second-div">
                        <p>Number of Minutes</p>				
                        <div class="job-row input-numbox">
                                <!-- <div class="num-box"><input type="text" maxlength="1" name="min1" id="" value="0"></div>
                                <div class="num-box"><input type="text" maxlength="1" name="min2" id="" value="0"></div> -->

                            <select id="minute"  name="minute" class="form-control">
                                <?php for ($minute = 0; $minute <= 30; $minute++) { ?>
                                    <option value="<?php echo str_pad($minute, 2, "0", STR_PAD_LEFT); ?>"><?php echo $minute; ?></option>
                                <?php } ?>
                            </select>							
                        </div>
                    </div>
                </div>
                <div class="job-row hours-minutes">				
                    <div class="box-shadow second-div margin-left0">
                        <p>Offered payment</p>					
                        <div class="job-row input-numbox">
                                <!--<div class="num-box"><input type="text" maxlength="1" name="offerpay1" id="" value="0"></div>
                                <div class="num-box"><input type="text" maxlength="1" name="offerpay2" id="" value="0"></div>
                                <div class="num-box"><input type="text" maxlength="1" name="offerpay3" id="" value="0"></div>
                                <div class="num-box"><input type="text" maxlength="1" name="offerpay4" id="" value="0"></div>
                                <div class="num-box"><input type="text" maxlength="1" name="offerpay5" id="" value="0"></div> -->
                            <select id="offerpay"  name="offerpay" class="form-control">
                                <?php for ($offerpay = 10; $offerpay <= 3000; $offerpay += 10) { ?>
                                    <option value="<?php echo $offerpay; ?>"><?php echo '$' . $offerpay; ?></option>
                                <?php } ?>
                            </select>
                            <!--<span class="usd-span">$</span> -->
                        </div>
                    </div>
                </div>
                <div class="meeting-topics">
                    <p class="grey">Meeting Topics</p>
                    <div class="box-shadow share-experience">
                        <textarea placeholder="Enter your word here..." id="texts" required name="meetingtopics" class="form-control"></textarea>
                    </div>
                </div>			
                <div class="meting-note red">Before you start the meeting:
                    Please handle meeting fees, that you already offered for the Freelancer, to Freelancers' hand.</div>
                <div class="row intrested-edit-cancel">
                    <div class="col-6">					
                        <input type="hidden" id="textss">
                        <button type="submit" name="submit" onclick="javascript:check_val();" style="width:183px;height:23" class="button">Send</button>
                    </div>
                    <div class="col-6">
                        <a href="invitation.php" class="button light-pink">Cancel</a>
                    </div>
                </div>				
            </div>
        </form>
        <!--end post job -->
    </section>
</div>

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
            $revquery = mysql_query("SELECT * FROM freelancer_mmv_reviewratings WHERE reviewto='$clientid' ORDER BY date DESC");
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

<!--end main-->
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
                $("#photoshoot").val(0);
            } else {
                $('.acceptBtn,.rejectBtn').show();
                $('.sendBtn,.cancelBtn').hide();
                $("#photoshoot").val(1);
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
