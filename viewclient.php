<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";

if ($_REQUEST[id] != "") {
    $clieid = $_REQUEST[id];
}
if ($_REQUEST[req] != "") {
    $fav = $_REQUEST[req];
}

if ($fav == 'fav') {
    mysql_query("UPDATE freelancer_mmv_favourites SET favstatus='1' WHERE memberid='$loginid' AND userid='$clieid'");
}

$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$clieid'");
$about_res = mysql_fetch_array($query);
//Ratings
$noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$clieid'");
$noofrev_res = mysql_fetch_array($noofrev_que);
$ratingsum = $noofrev_res[ratingsum];
$con = $noofrev_res[con];
$rateval = $ratingsum / $con;

if (isset($_POST[submitrating])) {
    $ratingnum = $_POST[ratingnum];
    $reviewdesc = $_POST[review];
    $invid = $_POST[invid];

    $query3 = mysql_query("INSERT INTO freelancer_mmv_reviewratings (id, invitationid, reviewto, reviewby, ratings, reviewdesc, date) VALUES ('', '$invid', '$clieid','$loginid','$ratingnum','$reviewdesc','$now')");

    if ($query3 == 1) {
        echo "<script>window.location='viewclient.php?id=$clieid&status=posted'</script>";
    }
    echo "<script>window.location='viewclient.php?id=$clieid'</script>";
}
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
                            <?php if ($about_res[image] == "") { ?>
                                <img src="images/user.png" alt="user"/>
                            <?php } else { ?>
                                <div class="photograph"><img src="uploads/users/<?php echo $about_res[image] ?>" alt="user"/></div>
                            <?php } ?>		
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
                                <a href="javascript:void(0);" class="button reviewPopupBtn">Reviews</a>
                                <?php if ($loginid != '') { ?>
                                    <a href="meeting-request.php?clientid=<?php echo $clieid ?>&num=1" class="button light-yellow">Meet</a>
                                <?php } else { ?>
                                    <a data-type="meeting-request.php?clientid=<?php echo $clieid ?>&num=1" href="javascript:void(0);" class="button light-yellow loginpopup">Meet</a>
                                <?php } ?>
                                <?php if ($about_res[verified_document] != "" && $about_res[verifiedstatus] != '0') { ?>
                                    <a href="javascript:void(0);" class="button verified">Verified</a>
                                <?php } else { ?>
                                    <a <?php if ($clieid == $loginid) { ?> href="veryfied-user.php" <?php } ?> class="button verified">Not Verified</a>
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
                    </div>
                    <div class="row">						
                        <!--<div class="col-6">
                                 <div class="prfl-div">
                                        <label>Major</label>
                                        <div class="prfl-dtl"><?php echo $about_res[freelance]; ?></div>
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
                                <?php
                                /* Get Distance */
                                /* $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                                  $login_result = mysql_fetch_array($login_que);
                                  $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$clieid'");
                                  $login_result1 = mysql_fetch_array($login_que1);

                                  if ($loginid == "") {
                                  $latitudeFrom = $_COOKIE['mylatitude'];
                                  $longitudeFrom = $_COOKIE['mylongitude'];
                                  } else {
                                  $latitudeFrom = $login_result['loginlats'];
                                  $longitudeFrom = $login_result['loginlong'];
                                  }

                                  $latitudeTo = $login_result1['loginlats'];
                                  $longitudeTo = $login_result1['loginlong'];

                                  $distt = distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, "K") . " Kilometers<br>";
                                  $finaldist = $distt; */
                                /* End */
                                ?>
                                <div class="left-div">
                                    <?php
                                    /* if ($about_res[loginlats] != "") {
                                      echo number_format($finaldist, 1) . ' km';
                                      } else {
                                      echo 'n/a';
                                      } */
                                    ?>
                                </div>							 	
                                <div class="right-div">Last seen <?php
                                    $lastseen = $about_res[lastseeen];
                                    $dbtimezone = $about_res[timezone];
                                    echo converToTz($lastseen, $timezone, $dbtimezone);
                                    ?></div>								
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
                                <label class="cv">User's posts</label>
                                <div class="photo-video gallery">
                                    <?php
                                    $img_query = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$clieid' and status = 1 ORDER BY id DESC");
                                    while ($img_res = mysql_fetch_array($img_query)) {
                                        $extension2 = end(explode(".", $img_res[image]));
                                        if ($extension2 != "mp4") {
                                            ?>
                                            <div class="photo-div"><a href="index.php?pid=<?php echo $img_res[id] ?>"><img src="<?php echo $img_res[image]; ?>" width="125px" height="125px" alt="photo" /></a></div>
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
        <?php
        //$comm_query = mysql_query("SELECT * FROM freelancer_mmv_member_invitation WHERE user_id='$clieid' AND invited_userid='$loginid' AND acceptedstatus=1 ORDER BY invitation_id DESC");	
        //$comm_query = mysql_query("SELECT * FROM freelancer_mmv_member_invitation WHERE (user_id='$clieid' AND invited_userid='$loginid') OR (invitation_return_reviewto='$loginid' AND invitation_return_reviewby='$clieid')  AND acceptedstatus=1 ORDER BY invitation_id DESC");
        //echo "SELECT * FROM freelancer_mmv_member_invitation WHERE ((user_id='$clieid' AND invited_userid='$loginid') OR (invitation_return_reviewby='$clieid' AND invitation_return_reviewto='$loginid')) AND acceptedstatus=1 ORDER BY invitation_id DESC";
        if ($clieid != $loginid) {
            //echo "1SELECT * FROM freelancer_mmv_member_invitation WHERE ((user_id='$loginid' AND invited_userid='$clieid') OR (invitation_return_reviewby='$clieid' AND invitation_return_reviewto='$loginid')) AND acceptedstatus=1 ORDER BY invitation_id DESC";
            $comm_query = mysql_query("SELECT * FROM freelancer_mmv_member_invitation WHERE ((user_id='$loginid' AND invited_userid='$clieid') OR (invitation_return_reviewby='$clieid' AND invitation_return_reviewto='$loginid')) AND acceptedstatus=1 ORDER BY invitation_id DESC");
        } else {
            //echo "2SELECT * FROM freelancer_mmv_member_invitation WHERE ((user_id='$clieid' AND invited_userid='$loginid') OR (invitation_return_reviewby='$clieid' AND invitation_return_reviewto='$loginid')) AND acceptedstatus=1 ORDER BY invitation_id DESC";
            $comm_query = mysql_query("SELECT * FROM freelancer_mmv_member_invitation WHERE ((user_id='$clieid' AND invited_userid='$loginid') OR (invitation_return_reviewby='$clieid' AND invitation_return_reviewto='$loginid')) AND acceptedstatus=1 ORDER BY invitation_id DESC");
        }

        $comm_res = mysql_num_rows($comm_query);
        $comm_result = mysql_fetch_array($comm_query);
        $hourmin = $comm_result[hours_minutes];
        $invid = $comm_result[invitation_id];

        $dbtimezone = $comm_result[timezone];
        $meetdate = $comm_result[meetingdate];

        if ($comm_result[meetingdate] != "") {
            $converted_datetime = converToTz($meetdate, $timezone, $dbtimezone);
            $appdate = strtotime($converted_datetime);
        } else {
            $appdate = strtotime($comm_result[meetingdate]);
        }

        $time = $hourmin;
        $timesplit = explode(':', $time);
        $min = ($timesplit[0] * 60) + ($timesplit[1]) + ($timesplit[2] > 30 ? 1 : 0) . ' minutes';
        $strtTime = strtotime("+" . $min, $appdate);
        $totaltime = $strtTime;
        $startdate = date('Y-m-d H:i', ($totaltime));
        $currentdate = date('Y-m-d H:i');

        //Check rating Query
        $rate_query = mysql_query("SELECT * FROM freelancer_mmv_reviewratings WHERE invitationid='$invid' AND reviewby='$loginid'");
        $rate_count = mysql_num_rows($rate_query);
        if ($rate_count == 0) {
            if ($comm_res >= '1' && $currentdate >= $startdate) {
                ?>
                <form name="ratings" method="post" action="">
                    <input type="hidden" name="invid" value="<?php echo $invid; ?>">
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
                                    <textarea required name="review" id="text" class="job-input review-textarea" placeholder="Write Your Comment Here..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row post-a-job-row text-align-center">
                            <div class="col-12 paddingtop10">
                                <div class="rating-review rating-submit"><button class="button" name="submitrating" type="submit" onclick="return check_value();">Submit</button></div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
            }
        }
        ?>
        <div class="review-main">
            <?php
            $revquery = mysql_query("SELECT * FROM freelancer_mmv_reviewratings WHERE reviewto='$clieid' ORDER BY date DESC");

            $totalnumber = mysql_num_rows($revquery);
            if ($totalnumber > 0) {
                echo '<h2>Review Comments</h2>';
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
                                        if ($myrat == 1) {
                                            echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                        } else if ($myrat == 2) {
                                            echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                        } else if ($myrat == 3) {
                                            echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                        } else if ($myrat == 4) {
                                            echo '<img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-yellow.png" alt="star"/>
										 <img src="images/star-grey.png" alt="star"/>';
                                        } else if ($myrat == 5) {
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
                                    </div>
                                </h3>					 
                            </div>
                        </div>
                        <p><?php echo $revres[reviewdesc] ?></p>				
                    </div>
                    <?php
                }
            } else {
                echo '<h5>No Reviews yet!</h5>';
            }
            ?>
        </div>
    </div>
</div>
<!--end main-->
<?php include "footer.php"; ?>
<script type="text/javascript">
    function check_value() {
        var wordInput = document.getElementById("text").value.toLowerCase();
        // split the words by spaces (" ")
        var arr = wordInput.split(" ");
        // bad words to look for
        var badWords = ['sex', 'ass', 'fuck', 'shit', 'asshole', 'cunt', 'fag', 'fuk', 'fck', 'fcuk', 'assfuck', 'assfucker', 'fucker', 'motherfucker', 'asscock', 'asshead', 'asslicker', 'asslick', 'assnigger', 'nigger', 'asssucker', 'bastard', 'bitch', 'bitchtits', 'bitches', 'bitch', 'brotherfucker', 'bullshit', 'bumblefuck', 'buttfucka', 'fucka', 'buttfucker', 'buttfucka', 'fagbag', 'fagfucker', 'faggit', 'faggot', 'faggotcock', 'fagtard', 'fatass', 'fuckoff', 'fuckstick', 'fucktard', 'fuckwad', 'fuckwit', 'dick', 'dickfuck', 'dickhead', 'dickjuice', 'dickmilk', 'doochbag', 'douchebag', 'douche', 'dickweed', 'dyke', 'dumbass', 'dumass', 'fuckboy', 'fuckbag', 'gayass', 'gayfuck', 'gaylord', 'gaytard', 'nigga', 'niggers', 'niglet', 'paki', 'piss', 'prick', 'pussy', 'poontang', 'poonany', 'porchmonkey', 'porchmonkey', 'poon', 'queer', 'queerbait', 'queerhole', 'queef', 'renob', 'rimjob', 'ruski', 'sandnigger', 'sandnigger', 'schlong', 'shitass', 'shitbag', 'shitbagger', 'shitbreath', 'chinc', 'carpetmuncher', 'chink', 'choad', 'clitface', 'clusterfuck', 'cockass', 'cockbite', 'cockface', 'skank', 'skeet', 'skullfuck', 'slut', 'slutbag', 'splooge', 'twatlips', 'twat', 'twats', 'twatwaffle', 'vaj', 'vajayjay', 'va-j-j', 'wank', 'wankjob', 'wetback', 'whore', 'whorebag', 'whoreface', 'horny', 'viagra', 'porn', 'testicles', 'butt', 'penis', 'dick', 'tits', 'suck', 'fart', 'porno', 'nude', 'nudes', 'nipple'];

        var index, totalWordAmount = 0, totalWordsFoundList = '';
        for (index = 0; index < arr.length; ++index) {
            if (jQuery.inArray(arr[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr[index];
                alert("Please use better words!");
                return false;
            }
        }
        for (index = 0; index < arr1.length; ++index) {
            if (jQuery.inArray(arr1[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr1[index];
                alert("Please use better words!");
                return false;
            }
        }
        for (index = 0; index < arr2.length; ++index) {
            if (jQuery.inArray(arr2[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr2[index];
                alert("Please use better words!");
                return false;
            }
        }
    }
</script>