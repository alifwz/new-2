<?php
session_start();
include "connection.php";
include "header.php";
include "auth.php";
$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: index.php#login");
    die;
}


$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$loginid'");
$res = mysql_fetch_array($query);

function compress_image($source_url, $destination_url, $quality) {

    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

if (isset($_POST['profile'])) {
    $firstname = mysql_real_escape_string($_POST['firstname']);
    $lastname = mysql_real_escape_string($_POST['lastname']);
    $country = mysql_real_escape_string($_POST['country']);
    $area = mysql_real_escape_string($_POST['area']);
    $nationality = mysql_real_escape_string($_POST['nationality']);
    $gender = mysql_real_escape_string($_POST['gender']);
    $education = mysql_real_escape_string($_POST['education']);
    $degree = mysql_real_escape_string($_POST['degree']);
    $expsector = mysql_real_escape_string($_POST['expsector']);
    $subexpsector = mysql_real_escape_string($_POST['subexpsector']);
    $jobtitle = mysql_real_escape_string($_POST['jobtitle']);
    $hobby = mysql_real_escape_string($_POST['hobby']);
    $sport = mysql_real_escape_string($_POST['sport']);
    $faith = mysql_real_escape_string($_POST['faith']);
    $mbti = mysql_real_escape_string($_POST['mbti']);
    $freelanceservice = mysql_real_escape_string($_POST['freelanceservice']);
    ;
    $timing = mysql_real_escape_string($_POST['timing']);
    $talentandexp = mysql_real_escape_string($_POST['talentandexp']);
    $expsectornew = mysql_real_escape_string($_POST['expsectornew']);
    $pimage = $_FILES['profilpic']['name'];

    if ($pimage != "") {
        //Check Rename
        $name = pathinfo($_FILES['profilpic']['name'], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES['profilpic']['name'], PATHINFO_EXTENSION);
        $ext = strtolower(pathinfo($_FILES['profilpic']['name'], PATHINFO_EXTENSION));
        $increment = '';
        $newfilename = 'meet' . date('YHidsm') . round(microtime(true));

        if (($ext == "png") || ($ext == "jpeg") || ($ext == "jpg")) {
            //start with no suffix
            while (file_exists("uploads/users/" . $name . $increment . '.' . $extension)) {
                $increment++;
            }
            $basename = $newfilename . $increment . '.' . $extension;
            $blocation = $basename;
            $target_file = "uploads/users/" . $basename;

            $filename = $_FILES['profilpic']['name'];
            $filePath = $_FILES['profilpic']['tmp_name'];
            $exif = exif_read_data($_FILES['profilpic']['tmp_name']);

            if (!empty($exif['Orientation'])) {
                $imageResource = imagecreatefromjpeg($filePath);
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($imageResource, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($imageResource, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($imageResource, 90, 0);
                        break;
                    default:
                        $image = $imageResource;
                }
                imagejpeg($image, "uploads/users/" . $filename, 90);

                $login_query = mysql_query("UPDATE freelancer_mmv_member_master SET image='$filename' WHERE member_id='$loginid'");
            } else {
                if (compress_image($_FILES['profilpic']['tmp_name'], $target_file, 40)) {
                    $login_query = mysql_query("UPDATE freelancer_mmv_member_master SET image='$blocation' WHERE member_id='$loginid'");
                }
            }

            //if (compress_image($_FILES['profilpic']['tmp_name'], $target_file,40)) {		
            //$login_query = mysql_query("UPDATE freelancer_mmv_member_master SET image='$blocation' WHERE member_id='$loginid'");			
            //} 
        } else {
            header("location:profile.php?msg=invalidext");
        }
    }

    $pppimage = $_FILES['files']['name'];

    if ($pppimage != "") {
        foreach ($_FILES['files']['name'] as $key => $val) {

            //Check Rename
            $name1 = pathinfo($_FILES['files']['name'][$key], PATHINFO_FILENAME);
            $extension1 = pathinfo($_FILES['files']['name'][$key], PATHINFO_EXTENSION);
            $increment1 = '';
            //start with no suffix
            while (file_exists("uploads/images/" . $name1 . $increment1 . '.' . $extension1)) {
                $increment1++;
            }
            $basename1 = $name1 . $increment1 . '.' . $extension1;
            $blocation1 = "uploads/images/" . $basename1;
            $target_file1 = "uploads/images/" . $basename1;

            if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $target_file1)) {
                $login_query1 = mysql_query("INSERT INTO freelancer_mmv_userimages (id, userid, image,type) VALUES ('', '$loginid', '" . $blocation1 . "','1')");
            }
        }
    }

    $query2 = mysql_query("UPDATE freelancer_mmv_member_master SET first_name='$firstname',last_name='$lastname',country='$country',nationality='$nationality',education='$education',gender='$gender',expsector='$expsector',subexpsector='$subexpsector',jobtitle='$jobtitle',hobby='$hobby',sporttoparticipate='$sport',faith='$faith',mbti='$mbti',freelance='$freelanceservice',freelancetiming='$timing',talentandexp='$talentandexp',degree='$degree',area='$area',expsectornew='$expsectornew',firstlogin='1' WHERE member_id='$loginid'");

    if ($query2 == '1' || $login_query == '1' || $login_query1 == '1') {
        echo "<script>window.location='profile.php?status=pupdate'</script>";
    }
}
?>
<style>
    .img-wrap {
        position: relative;
        display: block;
        border: 1px red solid;
        font-size: 0;
    }
    .img-wrap .close {
        position: absolute;
        top: 2px;
        right: 2px;
        z-index: 100;
        background-color: #FFF;
        padding: 5px 2px 2px;
        color: #000;
        font-weight: bold;
        cursor: pointer;
        opacity: .2;
        text-align: center;
        font-size: 22px;
        line-height: 10px;
        border-radius: 50%;
    }
    .img-wrap:hover .close {
        opacity: 1;
    }
</style>
<!--start main-->
<div class="main">		
    <section class="contenets-main">
        <!--start user-->
        <form name="profile" method="post" enctype="multipart/form-data" action="">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="user-main">
                            <div class="user-div uploader">
                                <div class="user-img setSize" >
                                    <?php if ($res[image] == "") { ?>
                                        <img src="images/user.png" alt="user"/>
                                    <?php } else { ?>
                                        <div class="photograph">
                                            <img src="uploads/users/<?php echo $res[image] ?>" alt="user"/>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="fileContainer"></div>
                                <input name="profilpic" type="file" id="change-photo" maxlength="1" class="multi with-preview" />
                                <!--<a href="javascript:void(0);" class="plus-sign">+</a>-->
                            </div>
                        </div>
                        <!--<h2 class="user-title text text-align-center"><?php echo $res[first_name] . ' ' . $res[last_name] ?></h2>-->
                        <p>&nbsp;</p>
                    </div>
                </div>
                <div class="row user-profile user-profile-section">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" name="firstname" id="text" value="<?php echo $res[first_name] ?>" required pattern="[a,A-z,Z]{3,15}" class="form-control" maxlength="15"
                                           oninput="setCustomValidity(''); checkValidity(); if (validity.patternMismatch) { setCustomValidity('Not allowed Spaces, numbers, etc..'); } else if (validity.valueMissing) { setCustomValidity('This field is required.'); } else if (validity.valid) { setCustomValidity(''); }" placeholder="First Name *" >							
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" name="lastname" maxlength="15" id="texts" value="<?php echo $res[last_name] ?>" required pattern="[a,A-z,Z]{3,15}" class="form-control" title="Enter Last Name"
                                           oninput="setCustomValidity(''); checkValidity(); if (validity.patternMismatch) { setCustomValidity('Not allowed Spaces, numbers, etc..'); } else if (validity.valueMissing) { setCustomValidity('This field is required.'); } else if (validity.valid) { setCustomValidity(''); }" placeholder="Last Name *" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select id="country" required oninvalid="this.setCustomValidity('Please select Country (Residence)')" oninput="setCustomValidity('')" name="country" class="form-control" style="font-size:14px;">
                                        <option value="" selected="selected" >Country (Residence)*</option>
                                        <?php
                                        $dbcid = $res[country];
                                        $country_query = mysql_query("SELECT * FROM `freelancer_mmv_countries` ORDER BY `freelancer_mmv_countries`.`countries_id` ASC");
                                        while ($country_res = mysql_fetch_array($country_query)) {
                                            $cid = $country_res[countries_id];
                                            ?>
                                            <option <?php
                                            if ($cid == $dbcid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $country_res[countries_id]; ?>"><?php echo $country_res[countries_name]; ?></option>
                                            <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <!--<div class="col-6">
                                    <div class="select-box select-style">
                                            <select id="nationality" title="Select Nationality" required name="nationality" class="form-control" oninvalid="this.setCustomValidity('Please select Nationality')" oninput="setCustomValidity('')">
                                                    <option value="" selected="selected">Nationality</option>									
                            <?php
                            $dbnid = $res[nationality];
                            $country_query = mysql_query("SELECT * FROM `freelancer_mmv_countries` ORDER BY `freelancer_mmv_countries`.`countries_id` ASC ");
                            while ($country_res = mysql_fetch_array($country_query)) {
                                $nid = $country_res[countries_id];
                                ?>
                                                                            <option <?php
                                if ($nid == $dbnid) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $country_res[countries_id]; ?>"><?php echo $country_res[countries_name]; ?></option>
                            <?php } ?>
                                            </select>
                    </div>
                            </div>-->
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select id="nationality" title="Select Nationality" required name="nationality" class="form-control" oninvalid="this.setCustomValidity('Please select Nationality')" oninput="setCustomValidity('')" style="font-size:14px;">
                                        <option value="" selected="selected">Nationality*</option>									
                                        <?php
                                        $dbnid = $res[nationality];
                                        $country_query = mysql_query("SELECT * FROM freelancer_mmv_nationalities Order By nationality_id asc ");
                                        while ($country_res = mysql_fetch_array($country_query)) {
                                            $nid = $country_res[nationality_id];
                                            ?>
                                            <option <?php
                                            if ($nid == $dbnid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $country_res[nationality_id]; ?>"><?php echo $country_res[nationality_name]; ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="education" id="education" class="form-control" style="font-size:14px;">
                                        <option value="0">Education</option>
                                        <?php
                                        $dbeid = $res[education];
                                        $edu_query = mysql_query("SELECT * FROM freelancer_mmv_education");
                                        while ($edu_res = mysql_fetch_array($edu_query)) {
                                            $eid = $edu_res[id];
                                            ?>
                                            <option <?php
                                            if ($eid == $dbeid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $edu_res[id]; ?>"><?php echo $edu_res[title]; ?></option>
                                            <?php } ?>									
                                    </select>
                                </div>
                            </div> 

                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="degree" id="degree" class="form-control" style="font-size:14px;" >								 
                                        <option value="0" selected="selected">Degree</option>									
                                        <?php
                                        $ddeid = $res[degree];
                                        $degre_query = mysql_query("SELECT * FROM freelancer_mmv_degree");
                                        while ($degree_res = mysql_fetch_array($degre_query)) {
                                            $did = $degree_res[id];
                                            ?>
                                            <option <?php
                                            if ($did == $ddeid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $degree_res[id]; ?>"><?php echo $degree_res[title]; ?></option>
                                            <?php } ?>	
                                    </select>
                                </div>
                            </div>				

                        </div>
                        <div class="row">	
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="expsector" required oninvalid="this.setCustomValidity('Please select Freelance Service')" oninput="setCustomValidity('')" id="expsector" class="form-control" onchange="showsubcat(this.value);" style="font-size:14px;">
                                        <option value="">Freelance Service*</option>
                                        <?php
                                        $expid = $res[expsector];
                                        $exp_query = mysql_query("SELECT * FROM freelancer_mmv_filter WHERE status='1' AND parent_id='0'");
                                        while ($exp_res = mysql_fetch_array($exp_query)) {
                                            $dbexpid = $exp_res[filter_id];
                                            ?>
                                            <option <?php
                                            if ($dbexpid == $expid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $exp_res[filter_id]; ?>"><?php echo $exp_res[title]; ?></option>
                                            <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">						
                                <div class="select-box select-style">
                                    <select name="subexpsector" required="" oninvalid="this.setCustomValidity('Please Select Freelance sub Service')" oninput="setCustomValidity('')" id="subexpsector" class="form-control" style="font-size:14px;">
                                        <option value="">Select SubCategory*</option>
                                        <?php
                                        $subcat = $res[subexpsector];
                                        $subcat_query = mysql_query("SELECT * from freelancer_mmv_filter where parent_id='$expid'");
                                        while ($subcat_res = mysql_fetch_array($subcat_query)) {
                                            $ssidi = $subcat_res['filter_id'];
                                            ?>
                                            <option <?php
                                            if ($ssidi == $subcat) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $subcat_res['filter_id'] ?>"><?php echo $subcat_res[title] ?></option>
                                                <?php
                                            }
                                            ?>							
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">

                                <input type="text" maxlength="25" name="timing" id="texts1" value="<?php echo $res[freelancetiming] ?>" class="form-control"  placeholder="Freelance Timing" style="font-size:14px;" >
                                        <!--<select name="timing" required id="timing">								 
                                                <option value="" selected="selected">Freelance Timing</option>
                                <?php
                                $freidtim = $res[freelancetiming];
                                $freeltim_query = mysql_query("SELECT * FROM freelancer_mmv_timings");
                                while ($freeltim_res = mysql_fetch_array($freeltim_query)) {
                                    $dbmftid = $freeltim_res[id];
                                    ?>
                                                                        <option <?php
                                    if ($dbmftid == $freidtim) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $freeltim_res[id]; ?>"><?php echo $freeltim_res[title]; ?></option>
                                <?php } ?>
                                        </select>-->

                            </div>
                            <div class="col-6">

                                <input type="text" maxlength="25" name="jobtitle" id="texts2" value="<?php echo $res[jobtitle] ?>" class="form-control" placeholder="Job Title" pattern="[A-Za-z\s]+" style="font-size:16px;" 
                                       oninput="setCustomValidity(''); checkValidity(); if (validity.patternMismatch) { setCustomValidity('Not allowed numbers, underscores, etc…'); } else if (validity.valueMissing) { setCustomValidity('This field is required.'); } else if (validity.valid) { setCustomValidity(''); }">								

                            </div>						
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="hobby" id="hobby" class="form-control" required="" oninvalid="this.setCustomValidity('Please select Hobby')" oninput="setCustomValidity('')" style="font-size:14px;">
                                        <option value="" selected="selected">Hobby*</option>
                                        <?php
                                        $hid = $res[hobby];
                                        $hob_query = mysql_query("SELECT * FROM freelancer_mmv_hobby");
                                        while ($hob_res = mysql_fetch_array($hob_query)) {
                                            $dbhid = $hob_res[id];
                                            ?>
                                            <option <?php
                                            if ($dbhid == $hid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $hob_res[id]; ?>"><?php echo $hob_res[title]; ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="sport" id="sport" class="form-control" required="" oninvalid="this.setCustomValidity('Please select Sport to Participate')" oninput="setCustomValidity('')" style="font-size:14px;">							 
                                        <option value="" selected="selected" >Sport to Participate*</option>
                                        <?php
                                        $spid = $res[sporttoparticipate];
                                        $spo_query = mysql_query("SELECT * FROM freelancer_mmv_sport");
                                        while ($spo_res = mysql_fetch_array($spo_query)) {
                                            $dbspoid = $spo_res[id];
                                            ?>
                                            <option <?php
                                            if ($dbspoid == $spid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $spo_res[id]; ?>"><?php echo $spo_res[title]; ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                            </div>						
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="mbti" id="mbti" class="form-control" required oninvalid="this.setCustomValidity('Please select MBTI Personality')" oninput="setCustomValidity('')" style="font-size:14px;">							 
                                        <option value="" selected="selected">MBTI Personality*</option>
                                        <?php
                                        $mbid = $res[mbti];
                                        $mbit_query = mysql_query("SELECT * FROM freelancer_mmv_mbti");
                                        while ($mbit_res = mysql_fetch_array($mbit_query)) {
                                            $dbmbid = $mbit_res[id];
                                            ?>
                                            <option <?php
                                            if ($dbmbid == $mbid) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $mbit_res[id]; ?>"><?php echo $mbit_res[title]; ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <select name="gender" id="gender" class="form-control" style="font-size:14px;" >									 
                                        <option value="" selected="selected">Gender</option>
                                        <option <?php
                                        if ($res[gender] == 'Male') {
                                            echo "selected";
                                        }
                                        ?> value="Male">Male</option>
                                        <option <?php
                                        if ($res[gender] == 'Female') {
                                            echo "selected";
                                        }
                                        ?> value="Female">Female</option>
                                    </select>
                                </div>
                            </div>						
                        </div>					

                        <div class="row">

                            <!--<div class="col-6">
                                    <div class="select-box select-style">
                                    <input type="text" name="freelanceservice" id="texts3" value="<?php echo $res[freelance] ?>" required class="form-control" placeholder="Major" maxlength="25" oninvalid="this.setCustomValidity('Please enter Major')" oninput="setCustomValidity('')">
                                    </div>
                            </div>-->					


                            <div class="col-6">

                                <input type="text" name="area" id="texts4" maxlength="25" value="<?php echo $res[area] ?>" required class="form-control" placeholder="Area(of residence)*" pattern="[A-Za-z\s]+" style="font-size:14px;" 
                                       oninput="setCustomValidity(''); checkValidity(); if (validity.patternMismatch) { setCustomValidity('Not allowed numbers, underscores, etc…'); } else if (validity.valueMissing) { setCustomValidity('This field is required.'); } else if (validity.valid) { setCustomValidity(''); }" style="font-size:14px;">
                                                                            <!--<select name="timing" required id="timing">								 
                                                                                    <option value="" selected="selected">Freelance Timing</option>
                                <?php
                                $freidtim = $res[freelancetiming];
                                $freeltim_query = mysql_query("SELECT * FROM freelancer_mmv_timings");
                                while ($freeltim_res = mysql_fetch_array($freeltim_query)) {
                                    $dbmftid = $freeltim_res[id];
                                    ?>
                                                                                                            <option <?php
                                    if ($dbmftid == $freidtim) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $freeltim_res[id]; ?>"><?php echo $freeltim_res[title]; ?></option>
                                <?php } ?>
                                                                            </select>-->

                            </div>
                            <div class="col-6">
                                <div class="select-box select-style">
                                    <input type="text" name="faith" id="texts5" maxlength="25" value="<?php echo $res[faith] ?>" class="form-control" placeholder="Faith" pattern="[A-Za-z\s]+" style="font-size:14px;" 
                                           oninput="setCustomValidity(''); checkValidity(); if (validity.patternMismatch) { setCustomValidity('Not allowed numbers, underscores, etc…'); } else if (validity.valueMissing) { setCustomValidity('This field is required.'); } else if (validity.valid) { setCustomValidity(''); }" >
                                                                                <!--<select name="faith" id="faith" required>
                                                                                        <option value="" selected="selected">Faith</option>
                                    <?php
                                    $fid = $res[faith];
                                    $fai_query = mysql_query("SELECT * FROM freelancer_mmv_faith");
                                    while ($fai_res = mysql_fetch_array($fai_query)) {
                                        $dbfid = $fai_res[id];
                                        ?>
                                                                                                                <option <?php
                                        if ($dbfid == $fid) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $fai_res[id]; ?>"><?php echo $fai_res[title]; ?></option>
                                    <?php } ?>
                                                                                </select>-->
                                </div>
                            </div>	
                            <div class="col-6">
                                <div class="select-box select-style">							
                                    <select name="expsectornew"  id="expsectornew" class="form-control" style="font-size:14px;" >								 
                                        <option value="0" selected="selected">Experience Sector</option>
                                        <?php
                                        $freidtim = $res[expsectornew];
                                        $freeltim_query = mysql_query("SELECT * FROM freelancer_mmv_expsectornew");
                                        while ($freeltim_res = mysql_fetch_array($freeltim_query)) {
                                            $dbmftid = $freeltim_res[id];
                                            ?>
                                            <option <?php
                                            if ($dbmftid == $freidtim) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $freeltim_res[id]; ?>"><?php echo $freeltim_res[title]; ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>						
                    </div>
                    <div class="col-12">
                        <textarea name="talentandexp" id="textss" maxlength="250" class="select-box select-style talent-textarea" placeholder="Explain more about your talent and experience" ><?php echo $res[talentandexp]; ?></textarea>					
                    </div>				
                    <div class="col-12">
                        <div class="photo-video-main">
                            <label class="cv">Photo & Video</label>
                            <div class="photo-video gallery">
                                <?php
                                $img_query = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$loginid' AND status=1 ORDER BY id DESC");
                                while ($img_res = mysql_fetch_array($img_query)) {
                                    $extension2 = end(explode(".", $img_res[image]));
                                    if ($extension2 != "mp4") {
                                        ?>									
                                        <div class="photo-div">
                                            <div class="img-wrap">
                                                <span class="close">&times;</span>
                                                <a href="<?php echo $img_res[image]; ?>" data-fancybox="images">
                                                    <img src="<?php echo $img_res[image]; ?>" data-id="<?php echo $img_res[id] ?>" width="125px" height="125px" alt="photo"/>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } else { ?>											
                                        <div class="photo-div">
                                            <div class="img-wrap">
                                                <span class="close">&times;</span>
                                                <img data-id="<?php echo $img_res[id] ?>"/>
                                                <video width="125"  height="125" controls>
                                                    <source src="<?php echo $img_res[image]; ?>" type="video/mp4">
                                                    <source src="<?php echo $img_res[image]; ?>" type="video/ogg">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <!--<a href="https://www.youtube.com/embed/<?php echo $videoname ?>?enablejsapi=1&wmode=opaque" class="play-link video-link fancybox.iframe" data-fancybox="images">
                                                <img height="125px" src="http://img.youtube.com/vi/<?php echo $videoname ?>/mqdefault.jpg" data-id="<?php echo $img_res[id] ?>" alt="photo"/><img src="images/play-icon.png" class="play-icon" alt="photo"/></a>-->
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-12 uploader">
                              <div class="form-group photo-upload">						
                                    <div class="fileContainer"></div>
                                    <input name="files[]" type="file" multiple id="other-test" maxlength="2" class="multi with-preview" />
                              </div>
                    </div>-->
                    <div class="col-12">
                        <!--<button type="submit" name="profile" onClick="location.href='profile.php'" class="button">Submit</button>-->
                        <button type="submit" name="profile" onclick="return check_valprof();" class="button">Submit</button>					
                    </div>
                </div>
            </div>
        </form>
        <!--end user-->		
    </section>
</div>
<!--end main-->
<script>
    $('.img-wrap .close').on('click', function () {
        var id = $(this).closest('.img-wrap').find('img').data('id');
        var r = confirm("Are you sure?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                data: "id=" + id,
                url: "deletecollection.php?type=uploads",
                success: function (data)
                {
                    if (data == "done")
                    {
                        window.location.href = 'make-profile.php?status=delete';
                        //	location.reload();
                    }
                }
            });
        } else
        {
            return false;
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#expsector').on("change", function () {
            var categoryId = $(this).find('option:selected').val();
            $.ajax({
                url: "getsubcat.php",
                type: "POST",
                data: "categoryId=" + categoryId,
                success: function (response) {
                    // console.log(response);
                    $("#subexpsector").html(response);
                },
            });
        });
    });
</script>

<script type="text/javascript">

    function check_valprof() {
        var wordInput = document.getElementById("text").value.toLowerCase();
        var wordInput1 = document.getElementById("texts").value.toLowerCase();
        var wordInput2 = document.getElementById("textss").value.toLowerCase();
        var wordInput3 = document.getElementById("texts1").value.toLowerCase();
        var wordInput4 = document.getElementById("texts2").value.toLowerCase();
        var wordInput5 = document.getElementById("texts3").value.toLowerCase();
        var wordInput6 = document.getElementById("texts4").value.toLowerCase();
        var wordInput7 = document.getElementById("texts5").value.toLowerCase();
        // split the words by spaces (" ")
        var arr = wordInput.split(" ");
        var arr1 = wordInput1.split(" ");
        var arr2 = wordInput2.split(" ");
        var arr3 = wordInput3.split(" ");
        var arr4 = wordInput4.split(" ");
        var arr5 = wordInput5.split(" ");
        var arr6 = wordInput6.split(" ");
        var arr7 = wordInput7.split(" ");
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
        for (index = 0; index < arr3.length; ++index) {
            if (jQuery.inArray(arr3[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr3[index];

                alert("Please use better words!");
                return false;
            }
        }
        for (index = 0; index < arr4.length; ++index) {
            if (jQuery.inArray(arr4[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr4[index];

                alert("Please use better words!");
                return false;
            }
        }
        for (index = 0; index < arr5.length; ++index) {
            if (jQuery.inArray(arr5[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr5[index];

                alert("Please use better words!");
                return false;
            }
        }
        for (index = 0; index < arr6.length; ++index) {
            if (jQuery.inArray(arr6[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr6[index];

                alert("Please use better words!");
                return false;
            }
        }
        for (index = 0; index < arr7.length; ++index) {
            if (jQuery.inArray(arr7[index], badWords) > -1) {
                totalWordAmount = totalWordAmount + 1;
                totalWordsFoundList = totalWordsFoundList + ' ' + arr7[index];

                alert("Please use better words!");
                return false;
            }
        }

    }

</script>
<?php include "footer.php"; ?>