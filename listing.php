

<?php

$page_title = 'Meetfreelancers | Best Place to Find Jobs
';
$seo_title = 'Meetfreelancers | Best Place to Find Jobs
';
$seo_description = "Meetfreelancers.com Has an feature to Find Jobs Easily by selecting country that you live in and Selecting the freelance service that you up to.";
$seo_keywords = 'meetfreelancers,jobs,meet,hire,work,employee,employer,freelancers,money,earn,influencer,register,new,webapp,rating,interested,invite,list,chat,date,friends,users,free,opportunity,experience,help,find,view,creative,web design';
//session_start();
include "connection.php";
include "header.php";
include "functions.php";

$filter_query = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND filter_id='$_SESSION[SESS_SUBCAT_ID]'");
$filter_res = mysql_fetch_array($filter_query);

if($_SESSION[countryid]==""){
	$countryid = '';
} else {
	$countryid = $_SESSION[countryid];
}


if($_REQUEST[cid]!=""){	
	$catid = $_REQUEST[cid];
	unset($_SESSION['SESS_SUBCAT_ID']);
 
	$_SESSION[SESS_SUBCAT_ID]=$_REQUEST[cid];	
	$inputs = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='$catid'");
	while($inputs_res = mysql_fetch_array($inputs)){
		$results[] = $inputs_res[filter_id];		
	}
	$allcats = implode(",",$results);
	
	$cat_query1 = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND filter_id=$catid");
	$cat_res1 = mysql_fetch_array($cat_query1);
	$parent_id = $cat_res1[parent_id];
	
	if($parent_id=="0"){
		$catfilter = "AND filter_id IN (".$allcats.")";
	} else {
		$catfilter = "AND filter_id IN (".$catid.")";
	}	
} else {
	$catfilter = "";	
}

?>
<!--start main-->

<div class="main">	
<?php if($filter_res[title]!=""){ echo '<h3 align="center" style="font-size:16px">'.$filter_res[title].'</h3>'; } else { echo '&nbsp'; } ?>
	<div class="listing-main">
	<?php
	if($countryid !=""){
		$banner_que = mysql_query("SELECT * from freelancer_mmv_work where countryid='$countryid' $catfilter AND status='1' ORDER BY lastseen DESC");		
	} else {		
		$banner_que = mysql_query("SELECT * from freelancer_mmv_work where status='1' $catfilter ORDER BY lastseen DESC");
	}
	
	$imgcount = mysql_num_rows($banner_que);
	if($imgcount==0){
			
			echo '<div class="contenets">
					<div class="topbar">
						<div class="container">
							<p align="center" style="font-size:18px">No results!!</p>
						</div>
					</div>
				 </div>';
				 
		} else {
	while($banner_result = mysql_fetch_array($banner_que))
	{
		if($loginid!=""){
						
			$memberid = $banner_result[member_id];
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
			
			$last 		= $banner_result[lastseen];
			$dbtimezone = $banner_result[timezone];									
			$lastseen	= converToTz($last, $timezone, $dbtimezone);
			/*End*/
			
				
		if($banner_result[member_id]!=$loginid){	
	?>	
		<div class="listing-row" onClick="location.href='listing-detail.php?id=<?php echo $banner_result[work_id] ?>'">			
			<h3><?php echo $banner_result[title] ?></h3>
			<div class="listing-div"><div class="listing-div-sub">
				<span style="font-size:11px;" class="cost-amt"><?php echo getBudget($banner_result[budget]) ?></span>
				
				<span style="font-size:11px;" class="last-seen">Last seen:<?php echo $lastseen; ?></span>
				<span style="font-size:11px;" class="days-div"><?php echo getDuration($banner_result[duration]) ?></span>
				<!-- <span style="font-size:11px;" class="km-num"><?php echo number_format($finaldist,1).' km Away' ?></span> -->
			</div><div class="arrow-right"></div></div>
		</div>
		<?php } else { ?>
		<div class="listing-row" onClick="location.href='postajobedit.php?id=<?php echo $banner_result[work_id] ?>'">			
			<h3><?php echo $banner_result[title] ?></h3>
			<div class="listing-div">
				<span style="font-size:11px;" class="cost-amt"><?php echo getBudget($banner_result[budget]) ?></span>
				
				<span style="font-size:11px;" class="last-seen">Last seen:<?php echo $lastseen; ?></span>
				<span style="font-size:11px;" class="days-div"><?php echo getDuration($banner_result[duration]) ?></span>
				<!-- <span style="font-size:11px;" class="km-num"><?php echo number_format($finaldist,1).' km Away' ?></span> -->
			</div>
		</div>
	<?php } } else {
			$memberid = $banner_result[member_id];
			/*Get Distance*/					
			$login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
			$login_result1 = mysql_fetch_array($login_que1);
			
			$latitudeFrom = $_COOKIE['mylatitude'];
			$longitudeFrom = $_COOKIE['mylongitude'];				
			
			$latitudeTo = $login_result1['loginlats'];
			$longitudeTo = $login_result1['loginlong'];			
			$distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
			$finaldist = $distt;
			
			$last 		= $banner_result[lastseen];
			$dbtimezone = $banner_result[timezone];									
			$lastseen	= converToTz($last, $timezone, $dbtimezone);
	?>
		<div class="listing-row" onClick="location.href='listing-detail.php?id=<?php echo $banner_result[work_id] ?>'">			
			<h3><?php echo $banner_result[title] ?></h3>
			<div class="listing-div"><div class="listing-div-sub">
				<span style="font-size:11px;" class="cost-amt"><?php echo getBudget($banner_result[budget]) ?></span>
				
				<span style="font-size:11px;" class="last-seen">Last seen:<?php echo $lastseen; ?></span>
				<span style="font-size:11px;" class="days-div"><?php echo getDuration($banner_result[duration]) ?></span>
				<!-- <span style="font-size:11px;" class="km-num"><?php echo number_format($finaldist,1).' km Away' ?></span> -->
			</div><div class="arrow-right"></div></div>
		</div>


		<?php } } } ?>
	</div>
</div>
<!--end main-->
<?php include "footer.php"; ?>
