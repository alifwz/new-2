<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";

$workid = $_REQUEST[id];
$query = mysql_query("SELECT * FROM freelancer_mmv_work WHERE work_id='$workid'");
$about_res = mysql_fetch_array($query);
$memid = $about_res[member_id];

$query2 = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$memid'");
$about_res2 = mysql_fetch_array($query2);
//Ratings
$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$memid'");
$noofrev_res = mysql_fetch_array($noofrev_que);
$ratingsum = $noofrev_res[ratingsum];
$con = $noofrev_res[con];
$rateval = $ratingsum/$con;

if(isset($_POST[submit])){	

	$clientid 	= $memid;
	$budget 	= $_POST[budjet];
	$duration 	= $_POST[duration];
	
	$query 		= mysql_query("SELECT * FROM freelancer_mmv_favourites WHERE memberid='$clientid' AND userid=$loginid AND workid=$workid");
	$about_res	= mysql_fetch_array($query);
	$count = mysql_num_rows($query);

	if($count>=1 && $budget=="" && $duration==""){
			header("location:favourite.php");
	} else {
		$query2 = mysql_query("INSERT INTO freelancer_mmv_favourites (`id`,`memberid`,`userid`,`workid`,`budget`,`duration`,`date`) VALUES ('','$clientid','$loginid','$workid','$budget','$duration','$now')");
		add_member_notification($loginid, $clientid, 'favourites');
		$clieinfo 	= getUserinfo($clientid);
		$userinfo 	= getUserinfo($loginid);
		
		$tou = $clieinfo[1];
		$fullname = $clieinfo[3].' '.$clieinfo[4];
		$subjectu = "Freelancer Interested if";			
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
						<h3>'.$userinfo[3].' '.$userinfo[4].'</h3> is interested if the Budget and Duration is as per the below.
					</td>
				</tr>
				<tr>		
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
						Budget: '.getBudget($budget).' <br>
						Duration: '.getDuration($duration).' <br>
					</td>
				</tr>
				<tr>		
					<td style="padding:10px 20px 15px 20px">
						<a target="_blank" href="'.$urlpath.'favourite.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">View Interested</a>
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
		//smtpmailer($tou, $fullname, '', '', $subjectu, $messageu);
		//mail($tou,$subjectu,$messageu,$headersu);
		smtpmailer($tou, $fullname, $from, $from_name, $subjectu, $messageu);

		if($query2){
			//header("location:listing.php?status=success");
			echo "<script>window.location='listing.php?status=success'</script>";
		}		
			
	}	
}



?>
<!--start main-->
<div class="main">	
	<div class="notification-dtl detail-main">
	<form name="sub" method="post" action="">
		<div class="notification">
			<div class="job-thumb favourite-box">
				<div class="job-row">
					<div class="detail-div">
						<h2 class="paddingtop0"><?php echo $about_res[title] ?></h2> 					
					</div>
				</div>
			</div>
			<div class="job-thumb favourite-box for-rating">
				<div class="job-row">
                                    <h4 class="paddingtop0">Posted By :</h4> 
					<div class="favourite-holder setSize">
						<a href="viewclient.php?id=<?php echo $memid ?>"><?php if($about_res2[image] ==""){ ?>
								<img src="images/user.png" alt=""/>
							<?php } else { ?>
							<div class="photograph">	
								<img src="uploads/users/<?php echo $about_res2[image] ?>" alt=""/>
							</div>
							<?php } ?>
                                                </a>
					</div>
					<div class="favourite-dtl">
						<h3><a href="viewclient.php?id=<?php echo $memid ?>"><?php echo $about_res2[first_name] ?> <?php echo $about_res2[last_name] ?></a>
<!--						<div class="rating-right">
							<div class="rating-div">							
								 <?php /* if($rateval>='0.5' && $rateval<'1.5'){
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
									} */									
								 ?>	
								 <span class="number">( <?php // echo $con; ?> )</span>
							</div>
							</div>-->
						</h3>
					</div>
                                    <div class="posted-dt" style="clear: both;">Posted: <?php 
							$posted 	= $about_res[lastseen];
							$dbtimezone = $about_res2[timezone];									
							echo $lastseen	= converToTz($posted, $timezone, $dbtimezone);
					?></div>
                                    
				</div>
<!--				<div class="job-row favourite-detail">
				<?php
				/*$memberid = $memid;
				
				$login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
				$login_result = mysql_fetch_array($login_que);			
				$login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
				$login_result1 = mysql_fetch_array($login_que1);
				
				if($loginid==""){			
					$latitudeFrom = $_COOKIE['mylatitude'];
					$longitudeFrom = $_COOKIE['mylongitude'];
				} else {				
					$latitudeFrom = $login_result['loginlats'];
					$longitudeFrom = $login_result['loginlong'];
				}	
				
				$latitudeTo = $login_result1['loginlats'];
				$longitudeTo = $login_result1['loginlong'];	
				
				$distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
				$finaldist = $distt;*/
				/*End*/
				?>
					<p style="line-height: 24px;"><span class="grey" style="font-size:12px;">Nationality:</span> 
					<?php if($about_res2[nationality]!=""){ echo getNationality($about_res2[nationality]) ?> <br>  <?php } ?> <span class="grey" style="font-size:12px;">Area of Residence:</span> 
                        <strong class="black"><?php if($about_res2[area]!=""){ echo $about_res2[area] ?></strong> <br> <?php } ?> 
                        <span class="grey" style="font-size:10px;">Gender:</span>
                        <span style="font-size:12px;"><?php if($about_res2[gender]!=""){ echo $about_res2[gender] ?> <?php } ?> </span>
                    <span class="grey" style="font-size:10px;">MBTI Personality:</span>
                        <span style="font-size:12px;"><?php if($about_res2[mbti]!=""){ echo getMBTI($about_res2[mbti]) ?> <?php } ?> </span>
                     <br>   <span class="grey" style="font-size:12px;">Education:</span>
					<?php if($about_res2[education]!=""){ echo getEducation($about_res2[education]) ?>  <?php } ?>  
                      <br>  <span class="grey" style="font-size:12px;">degree:</span>
					<?php if($about_res2[degree]!=""){ echo getDegree($about_res2[degree]) ?><?php } ?> 
                      <br>  <span class="grey" style="font-size:12px;">Job Title:</span>
					<?php if($about_res2[jobtitle]!=""){ echo $about_res2[jobtitle]; } ?></p>					 
					<p><span class="grey" style="font-size:12px;">Freelance Service:</span> <strong class="black"><?php echo getExperience($about_res2[expsector]) ?></strong></p>
					<span class="km-away" style="font-size:16px;"><?php echo number_format($finaldist,1).' km' ?><br>Away</span>
				</div>-->
			</div>
                    <div class="job-thumb favourite-box">
                        <div class="job-row">
                            <div class="detail-div">
                                <?php echo $about_res[description] ?>
                            </div>
                        </div>
                    </div>
                    <div class="job-thumb favourite-box">
				<div class="job-row">
					<div class="detail-div">
						<h4 class="paddingtop0">Skills required</h4> 					
						<p><?php echo $about_res[skills] ?></p>						
					</div>
				</div>
			</div>
			<div class="job-thumb favourite-box">
				<div class="job-row">
					<div class="row budget-duration">
						<div class="col-6"><div class="budget-div">Budget: <span class="typeAmt"><span class="red"></span><span class="red amountText"><?php echo getBudget($about_res[budget]) ?></span><span class="budgetVal">
						<select name="budjet" id="budjet" required class="job-input">							
							<option value="">-Select Budjet-</option>								
								<?php
									$country_query = mysql_query("SELECT * FROM freelancer_mmv_expsector");
									while($country_res = mysql_fetch_array($country_query)){
									$budid = $country_res[id];										
								?>
									<option <?php if($budid==$about_res[budget]) { echo "selected"; } ?> value="<?php echo $country_res[id] ?>"><?php echo $country_res[title]; ?></option>								
								<?php } ?>				
						</select>
						</span></span>
							</div></div>
						<div class="col-6"><div class="duration-div" style="align:center">Duration: <span class="daysAmt"><span class="red dayText"><?php echo getDuration($about_res[duration]) ?></span><span class="dayVal">
						<select name="duration" id="duration" required class="job-input">							
							<option value="">-Select Duration-</option>								
								<?php
									$dur_query = mysql_query("SELECT * FROM freelancer_mmv_duration");
									while($dur_res = mysql_fetch_array($dur_query)){
									$durid = $dur_res[id];
								?>
									<option <?php if($durid==$about_res[duration]) { echo "selected"; } ?> value="<?php echo $dur_res[id] ?>"><?php echo $dur_res[durange]; ?></option>								
								<?php } ?>				
						</select>
						
						</span> <span class="red"></span></span>		
						</div></div>
					</div>
					
				</div>
			</div>
			<?php if($loginid!=""){ ?>			
				<?php if($memid!=$loginid){ ?>
				<div class="threebtns">
					<div class="row intrested-edit-cancel">
						<div class="col-4 intrested-div">
							<a href="submitfavourites.php?id=<?php echo $memid; ?>&workid=<?php echo $workid ?>" class="button">Interested</a>
						</div>
						<div class="col-4 edit-div">
							<a href="javascript:void(0);" class="button light-yellow editHourDayBtn">Interested if</a>
						</div>	
						<div class="col-4 cancel-div">
							<a href="listing.php" class="button light-pink">Cancel</a>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<div class="threebtns">
					<div class="row intrested-edit-cancel">						
					</div>
				</div>
				<?php } ?>
				
			<?php } else { ?>
			
				<div class="threebtns">
					<div class="row intrested-edit-cancel">
						<div class="col-4 intrested-div">
							<a href="#" data-fancybox data-type="inline" data-src="#loginPopup" class="button">Interested</a>
						</div>
						<div class="col-4 edit-div">
							<a href="#" data-fancybox data-type="inline" data-src="#loginPopup" class="button light-yellow">Interested if</a>
						</div>	
						<div class="col-4 cancel-div">
							<a href="index.php" class="button light-pink">Cancel</a>
						</div>
					</div>
				</div>
			
			<?php } ?>
			
			<div class="twobtns">
				<div class="row intrested-edit-cancel">
					<div class="col-6">
						<!--<a href="javascript:void(0);" class="button sendTimingBtn">Send</a>-->
						<button type="submit" style="width:183px;height:23" name="submit" class="button">Send</button> 
					</div>
					<div class="col-6">
						<a href="javascript:void(0);" class="button light-pink cancelEditBtn">Cancel</a>
					</div>
				</div>
			</div>			
		</div>
		</form>
	</div>
</div>
<!--end main-->
<?php include "footer.php"; ?>