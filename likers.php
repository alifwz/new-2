<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";
include "auth.php";

if($_REQUEST[ccid]!=""){	
	$ccid=$_REQUEST[ccid];
} 
?>

<!--<form name="meetinginvite" id="meetinginvite" action="invite-request.php" method="post">-->
<form name="meetinginvite" id="meetinginvite" action="redirect.php" method="post">
<!--start main-->
<div class="main">		
	<section class="contenets-main">
		<!--start post job -->
		
		<div class="contenets post-a-job-main" style="min-height:700px;">
			<label class="cont rol"><h5>Liked By:	</h5>
			</label>
			<?php
			$about_que = mysql_query("SELECT * from freelancer_mmv_member_like where workid=$ccid ORDER BY date DESC");			
			while($about_res = mysql_fetch_array($about_que))
			{
				$userid = $about_res[user_id];				
				$user_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id=$userid");			
				$user_res = mysql_fetch_array($user_que);
				
				//Ratings
				$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$userid'");
				$noofrev_res = mysql_fetch_array($noofrev_que);
				$ratingsum = $noofrev_res[ratingsum];
				$con = $noofrev_res[con];
				$rateval = $ratingsum/$con;
			?>
			<div class="control-group">
				<div class="job-thumb favourite-box for-rating">
					<?php if($loginid != $user_res[member_id]){ ?>
						<div class="job-row" onClick="location.href='viewclient.php?id=<?php echo $userid ?>'">
					<?php } else{ ?>
						<div class="job-row" onClick="location.href='profile.php'">
					<?php } ?>
						<div class="favourite-holder">
						<?php if($user_res[image] ==""){ ?>
								<img src="images/user.png" alt=""/>
							<?php } else { ?>
								<img src="uploads/users/<?php echo $user_res[image] ?>" alt=""/>
							<?php } ?>
						</div>
						<div class="favourite-dtl">
						<?php if($loginid != $user_res[member_id]){ ?>
							<a href="viewclient.php?id=<?php echo $userid ?>"><h3 style="font-size:17px;"><?php echo $user_res[first_name].' '. $user_res[created]; ?>
						<?php } else{ ?>
							<a href="profile.php"><h3><?php echo $user_res[first_name].' '. $user_res[created]; ?>
						<?php } ?>
						<div class="rating-right">
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
							</div>
							</h3></a>
						</div>
					</div>
					<div class="job-row thumb-row">
						<div class="favourite-holder">							
						</div>
						<div class="favourite-dtl">
							<!--<p><span class="grey">Location:</span> <?php echo getNationality($user_res[nationality]) ?>, <?php echo getArea($user_res[area]) ?>  <?php echo $user_res[gender] ?>  <?php echo getMBTI($user_res[mbti]) ?>  <?php echo getEducation($user_res[education]) ?>    <?php echo getDegree($user_res[degree]) ?> <?php echo $user_res[faith] ?> <?php echo $user_res[job] ?><br />
							<span class="grey">Freelancing:</span> <strong class="black"><?php echo getExperience($user_res[expsector]); ?></strong></p>-->
							<p><span class="grey" style="font-size:12px;">Nationality:</span> 
					<?php if($user_res[nationality]!=""){ echo getNationality($user_res[nationality]) ?>  <?php } ?><br>
                                <span class="grey" style="font-size:12px;">Area of Residence: </span>
				<strong class="black">	<?php if($user_res[area]!=""){ echo  $user_res[area] ?> <?php } ?> </strong>
                                <br>    
                                <span class="grey" style="font-size:12px;">Gender: </span>
				<span style="font-size:12px">	<?php if($user_res[gender]!=""){ echo ''. $user_res[gender] ?> <?php } ?></span> 
                                <span class="grey" style="font-size:12px;">MBTI Personality: </span>
					<span style="font-size:12px"><?php if($user_res[mbti]!=""){ echo ''. getMBTI($user_res[mbti]) ?> <?php } ?> </span>  <br>
					<?php if($user_res[education]!=""){ echo '<span class="grey" style="font-size:12px;">Education: </span>'. getEducation($user_res[education]) ?> <?php } ?>   <br>
					<?php if($user_res[degree]!=""){ echo '<span class="grey" style="font-size:12px;">Degree: </span>'. getDegree($user_res[degree]) ?><?php } ?> <BR>
					<?php if($user_res[jobtitle]!=""){ echo '<span class="grey" style="font-size:12px;">Job Title: </span>'. $user_res[jobtitle]; } ?></p>
							<div class="km-last-seen">
								<div class="km-left"><?php //echo round($distance).' km' ?></div>
								<!--<div class="last-seen-right">Last seen 25/12/2017 | 03:13 PM</div>-->
							</div>
						</div>
					</div>
				</div>				
			</div>
			<?php } ?>
			<p><br><br></p>
		</div>
		
		<!--end post job -->
	</section>
</div>
<!--end main-->


</form>
<!--end meet invite-->
<?php include "footer.php"; ?>
