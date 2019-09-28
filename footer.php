<?php
$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$loginid'");
$res = mysql_fetch_array($query);
$freelanceserviceid = $res[16];
?>
<style>
    .fancybox-close-small:after {
        content: "x";
        background: red !important;
        position: absolute;
        top: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        font: 20px/30px Arial,Helvetica Neue,Helvetica,sans-serif;
        color: white;
        font-weight: 300;
        text-align: center;
        border-radius: 50%;
        border-width: 0;
        transition: background .2s;
        box-sizing: border-box;
        z-index: 2;
    }
    .main-user {
        background:none;
    }
    .loged-user-title-sub{
        color:whit !important;
    }
    .add-account .loged-user-img {
        display:none !important;
    </style>
    <style>
        ::placeholder{
            font-size: 15px
        }
    </style>
    <!--start menubar-->
    <div class="menu-wrapper">
        <div class="menu-wrapper-sub">
            <div class="menu-main">
                <div class="menu-main-sub">
                    <div class="loged-user">
                        <div class="loged main-user">
                            <div class="loged-user-img setSize">
                                <?php if ($res[image] != "") { ?>
                                    <div class="photograph">
                                        <img src="uploads/users/<?php echo $res[image] ?>" alt="" />
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="loged-user-title"><div class="loged-user-title-sub" style="color:black;"><?php echo $res[first_name] . ' ' . $res[last_name] ?></div></div>
                        </div>

                        <ul class="subuser">
                            <?php
                            $sess = (array_unique($_SESSION['SESS_MEMBER_ID']));
                            foreach (($sess) as $key => $uservalue) {
                                $query2 = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$uservalue'");
                                $res2 = mysql_fetch_array($query2);
                                if ($loginid == $uservalue) {
                                    
                                } else {
                                    ?>
                                    <a href="changesessionval.php?id=<?php echo $uservalue ?>"><li class="loged">
                                            <div class="loged-user-img setSize">

                                                <?php if ($res2[image] != "") { ?>
                                                    <div class="photograph">
                                                        <img src="uploads/users/<?php echo $res2[image] ?>" alt="" />
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="loged-user-title"><div class="loged-user-title-sub"><?php echo $res2[first_name] . ' ' . $res2[last_name] ?></div></div>
                                        </li></a>
                                    <?php
                                }
                            }
                            ?>
                            <!--<a data-fancybox data-type="inline" data-src="#loginPopup" href="javascript:;">
                                    <li class="loged add-account">
                                            <div class="loged-user-img"></div>
                                            <div class="loged-user-title"><div class="loged-user-title-sub">Add Account</div></div>
                                    </li>
                            </a>-->
                        </ul>
                        <ul>
                            <a data-fancybox data-type="inline" data-src="#loginPopup" href="javascript:;">
                                <li class="loged add-account">
                                    <div class="loged-user-img"></div>
                                    <div class="loged-user-title"><div class="loged-user-title-sub" style=" color:white; ">Add Account</div></div>
                                </li>
                            </a>
                        </ul>
                    </div>
                    <div class="menu-links">
                        <ul>
                            <li><a href="advance-search.php"><span class="menu-icon"><img src="images/icon-search.png" alt="Advance search" /></span> Advance search</a></li>
                            <?php if ($loginid != "") { ?>
                                <li><a href="profile.php"><span class="menu-icon"><img src="images/icon-upload-profile.png" alt="Profile" /></span> Edit Profile </a></li>
                                <li><a href="change-password.php"><span class="menu-icon"><img src="images/icon-password.png" alt="Change Password" /></span> Change Password </a></li>
                                <!--<li><a href="buy-tokens.php"><span class="menu-icon"><img src="images/icon-buy-tokens.png" alt="Buy Tokens" /></span> Buy Tokens</a></li>
                                <li><a href="tokens-balance.php"><span class="menu-icon"><img src="images/icon-tokens-balance.png" alt="Tokens balance" /></span> Tokens balance</a></li>-->
                                <li><a href="feedback.php"><span class="menu-icon"><img src="images/icon-feedback.png" alt="Feedback / Report" /></span> Feedback / Report</a></li>
                                <?php if ($res[verifiedstatus] == 0) { ?>
                                    <li><a href="veryfied-user.php"><span class="menu-icon"><img src="images/icon-verified.png" alt="Verified User" /></span> Verified User</a></li>
                                <?php } ?>
                            <?php } else { ?>
                                <li><a data-type="profile.php" href="javascript:void(0);" class="border_post loginpopup"><span class="menu-icon"><img src="images/icon-upload-profile.png" alt="Profile" /></span> Edit Profile </a></li>
                                <li><a data-type="change-password.php" href="javascript:void(0);" class="border_post loginpopup"><span class="menu-icon"><img src="images/icon-password.png" alt="Change Password" /></span> Change Password </a></li>
                                <!--<li><a href="#" data-fancybox data-type="inline" data-options='{"closeClickOutside":false,"touch":false}' data-src="#loginPopup"><span class="menu-icon"><img src="images/icon-buy-tokens.png" alt="Buy Tokens" /></span> Buy Tokens</a></li>
                                <li><a href="#" data-fancybox data-type="inline" data-options='{"closeClickOutside":false,"touch":false}' data-src="#loginPopup"><span class="menu-icon"><img src="images/icon-tokens-balance.png" alt="Tokens balance" /></span> Tokens balance</a></li>-->
                                <li><a data-type="feedback.php" href="javascript:void(0);" class="border_post loginpopup"><span class="menu-icon"><img src="images/icon-feedback.png" alt="Feedback / Report" /></span> Feedback / Report</a></li>
                                <li><a data-type="veryfied-user.php" href="javascript:void(0);" class="border_post loginpopup"><span class="menu-icon"><img src="images/icon-verified.png" alt="Verified User" /></span> Verified User</a></li>
                            <?php } ?>
                            <li><a href="about-us.php"><span class="menu-icon"><img src="images/icon-about-us.png" alt="About Us" /></span> About Us</a></li>
                            <li>
                                <?php
                                if ($loginid != "") {
                                    ?>
                                    <a href="logout.php?id=<?php echo $loginid ?>"><span class="menu-icon"><img src="images/icon-logout.png" alt="Logout" /></span> Logout</a>
                                <?php } else { ?>
                                    <a data-fancybox data-type="inline" data-options='{"closeClickOutside":false,"touch":false}' data-src="#loginPopup" href="javascript:;"><span class="menu-icon"><img src="images/icon-logout.png" alt="Logout" /></span> Login</a>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="close-menu"> </div>
    </div>
    <div class="popbox"><div id="sublinks" class="popupbox sublinks text-align-center"></div></div>
    <!--end menubar-->
    <!--start bottom bar-->
    <div class="bottom-holder">
        <div class="sub-bottom-bar">
            <!--<div class="photo-job-video">
                    <div class="row">
                            <div class="col-4 uploader"><a href="javascript:void(0);">Post a photo</a><div class="fileContainer"></div><input name="photo" type="file" id="post-photo-upload" maxlength="1" class="multi with-preview" /></div>
                            <div class="col-4"><a href="post-a-job.php" class="post-a-job">Post a job</a></div>
                            <div class="col-4 uploader"><a href="javascript:void(0);">Post a Video</a><div class="fileContainer"></div><input name="files[]" type="file" id="post-video-upload" maxlength="1" class="multi with-preview" /></div>
                    </div>
            </div>-->
            <form name="upload" id="imageform" action='ajaximage.php' method="post" enctype="multipart/form-data">
                <div class="photo-job-video">
                    <div class="row" style="margin-bottom: 0px;">
                        <?php if ($loginid == "") { ?>
                            <div class="col-4 uploader"><a data-type="postanimage.php" href="javascript:void(0);" class="border_post loginpopup">Post a photo</a></div>
                            <div class="col-4"><a data-type="post-a-job.php" href="javascript:void(0);" class="border_post loginpopup">Post a job</a></div>
                            <div class="col-4 uploader"><a data-type="postvideo" href="javascript:void(0);" class="border_post loginpopup">Post a Video</a></div>
                        <?php } else { ?>

                            <div class="col-4 uploader"><a href="postanimage.php">Post a photo</a><div class="fileContainer"></div>
                                <!--<div class="col-4 uploader"><a href="javascript:void(0);" data-options='{"touch": false}' data-fancybox data-type="inline" data-src="#imgPopup">Post a photo</a><div class="fileContainer"></div>-->
                                <!--<input name="photoimg" accept=".jpg, .jpeg, .png" type="file" id="post-photo-upload" required class="multi with-preview"/>
                                <a href="javascript:void(0);" data-fancybox data-type="inline" data-src="#videoPopup">Post a Photo</a>-->
                            </div>
                            <div class="col-4"><a href="post-a-job.php" class="post-a-job">Post a job</a></div>
                            <div class="col-4 uploader"><a href="javascript:void(0);" data-options='{"closeClickOutside":false,"touch": false}' data-fancybox data-type="inline" data-src="#videoPopup">Post a Video</a><div class="fileContainer"></div>
                                <!--<a href="javascript:void(0);" data-fancybox data-type="inline" data-src="#videoPopup">Post a Video</a>
                                <input name="videoimg" accept=".mp4" type="file" id="post-video-upload" required class="multi with-preview"/>--></div>
                        <?php } ?>
                    </div>
                </div>
            </form>
            <div class="work-hire-main">
                <div class="container clearfix">
                    <?php
                    if ($_SESSION[SESS_SUBCAT_ID] != "") {
                        $id = $_SESSION[SESS_SUBCAT_ID];
                        ?>
                        <a href="index.php?cid=<?php echo $id ?>" class="home-link"><img src="images/home-icon.png" alt="Home"/></a>
                    <?php } else { ?>
                        <a href="index.php" class="home-link"><img src="images/home-icon.png" alt="Home"/></a>
                    <?php } ?>

                    <?php
                    if ($_SESSION[SESS_SUBCAT_ID] != "") {
                        $id = $_SESSION[SESS_SUBCAT_ID];
                        ?>
                        <a href="listing.php?cid=<?php echo $id ?>" class="work-link">WORK</a>
                    <?php } else { ?>
                        <a href="listing.php" class="work-link">WORK</a>
                    <?php } ?>
                    <a href="javascript:void(0);" class="plus-link">+</a>
                    <?php
                    if ($_SESSION[SESS_SUBCAT_ID] != "") {
                        $id = $_SESSION[SESS_SUBCAT_ID];
                        ?>
                        <a href="hire.php?cid=<?php echo $id ?>" class="hire-link">HIRE</a>
                    <?php } else { ?>
                        <a href="hire.php" class="hire-link">HIRE</a>
                    <?php } ?>
                    <a href="javascript:void(0);" id="menulink" class="menu-link slidmenu"><img src="images/menu-icon.png" alt="Menu"/></a>
                </div>
            </div>
        </div>
    </div>
    <!--end bottom bar-->
    <!--start other popup boxes-->
    <a data-fancybox="" data-type="inline" data-src="#loginPopup" href="javascript:void(0);" class="loginPopupLink none"></a>
    <div class="popbox">
        <div id="loginPopup" class="popupbox text-align-center">

            <div class="login-main">
                <form name="login" method="post" action="submitregister.php">
                    <input type="hidden" name="timezone" id="timezone" value="<?php echo $timezone ?>">
                    <input type="hidden" name="now" id="now" value="<?php echo $now ?>">
                    <input type="hidden" name="lats" id="lats" value="">
                    <input type="hidden" name="long" id="long" value="">
                    <input type="hidden" name="redirection" id="login_redirection" value="">
                    <div class="form-group">
                        <input type="text" name="lusername" required class="form-control text-align-center inputbg" placeholder="Email address" id="lusername">
                    </div>
                    <div class="form-group">
                        <input type="password" name="lpassword" required class="form-control text-align-center inputbg" placeholder="Password" id="lpassword">
                    </div>
                    <div class="form-group">
                        <div class="row login-register">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <button type="submit" name="login" class="button loginbtn">Log In</button>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <a href="register.php" class="button light-yellow registered-btn">Register Now</a>
                            </div>
                        </div>

                        <div><a href="javascript:void(0);" class="forgot-pass">Forgot your password</a></div>
                    </div>
                </form>
                <form name="login" method="post" action="submitregister.php">
                    <div class="forgotpass-main">
                        <div class="form-group">
                            <input type="text" name="email" class="form-control text-align-center inputbg" placeholder="Email Address" id="">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="forgot" class="button loginbtn">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
            </form>
            <!--<div class="registered-main">
                    <p>Not registered yet!</p>
                    <a href="register.php" class="button light-yellow registered-btn">Register Now</a>
            </div>-->
        </div>

        <div id="imgPopup" class="popupbox text-align-center">
            <div class="login-main">
                <form name="login" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="freelanceserviceid" id="freelanceserviceid" value="<?php echo $freelanceserviceid; ?>">
                    <div class="for gotpass-main">
                        <div class="form-group">
                            <div id="upload-demo" ></div>
                        </div>
                        <div class="form-group">
                            <input name="photoimg" accept=".jpg, .jpeg, .png" type="file" id="upload" required class="form-control with-preview inputbg"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="videolink" id="videolink" class="form-control text-align-center inputbg" placeholder="Website URL" id="">
                        </div>
                        <div class="form-group">
                            <select name="pcountry" id="pcountry" required class="form-control inputbg">
                                <option value="">-Select Country-</option>
                                <?php
                                $country_query = mysql_query("SELECT * FROM `freelancer_mmv_countries` ORDER BY `freelancer_mmv_countries`.`countries_id` ASC");
                                while ($country_res = mysql_fetch_array($country_query)) {
                                    $selcountryid = $country_res[countries_id];
                                    ?>
                                    <option value="<?php echo $selcountryid ?>"><?php echo $country_res[countries_name]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4" style="">
                        </div>
                        <div class="form-group">
                            <button class="button loginbtn upload-result">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            </form>
        </div>
        <?php if ($loginid) { ?>
            <style>
                .fancybox-slide {
                    position: absolute;
                    top: -40px !important;
                }
            </style>
            <a data-fancybox="" data-type="inline" data-src="#videoPopup" href="javascript:void(0);" class="videoPopupLink none"></a>
            <div id="videoPopup" class="popupbox text-align-center">
                <div class="login-main" style="padding: 10px 45px !important;
                     top: 6px;
                     position: relative;">
                    <form name="login" method="post" action="submitvideo.php" enctype="multipart/form-data">
                        <input type="hidden" name="freelanceserviceid" id="freelanceserviceid" value="<?php echo $freelanceserviceid; ?>">
                        <div class="for gotpass-main">
                            <div class="form-group">
                                <input name="videoimg" accept=".mp4,.mov,.MOV,.MP4" type="file" onchange="checkFileDuration()" id="videoimg" required class="form-control with-preview inputbg"/>
                            </div>
                            <div class="form-group">
                                <!--<input type="text" name="videolink" class="form-control text-align-center inputbg" placeholder="Website URL" id="">-->
                                <textarea style="height:300px;" maxlength="800" name="description" id="description_textarea" class="form-control text-align-center inputbg" placeholder="Say something about this video"></textarea>
                            </div>
                            <div class="form-group">
                                <select style="
                                        font-size: 15px;
                                        height: 60px;
                                        "  name="pcountry" id="pcountry" required class="form-control inputbg">
                                    <option value="">Select Country</option>
                                    <?php
                                    $country_query = mysql_query("SELECT * FROM `freelancer_mmv_countries` ORDER BY `freelancer_mmv_countries`.`countries_id` ASC");
                                    while ($country_res = mysql_fetch_array($country_query)) {
                                        $selcountryid = $country_res[countries_id];
                                        ?>
                                        <option value="<?php echo $selcountryid ?>"><?php echo $country_res[countries_name]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submitvideo" class="button loginbtn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#alertPopup" href="javascript:void(0);" class="alertactivated none"></a>
<div class="popbox">
    <div id="alertPopup" class="popupbox text-align-center abuseOption" style="background:white;">
        <form name="abuse" method="post" action="">
            <input type="hidden" name="postid" id="postid" value=""/>

            <p style="color:#00b159; font-weight:bold;">Account has been activated</p>
        </form>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#invalidPass" href="javascript:void(0);" class="alertPopupLink none"></a>
<div class="popbox">
    <div id="invalidPass" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Wrong Password!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#invalidcred" href="javascript:void(0);" class="accinactive none"></a>
<div class="popbox">
    <div id="invalidcred" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Wrong Email address/account Inactive!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#uploadfailed" href="javascript:void(0);" class="uploadfail none"></a>
<div class="popbox">
    <div id="uploadfailed" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Upload failed!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#maxsizelimit" href="javascript:void(0);" class="maxsize none"></a>
<div class="popbox">
    <div id="maxsizelimit" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Max file size is 2 MB!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#invitation" href="javascript:void(0);" class="invi none"></a>
<div class="popbox">
    <div id="invitation" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#00b159; font-weight:bold;">Invited Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#success" href="javascript:void(0);" class="success none"></a>
<div class="popbox">
    <div id="success" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#00b159; font-weight:bold;">Posted Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#failed" href="javascript:void(0);" class="failedPopupLink none"></a>
<div class="popbox">
    <div id="failed" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Invalid action performs. Please login first and apply the same action</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#chooseone" href="javascript:void(0);" class="chooseone none"></a>
<div class="popbox">
    <div id="chooseone" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Please choose at least one!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#passsuccess" href="javascript:void(0);" class="passsuccess none"></a>
<div class="popbox">
    <div id="passsuccess" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#00b159; font-weight:bold;">Password Changed Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#oldpassnotmatch" href="javascript:void(0);" class="oldpassnotmatch none"></a>
<div class="popbox">
    <div id="oldpassnotmatch" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Old password do not match!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#passnotmatch" href="javascript:void(0);" class="passnotmatch none"></a>
<div class="popbox">
    <div id="passnotmatch" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Confirm password do not match!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#posted" href="javascript:void(0);" class="posted none"></a>
<div class="popbox">
    <div id="posted" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#00b159; font-weight:bold;">Posted Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#fail" href="javascript:void(0);" class="fail none"></a>
<div class="popbox">
    <div id="fail" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Payment failed!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#postfail" href="javascript:void(0);" class="postfail none"></a>
<div class="popbox">
    <div id="postfail" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Something went wrong!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#alreadyset" href="javascript:void(0);" class="alreadyset none"></a>
<div class="popbox">
    <div id="alreadyset" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Already meeting scheduled with in this Date and Time!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#atleastone" href="javascript:void(0);" class="atleastone none"></a>
<div class="popbox">
    <div id="atleastone" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Please select at least one user!</p>
    </div>S
</div>

<a data-fancybox="" data-type="inline" data-src="#delete" href="javascript:void(0);" class="delete none"></a>
<div class="popbox">
    <div id="delete" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#00b159; font-weight:bold;">Data deleted successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#emailnotmatch" href="javascript:void(0);" class="emailnotmatch none"></a>
<div class="popbox">
    <div id="emailnotmatch" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Email Address not matching with the records!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#regsuccess" href="javascript:void(0);" class="alertregsuccess none"></a>
<div class="popbox">
    <div id="regsuccess" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#00b159; font-weight:bold;">Account activation link has been sent to your registered Email  Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#invalidfile" href="javascript:void(0);" class="alertinvalidfile none"></a>
<div class="popbox">
    <div id="invalidfile" class="popupbox text-align-center abuseOption" style="background:white;">

        <p style="color:#e11e26; font-weight:bold;">Invalid file!</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#invitaionaccepted" href="javascript:void(0);" class="alertinvitaionaccepted none"></a>
<div class="popbox">
    <div id="invitaionaccepted" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Accepted Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#psuccess" href="javascript:void(0);" class="psuccess none"></a>
<div class="popbox">
    <div id="psuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Posted Successfully</p>
    </div>
</div>
<a data-fancybox="" data-type="inline" data-src="#delsuccess" href="javascript:void(0);" class="delsuccess none"></a>
<div class="popbox">
    <div id="delsuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#e11e26; font-weight:bold;">Deleted Successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#abusesuccess" href="javascript:void(0);" class="abusesuccess none"></a>
<div class="popbox">
    <div id="abusesuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Reported successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#isuccess" href="javascript:void(0);" class="isuccess none"></a>
<div class="popbox">
    <div id="isuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Send successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#usuccess" href="javascript:void(0);" class="usuccess none"></a>
<div class="popbox">
    <div id="usuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Edited successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#pupdate" href="javascript:void(0);" class="pupdate none"></a>
<div class="popbox">
    <div id="pupdate" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Profile updated successfully</p>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#vsuccess" href="javascript:void(0);" class="vsuccess none"></a>
<div class="popbox">
    <div id="vsuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
        <p style="color:#00b159; font-weight:bold;">Uploaded successfully</p>
    </div>
</div>

<!--<a data-fancybox="" data-type="inline" data-src="#updateprof" href="javascript:void(0);" class="alertupdateprof none"></a>
<div class="popbox">
        <div id="updateprof" class="popupbox text-align-center abuseOption">
                <h2>Alert!</h2>
                <p>Please update current location in notification page to refresh your location</p>
        </div>
</div>-->

<script>
    $(function () {
        $('.info_link').click(function () {
            alert($(this).attr('href'));
            // or alert($(this).hash();
        });
    });
</script>

<script src="js/jquery.js"></script>
<script src="js/croppie.js"></script>
<!--<link rel="stylesheet" href="css/bootstrap-3.min.css">-->
<link rel="stylesheet" href="css/croppie.css">
<script type="text/javascript">
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: 300,
            height: 300,
        },
        boundary: {
            width: 350,
            height: 350
        }
    });
    $('#upload').on('change', function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
    });
    $('.upload-result').on('click', function (ev) {
        var video = $('#videolink').val();
        var countr = $('#pcountry').val();
        var freelanceserviceid = $('#freelanceserviceid').val();
        var data;
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {
            $.ajax({
                url: "ajaxpro.php",
                type: "POST",
                data: {"image": resp, "video": video, "pcountry": countr, "freelanceserviceid": freelanceserviceid},
                //dataType: "json",
                async: false,
                success: function (data) {
                    if (data == 'nocharge') {
                        window.location = "index.php?status=success";
                        return false;
                    } else {
                        myFunction(data);
                    }
                }
            });
        });
    });
</script>

<script type="text/javascript">
    $(".reviewPopupBtn").on('click', function () {
        $.fancybox.open({
            src: '#reviewPopup',
            type: 'inline',
            opts: {
                afterShow: function (instance, current) {
                    console.info('done!');
                }
            }
        });
    });
</script>
<script>
    function myFunction(data) {
        window.location.href = "paypal.php?type=1&uid=<?php echo $loginid; ?>&imgid=" + data;
        alert('Please wait..!!');
    }
    function myFunction2() {
        window.location.href = "index.php?status=success";
    }
    $("#selectcountry").change(function () {
        var country = $("#selectcountry").val();
        $.ajax({
            type: "POST",
            data: "id=" + country,
            url: "backend.php",
            success: function (data)
            {
                location.reload();
            }
        });
    });
    /*$(document).ready(function() {
     $('#post-photo-upload').on('change', function(){
     $("#imageform").ajaxForm({
     success: function() {
     myFunction();
     //location.reload();
     } ,
     error: function() {
     alert('Upload failed!!');
     }
     
     }).submit();
     });
     });*/
    /*$(document).ready(function() {
     $('#post-video-upload').on('change', function(){
     $("#imageform").ajaxForm({
     success: function(msg) {
     alert(msg);
     if(msg=='Max file size is 2 MB'){
     return false;
     } else if(msg=='Invalid file format..')	{
     return false;
     } else if(msg=='Failed'){
     return false;
     } else {
     //alert('Video Uploaded!!');
     myFunction();
     location.reload();
     }
     
     } ,
     error: function() {
     alert('Upload failed!!');
     }
     
     }).submit();
     });
     });*/
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="js/rating.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/fontsmoothie.min.js"></script>
<script type="text/javascript" src="js/jquery.selectric.js"></script>
<script type="text/javascript" src="js/file-upload.js"></script>
<script src="upload/jquery.form.js" type="text/javascript" language="javascript"></script>
<script src="upload/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
<script src="upload/jquery.MultiFile.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" src="lightbox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--<script type="text/javascript" src="js/geoLocation.js"></script>-->
<script>
    $(window).load(function () {
        "use strict";
        adjustWidthHeight();
<?php if ($_REQUEST[status] == 'activated') { ?>
            $('.alertactivated').trigger('click');
            setTimeout(function () {
                $('.loginPopupLink').trigger('click');
            }, 5000);
<?php } else if ($_REQUEST[status] == 'invalidpass') { ?>
            $('.alertPopupLink').trigger('click');
            setTimeout(function () {
                $('.loginPopupLink').trigger('click');
            }, 3000);
<?php } elseif ($_REQUEST[status] == 'failed') { ?>
            $('.failedPopupLink').trigger('click');
            /* setTimeout(function() {
             $('.failedPopupLink').trigger('click');
             }, 3000); */
<?php } else if ($_REQUEST[status] == 'invalidcred') { ?>
            $('.accinactive').trigger('click');
            setTimeout(function () {
                $('.loginPopupLink').trigger('click');
            }, 3000);
<?php } else if ($_REQUEST[status] == 'uploadfail') { ?>
            $('.uploadfail').trigger('click');
<?php } else if ($_REQUEST[status] == 'maxsize') { ?>
            $('.maxsize').trigger('click');
<?php } else if ($_REQUEST[status] == 'invited') { ?>
            $('.invi').trigger('click');
<?php } else if ($_REQUEST[status] == 'success') { ?>
            $('.success').trigger('click');
<?php } else if ($_REQUEST[status] == 'chooseone') { ?>
            $('.chooseone').trigger('click');
<?php } else if ($_REQUEST[status] == 'passsuccess') { ?>
            $('.passsuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'oldpassnotmatch') { ?>
            $('.oldpassnotmatch').trigger('click');
<?php } else if ($_REQUEST[status] == 'passnotmatch') { ?>
            $('.passnotmatch').trigger('click');
<?php } else if ($_REQUEST[status] == 'posted') { ?>
            $('.posted').trigger('click');
<?php } else if ($_REQUEST[status] == 'fail') { ?>
            $('.fail').trigger('click');
<?php } else if ($_REQUEST[status] == 'postfail') { ?>
            $('.postfail').trigger('click');
<?php } else if ($_REQUEST[status] == 'alreadyset') { ?>
            $('.alreadyset').trigger('click');
<?php } else if ($_REQUEST[status] == 'atleastone') { ?>
            $('.atleastone').trigger('click');
<?php } else if ($_REQUEST[status] == 'delete') { ?>
            $('.delete').trigger('click');
<?php } else if ($_REQUEST[status] == 'emailnotmatch') { ?>
            $('.emailnotmatch').trigger('click');
<?php } else if ($_REQUEST[status] == 'regsuccess') { ?>
            $('.alertregsuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'invalidfile') { ?>
            $('.alertinvalidfile').trigger('click');
<?php } else if ($_REQUEST[status] == 'updateprof') { ?>
            $('.alertupdateprof').trigger('click');
<?php } else if ($_REQUEST[status] == 'jobdelete') { ?>
            $('.alertjobdeleted').trigger('click');
<?php } else if ($_REQUEST[status] == 'invitaionaccepted') { ?>
            $('.alertinvitaionaccepted').trigger('click');
<?php } else if ($_REQUEST[status] == 'psuccess') { ?>
            $('.psuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'delsuccess') { ?>
            $('.delsuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'abusesuccess') { ?>
            $('.abusesuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'isuccess') { ?>
            $('.isuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'usuccess') { ?>
            $('.usuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'pupdate') { ?>
            $('.pupdate').trigger('click');
<?php } else if ($_REQUEST[status] == 'fsuccess') { ?>
            $('.abusesuccess').trigger('click');
<?php } else if ($_REQUEST[status] == 'vsuccess') { ?>
            $('.vsuccess').trigger('click');
<?php } ?>
    });
</script>
<script type="text/javascript">
    function check_val() {
        var wordInput = document.getElementById("text").value.toLowerCase();
        var wordInput1 = document.getElementById("texts").value.toLowerCase();
        var wordInput2 = document.getElementById("textss").value.toLowerCase();
        // split the words by spaces (" ")
        var arr = wordInput.split(" ");
        var arr1 = wordInput1.split(" ");
        var arr2 = wordInput2.split(" ");
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
//video size
//reference
//https://developer.mozilla.org/en-US/docs/Web/API/FileReader/
//http://community.sitepoint.com/t/get-video-duration-before-upload/30623/4
//set your time on MaxTime like minutes:seconds
//if you wanna hours just replace below line on line 25
//var time = hours+':'minutes + ':' + seconds;
//maxTime = "01:00:00"; //if add a hours
    var videoMaxTime = "01:00"; //minutes:seconds   //video
    var audioMaxTime = "05:00"; //minutes:seconds   //audio
    var uploadMax = 3145728000; //bytes
//for seconds to time
    function secondsToTime(in_seconds) {
        var time = '';
        in_seconds = parseFloat(in_seconds.toFixed(2));
        var hours = Math.floor(in_seconds / 3600);
        var minutes = Math.floor((in_seconds - (hours * 3600)) / 60);
        var seconds = in_seconds - (hours * 3600) - (minutes * 60);
        //seconds = Math.floor( seconds );
        seconds = seconds.toFixed(0);
        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        var time = minutes + ':' + seconds;
        return time;
    }
    function checkFileDuration() {
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();
        var fileSize = file.size;
        if (file.type == "image/jpg" || file.type == "image/jpeg" || file.type == "image/png") {
            alert('Only Videos are allowed to upload!!');
            $('#videoimg').val('');
        } else {
            if (fileSize > uploadMax) {
                alert('file too large');
                $('#vid').val("");
            } else {
                $('#pross').show();
                reader.onload = function (e) {
                    if (file.type == "video/mp4" || file.type == "video/MOV" || file.type == "video/mov") {
                        var videoElement = document.createElement('video');
                        videoElement.src = e.target.result;
                        var timer = setInterval(function () {
                            if (videoElement.readyState === 4) {
                                getTime = secondsToTime(videoElement.duration);
                                if (getTime > videoMaxTime) {
                                    alert('1 minutes video only')
                                    $('#vid').val("");
                                }
                                $('#pross').hide();
                                clearInterval(timer);
                            }
                        }, 500)
                    } else if (file.type == "audio/mpeg" || file.type == "audio/wav" || file.type == "audio/ogg") {
                        var audioElement = document.createElement('audio');
                        audioElement.src = e.target.result;
                        var timer = setInterval(function () {
                            if (audioElement.readyState === 4) {
                                getTime = secondsToTime(audioElement.duration);
                                if (getTime > audioMaxTime) {
                                    alert('1 minutes audio only')
                                    $('#vid').val("");
                                }
                                $('#pross').hide();
                                clearInterval(timer);
                            }
                        }, 500)
                    } else {
                        var timer = setInterval(function () {
                            if (file) {
                                //alert('invaild File')
                                $('#vid').val("");
                                $('#pross').hide();
                                clearInterval(timer);
                            }
                        }, 500)
                    }
                };
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    alert('nofile');
                }
            }
        }
    }
    function getLocation() {
        var msg;
        /**
         first, test for feature support
         **/
        if ('geolocation' in navigator) {
            // geolocation is supported :)
<?php if (($_SESSION[allowLocation] != "")) { ?>
                requestLocation();
<?php } ?>
        } else {
            // no geolocation :(
            msg = "Sorry, looks like your browser doesn't support geolocation";
            outputResult(msg); // output error message
            $('.pure-button').removeClass('pure-button-primary').addClass('pure-button-success'); // change button style
        }
        /***
         requestLocation() returns a message, either the users coordinates, or an error message
         **/
        function requestLocation() {
            /**
             getCurrentPosition() below accepts 3 arguments:
             a success callback (required), an error callback  (optional), and a set of options (optional)
             **/
            var options = {
                // enableHighAccuracy = should the device take extra time or power to return a really accurate result, or should it give you the quick (but less accurate) answer?
                enableHighAccuracy: false,
                // timeout = how long does the device have, in milliseconds to return a result?
                timeout: 5000,
                // maximumAge = maximum age for a possible previously-cached position. 0 = must return the current position, not a prior cached position
                maximumAge: 0
            };
            // call getCurrentPosition()
            navigator.geolocation.getCurrentPosition(success, error, options);
            navigator.geolocation.watchPosition(function (position) {
                console.log("i'm tracking you!");
<?php $_SESSION['allowLocation'] = 1; ?>
                console.log(<?php echo $_SESSION['allowLocation'] ?>);
            },
                    function (error) {
                        if (error.code == error.PERMISSION_DENIED)
                            console.log("you denied me :-(");
                    });
            // upon success, do this
            function success(pos) {
                // get longitude and latitude from the position object passed in
                var lng = pos.coords.longitude;
                var lat = pos.coords.latitude;
                // and presto, we have the device's location!
                msg = 'You appear to be at longitude: ' + lng + ' and latitude: ' + lat + '<img src="https://maps.googleapis.com/maps/api/staticmap?zoom=15&size=300x300&maptype=roadmap&markers=color:red%7Clabel:A%7C' + lat + ',' + lng + '&sensor=false">';
                outputResult(msg); // output message
                $('.pure-button').removeClass('pure-button-primary').addClass('pure-button-success'); // change button style
                document.getElementById("lats").value = lat;
                document.getElementById("long").value = lng;
                document.cookie = "mylatitude=" + lat;
                document.cookie = "mylongitude=" + lng;
            }
            // upon error, do this
            function error(err) {
                // return the error message
                msg = 'Error: ' + err + ' :(';
                outputResult(msg); // output button
                $('.pure-button').removeClass('pure-button-primary').addClass('pure-button-error'); // change button style
            }
        } // end requestLocation();
        /***
         outputResult() inserts msg into the DOM
         **/
        function outputResult(msg) {
            $('.result').addClass('result').html(msg);
            //alert(msg);
        }
    } // end getLocation()
// attach getLocation() to button click
    $('.pure-button').on('click', function () {
        // show spinner while getlocation() does its thing
        $('.result').html('<i class="fa fa-spinner fa-spin"></i>');
    });
    getLocation();
</script>
<script type="text/javascript">
    $(window).load(function () {
        $(".loader").fadeOut("slow");
    });
    $('img').bind('contextmenu', function (e) {
        return false;
    });
    $('.sub-link-list a').click(function (e) {
        var sid = $(this).attr('id');
        $.ajax({
            url: "getsubfilterval.php",
            type: "POST",
            data: "subcategoryId=" + sid,
            success: function (response) {
                //alert(response);
                $("#jqval").html(response);
            },
        });
    });
</script>
<script type="text/javascript" src="date-time/anypicker.js"></script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#ip-ios").AnyPicker(
                {
                    mode: "datetime",
                    dateTimeFormat: "d MMMM yyyy hh:mm AA",
                    inputChangeEvent: "onChange",
                    onChange: function (iRow, iComp, oSelectedValues)
                    {
                        console.log("Changed Value : " + iRow + " " + iComp + " " + oSelectedValues);
                    },
                    theme: "iOS" // "Default", "iOS", "Android", "Windows"
                });
    });
    if (window.location.href.indexOf("#") > -1) {
        var type = window.location.hash.substr(1);
        console.log(type)
        if (type == 'login') {
            $('#login_btn').trigger("click");
        } else if (type == 'videoPopup') {
            $('.videoPopupLink').trigger('click');
        }
    }

    $(document).on('click', '.loginpopup', function () {
        var redirection = $(this).attr('data-type');
        $('#login_redirection').val(redirection)
        $('.loginPopupLink').trigger('click');
    })
    $(document).on('keyup', '#description_textarea', function () {
        var ch = $(this).val();
        if (ch != '') {
            var filter = /[a-zA-Z]/;
            if (!filter.test(ch)) {
                $(this).val('');
                alert('Please use English language.');
            }
        }
    })
</script>
<?php
$url = "https://" . $_SERVER[HTTP_HOST] . strtok($_SERVER["REQUEST_URI"], '?');
if (isset($_POST[destroy])) {
    unset($_SESSION['SESS_CAT_ID']);
    unset($_SESSION['SESS_SUBCAT_ID']);
    unset($_SESSION['SESS_CAT_NAME']);
    unset($_SESSION['countryid']);
    if (strpos($pagename, 'listing.php') == 'listing.php' || strpos($pagename, 'hire.php') == 'hire.php' || strpos($pagename, 'index.php') == 'index.php') {
        $actual_link = $url;
    } else {
        $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    echo "<script>window.location='$actual_link'</script>";
}
?>
</body>
</html>