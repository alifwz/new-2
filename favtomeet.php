<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";
include "auth.php";

$id = $_REQUEST[id];

//Get Budget
$query3 = mysql_query("SELECT * FROM freelancer_mmv_favourites WHERE id='$id'");
$about_res3 = mysql_fetch_array($query3);
$newbudget = $about_res3[budget];
$newduration = $about_res3[duration];
$workid = $about_res3[workid];

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

if(isset($_POST[deletefavourites])){
	//$querry = mysql_query("DELETE FROM freelancer_mmv_favourites WHERE id='$id'");
	//if($querry){
		echo "<script>window.location='favourite.php'</script>";
	//}	
}

?>
<!--start main-->
<form name="deletefav" method="post">
<div class="main">	
	<div class="notification-dtl detail-main">
		<div class="notification">
			<div class="job-thumb favourite-box">
				<div class="job-row">
					<div class="detail-div">
						<h2 class="paddingtop0"><?php echo $about_res[title] ?></h2> 					
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
						<div class="col-6"><div class="budget-div">Budget: <span class="typeAmt"><span class="red"></span><span class="red amountText"><?php echo getBudget($newbudget); ?></span><span class="budgetVal">					
						</span></span>
							</div></div>
						<div class="col-6"><div class="duration-div">Duration: <span class="red"><?php echo getDuration($newduration) ?></span></div></div>
					</div>
					<div class="posted-dt">Posted: <?php echo $about_res[created] ?></div>
				</div>
			</div>
			<div class="job-thumb favourite-box for-rating">
				<div class="job-row">
					<div class="favourite-holder">
						<?php if($about_res[image] ==""){ ?>
							<img src="images/user.png" alt=""/>
						<?php } else { ?>
							<img src="uploads/users/<?php echo $about_res[image] ?>" alt=""/>
						<?php } ?>
					</div>
					<div class="favourite-dtl">
						<h3><a href="viewclient.php?id=<?php echo $memid ?>"><?php echo $about_res2[first_name] ?> <?php echo $about_res2[last_name] ?></a>	
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
						</h3>
					</div>
				</div>
				<div class="job-row favourite-detail">
				<?php
				$memberid = $memid;
				/*Get Distance*/
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
				$finaldist = $distt;
				/*End*/
				?>
					<p><span class="grey">Nationality:</span> <?php echo getNationality($about_res2[nationality]) ?> <span class="grey pipe">I</span> <?php echo getArea($about_res2[area]) ?>  <?php echo $about_res2[gender] ?>  <?php echo getMBTI($about_res2[mbti]) ?>  <?php echo getEducation($about_res2[education]) ?>    <?php echo getDegree($about_res2[degree]) ?> <?php echo getJob($about_res2[jobtitle]) ?></p>					 
					<p><span class="grey">Freelance:</span> <strong class="black"><?php echo getExperience($about_res2[expsector]) ?></strong></p>
					<!-- <span class="km-away"><?php echo number_format($finaldist,1).' km' ?><br>Away</span> -->
				</div>
			</div>
			<div class="row intrested-edit-cancel">
				<div class="col-6">
					<a href="meeting-request.php?id=<?php echo $memid; ?>" class="button">Meet</a>
				</div>
				<div class="col-6">
					<!--<a href="favourite.php" class="button light-pink">Cancel</a>-->
					<button type="submit" name="deletefavourites" class="button light-pink">Cancel</button>
				</div>
			</div>			
		</div>
	</div>
</div>
</form>
<!--end main-->
<?php include "footer.php"; ?>