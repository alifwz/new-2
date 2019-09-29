<?php
session_start();
$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php#login");
    die;
}
include "connection.php";
include "header.php";
include "functions.php";

$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$loginid'");
$about_res = mysql_fetch_array($query);

//Ratings
$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$loginid'");
$noofrev_res = mysql_fetch_array($noofrev_que);
$ratingsum = $noofrev_res[ratingsum];
$con = $noofrev_res[con];
$rateval = $ratingsum / $con;
?>
<!--start main-->
<div class="main">		
    <section class="contenets-main">
        <!--start user-->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="blacking-wing-main reviews-profile-verified">
                        <div class="user-div setSize">
                            <div class="photograph">
                                <?php if ($about_res[image] == "") { ?>
                                    <img src="images/user.png" alt="user"/>
                                <?php } else { ?>
                                    <img src="uploads/users/<?php echo $about_res[image] ?>" alt="user"/>
                                <?php } ?>
                            </div>							
                        </div>
                        <div class="favourite-dtl">
                            <h3><?php echo $about_res[first_name] . ' ' . $about_res[last_name] ?></h3>
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
                            <div class="three-btns-div">
                                <a href="javascript:void(0);" class="button reviewPopupBtn">Reviews</a><a href="make-profile.php" class="button light-yellow">Edit profile</a>
                                <?php
                                $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                $login_result = mysql_fetch_array($login_que);
                                if ($login_result['first_name'] == '' || $login_result['last_name'] == '' || $login_result['country'] == '' || $login_result['nationality'] == '' || $login_result['expsectornew'] == '' || $login_result['hobby'] == '' || $login_result['mbti'] == '' || $login_result['area'] == '') {
                                    ?>
                                    <a data-fancybox data-type="inline" data-src="#profilefillPopup" class="button verified">Not Verified</a>
                                <?php } else if ($about_res[verified_document] != "" && $about_res[verifiedstatus] != '0') {
                                    ?>
                                    <a href="javascript:void(0);" class="button verified">Verified</a>
                                <?php } else { ?>
                                    <a href="veryfied-user.php" class="button verified">Not Verified</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row user-profile profile-page">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Country of Residence</label>
                                <div class="prfl-dtl"><?php echo getCountry($about_res[country]) ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Nationality</label>
                                <div class="prfl-dtl"><?php echo getNationality($about_res[nationality]) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Education:</label>
                                <div class="prfl-dtl"><?php echo getEducation($about_res[education]); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Degree:</label>
                                <div class="prfl-dtl"><?php echo getDegree($about_res[degree]); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Freelance Service</label>
                                <div class="prfl-dtl"><?php echo getExperience($about_res[expsector]); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Freelance Sub Service</label>
                                <div class="prfl-dtl"><?php echo getSubExperience($about_res[subexpsector]); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Freelance Timing </label>
                                <div class="prfl-dtl"><?php echo $about_res[freelancetiming]; ?> </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Job Title</label>
                                <div class="prfl-dtl"><?php echo $about_res[jobtitle]; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Hobby</label>
                                <div class="prfl-dtl"><?php echo getHobby($about_res[hobby]); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Sport </label>
                                <div class="prfl-dtl"><?php echo getSport($about_res[sporttoparticipate]); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>MBTI Personality:</label>
                                <div class="prfl-dtl"><?php echo getMBTI($about_res[mbti]); ?> </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Faith </label>
                                <div class="prfl-dtl"><?php echo $about_res[faith]; ?> </div>
                            </div>
                        </div>
                    </div>					
                    <div class="row">
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Gender </label>
                                <div class="prfl-dtl"><?php echo ($about_res[gender]); ?></div>
                            </div> 
                        </div>
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Area (of residence)</label>
                                <div class="prfl-dtl"><?php echo ($about_res[area]); ?></div>
                            </div> 
                        </div>	
                        <!--<div class="col-6">
                                 <div class="prfl-div">
                                        <label>Major </label>
                                        <div class="prfl-dtl"><?php echo $about_res[freelance]; ?> </div>
                                 </div>
                        </div>-->
                        <div class="col-6">
                            <div class="prfl-div">
                                <label>Experience Sector </label>
                                <div class="prfl-dtl"><?php echo getExperienceSector($about_res[expsectornew]); ?> </div>
                            </div>
                        </div>						
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="div-row grey-bg">
                                <!--<div class="left-div">03 Km</div>							 	
                                <div class="right-div">Last seen 25 - 11 -2017 | 10:30 AM</div>-->
                            </div>
                            <div class="div-row grey-bg cv-dtl">
                                <label class="cv">CV</label>							 	
                                <p><?php echo $about_res[talentandexp]; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="photo-video-main">
                                <label class="cv">Photo & Video</label>
                                <div class="photo-video gallery">
                                    <?php
                                    $img_query = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$loginid' and status = 1 ORDER BY id DESC");
                                    while ($img_res = mysql_fetch_array($img_query)) {
                                        $extension2 = end(explode(".", $img_res[image]));
                                        if ($extension2 != "mp4") {
                                            ?>
                                            <div class="photo-div"><a href="<?php echo $img_res[image]; ?>" data-fancybox="images"><img src="<?php echo $img_res[image]; ?>" width="125px" height="125px" alt="photo"/></a></div>
                                        <?php } else { ?>
                                            <div class="photo-div">
                                                <video width="125" height="125" controls>
                                                    <source src="<?php echo $img_res[image]; ?>" type="video/mp4">
                                                    <source src="<?php echo $img_res[image]; ?>" type="video/ogg">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>	
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>	
                </div>
            </div>
        </div>
        <!--end user-->		
    </section>
</div>

<div class="popbox">
    <div class="review-popup" id="reviewPopup">
        <form name="ratings" method="post" action="">
            <!--<div class="write-comment-main">
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
            </div>-->
        </form>
        <div class="review-main">
            <h2>Review Comments</h2>
            <?php
            $revquery = mysql_query("SELECT * FROM freelancer_mmv_reviewratings WHERE reviewto='$loginid' ORDER BY date DESC");
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