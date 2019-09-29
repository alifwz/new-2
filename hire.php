<?php
//session_start();
include "connection.php";
include "header.php";
include "functions.php";

if ($_SESSION[countryid] == "") {
    $countryid = '';
} else {
    $countryid = $_SESSION[countryid];
}

if ($_REQUEST[cid] != "") {
    unset($_SESSION['SESS_SUBCAT_ID']);
    $_SESSION[SESS_SUBCAT_ID] = $_REQUEST[cid];
    $catid = $_REQUEST[cid];

    $cat_query1 = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND filter_id=$catid");
    $cat_res1 = mysql_fetch_array($cat_query1);
    $parent_id = $cat_res1[parent_id];

    $inputs = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='$catid'");
    while ($inputs_res = mysql_fetch_array($inputs)) {
        $results[] = $inputs_res[filter_id];
    }


    $allcats = implode(",", $results);

    if ($parent_id == "0") {
        $filterid = "AND subexpsector IN (" . $allcats . ")";
    } else {
        $filterid = "AND subexpsector=" . $_REQUEST[cid];
    }
    if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'cat') {
        $filterid .= " OR expsector=" . $_REQUEST[cid];
    }
} else {
    $filterid = "";
}
?>

<!--<form name="meetinginvite" id="meetinginvite" action="invite-request.php" method="post">-->
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
                font-size: 8px;
            }
        }
        @media screen and (max-width: 450px) and (min-width: 300px){
            .for-rating .favourite-dtl {
                margin: 0 0 0 60px;
            }
            .km-last-seen {
    margin-top: -25px;
}
        }
    </style>
<form name="meetinginvite" id="meetinginvite" action="invite-request.php" method="post">
    <!--start main-->
    <div class="main">
        <section class="contenets-main">
            <!--start post job -->

            <div class="contenets post-a-job-main">

                <?php
                if ($_SESSION[countryid] == "") {
                    //echo "SELECT * from freelancer_mmv_member_master where 1=1 AND member_id!='$loginid' $filterid AND status='1' ORDER BY lastseeen DESC";	
                    $about_que = mysql_query("SELECT * from freelancer_mmv_member_master where 1=1 AND member_id!='$loginid' $filterid AND status='1' ORDER BY lastseeen DESC");
                } else {
                    $about_que = mysql_query("SELECT * from freelancer_mmv_member_master where 1=1 AND country='$countryid' AND member_id!='$loginid' $filterid AND status='1' ORDER BY lastseeen DESC");
                }
                $imgcount = mysql_num_rows($about_que);
                if ($imgcount == 0) {

                    echo '<div class="contenets">
							<div class="topbar">
								<div class="container">
									<p align="center" style="font-size:18px">No results!!</p>
								</div>
							</div>
						 </div>';
                } else {

                    echo '<label class="control control--checkbox select-all">Select All
						<input type="checkbox" name="selectAll" />
						<div class="control__indicator"></div>
					</label>';

                    while ($about_res = mysql_fetch_array($about_que)) {
                        $memid = $about_res[member_id];

                        $memberid = $memid;
                        /* Get Distance */
                        $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                        $login_result = mysql_fetch_array($login_que);
                        $login_que1 = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$memberid'");
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
                        $finaldist = $distt;
                        /* End */

                        //Ratings
                        $noofrev_que = mysql_query("SELECT count(*) as con, sum(ratings) as ratingsum FROM freelancer_mmv_reviewratings WHERE reviewto='$memid'");
                        $noofrev_res = mysql_fetch_array($noofrev_que);
                        $ratingsum = $noofrev_res[ratingsum];
                        $con = $noofrev_res[con];
                        $rateval = $ratingsum / $con;

                        /* if($about_res[first_name]!="" && $about_res[last_name]!="" && $about_res[nationality]!="" && $about_res[area]!="" && $about_res[gender]!="" && $about_res[mbti]!="" && $about_res[education]!="" && $about_res[degree]!="" && $about_res[jobtitle]!="" && $about_res[expsector]!=""){ */
                        if ($about_res[first_name] != "" && $about_res[last_name] != "" && $about_res[nationality] != "" && $about_res[expsector] != "") {
                            ?>
                            <div class="control-group">
                                <div class="job-thumb favourite-box for-rating">
                                    <div class="job-row" onClick="location.href = 'viewclient.php?id=<?php echo $about_res[member_id] ?>'">
                                        <div class="favourite-holder setSize">
                                            <?php if ($about_res[image] == "") { ?>
                                                <img src="images/user.png" alt="" />
                                            <?php } else { ?>
                                                <div class="photograph"><img src="uploads/users/<?php echo $about_res[image] ?>" alt="" /></div>
                                            <?php } ?>
                                        </div>

                                        <div class="favourite-dtl">
                                            <h3><?php echo $about_res[first_name] . ' ' . $about_res[created]; ?></h3>
                                            <div class="km-last-seen">
                                               <!-- <div class="km-left"><?php
                                                    if ($about_res[loginlats] != "") {
                                                        echo number_format($finaldist, 1) . ' Km Away';
                                                    } else {
                                                        echo 'n/a Km Away';
                                                    }
                                                    ?></div>-->
                                                    <div class="last-seen-right">Last seen <?php
                                                    $lastseen = $about_res[lastseeen];
                                                    $dbtimezone = $about_res[timezone];
                                                    echo converToTz($lastseen, $timezone, $dbtimezone);
                                                    ?></div>
                                            </div>
                                            <div class="rating-right">
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
                                            </div>

                                        </div>
                                    </div>
                                    <div class="job-row thumb-row">
                                        <div class="favourite-holder" >
                                            <label class="control control--checkbox">
                                                <input type="checkbox" name="invitehire[]" class="invitehire" id="invitehire" value="<?php echo $about_res[member_id] ?>" />
                                                <input type="hidden" name="invitehireeee[]" id="invitehireeee" class="invitehireeee" value="<?php echo $about_res[member_id] ?>" />
                                                <div class="control__indicator"></div>
                                            </label>

                                        </div>
                                        <div class="favourite-dtl">
                                            <p><span class="grey" style="font-size:12px;">Nationality:</span> <?php echo "" . getNationality($about_res[nationality]) ?> <br> <span class="grey" style="font-size:12px;">Area of Residence:</span> <strong class="black"><?php echo $about_res[area] ?></strong> <br> <span class="grey" style="font-size:10px;">Gender:</span> <span  style="font-size:12px;"> <?php echo "  " . $about_res[gender] ?></span> <span class="grey" style="font-size:10px;">Faith:</span><span style="font-size:12px;"><?php echo "  " . $about_res[faith] ?> </span><span class="grey" style="font-size:10px;">MBTI Personality:</span> <span style="font-size:12px;"><?php echo"  " . getMBTI($about_res[mbti]) ?></span> <Br> <span class="grey" style="font-size:12px;">Education:</span> <?php echo"  " . getEducation($about_res[education]) ?> <br> <span class="grey" style="font-size:12px;">Degree:</span> <?php echo"  " . getDegree($about_res[degree]) ?><br> <span class="grey" style="font-size:12px;">Job Title:</span> <?php echo"  " . ($about_res[jobtitle]) ?> <?php //echo getSubExperience($about_res[subexpsector])                                   ?><br />
                                                <span class="grey" style="font-size:12px;">Freelancing Service:</span> <strong class="black"><?php echo getSubExperience($about_res[subexpsector]) ?></strong></p>

                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                <p><br><br></p>
            </div>

            <!--end post job -->
        </section>
    </div>
    <!--end main-->
    <!--start meet invite-->
    <div class="menu-wrapper-sub">
        <div class="meet-invite">
            <div class="meet-invite-sub">
                <?php
                if ($loginid != '') {
                    $login_que = mysql_query("SELECT * from freelancer_mmv_member_master where member_id='$loginid'");
                    $login_result = mysql_fetch_array($login_que);
                    if ($login_result['first_name'] == '' || $login_result['last_name'] == '' || $login_result['country'] == '' || $login_result['nationality'] == '' || $login_result['expsectornew'] == '' || $login_result['hobby'] == '' || $login_result['mbti'] == '' || $login_result['area'] == '') {
                        ?>
                        <a href="#" class="meet-link">
                            <button data-fancybox data-type="inline" data-src="#profilefillPopup" style="border: none; background: none;cursor:pointer;width:200px">
                                <span class="meet-icon"></span>
                                <span class="mi-title">Meet</span>
                            </button>
                        </a>
                        <?php
                    } else {
                        ?>
                        <a href="#" class="meet-link">
                            <button name="submit" id="submit" style="border: none; background: none;cursor:pointer;width:200px">
                                <span class="meet-icon"></span>
                                <span class="mi-title">Meet</span>
                            </button>
                        </a>
                        <?php
                    }
                } else {
                    ?>
                    <a href="#" data-fancybox data-type="inline" data-src="#loginPopup" class="meet-link">
                        <span class="meet-icon"> </span>
                        <span class="mi-title">Meet</span>
                    </a>
                <?php } ?>

                <?php
                if ($loginid != '') {
                    if ($login_result['first_name'] == '' || $login_result['last_name'] == '' || $login_result['country'] == '' || $login_result['nationality'] == '' || $login_result['expsectornew'] == '' || $login_result['hobby'] == '' || $login_result['mbti'] == '' || $login_result['area'] == '') {
                        ?>
                        <a href="#" class="invite-link">
                            <button data-fancybox data-type="inline" data-src="#profilefillPopup" style="border:none; background:none;cursor:pointer;width:200px">
                                <span class="meet-icon"></span>
                                <span class="mi-title">Invite</span>
                            </button>
                        </a>
                        <?php
                    } else {
                        ?>
                        <a href="#" class="invite-link">
                            <button name="invitesubmit" id="invitesubmit" style="border:none; background:none;cursor:pointer;width:200px">
                                <span class="meet-icon"></span>
                                <span class="mi-title">Invite</span>
                            </button>
                        </a>
                        <?php
                    }
                } else {
                    ?>
                    <a href="javascript;;" data-fancybox data-type="inline" data-src="#loginPopup" class="invite-link">
                        <span class="meet-icon"> </span>
                        <span class="mi-title">Invite</span></a>
                    <?php } ?>
            </div>
        </div>
    </div>
</form>

<script type='text/javascript'>
    $("#submit").click(function () {
        //If more than 15 are checked - don't allow
        if ($('.invitehire:checkbox:checked').length == 0) {
            window.location = 'hire.php?status=atleastone';
            //alert('Please select at least one user');
            return false;
        } else if ($('.invitehire:checkbox:checked').length > 1) {
            alert('Only 1 user is allowed to meet!!');
            $('#submit').prop('disabled', true);
            return false;
        }
    });

    $("#invitesubmit").click(function () {
        //If more than 15 are checked - don't allow
        if ($('.invitehire:checkbox:checked').length == 0) {
            window.location = 'hire.php?status=atleastone';
            //alert('Please select atleast one user');
            return false;
        }
    });

</script>
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

    $(function () {
        $('[name="selectAll"]').change(function () {
            if ($(this).is(':checked')) {
                $('.control--checkbox input').prop('checked', true);
                $('.meet-link').addClass('active');
            } else {
                $('.control--checkbox input').prop('checked', false);
                $('.meet-link').removeClass('active');
            }
            ;
        });
        $('.control--checkbox input').change(function () {
            if ($(this).is(':checked')) {
                var ccc = $('input[name="invitehire[]"]:checked').length;
                if (ccc > 1) {
                    //alert(ccc);			
                    $('.meet-link').addClass('active');
                }
            } else {
                $('.select-all input').prop('checked', false);
                $('.meet-link').removeClass('active');
            }
            ;
        });
    });

    $('#submit').addClass('invite-link');

</script>
<!--end meet invite-->
<?php include "footer.php"; ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
    $(window).on("scroll", function () {
        $.cookie("hireScrollTop", $(window).scrollTop());
        $.cookie("hireclicks", 1);
    });

    if ($.cookie("hireScrollTop")) {
        $(window).scrollTop($.cookie("hireScrollTop"));
    }

    $(".hire-link").click(function () {
        var $btn = $(this);
        var count = ($.cookie("hireclicks", 2) || 0) + 1;
        $.cookie("hireclicks", count);
        if (count == 1)
            alert(1);
        else if (count == 2)
            alert(2);
        else {
            removecookies();
            return true;
        }
        return false;
    });

    function removecookies() {
        $.cookie('hireScrollTop', 0);
    }

</script>
