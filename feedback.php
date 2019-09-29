<?php
session_start();
$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: index.php#login");
    die;
}
include "connection.php";
// checks if profile is updated
$login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$user_id'");
$login_result = mysql_fetch_array($login_que);
if ($login_result['first_name'] == '' || $login_result['last_name'] == '' || $login_result['country'] == '' || $login_result['nationality'] == '' || $login_result['expsectornew'] == '' || $login_result['hobby'] == '' || $login_result['mbti'] == '' || $login_result['area'] == '') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/make-profile.php?status=updateprof");
    die;
}
include "header.php";
include "functions.php";
if (isset($_POST[submit])) {
    $errorinapp = $_POST[errorinapp];
    $reportemployerpay = $_POST[reportemployerpay];
    $reportfrelancerenoshow = $_POST[reportfrelancerenoshow];
    $empmeetingcancelled = $_POST[empmeetingcancelled];
    $freemeetingcancelled = $_POST[freemeetingcancelled];
    $suggestion = $_POST[suggestion];
    $complain = $_POST[complain];
    $feedback = $_POST[feedback];

    if ($errorinapp != "") {
        $subject = "Report an error in the app";
    } else if ($reportemployerpay != "") {
        $subject = "Report if an employer did not pay";
    } else if ($reportfrelancerenoshow != "") {
        $subject = "Report if a Freelancer did not show";
    } else if ($empmeetingcancelled != "") {
        $subject = "Report if an employer cancelled the meeting without process cancellation";
    } else if ($freemeetingcancelled != "") {
        $subject = "Report if a Freelancer cancelled the meeting without process cancellation";
    } else if ($suggestion != "") {
        $subject = "Suggestion";
    } else if ($complain != "") {
        $subject = "Complaint";
    } else if ($feedback != "") {
        $subject = "Feedback";
    }

    $query = mysql_query("INSERT INTO freelancer_mmv_feedbackreport (userid, errorinapp, reportemployerpay, reportfrelancerenoshow, empmeetingcancelled, freemeetingcancelled, suggestion, complain, feedback,date) VALUES ('$loginid','$errorinapp','$reportemployerpay','$reportfrelancerenoshow','$empmeetingcancelled','$freemeetingcancelled','$suggestion','$complain','$feedback',NOW())");

    $emailquery = mysql_query("SELECT * FROM freelancer_mmv_aboutus WHERE id='3'");
    $emailres = mysql_fetch_array($emailquery);
    $adminemail = $emailres[content];

    $userinfo = getUserinfo($loginid);
    $fullname = $userinfo[3] . ' ' . $userinfo[4];
    $tou = $adminemail;
    $subjectu = "Freelancer - " . $subject;
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
				<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Dear Admin,</td>
			</tr>
			<tr>		
				<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
					You have received an Feedback/Report from <h3>' . $fullname . '</h3> please login to Freelancer Admin and check for more information.
				</td>
			</tr>
			<tr>		
				<td style="padding:10px 20px 15px 20px">
					<a target="_blank" href="' . $urlpath . 'reportabuse.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Check in CMS</a>
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
    //mail($tou,$subjectu,$messageu,$headersu);		
    smtpmailer($tou, $fullname, $from, $from_name, $subjectu, $messageu);

    if ($query == 1) {
        echo "<script>window.location='feedback.php?status=fsuccess'</script>";
    } else {
        echo "<script>window.location='feedback.php?status=postfail'</script>";
    }
}
?>
<!--start main-->
<div class="main">		
    <section class="contenets-main">
        <!--start form-->
        <form name="" method="post">
            <div class="container">
                <div class="row form-main feedback-main">
                    <div class="col-12 text-align-center"><h2>Feedback / Report</h2></div>				
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-error.png" alt="img"/></div>
                                <h3>Report an error in the app</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="errorinapp" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">					
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon--id.png" alt="img"/></div>
                                <h3>Report if an employer did not pay</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="reportemployerpay" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-show.png" alt="img"/></div>
                                <h3>Report if a Freelancer did not show</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="reportfrelancerenoshow" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-meeting-cancel.png" alt="img"/></div>
                                <h3>Report if an employer cancelled the meeting without process cancellation</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="empmeetingcancelled" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-process.png" alt="img"/></div>
                                <h3>Report if a Freelancer cancelled the meeting without process cancellation</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="freemeetingcancelled" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-suggestion.png" alt="img"/></div>
                                <h3>Suggestion</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="suggestion" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-complain.png" alt="img"/></div>
                                <h3>Complain</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="complain" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="feedback-div">
                                <div class="feedback-icon"><img src="images/icon-feedback-writing.png" alt="img"/></div>
                                <h3>Write feedback</h3>
                            </div>
                            <div class="feedTextarea"><textarea class="form-control" name="feedback" placeholder="Write some words here..."></textarea></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group register-main">						
                            <button class="button light-pink width325" name="submit" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>	
        </form>		
        <!--end form-->		 
    </section>
</div>
<!--end main-->
<?php include "footer.php"; ?>