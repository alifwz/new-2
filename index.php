<?php
$page_title = 'Meetfreelancers | Best Freelance Meeting Site';
$seo_title = 'Meetfreelancers | Best Freelance Meeting Site';
$seo_description = "Meetfreelancers.com is the world's best freelancing and marketplace to meet and invite freelancers around the world.";
$seo_keywords = 'meetfreelancers,jobs,meet,hire,work,employee,employer,freelancers,money,earn,influencer,register,new,webapp,rating,interested,invite,list,chat,date,friends,users,free,opportunity,experience,help,find,view,creative,web design';
session_start();
include "connection.php";
include "header.php";
include "functions.php";
$pid = $_REQUEST[pid];
if ($_SESSION[countryid] == "") {
    $countryid = '';
} else {
    $countryid = $_SESSION[countryid];
}
if (isset($_POST[abuse])) {
    if (!isset($_SESSION['SESS_MEMBER_ID']) || empty($_SESSION['SESS_MEMBER_ID'])) {
        echo "<script>window.location='index.php?status=failed'</script>";
        die;
    }
    if (!isset($_POST[postid]) || empty($_POST[postid])) {
        echo "<script>window.location='index.php?status=failed'</script>";
        die;
    }
    $content = $_POST[content];
    $postid = $_POST[postid];
    $postSql = mysql_query("select * from freelancer_mmv_userimages WHERE id=$postid");
    $imgcount = mysql_num_rows($postSql);
    if ($imgcount == 0) {
        echo "<script>window.location='index.php?status=failed'</script>";
        die;
    }
    $abuse_que = mysql_query("INSERT INTO freelancer_mmv_abuse(`id`, `abuserid`, `postid`, `content`, `date`) VALUES ('','$loginid','$postid','$content',NOW())");
    $emailquery = mysql_query("SELECT * FROM freelancer_mmv_aboutus WHERE id='3'");
    $emailres = mysql_fetch_array($emailquery);
    $adminemail = $emailres[content];
    $userinfo = getUserinfo($loginid);
    $fullname = $userinfo[3] . ' ' . $userinfo[4];
    $tou = $adminemail;
    $subjectu = "Freelancer - REPORT/ABUSE";
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
				<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Dear Admin,</td>
			</tr>
			<tr>
				<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
					You have received an REPORT/ABUSE from <h3>' . $fullname . '</h3> please login to Freelancer Admin and check for more information.
				</td>
			</tr>
			<tr>
				<td style="padding:10px 20px 15px 20px">
					<a target="_blank" href="' . $urlpath . 'meet-admin/reportabuse.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Check in CMS</a>
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
    smtpmailer($tou, $fullname, $from, $from_name, $subjectu, $messageu);
    if ($abuse_que == 1) {
        echo "<script>window.location='index.php?status=abusesuccess'</script>";
    }
}
if (isset($_POST[submiturl])) {
    $editid = $_POST[editid];
    $description = $_POST[description];
    $abuse_que = mysql_query("UPDATE freelancer_mmv_userimages SET `description`='$description' WHERE id=$editid");
    if ($abuse_que == 1) {
        echo "<script>window.location='index.php?status=usuccess'</script>";
    }
}
if ($_REQUEST[cid] != "") {
    $id = $_REQUEST[cid];
    unset($_SESSION['SESS_SUBCAT_ID']);
    $_SESSION[SESS_SUBCAT_ID] = $_REQUEST[cid];
}
if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'cat') {
    $inputs = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='$_SESSION[SESS_SUBCAT_ID]'");
    while ($inputs_res = mysql_fetch_array($inputs)) {
        $results[] = $inputs_res[filter_id];
    }
    $allcats = implode(",", $results);
    if ($allcats)
        $filterid = "AND freelanceserviceid IN (" . $allcats . ")";
    else
        $filterid = '';
} else {
    $filterid = '';
}
?>
<style>
    #video{object-fit: cover; height:430px;}
</style>
<!--start main-->
<div class="main">
    <section class="contenets-main">
        <?php
        if ($pid != "") {
            $about_que = mysql_query("SELECT * from freelancer_mmv_userimages where id='$pid' AND status=1 AND userid!='' ORDER BY id DESC");
            $about_res = mysql_fetch_array($about_que);
            $idd = $about_res[id];
            $uid = $about_res[userid];
            $userinfo = getUserinfo($uid);
            $jobid = $userinfo[16];
            $jobdesc = getSubExperience($jobid);
            $like_que = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$idd'");
            $count = mysql_num_rows($like_que);
            $userlikcount = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$idd' AND user_id='$loginid'");
            $mycount = mysql_num_rows($userlikcount);
            ?>
            <div class="contenets">
                <div class="topbar">
                    <div class="container clearfix">
                        <?php
                        $web = $about_res[website];
                        if (false === strpos($web, '://')) {
                            $url = 'http://' . $web;
                        }
                        if ($about_res[website] != "") {
                            ?>
                                                                                                                                                            <!--<a href="<?php echo $url; ?>" target="_blank" class="view-website">View website</a>-->
                        <?php } ?>
                        <div class="doted-main">
                            <?php if ($uid != $loginid && $loginid != "") { ?>
                                <a href="javascript:void(0);" name="abuse" data-index="<?php echo $idd ?>"  class="more-link" data-fancybox="" data-type="inline" data-src="#abuseOption"><img src="images/dotted-img.png" alt="More"/></a>
                            <?php } else if ($uid == $loginid && $loginid != "") { ?>
                                <a href="javascript:void(0);" class="more-link" data-fancybox="" data-type="inline" data-src="#moreLinks<?php echo $idd1; ?>"><img src="images/dotted-img.png" alt="More"/></a>
                            <?php } else { ?>
                                <a href="javascript:void(0);" class="more-link" data-fancybox="" data-type="inline" data-src="#loginPopup"><img src="images/dotted-img.png" alt="More"/></a>
                            <?php } ?>
                        </div>
                        <?php
                        if ($about_res[description] != "") {
                            ?>
                            <span style="word-break: break-all; font-size: 13.5px">
                                <?php if (strlen($about_res[description]) > 196) { ?>
                                    <span style='color:black'><?= substr($about_res[description], 0, 196) ?><span style="display:none;color:black" id="full_text_<?= $idd1 ?>"><?= substr($about_res[description], 196, 900) ?></span>
                                    </span>
                                    <span class="dot_<?= $idd1 ?>">...</span>
                                    <br>
                                    <a class="read_more" style="color:grey;float:right" data-id="<?= $idd1 ?>" id="read_more_<?= $idd1 ?>" href="javascript:void(0);">
                                        Read More
                                    </a>
                                    <a style="color:grey;float:right;display: none;" class="less_more" data-id="<?= $idd1 ?>" id="less_more_<?= $idd1 ?> href="javascript:void(0);">Show Less</a>
                                    <?php
                                } else {
                                    echo "<span style='color:black'>" . $about_res[description] . "</span>";
                                }
                                ?>
                            <?php } ?>
                    </div>
                </div>
                <?php
                if ($loginid) {
                    ?>
                    <div class="popbox">
                        <div id="moreLinks<?php echo $idd1; ?>" class="popupbox text-align-center abuseOption url-and-post">
                            <p><a href="javascript:void(0);" data-fancybox="" data-src="#editUrl<?php echo $idd1; ?>" data-type="inline" class="button more-link">Edit Description</a></p>
                            <p><a href="deletecollection.php?id=<?php echo $idd1; ?>&type=delpost" class="button">Delete Post</a></p>
                        </div>
                    </div>
                    <div class="popbox">
                        <div id="editUrl<?php echo $idd1; ?>" class="popupbox text-align-center abuseOption url-and-post">
                            <form name="edits" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="editid" value="<?php echo $idd; ?>">
                                <?php
                                $web_que = mysql_query("SELECT * from freelancer_mmv_userimages where id='$idd' AND status=1");
                                $web_res = mysql_fetch_array($web_que);
                                ?>
                                <p>
                                    <textarea required="" style="height:170px" maxlength="800" name="description" id="description_textarea" class="form-control text-align-center inputbg" placeholder="Say something about this photo"><?php echo $web_res[description] ?></textarea>

                                    <button type="submit" name="submiturl" class="button loginbtn">Submit</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                <?php
                $extension = strtolower(end(explode(".", $about_res[image])));
                if ($extension == "mp4" || $extension == "mov") {
                    $filename = preg_replace('"\.(mp4|MP4|MOV|mov)$"', '.png', $about_res[image]);
                    ?>
                    <div class="contenets-img" align="center">
                            <!--<video style="background-color:black" width="425" height="430" preload="none" controls poster="https://meetfreelancers.com/beta/<?php echo $filename ?>">
                                    <source src="<?php echo $about_res[image]; ?>" type='video/mp4'>
                                    <source src="<?php echo $about_res[image]; ?>" type="video/ogg; codecs=theora, vorbis" />
                                    <source src="<?php echo $about_res[image]; ?>" type="video/webm; codecs=vp8, vorbis" />
                            <img src="https://meetfreelancers.com/beta/<?php echo $filename ?>" title="Your browser does not support the <video> tag">
                            </video>-->
                        <video id="video" style="background-color:black" width="100%" controls="true" poster="<?php echo $urlpath . $filename ?>">
                            <source src="<?php echo $about_res[image]; ?>" type="video/mp4">
                            <source src="<?php echo $about_res[image]; ?>" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php } else { ?>
                    <div class="contenets-img">
                        <img src="<?php echo $about_res[image]; ?>" <?php if ($loginid != "") { ?> ondblclick="mydoubleFunction(<?php echo $about_res[id] ?>)" <?php } ?> alt=""/>
                    </div>
                <?php } ?>


                <div class="btmbar">
                    <div class="container clearfix">
                        <table width="100%">
                            <tr>
                                <td class="job-posted-user">
                                    <?php if ($uid == $loginid) { ?>
                                        <a href="profile.php"><?php echo $userinfo[3] . '<br>' . $userinfo[4] ?></a>
                                    <?php } else { ?>
                                        <a href="viewclient.php?id=<?php echo $uid ?>"><?php echo $userinfo[3] . '<br>' . $userinfo[4] ?></a>
                                    <?php } ?>
                                </td>

                                <?php if ($uid == $loginid) { ?>
                                    <td align="center" class="job-title"><a href="profile.php"><?php echo $jobdesc ?></a></td>
                                <?php } else { ?>
                                    <td align="center" class="job-title"><a href="viewclient.php?id=<?php echo $uid ?>"><?php echo $jobdesc ?></a></td>
                                <?php } ?>

                                <?php
                                if ($loginid != '') {
                                    if ($mycount < 1) {
                                        ?>
                                        <td class="likes-div" style="cursor:pointer"><i id="delete_<?php echo $about_res[id] ?>" class="fa">&#xf08a;</i> <a href="likers.php?ccid=<?php echo $idd; ?>"><span id="this<?php echo $about_res[id] ?>"><?php echo $count; ?></span> likes</a></td>
                                    <?php } else { ?>
                                        <td class="likes-div" style="cursor:pointer"><i class="fa">&#xf08a;</i>
                                            <a href="likers.php?ccid=<?php echo $idd; ?>"> <?php echo $count; ?> likes</a></td>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <td class="likes-div" style="cursor:pointer"><a href="#" data-fancybox data-type="inline" data-src="#loginPopup"><i class="fa">&#xf08a;</i> <?php echo $count; ?> likes</a></td>
                                <?php } ?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!--start job contenets-->
        <div id="postList">
            <?php
            if ($_SESSION[countryid] == "" && $id == "" && $filterid == '') {
                $about_que1 = mysql_query("SELECT * from freelancer_mmv_userimages where  userid!='' AND status=1 ORDER BY id DESC LIMIT 10");
            } else if ($_SESSION[countryid] == "" && $id != "" && $filterid == '') {
                $about_que1 = mysql_query("SELECT * from freelancer_mmv_userimages where  userid!='' AND status=1 AND freelanceserviceid=$id ORDER BY id DESC LIMIT 10");
            } else if ($_SESSION[countryid] != "" && $id == "" && $filterid == '') {
                $about_que1 = mysql_query("SELECT * from freelancer_mmv_userimages where  userid!='' AND status=1 AND countryid='$countryid' ORDER BY id DESC LIMIT 10");
            } else if ($_SESSION[countryid] == "" && $filterid != '') {
                $about_que1 = mysql_query("SELECT * from freelancer_mmv_userimages where  userid!='' AND status=1 $filterid ORDER BY id DESC LIMIT 10");
            } else if ($_SESSION[countryid] != "" && $filterid != '') {
                $about_que1 = mysql_query("SELECT * from freelancer_mmv_userimages where  userid!='' AND status=1 $filterid AND countryid='$countryid' ORDER BY id DESC LIMIT 10");
            } else {
                $about_que1 = mysql_query("SELECT * from freelancer_mmv_userimages where  userid!='' AND status=1 AND freelanceserviceid=$id AND countryid='$countryid' ORDER BY id DESC LIMIT 10");
            }
            $imgcount = mysql_num_rows($about_que1);
            if ($imgcount == 0) {
                echo '<div class="contenets">
					<div class="topbar">
						<div class="container">
							<p align="center" style="font-size:18px">No results!!</p>
						</div>
					</div>
				 </div>';
            } else {
                while ($about_res1 = mysql_fetch_array($about_que1)) {
                    $idd = $about_res1[id];
                    $idd1 = $about_res1[id];
                    $uid = $about_res1[userid];
                    $userinfo = getUserinfo($uid);
                    $jobid = $userinfo[16];
                    $jobdesc = getSubExperience($jobid);
                    $postIDD = $idd;
                    $like_que = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$idd'");
                    $count = mysql_num_rows($like_que);
                    $userlikcount = mysql_query("SELECT * from freelancer_mmv_member_like where workid='$idd' AND user_id='$loginid'");
                    $mycount = mysql_num_rows($userlikcount);
                    ?>
                    <div class="contenets">
                        <div class="topbar">
                            <div class="container clearfix">
                                <?php
//                                $web = $about_res1[website];
//                                if (false === strpos($web, '://')) {
//                                    $url = 'http://' . $web;
//                                }
//                                if ($about_res1[website] != "") {
//                                    
                                ?>
                                <!--<a href="<?php echo $url; ?>" target="_blank" class="view-website">View website</a>-->
                                <?php // } ?>
                                <div class="doted-main">
                                    <?php if ($uid != $loginid && $loginid != "") { ?>
                                        <a href="javascript:void(0);" name="abuse" data-index="<?php echo $idd ?>"  class="more-link" data-fancybox="" data-type="inline" data-src="#abuseOption"><img src="images/dotted-img.png" alt="More"/></a>
                                    <?php } else if ($uid == $loginid && $loginid != "") { ?>
                                        <a href="javascript:void(0);" class="more-link" data-fancybox="" data-type="inline" data-src="#moreLinks<?php echo $idd1; ?>"><img src="images/dotted-img.png" alt="More"/></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0);" class="more-link" data-fancybox="" data-type="inline" data-src="#loginPopup"><img src="images/dotted-img.png" alt="More"/></a>
                                    <?php } ?>
                                </div>
                                <br>
                                <?php
                                if ($about_res1[description] != "") {
                                    ?>
                                    <span style="word-break: break-all; font-size: 13.5px">
                                        <?php if (strlen($about_res1[description]) > 196) { ?>
                                            <span style='color:black'><?= substr($about_res1[description], 0, 196) ?></span>
                                            <span style="display:none;color:black" id="full_text_<?= $idd1 ?>"><?= substr($about_res1[description], 196, 900) ?></span>
                                            <span class="dot_<?= $idd1 ?>">...</span>
                                            <br>
                                            <a class="read_more" style="color:grey;float:right" data-id="<?= $idd1 ?>" id="read_more_<?= $idd1 ?>" href="javascript:void(0);">
                                                Read More
                                            </a>
                                            <a style="color:grey;float:right;display: none;" class="less_more" data-id="<?= $idd1 ?>" id="less_more_<?= $idd1 ?>" href="javascript:void(0);">Show Less</a>
                                            <?php
                                        } else {
                                            echo "<span style='color:black'>" . $about_res1[description] . "</span>";
                                        }
                                        ?>
                                    </span>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        if ($loginid) {
                            ?>
                            <div class="popbox">
                                <div id="moreLinks<?php echo $idd1; ?>" class="popupbox text-align-center abuseOption url-and-post">
                                    <p><a href="javascript:void(0);" data-fancybox="" data-src="#editUrl<?php echo $idd1; ?>" data-type="inline" class="button more-link">Edit Description</a></p>
                                    <p><a href="deletecollection.php?id=<?php echo $idd1; ?>&type=delpost" class="button">Delete Post</a></p>
                                </div>
                            </div>

                            <div class="popbox">
                                <div align="center" id="editUrl<?php echo $idd1; ?>" class="popupbox text-align-center abuseOption url-and-post">
                                    <form name="edits" method="post" action="" enctype="multipart/form-data">
                                        <input type="hidden" name="editid" value="<?php echo $idd1; ?>">
                                        <?php
                                        $web_que = mysql_query("SELECT * from freelancer_mmv_userimages where id='$idd1' AND status=1");
                                        $web_res = mysql_fetch_array($web_que);
                                        ?>
                                        <p align="center">
                                            <textarea required="" style="height:170px" maxlength="800" name="description" id="description_textarea" class="form-control text-align-center inputbg" placeholder="Say something about this photo"><?php echo $web_res[description] ?></textarea>

                                                                                                                                                                                                                                                                                                                                                                                                                                     <!--<input type="text" name="weblink" value="<?php echo $web_res[website] ?>" required class="form-control text-align-center inputbg" placeholder="Website URL" id=""><br>-->
                                            <button type="submit" name="submiturl" class="button loginbtn">Submit</button></p>
                                    </form>
                                </div>
                            </div>

                            <?php
                        }
                        $extension = strtolower(end(explode(".", $about_res1[image])));
                        if ($extension == "mp4" || $extension == "mov") {
                            $filename = preg_replace('"\.(mp4|MP4|MOV|mov)$" ', ' .png ', $about_res1[image]);
                            ?>
                            <div class="contenets-img" align="center">
                            <!--<a id="anchor1" onclick="PlayVideo('anchor1','video1');"><img src ="https://meetfreelancers.com/beta/<?php echo $filename ?>" alt="trail" /></a>
                            <video  id="video1" controls="controls" style="display:none" poster="https://meetfreelancers.com/beta/<?php echo $filename ?>">
                                    <source src="<?php echo $about_res1[image]; ?>"    type="video/mp4" />
                                    <source src="<?php echo $about_res1[image]; ?>" type="video/ogg" />
                                    Your browser does not support the video element.
                            </video>
                                    <video style="background-color:black" width="425" height="430" controls >
                                      <source src="<?php echo $about_res1[image]; ?>" type="video/mp4">
                                    <img width="425" height="430" src="https://meetfreelancers.com/beta/<?php echo $filename ?>" title="Your browser does not support the <video> tag">
                                    </video>-->
                                <video id="video" style="background-color:black" width="100%" controls="true" poster="<?php echo $urlpath . $filename; ?>">
                                    <source src="<?php echo $about_res1[image]; ?>" type="video/mp4">
                                    <source src="<?php echo $about_res1[image]; ?>" type="video/ogg">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php } else { ?>
                            <div class="contenets-img">
                                <img class="dbclickimage_<?php echo $about_res1[id]; ?>" id="<?php echo $about_res1[id]; ?>" src="<?php echo $about_res1[image]; ?>" alt=""/>
                            </div>
                        <?php } ?>


                        <div class="btmbar">
                            <div class="container clearfix">
                                <table width="100%">
                                    <tr>
                                        <td class="job-posted-user">
                                            <?php if ($uid == $loginid) { ?>
                                                <a href="profile.php"><?php echo $userinfo[3] . ' <br>' . $userinfo[4] ?></a>
                                            <?php } else { ?>
                                                <a href="viewclient.php?id=<?php echo $uid ?>"><?php echo $userinfo[3] . ' <br>' . $userinfo[4] ?></a>
                                            <?php } ?>
                                        </td>

                                        <?php if ($uid == $loginid) { ?>
                                            <td align="center" class="job-title"><a href="profile.php"><?php echo $jobdesc ?></a></td>
                                        <?php } else { ?>
                                            <td align="center" class="job-title"><a href="viewclient.php?id=<?php echo $uid ?>"><?php echo $jobdesc ?></a></td>
                                        <?php } ?>

                                        <?php
                                        if ($loginid != '') {
                                            if ($mycount < 1) {
                                                ?>
                                                <td class="likes-div" style="cursor:pointer"><i id="delete_<?php echo $about_res1[id] ?>" class="fa">&#xf08a;</i> <a href="likers.php?ccid=<?php echo $idd; ?>"><span id="this<?php echo $about_res1[id] ?>"><?php echo $count; ?></span> likes</a></td>
                                            <?php } else { ?>
                                                <td class="likes-div" style="cursor:pointer"><i class="fa">&#xf08a;</i><a href="likers.php?ccid=<?php echo $idd; ?>"> <?php echo $count; ?> likes</a></td>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <td class="likes-div" style="cursor:pointer"><a href="#" data-fancybox data-type="inline" data-src="#loginPopup"><i class="fa">&#xf08a;</i> <?php echo $count; ?> likes</a></td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="load-more" lastID="<?php echo $postIDD; ?>" style="display: none;">
                <img src="images/loading.gif"/>
            </div>
        </div>
        <!--end job contenets-->
    </section>
</div>
<!--end main-->
<!--start other popup boxes-->
<?php if ($loginid) { ?>
    <div class="popbox">
        <div id="abuseOption" class="popupbox text-align-center abuseOption">
            <form name="abuse" method="post" action="">
                <input type="hidden" name="postid" id="postid" value=""/>
                <h2>report/abuse</h2>
                <div class="form-group">
                    <textarea name="content" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" name="abuse" class="button">Submit</button>
                </div>
            </form>
        </div>

    </div>
<?php } ?>

<script>
    $("[class^='dbclickimage_']").dblclick(function () {
        var i = $(this).attr('class');
        var arr = i.split("_");
        var idim = arr[1];
        //var idim = $('.dbclickimage').attr('id');
        //alert(i);
        $.ajax({
            type: "POST",
            data: "id=" + idim,
            url: "likescript.php",
            success: function (data)
            {
                if (data != "")
                {
                    $('#this' + idim).text(data);
                }
            }
        });
    });
    /* function mydoubleFunction(myval) {
     var i= myval;
     //alert(i);
     $.ajax({
     type:"POST",
     data:"id="+i,
     url:"likescript.php",
     success:function(data)
     {
     if(data!="")
     {
     $('#this'+i).text(data);
     }
     }
     });
     } */
</script>

<script>
    $(function () {
        $("a[name=abuse]").on("click", function () {
            var abu = $(this).attr("data-index");
            document.getElementById("postid").value = abu;
        });
        $("[id^='delete_']").click(function () {
            var i = $(this).attr('id');
            var arr = i.split("_");
            var i = arr[1];
            $.ajax({
                type: "POST",
                data: "id=" + i,
                url: "likescript.php",
                success: function (data)
                {
                    if (data != "")
                    {
                        $('#this' + i).text(data);
                    }
                }
            });
        });
    });
</script>
<?php include "footer.php"; ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
    $(window).on("scroll", function () {
        $.cookie("tempScrollTop", $(window).scrollTop());
        $.cookie("numclicks", 1);
    });
    $(function () {
        if ($.cookie("tempScrollTop")) {
            $(window).scrollTop($.cookie("tempScrollTop"));
        }
    });
    $(".home-link").click(function () {
        var $btn = $(this);
        var count = ($.cookie("numclicks", 2) || 0) + 1;
        $.cookie("numclicks", count);
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
        $.cookie('tempScrollTop', 0);
    }
</script>

<script type="text/javascript">
    //$(document).ready(function(){
    $(window).scroll(function () {
        var lastID = $('.load-more').attr('lastID');
<?php if ($_SESSION[SESS_SUBCAT_ID] == "") { ?>
            var datastring = 'id=' + lastID;
    <?php
} else if ($filterid != '') {
    ?>
            var catid = <?php echo $_SESSION[SESS_SUBCAT_ID]; ?>;
            var datastring = 'id=' + lastID + '&catid=' + catid + '&type=' + '<?php echo $allcats ?>';
<?php } else {
    ?>
            var catid = <?php echo $_SESSION[SESS_SUBCAT_ID]; ?>;
            var datastring = 'id=' + lastID + '&catid=' + catid;
<?php } ?>
        if (($(window).scrollTop() == $(document).height() - $(window).height()) && (lastID != 0)) {
            $.ajax({
                type: 'POST',
                url: 'getData.php',
                data: datastring,
                beforeSend: function () {
                    $('.load-more').show();
                },
                success: function (html) {
                    $('.load-more').remove();
                    $('#postList').append(html);
                }
            });
        }
    });
    $(document).on('click', '.read_more', function () {
        var id = $(this).attr('data-id');
        $('#full_text_' + id).show();
        $('#less_more_' + id).show();
        $('.dot_' + id).hide();
        $(this).hide();
    })
    $(document).on('click', '.less_more', function () {
        var id = $(this).attr('data-id');
        $('#full_text_' + id).hide();
        $('#read_more_' + id).show();
        $('.dot_' + id).show();
        $(this).hide();
    })
    //});
</script>