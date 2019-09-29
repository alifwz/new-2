<?php
session_start();
$user_id = isset($_SESSION["SESS_MEMBER_ID"][0]) ? $_SESSION["SESS_MEMBER_ID"][0] : '';
if (!$user_id) {
    header("Location: index.php#login");
    die;
}
include "connection.php";
include "header.php";
include "auth.php";

// RESET IMAGE
if (isset($_GET['t']) && $_GET['t'] != '') {
    $image = mysql_query("SELECT image FROM freelancer_mmv_userimages WHERE id='" . trim($_GET['t']) . "'");
    $image_details = mysql_fetch_array($image);
    unlink($image_details['image']);
    mysql_query("DELETE from freelancer_mmv_userimages WHERE id='" . trim($_GET['t']) . "'");
}

if (isset($_POST[submitimage])) {
    $description = $_POST[description];
    $videolink = $_POST[videolink];
    $pcountry = $_POST[pcountry];
    $imageid = $_POST[imageid];

    $quee = mysql_query("UPDATE freelancer_mmv_userimages SET description='$description', countryid='$pcountry' WHERE id='$imageid'");

    if ($quee == 1) {
        $ppquery = mysql_query("SELECT * FROM freelancer_mmv_paypalsettings WHERE id='1'");
        $ppres = mysql_fetch_array($ppquery);
        $ppamount = $ppres['image'];
        if ($ppamount == '0' || $ppamount == '0.00') {

            $countquery = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$loginid' and status = 1");
            $count_res = mysql_num_rows($countquery);
            /* if ($count_res >= 9) {
              mysql_query("DELETE FROM freelancer_mmv_userimages WHERE userid='$loginid' and status = 1 ORDER BY id ASC LIMIT 1");
              } */

            mysql_query("UPDATE freelancer_mmv_userimages SET status='1' WHERE id='$imageid'");
            echo '<script>window.location.href="index.php?status=psuccess"</script>';
        } else {
            echo '<script>window.location.href="paypal.php?type=1&uid=' . $loginid . '&imgid=' . $imageid . '"</script>';
        }
    }
}
?>
<style>
    .login-main {
        padding: 10px 45px !important;
        top: 6px;
        position: relative;
    }
    .fancybox-slide {
        position: absolute;
        top: -40px !important;
    }
    ::placeholder{
        font-size: 15px
    }
</style>
<style>
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('images/wait.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>
<script src="js/jquery.js"></script> 
<link  href="js/cropper.min.css" rel="stylesheet">
<script src="js/cropper.min.js"></script>

<div class="loa der"></div>
<div class="main">
    <div class="login-main">		
        <form name="login" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="freelanceserviceid" id="freelanceserviceid" value="<?php echo $freelanceserviceid; ?>">
            <div class="for gotpass-main">	
                <div class="form-group">
                    <div class="image_container" style="max-height:350px">
                        <img id="blah" src="" alt="" />
                    </div>
                    <div id="cropped_result" style="display:none"></div> 
                </div>
                <div class="form-group">
                    <!-- onchange="readURL(this);"-->
                    <input name="image" accept="image/*" type="file" id="image" required class="form-control " onchange="checkfile(this);"/>
                </div>
                <!--                <div class="form-group" style="display:none">						
                                    <input type="hidden" name="videolink" id="videolink" class="form-control text-align-center inputbg" placeholder="Website URL">
                                </div>-->
                <br>
                <div class="form-group" style="display:none">						
                    <select style="
                            font-size: 15px;
                            height: 60px;
                            " name="pcountry" id="pcountry" required class="form-control inputbg">							
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
                <div class="col-md-4" style="">						
                </div>
                <div class="form-group">
                    <button id="crop_button" style="color:#000000" class="button loginbtn">Crop</button>						
                    <a href="postanimage.php?t=reset" id="resetbtn" class="button light-yellow">Reset</a> 						
                </div>
            </div>
        </form>
    </div>
</div>

<a data-fancybox="" data-type="inline" data-src="#invalidPass" href="javascript:void(0);" class="addcountry none"></a>
<div class="popbox">
    <div id="invalidPass" class="popupbox text-align-center">	
        <div class="login-main">		
            <form name="login" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="imageid" id="imageid" value="">
                <div class="for gotpass-main">		
                    <div class="form-group">						
                        <textarea style="height:300px;" maxlength="800" name="description" id="description_textarea" class="form-control text-align-center inputbg" placeholder="Say something about this photo"></textarea>
                    </div>
                    <!--                    <div class="form-group">						
                                            <input type="text" name="videolink" id="videolink" class="form-control text-align-center inputbg" placeholder="Website URL" id="">
                                        </div>-->
                    <br>
                    <div class="form-group">						
                        <select style="
                                font-size: 15px;
                                height: 60px;
                                " name="pcountry" id="pcountry" required class="form-control inputbg">							
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
                    <div class="col-md-4" style="">						
                    </div>
                    <br>
                    <div class="form-group">
                        <button name="submitimage" class="button loginbtn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        </form>		
    </div>
</div>
<script type="text/javascript" defer>
    function checkfile(sender) {
        var validExts = new Array(".jpeg", ".jpg", ".png");
        var fileExt = sender.value;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
            alert("Invalid file selected, valid files are of " +
                    validExts.toString() + " types.");
            $("#image").val('');
            return false;
        } else
            readURL(sender);
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result)
            };
            reader.readAsDataURL(input.files[0]);
            setTimeout(initCropper, 1000);
        }
    }
    function initCropper() {
        var image = document.getElementById('blah');
        var cropper = new Cropper(image, {
            aspectRatio: 1 / 1,
            crop: function (e) {

            }
        });

        // On crop button clicked
        document.getElementById('crop_button').addEventListener('click', function () {

            $(".loader").show();

            /*var wweburl = document.getElementById("videolink").value;
             var eee = document.getElementById("pcountry");
             var ppcountry = eee.options[eee.selectedIndex].value;*/
            var imgurl = cropper.getCroppedCanvas().toDataURL();
            var img = document.createElement("img");
            img.src = imgurl;
            document.getElementById("cropped_result").appendChild(img);

            /* ---------------- SEND IMAGE TO THE SERVER-------------------------*/
            cropper.getCroppedCanvas().toBlob(function (blob) {
                console.log(blob)
                var formData = new FormData();
                formData.append('croppedImage', blob);
                console.log(formData)
                // Use `jQuery.ajax` method
                $.ajax('savecropimage.php', {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'failed') {
                            $('.uploadfail').trigger('click');
                        } else {
                            document.getElementById("imageid").value = data;
                            document.getElementById("resetbtn").href = 'postanimage.php?t=' + data;
                            $('.addcountry').trigger('click');
                        }
                    },
                    error: function () {
                        console.log('Upload error');
                    }
                });
            });
            /* ----------------------------------------------------*/

        })
    }

    function myFunction(data) {
        window.location.href = "paypal.php?type=1&uid=<?php echo $loginid; ?>&imgid=" + data;
        alert('Please wait..!!');
    }
</script>	

<!--end main-->
<?php include "footer.php"; ?>