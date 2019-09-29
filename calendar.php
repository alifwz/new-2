<?php
include "functions.php";
include "what3wordsapp.php";
include "auth.php";
include "headerinvitation.php";
?>
<!--start main-->
<div class="main">	
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
						if($banner_result!=0){
						?>
						<span class="bell-notify"></span>
						<?php } ?>
					</a>
				</td>
				<td>
					<a href="calendar.php" class="active">
						<span class="tabs-title"><img src="images/icon-calendar.png" alt="Calendar" /></span>
						<?php
							$cal_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' AND calreadstatus=0");
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
							/*$fav_que = mysql_query("SELECT * from freelancer_mmv_favourites where userid='$loginid' AND favstatus=0");
							$fav_result = mysql_num_rows($fav_que);*/
							$fav_que1 = mysql_query("SELECT * from freelancer_mmv_favourites where memberid='$loginid' AND favstatus=0");
							$fav_result1 = mysql_num_rows($fav_que1);
								
							$subcat_query = mysql_query("SELECT * FROM freelancer_mmv_member_like WHERE memberid='$loginid' AND postedby='$loginid' AND readstatus=0");
							$cal_result1 = mysql_num_rows($subcat_query); 
							if(($fav_result1!=0)|| ($cal_result1!=0)){
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
	<div class="notification-dtl">
		<div class="notification">
			<div id="header" class="update-location-div">
				<a href="updatecurrentlocation.php" class="update-location">
				<img src="images/update-location.png" alt="update location"/> 
				Update Current Location</a>
			</div>
			<?php 
			$inv_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where invited_userid='$loginid' and acceptedstatus='1' ORDER BY cast(invitation_id as int) DESC");
			$inv_result = mysql_fetch_array($inv_que);
			$count = mysql_num_rows($inv_que);
						
			if($count>=1){
				
			$appdate = strtotime($inv_result[meetingdate]); 
			$startdate = date('Y-m-d H:i',$appdate);		
			$strtTime = strtotime("-180 minutes", $appdate);
			$realdate = $appdate+$ttt;	
			$appointdate = date('Y-m-d H:i',$strtTime);	
			$currentdate = date('Y-m-d H:i');
			$dbtimezone = $inv_result[timezone];
			
			$converted_appdate = converToTz($appointdate, $timezone, $dbtimezone);
			$converted_startdate = converToTz($startdate, $timezone, $dbtimezone);

			mysql_query("UPDATE freelancer_mmv_member_invitation SET calreadstatus='1' WHERE user_id='$loginid' and acceptedstatus='1'");
add_member_notification($inv_result['invited_userid'], $inv_result['user_id'], 'meet_accepted',1);
			$clientid = $inv_result[user_id];
			$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$clientid'");
			$about_res = mysql_fetch_array($query);
			$invitedinfo = getUserinfo($clientid);
						
			if($currentdate>=$converted_appdate && $currentdate<= $converted_startdate){
			if($inv_result[invited_userid]!='0'){				
			?>
			<div class="job-thumb green">
				<div class="job-row">
					<div class="job-thumb-holder setSize">
						<?php if($invitedinfo[11] =="" || $invitedinfo[11] =="NULL"){ ?>
								<img src="images/user.png" alt="user"/>
							<?php } else { ?>
								<div class="photograph"><img src="uploads/users/<?php echo $about_res[image] ?>" alt="user"/></div>
							<?php } ?>
					</div>
				<?php
				$what3data = Getwhat3words($inv_result[what3word]);
				$split = explode(',',$what3data);	
				
				 $apilats = $split[0];
				 $apilongs = $split[1];
				
				$memberid = $clientid;
				/*Get Distance*/
				$login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
				$login_result = mysql_fetch_array($login_que);			
				$login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
				$login_result1 = mysql_fetch_array($login_que1);
				
				$latitudeFrom = $login_result['loginlats'];
				$longitudeFrom = $login_result['loginlong'];						
				
				if($inv_result[what3word]!=''){
					$latitudeTo = $apilats;
					$longitudeTo = $apilongs;
				} else {
					$latitudeTo = $login_result1['loginlats'];
					$longitudeTo = $login_result1['loginlong'];	
				}	
				
				$distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
				$finaldist = $distt;
				/*End*/				
				?>
					<div class="job-dtl">
						<div class="meeting-row">
							Accepted Meeting with 
						</div>
						<h3><a href="viewclient.php?id=<?php echo $clientid ?>"><?php echo $invitedinfo[3].' '.$invitedinfo[4] ?></a></h3>
						<div class="date-time">
							<span>Date:</span> <?php echo date('Y-m-d h:i A',strtotime($converted_startdate)); ?>
							<!--<div class="time"><span>Time:</span> 2.30PM</div>-->
							<!-- <div class="meeting-row"><span class="km"><?php echo number_format($finaldist,1).' km' ?></span></div> -->
						</div>											
					</div>
				</div>
				<div class="row user-profile profile-page invitation-profile-data">
				<div class="col-12">
					<div class="inv-pro-row">
						<label>Area of Residence:</label>
						<div class="prfl-dtl"><?php echo getCountry($about_res[country]) ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Nationality:</label>
						<div class="prfl-dtl"><?php echo getNationality($about_res[nationality]) ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Education:</label>
						<div class="prfl-dtl"><?php echo getEducation($about_res[education]); ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Degree:</label>
						<div class="prfl-dtl"><?php echo getDegree($about_res[degree]); ?></div>
					</div>		 
					<div class="inv-pro-row">
						<label>Experience Sector:</label>
						<div class="prfl-dtl"><?php echo getExperienceSector($about_res[expsectornew]); ?></div>
					</div>						
					<div class="inv-pro-row">
						<label>Job Title:</label>
						<div class="prfl-dtl"><?php echo $about_res[jobtitle]; ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Hobby:</label>
						<div class="prfl-dtl"><?php echo getHobby($about_res[hobby]); ?></div>
					</div>						
					<div class="inv-pro-row">
						<label>Sport: </label>
						<div class="prfl-dtl"><?php echo getSport($about_res[sporttoparticipate]); ?></div>
					</div>	 
					<div class="inv-pro-row">
						<label>MBTI Personality:</label>
						<div class="prfl-dtl"><?php echo getMBTI($about_res[mbti]); ?> </div>
					</div>						
					<div class="inv-pro-row">
						<label>Faith: </label>
						<div class="prfl-dtl"><?php echo $about_res[faith]; ?> </div>
					</div>
					<div class="inv-pro-row">
						<label>Freelance Timing: </label>
						<div class="prfl-dtl"><?php echo $about_res[freelancetiming]; ?></div>
					</div>						
					<!--<div class="inv-pro-row">
						<label>Freelance Timing: </label>
						<div class="prfl-dtl"><?php echo getFreelanceTiming($about_res[freelancetiming]); ?></div>
					</div>	 		 		 		 -->
				</div>
			</div>
				<div class="job-row location-main">
					<span class="location-pin"><img src="images/location-pin.png" alt="location"/></span>						
					<a class="send-mesage" href="messages-detail2.php?id=<?php echo $clientid; ?>&inv=<?php echo $inv_result[invitation_id]; ?>">Send<br />Message</a>
					<div class="location-address">
						<p><?php echo $about_res[location] ?></p>
					</div>
					<!--<div class="wordaapp">Exact Location using What3words</div>
					<div class="butchers">
					<?php if($about_res[what3word]!=''){ ?>
						<?php echo $about_res[what3word]; ?>
					<?php } else { ?>
						Eg: Butchers.flattening. paded			
					<?php } ?>
					</div>-->
				</div>
			</div>
			<?php } } } else { 			
				echo '<div class="contenets">
					<div class="topbar">
						<div class="container">
							<p align="center" style="font-size:18px"></p>
						</div>
					</div>
				 </div>'; 			
			}			
			?>
			<?php 
			
			$inv_que = mysql_query("SELECT * from freelancer_mmv_member_invitation where user_id='$loginid' and acceptedstatus='1' order by invitation_id DESC");
			$inv_result = mysql_fetch_array($inv_que);
			$count = mysql_num_rows($inv_que);
								
			if($count>=1){
				
			$appdate = strtotime($inv_result[meetingdate]); 
			$startdate = date('Y-m-d H:i',$appdate);		
			$strtTime = strtotime("-180 minutes", $appdate);
			$realdate = $appdate+$ttt;	
			$appointdate = date('Y-m-d H:i',$strtTime);	
			$currentdate = date('Y-m-d H:i');
			$dbtimezone = $inv_result[timezone];
			$inv_result[location];

			mysql_query("UPDATE freelancer_mmv_member_invitation SET calreadstatus='1' WHERE user_id='$loginid' and acceptedstatus='1'");
add_member_notification($inv_result['invited_userid'], $inv_result['user_id'], 'meet_accepted',1);
			$clientid = $inv_result[invited_userid];
			$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$clientid'");
			$about_res = mysql_fetch_array($query);
			$invitedinfo = getUserinfo($clientid);
			
			$converted_appdate = converToTz($appointdate, $timezone, $dbtimezone);
			$converted_startdate = converToTz($startdate, $timezone, $dbtimezone);
				
			if($currentdate>=$converted_appdate && $currentdate<= $converted_startdate){
			if($inv_result[invited_userid]!='0'){
			?>
			<div class="job-thumb green">
				<div class="job-row">
					<div class="job-thumb-holder setSize">
						<?php if($invitedinfo[11] =="" || $invitedinfo[11] =="NULL"){ ?>
								<img src="images/user.png" alt="user"/>
							<?php } else { ?>
								<div class="photograph"><img src="uploads/users/<?php echo $about_res[image] ?>" alt="user"/></div>
							<?php } ?>
					</div>
					<?php
				$memberid = $clientid;
				/*Get Distance*/
				$login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
				$login_result = mysql_fetch_array($login_que);			
				$login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
				$login_result1 = mysql_fetch_array($login_que1);
				
				$latitudeFrom = $login_result['loginlats'];
				$longitudeFrom = $login_result['loginlong'];						
				
				$latitudeTo = $login_result1['loginlats'];
				$longitudeTo = $login_result1['loginlong'];			
				$distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
				$finaldist = $distt;
				/*End*/				
				?>
					<div class="job-dtl">
						<div class="meeting-row">
							Accepted Meeting with 
						</div>
						<h3><a href="viewclient.php?id=<?php echo $clientid ?>"><?php echo $invitedinfo[3].' '.$invitedinfo[4] ?></a></h3>
						<div class="date-time">
							<span>Date:</span> <?php echo date('Y-m-d h:i A',strtotime($converted_startdate)); ?>							
							<!--<div class="time"><span>Time:</span> 2.30PM</div>-->
							<!-- <div class="meeting-row"><span class="km"><?php echo number_format($finaldist,1).' km' ?> -->
						</div>	
												
					</div>
				</div>
				<div class="row user-profile profile-page invitation-profile-data">
				<div class="col-12">
					<div class="inv-pro-row">
						<label>Area of Residence:</label>
						<div class="prfl-dtl"><?php echo getCountry($about_res[country]) ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Nationality:</label>
						<div class="prfl-dtl"><?php echo getNationality($about_res[nationality]) ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Education:</label>
						<div class="prfl-dtl"><?php echo getEducation($about_res[education]); ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Degree:</label>
						<div class="prfl-dtl"><?php echo getDegree($about_res[degree]); ?></div>
					</div>		 
					<div class="inv-pro-row">
						<label>Experience Sector:</label>
						<div class="prfl-dtl"><?php echo getExperienceSector($about_res[expsector]); ?></div>
					</div>						
					<div class="inv-pro-row">
						<label>Job Title:</label>
						<div class="prfl-dtl"><?php echo $about_res[jobtitle]; ?></div>
					</div>
					<div class="inv-pro-row">
						<label>Hobby:</label>
						<div class="prfl-dtl"><?php echo getHobby($about_res[hobby]); ?></div>
					</div>						
					<div class="inv-pro-row">
						<label>Sport: </label>
						<div class="prfl-dtl"><?php echo getSport($about_res[sporttoparticipate]); ?></div>
					</div>	 
					<div class="inv-pro-row">
						<label>MBTI Personality:</label>
						<div class="prfl-dtl"><?php echo getMBTI($about_res[mbti]); ?> </div>
					</div>						
					<div class="inv-pro-row">
						<label>Faith: </label>
						<div class="prfl-dtl"><?php echo $about_res[faith]; ?> </div>
					</div>
					<div class="inv-pro-row">
						<label>Freelance Timing: </label>
						<div class="prfl-dtl"><?php echo $about_res[freelancetiming]; ?></div>
					</div>						
					<!--<div class="inv-pro-row">
						<label>Freelance Timing: </label>
						<div class="prfl-dtl"><?php echo getFreelanceTiming($about_res[freelancetiming]); ?></div>
					</div>	 		 		 		 -->
				</div>
			</div>
				<div class="job-row location-main">
					<span class="location-pin"><img src="images/location-pin.png" alt="location"/></span>						
					<a class="send-mesage" href="messages-detail2.php?id=<?php echo $clientid; ?>&inv=<?php echo $inv_result[invitation_id]; ?>">Send<br />Message</a>
					<div class="location-address">
						<p><?php echo $inv_result[location] ?></p>
					</div>
					<!--<div class="wordaapp">Exact Location using What3words</div>
					<div class="butchers">
					<?php if($inv_result[what3word]!=''){ ?>
						<p style="font-size:18px"><?php echo $inv_result[what3word]; ?></p>
					<?php } else { ?>
						<p style="font-size:18px">Eg: Butchers.flattening. paded</p>				
					<?php } ?>
					</div>-->
				</div>
			</div>
			<?php } } } else { 
			
				echo '<div class="contenets">
					<div class="topbar">
						<div class="container">
							<p align="center" style="font-size:18px"></p>
						</div>
					</div>
				 </div>'; 			
			}
			?>
		</div>
	</div>
</div>

<!--end main-->
<?php include "footer.php"; ?>
<script type="text/javascript" src="js/jquery.sticky.js"></script>
<script type="text/javascript">
    $(window).load(function(){
		$("#header").sticky({ topSpacing: $('header').innerHeight() + $('#sticktabs').innerHeight()});
		$("#sticktabs").sticky({ topSpacing: $('header').height()});
    });
</script>