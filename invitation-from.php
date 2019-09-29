<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";
include "what3wordsapp.php";
include "what3wordsmapp.php";
include "auth.php";
include "timezone.php";

$invid = $_REQUEST[id];
$inv_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where invitation_id='$invid'");
$inv_result = mysql_fetch_array($inv_que);
$clientid = $inv_result[user_id];
$invstatus = $inv_result[acceptedstatus];

mysql_query("UPDATE freelancer_mmv_member_invitation SET readstatus='1' WHERE invitation_id='$invid'");

$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$clientid'");
$about_res = mysql_fetch_array($query);

//Ratings
$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$clientid'");
$noofrev_res = mysql_fetch_array($noofrev_que);
$ratingsum = $noofrev_res[ratingsum];
$con = $noofrev_res[con];
$rateval = $ratingsum/$con;

if(isset($_POST[submit])){
				
	$qqq = mysql_query("UPDATE freelancer_mmv_member_invitation SET acceptedstatus='1' WHERE invitation_id='$invid'");
	
	if($qqq){		
		
		$clieinfo 	= getUserinfo($clientid);
		$userinfo 	= getUserinfo($loginid);
		add_member_notification($loginid, $clientid, 'invite_accepted');
		$tou = $clieinfo[1];
		$fullname = $clieinfo[3].' '.$clieinfo[4];
		$subjectu = "Freelancer Invitation";			
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
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Hello '.$clieinfo[3].' '.$clieinfo[4].',</td>
				</tr>
				<tr>		
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
						 <h3>'.$userinfo[3].' '.$userinfo[4].'</h3> has Accepted your invitation. Please login to your freelancer account and check more information.
					</td>
				</tr>
				<tr>		
					<td style="padding:10px 20px 15px 20px">
						<a target="_blank" href="'.$urlpath.'index.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Invitation link</a>
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

		echo "<script>window.location='invitation.php?status=success'</script>";	
	}
}

if(isset($_POST[reject])){
				
	$qqq = mysql_query("UPDATE freelancer_mmv_member_invitation SET acceptedstatus='2' WHERE invitation_id='$invid'");
	
	if($qqq){
		
		$clieinfo 	= getUserinfo($clientid);
		$userinfo 	= getUserinfo($loginid);
		add_member_notification( $loginid,$clientid, 'invite_rejected');
		$tou = $clieinfo[1];
		$fullname = $clieinfo[3].' '.$clieinfo[4];
		$subjectu = "Freelancer Invitation";			
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
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Hello '.$clieinfo[3].' '.$clieinfo[4].',</td>
				</tr>
				<tr>		
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
						 <h3>'.$userinfo[3].' '.$userinfo[4].'</h3> has Rejected your invitation. Please login to your freelancer account and check more information.
					</td>
				</tr>
				<tr>		
					<td style="padding:10px 20px 15px 20px">
						<a target="_blank" href="'.$urlpath.'invitation.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Invitation link</a>
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
		echo "<script>window.location='invitation.php'</script>";
	}
}

if(isset($_POST[submitrating])){
	$ratingnum = $_POST[ratingnum];
	$reviewdesc = $_POST[review];
	
	$query3 = mysql_query("INSERT INTO freelancer_mmv_reviewratings (id, reviewto, reviewby, ratings, reviewdesc, date) VALUES ('', '$clientid','$loginid','$ratingnum','$reviewdesc',NOW())");
	
	if($query3==1){
		echo "<script>window.location='invitation-from.php?status=posted'</script>";
	}
	echo "<script>window.location='invitation-from.php?id=$invid'</script>";	
}


?>
<!--start main-->
<form name="hireedit" method="post" action="">
<div class="main">	
<div id="sticktabs" class="notification-main">
		<!--<div class="notify-title">Notification</div>-->
		<table class="notification-links tabs">
			<tr>
				<td>
					<a href="invitation.php" class="active">
						<span class="tabs-title">Invitation</span>
						<?php
							$banner_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' AND readstatus=0");
							$banner_result = mysql_num_rows($banner_que);
							if($banner_result!=0){
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
							if($cal_result!=0){
						?>
						<span class="bell-notify"></span>
						<?php } ?>
					</a>
				</td>
				<td>
					<a href="favourite.php">
						<span class="tabs-title"><img src="images/icon-favourite.png" alt="Favourite" /></span>
						<?php
							$fav_que = mysql_query("SELECT * from freelancer_mmv_favourites where userid='$loginid' AND favstatus=0");
							$fav_result = mysql_num_rows($fav_que);
							$fav_que1 = mysql_query("SELECT * from freelancer_mmv_favourites where memberid='$loginid' AND favstatus=0");
							$fav_result1 = mysql_num_rows($fav_que1);
							if(($fav_result!=0) || ($fav_result1!=0)){
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
							if($mes_result!=0){
						?>
						<span class="bell-notify"></span>
						<?php } ?>
					</a>
				</td>
			</tr>
		</table>
	</div>		
	<section class="contenets-main">
		<!--start post job -->
		<div class="contenets invite-main">
			<h2>Invitation From</h2>
			<div class="job-thumb favourite-box for-rating invite-box">
				<div class="job-row">
					<div class="favourite-holder">
					<?php if($about_res[image] ==""){ ?>
								<img src="images/user.png" alt="user"/>
							<?php } else { ?>
								<img src="uploads/users/<?php echo $about_res[image] ?>" alt="user"/>
							<?php } ?>
					</div>
					<div class="favourite-dtl">
						<h3><a href="viewclient.php?id=<?php echo $clientid ?>"><?php echo $about_res[first_name].' '.$about_res[last_name] ?></a>
							<p><span class="grey">Country:</span> <?php echo getCountry($about_res[country]) ?></p>
						</h3>
					</div>
					<div class="favourite-dtl" style="margin-bottom:60px;">	
						<div class="rating-review">
							<div class="rating-div">							
								 <?php if($rateval>='0.5' && $rateval<'1.5'){
									echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';									
									} else if($rateval>='1.5' && $rateval<'2.5'){
										echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';	
									} else if($rateval>='2.5' && $rateval<'3.5'){
										echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';	
									} else if($rateval>='3.5' && $rateval<'4.5'){
										echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';	
									} else if($rateval>='4.5' && $rateval<='5'){
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
			</div>
			<div class="job-thumb favourite-box for-rating invite-box">
				<div class="job-row location-text">
					<p class="grey">Location</p>
					<p class="font-size24"><?php echo $inv_result[location] ?></p>
				</div>
			</div>
			<div class="exact-location"><p>(Exact Location using What3words)</p></div>
			<div class="job-thumb favourite-box for-rating invite-box">
				<div class="job-row butchers-flattening-div">
				<?php if($inv_result[what3word]!=''){ ?>
					<a target="blank" href="<?php Getwhat3wordsmap($inv_result[what3word]) ?>"><p><?php echo $inv_result[what3word]; ?></p></a>
				<?php } else { ?>
					<p>Eg: Butchers.flattening. paded</p>				
				<?php } ?>
				</div>
			</div>
			<?php 					
				$meetdate = $inv_result[meetingdate]; 
				$dbtimezone = $inv_result[timezone];
				$converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);
			?>
			<div class="job-thumb favourite-box for-rating invite-box">
				<div class="job-row date-time-div">
					<?php echo date('d F Y h:i A', strtotime($converted_datetime)); ?>
				</div>
			</div>
			<div class="meeting-topics">
				<p class="grey">Event Description</p>
				<div class="box-shadow share-experience">
					<textarea placeholder="Enter your word here..." readonly name="meetingtopics" class="form-control"><?php echo $inv_result[meeting_topics]; ?></textarea>
				</div>
			</div>	
			<?php if($invstatus!=1&&$invstatus!=2){ ?>	
			<div class="row intrested-edit-cancel">
				<div class="col-6">
					<!--<a href="javascript:void(0);" class="button">Accept</a>-->
					<button type="submit" name="submit"  class="button acceptBtn">Accept</button>
				</div>
				<div class="col-6">
				<button type="reject" name="reject" class="button light-pink">Reject</button>
					<!--<a href="javascript:void(0);" class="button light-pink">Reject</a>-->
				</div>
			</div>
			<?php } ?>			 		
		</div>
		<!--end post job -->
	</section>
</div>
<!--end main-->
</form>

<div class="popbox">
	<div class="review-popup" id="reviewPopup">
	<?php		
	$comm_query = mysql_query("SELECT * FROM freelancer_mmv_member_invitation WHERE user_id='$loginid' AND invited_userid='$clientid' AND acceptedstatus=1");
	$comm_res = mysql_num_rows($comm_query);
	$comm_result = mysql_fetch_array($comm_query);
	$appdate = strtotime($comm_result[meetingdate]);
	$hourmin = $comm_result[hours_minutes];
	
	$time=$hourmin;
    $timesplit=explode(':',$time);
    $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0).' minutes';
    $strtTime = strtotime("+".$min, $appdate);
	$totaltime = $strtTime;		
	$startdate = date('Y-m-d H:i',($totaltime));
	$currentdate = date('Y-m-d H:i');		
	
	if($comm_res>='1' && $currentdate>=$startdate){		
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
			while($revres = mysql_fetch_array($revquery)){
				$givenby = $revres[reviewby];
				$myrat = $revres[ratings];
				$resquery = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$givenby'");
				$rev_res = mysql_fetch_array($resquery);
			?>
			<div class="job-thumb favourite-box for-rating invite-box">
				<div class="job-row">
					<div class="favourite-holder">
					<?php if($rev_res[image] ==""){ ?>
						<img src="images/user.png" alt="user"/>
					<?php } else { ?>
						<img src="uploads/users/<?php echo $rev_res[image] ?>" alt="user"/>
					<?php } ?>
					</div>
					<div class="favourite-dtl">
						<h3>
						<?php if($givenby != $loginid){ ?>
							<a href="viewclient.php?id=<?php echo $givenby ?>"><?php echo $rev_res[first_name].' '.$rev_res[last_name] ?></a>
						<?php } else { ?>
							<a href="#"><?php echo $rev_res[first_name].' '.$rev_res[last_name] ?></a>
						<?php } ?>						
							<div class="review-rating">	
								<?php if($rateval>='0.5' && $rateval<'1.5'){
									echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';									
									} else if($rateval>='1.5' && $rateval<'2.5'){
										echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';	
									} else if($rateval>='2.5' && $rateval<'3.5'){
										echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';	
									}else if($rateval>='3.5' && $rateval<'4.5'){
										echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';	
									}else if($rateval>='4.5' && $rateval<='5'){
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