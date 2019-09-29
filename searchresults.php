<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";

if($_SESSION[countryid]==""){
	$countryid = '';
} else {
	$countryid = $_SESSION[countryid];
}
	$hobby 		= $_POST[hobby];
	$sport 		= $_POST[sport];
	$expsector 	= $_POST[expsector];
	$mbti 		= $_POST[mbti];
	
?>
<style type="text/css">
        .km-last-seen{margin-top:-20px;}
        .last-seen-right{font-size:12px;}
        @media screen and (min-width: 414px){
            .for-rating .favourite-dtl h3 {
                padding: 10px 0 0px 0;
                font-size: 17px;
            }
        }
        @media screen and (max-width: 450px) and (min-width: 300px){
            .for-rating .favourite-dtl h3 {
                font-size: 17px;
            }
            .last-seen-right {
                font-size: 9px;
            }
        }
        @media screen and (max-width: 450px) and (min-width: 300px){
            .for-rating .favourite-dtl {
                margin: 0 0 0 60px;
            }
                        .km-last-seen {
    margin-top: -20px;
}
        }
    </style>
<!--<form name="meetinginvite" id="meetinginvite" action="invite-request.php" method="post">-->
<form name="meetinginvite" id="meetinginvite" action="redirect.php" method="post">
<!--start main-->
<div class="main">		
	<section class="contenets-main">
		<!--start post job -->
		
		<div class="contenets post-a-job-main" style="min-height:700px;">
			<!--<label class="control control--checkbox select-all">Select All
				<input type="checkbox" name="selectAll" />
				<div class="control__indicator"></div>
			</label>-->
			<?php
			if($_SESSION[countryid]==""){			
				$where = "AND member_id!='$loginid'";
			} else {
				$where = "AND country='$countryid' AND member_id!='$loginid'";
			}
			
			if($hobby!="" && $sport!="" && $expsector!="" && $mbti!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where hobby='$hobby' AND sporttoparticipate='$sport' AND expsector='$expsector' AND mbti='$mbti' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($hobby!="" && $sport!="" && $expsector!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where hobby='$hobby' AND sporttoparticipate='$sport' AND expsector='$expsector' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($sport!="" && $expsector!="" && $mbti!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where sporttoparticipate='$sport' AND expsector='$expsector' AND mbti='$mbti' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($expsector!="" && $mbti!="" && $hobby!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where mbti='$mbti' AND expsector='$expsector' AND hobby='$hobby' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($hobby!="" && $sport!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where hobby='$hobby' AND sporttoparticipate='$sport' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($sport!="" && $expsector!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where sporttoparticipate='$sport' AND expsector='$expsector'$where AND status='1'");
			} else if($expsector!="" && $mbti!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where expsectornew='$expsector' AND mbti='$mbti' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($mbti!="" && $hobby!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where mbti='$mbti' AND hobby='$hobby' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($hobby!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where hobby='$hobby' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($sport!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where sporttoparticipate='$sport' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($expsector!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where expsectornew='$expsector' $where AND status='1' ORDER BY lastseeen DESC");
			} else if($mbti!=""){
				$about_que = mysql_query("SELECT * from freelancer_mmv_member_master where mbti='$mbti' $where AND status='1' ORDER BY lastseeen DESC");
			}
			$count = mysql_num_rows($about_que);
			if($count>=1){
			while($about_res = mysql_fetch_array($about_que))
			{
				$clientid = $about_res[member_id];
				//Ratings
				$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$clientid'");
				$noofrev_res = mysql_fetch_array($noofrev_que);
				$ratingsum = $noofrev_res[ratingsum];
				$con = $noofrev_res[con];
				$rateval = $ratingsum/$con;
				// echo "<pre>";
				//    print_r($about_res);
				// echo "</pre>";exit;
				$last =  strtotime($about_res[lastseeen]);
				$lastseen = date('d-m-Y h:i:s',$last);
				
					/*$latitudeFrom = $_GET['currentlat'];
					$longitudeFrom = $_GET['currentlong'];
					
					$latitudeTo = $about_res['loginlats'];
					$longitudeTo = $about_res['loginlong'];
					
					//Calculate distance from latitude and longitude
					$theta = $longitudeFrom - $longitudeTo;
					$dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
					$dist = acos($dist);
					$dist = rad2deg($dist);
					$miles = $dist * 60 * 1.1515;
					$distance = ($miles * 1.609344);*/
			?>
			<div class="control-group">
				<div class="job-thumb favourite-box for-rating">
					<div class="job-row" onClick="location.href='viewclient.php?id=<?php echo $about_res[member_id] ?>'">
						<div class="favourite-holder setSize">
						<?php if($about_res[image] ==""){ ?>
								<img src="images/user.png" alt=""/>
							<?php } else { ?>
								<div class="photograph"><img src="uploads/users/<?php echo $about_res[image] ?>" alt=""/></div>
							<?php } ?>
						</div>
						<div class="favourite-dtl">
							<h3><?php echo $about_res[first_name].' '. $about_res[created]; ?>
							<div class="km-last-seen">
								<!-- <div class="km-left"><?php //echo round($distance).' km' ?></div> -->
								
								<!--<div class="last-seen-right">Last seen <?php 
								//echo $lastseen; ?>
								</div>-->
                                
                                <div class="last-seen-right" style="text-transform:none !important;">Last seen <?php
                                                    $lastseen = $about_res[lastseeen];
                                                    $dbtimezone = $about_res[timezone];
                                                    echo converToTz($lastseen, $timezone, $dbtimezone);
                                                    ?></div>
							</div>
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
					<div class="job-row thumb-row">
						<div class="favourite-holder">
							<label class="control control--checkbox"> 
								<input type="checkbox" name="invitehire" class="invitehire" id="invitehire" value="<?php echo $about_res[member_id] ?>"/>
								<input type="hidden" name="invitehireeee[]" id="invitehireeee" class="invitehireeee" value="<?php echo $about_res[member_id] ?>"/>
								<div class="control__indicator"></div>
							</label>
						</div>
						<div class="favourite-dtl">
                            <p><span class="grey" style="font-size:12px;">Nationality:</span> <?php echo getNationality($about_res[nationality]) ?><br> <span class="grey" style="font-size:12px;">Area of Residence:</span><strong class="black"> <?php echo $about_res[area]; ?></strong> <br> <span class="grey" style="font-size:10px;">Gender:</span> <span style="font-size:12px;"><?php echo $about_res[gender] ?></span>  <span class="grey" style="font-size:10px;">Faith:</span> <span style="font-size:12px;"><?php echo $about_res[faith] ?></span> <span class="grey" style="font-size:10px;">MBTI Personality:</span> <span style="font-size:12px;"> <?php echo getMBTI($about_res[mbti]) ?> </span> <br> <span class="grey" style="font-size:12px;">Education:</span>  <?php echo getEducation($about_res[education]) ?> <Br>  <span class="grey" style="font-size:12px;">Degree:</span>  <?php echo getDegree($about_res[degree]) ?><br> <span class="grey" style="font-size:12px;">Job Title:</span> <?php echo ($about_res[jobtitle]) ?> <?php //echo getSubExperience($about_res[subexpsector]) ?><br />
							<span class="grey" style="font-size:12px;">Freelancing Service:</span> <strong class="black"><?php echo getSubExperience($about_res[subexpsector]) ?></strong></p>
							
						</div>
					</div>
				</div>				
			</div>
			<?php } } else {
				echo "<h2 align='center'>No results!</h2>";
			} ?>
			<p><br><br></p>
		</div>
		
		<!--end post job -->
	</section>
</div>
<!--end main-->
<!--start meet invite-->
<div class="meet-invite">
	<?php if($loginid!=''){ ?>
		<a href="#" class="meet-link">	
			<button type="submit"  name="submit" id="submit" style="border: none; background: none;cursor:pointer"> 
				<span class="meet-icon"></span>
				<span class="mi-title">Meet</span>
			</button>
		</a>
	<?php } else { ?>
		<a href="#" data-fancybox data-type="inline" data-src="#loginPopup" class="meet-link">
			<span class="meet-icon"> </span>
			<span class="mi-title">Meet</span>
		</a>
	<?php } ?>

	<?php if($loginid!=''){ ?>
		<a href="#" class="invite-link">
			<button name="invitesubmit" id="invitesubmit" style="border:none; background:none;cursor:pointer"> 
				<span class="meet-icon"></span>
				<span class="mi-title">Invite</span>
			</button>
		</a>
	<?php } else { ?>
		<a href="javascript;;" data-fancybox data-type="inline" data-src="#loginPopup" class="invite-link">
		<span class="meet-icon"> </span>
		<span class="mi-title">Invite</span></a>
	<?php } ?>
</div>
<script type='text/javascript'>
	
$("#submit").click(function(){ 
	//If more than 15 are checked - don't allow
	if($('.invitehire:checkbox:checked').length == 0){
		window.location='searchresults.php?status=atleastone';
		return false;
	} 
});

$("#invitesubmit").click(function(){ 
	//If more than 15 are checked - don't allow
	if($('.invitehire:checkbox:checked').length == 0){
		window.location='searchresults.php?status=atleastone';
		return false;
	} 
});
</script>

<!--<div class="meet-invite">
	<a href="meeting-request.html" class="meet-link">
		<span class="meet-icon"> </span>
		<span class="mi-title">Meet</span>
	</a>
	<a href="invite-request.php" id="submit" class="invite-link">
		<span class="meet-icon"> </span>
		<span class="mi-title">Invite</span>
	</a>	
</div>-->

</form>
<!--end meet invite-->
<?php include "footer.php"; ?>
<script type="text/javascript">

	/*$('.invite-link').on('click', function(e) {
		e.preventDefault(); // prevents a window.location change to the href
		$('#invitehireeee').val( $(this).data('val') );  // sets to 123 or abc, respectively
		$('#meetinginvite').submit();
	});*/
	
	/*$('#meetinginvite').on('submit', function(){ 
		alert($('#invitehireeee').val()); 
		return false;
	});
	
	$(".submit").click(function() {
		var link = $(this).attr('var');
		$('.invitehireeee').attr("value",link);
		$('.redirect').submit();
	});*/

    $(function()
    {
      $('[name="selectAll"]').change(function()
      {
        if ($(this).is(':checked')) {           
			$('.control--checkbox input').prop('checked', true);
			$('.meet-link').addClass('active');
        }
		  else{
			$('.control--checkbox input').prop('checked', false);
			  $('.meet-link').removeClass('active');
		  };
      });		
	$('.control--checkbox input').change(function()
      {
        if ($(this).is(':checked')) {           
			
        }
		  else{
			$('.select-all input').prop('checked', false);
			  $('.meet-link').removeClass('active');
		  };
      });	
    });
	
	$('#submit').addClass('invite-link');
  </script>
