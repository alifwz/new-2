<?php
session_start();
include "connection.php";
include "header.php";
include "functions.php";
include "auth.php";

$id = $_REQUEST[id];

if (isset($_POST[submit])) {

    $title = $_POST[title];
    $category = $_POST[category];
    $subcategory = $_POST[subcategory];
    $description = $_POST[description];
    $skills = $_POST[skills];
    $budjet = $_POST[budjet];
    $duration = $_POST[duration];
    $lats = $_POST[lats];
    $long = $_POST[long];
    $country = $_POST[country];

    $query = mysql_query("UPDATE freelancer_mmv_work SET `filter_id`='$subcategory',`title`='$title',`description`='$description',`skills`='$skills',`budget`='$budjet',`duration`='$duration',`countryid`='$country' WHERE work_id=$id");

    if ($query == 1) {
        echo "<script>window.location='listing.php?status=posted'</script>";
    } else {
        echo "<script>window.location='listing.php?status=postfail'</script>";
    }
}

$dbdata_que = mysql_query("SELECT * from freelancer_mmv_work where work_id='$id'");
$db_result = mysql_fetch_array($dbdata_que);
?>
<!--start main-->
<div class="main">		
    <section class="contenets-main">
        <!--start post job -->
        <form name="" method="post">
            <input type="hidden" name="lats" id="lats" value="">	
            <input type="hidden" name="long" id="long" value="">	
            <div class="contenets post-a-job-main">
                <h2 class="text-align-center">Edit JOB</h2>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox">						
                            <textarea style="height:75px;" class="job-input rq-textarea" id="text" required name="title" placeholder="Give title"><?php echo $db_result[title] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox">
                            <select name="country" id="country" required class="job-input">							
                                <option value="">-Select Country-</option>								
                                <?php
                                $conid = $db_result[countryid];
                                $country_query = mysql_query("SELECT * FROM freelancer_mmv_countries");
                                while ($country_res = mysql_fetch_array($country_query)) {
                                    $selcountryid = $country_res[countries_id];
                                    ?>
                                    <option <?php
                                    if ($conid == $selcountryid) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $selcountryid ?>"><?php echo $country_res[countries_name]; ?></option>								
                                    <?php } ?>				
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox">
                            <select name="category" id="category" required class="job-input">							
                                <option value="">-Select category-</option>								
                                <?php
                                $filid = $db_result[filter_id];
                                $filt_que = mysql_query("SELECT * from freelancer_mmv_filter where filter_id='$filid'");
                                $filt_result = mysql_fetch_array($filt_que);
                                $categ = $filt_result[parent_id];

                                $banner_que = mysql_query("SELECT * from freelancer_mmv_filter where 1=1 AND parent_id=0 AND status='1'");
                                while ($banner_result = mysql_fetch_array($banner_que)) {
                                    $dbfillid = $banner_result[filter_id];
                                    ?>
                                    <option <?php
                                    if ($categ == $dbfillid) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $banner_result[filter_id] ?>"><?php echo $banner_result[title] ?></option>								
                                    <?php } ?>				
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox">
                            <select name="subcategory" required id="subcategory" class="job-input">	
                                <?php
                                $subbanner_que = mysql_query("SELECT * from freelancer_mmv_filter where parent_id='$categ' AND status='1'");
                                while ($subbanner_result = mysql_fetch_array($subbanner_que)) {
                                    $subdbfillid = $subbanner_result[filter_id];
                                    ?>
                                    <option <?php
                                    if ($filid == $subdbfillid) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $subbanner_result[filter_id] ?>"><?php echo $subbanner_result[title] ?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox">
                            <textarea class="job-input job-textarea" id="texts" name="description" placeholder="Describe about job"><?php echo $db_result[description] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox">
                            <textarea class="job-input rq-textarea" id="textss" required name="skills" placeholder="Required skills"><?php echo $db_result[skills] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-6"><div class="job-inputbox">
                    <!--<input type="text" required name="budjet" class="job-input" placeholder="Budget" />-->
                            <select name="budjet" id="budjet" required class="job-input">							
                                <option value="">-Select Budjet-</option>								
                                <?php
                                $budg = $db_result[budget];
                                $bud_query = mysql_query("SELECT * FROM freelancer_mmv_expsector");
                                while ($bud_res = mysql_fetch_array($bud_query)) {
                                    $dbbud = $bud_res[id];
                                    ?>
                                    <option <?php
                                    if ($dbbud == $budg) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $bud_res[id] ?>"><?php echo $bud_res[title]; ?></option>								
                                    <?php } ?>				
                            </select>

                        </div></div>
                    <div class="col-6"><div class="job-inputbox">
                            <!--<input type="text" class="job-input" name="duration" required placeholder="Duration" />-->
                            <select name="duration" id="duration" required class="job-input">							
                                <option value="">-Select Duration-</option>								
                                <?php
                                $dura = $db_result[duration];
                                $dur_query = mysql_query("SELECT * FROM freelancer_mmv_duration");
                                while ($dur_res = mysql_fetch_array($dur_query)) {
                                    $dbdur = $dur_res[id];
                                    ?>
                                    <option <?php
                                    if ($dbdur == $dura) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $dur_res[id] ?>"><?php echo $dur_res[durange]; ?></option>								
                                    <?php } ?>				
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row post-a-job-row text-align-center">
                    <div class="col-6 paddingtop10">
                        <button class="button" name="submit" onclick="return check_val();" type="submit">Submit</button>
                    </div>
                    <div class="col-6 paddingtop10">
                        <a id="delete_job" style="background: red; color: white" data-id="<?php echo $_GET['id']; ?>" class="button">Delete</a>
                    </div>
                </div>
            </div>
        </form>
        <!--end post job -->
    </section>
</div>
<!--end main-->
<script type="text/javascript">
    $(document).ready(function () {

        $('#category').on("change", function () {
            var categoryId = $(this).find('option:selected').val();
            $.ajax({
                url: "subcatajax.php",
                type: "POST",
                data: "categoryId=" + categoryId,
                success: function (response) {
                    // console.log(response);
                    $("#subcategory").html(response);
                },
            });
        });

        $('#delete_job').on('click', function () {
            var id = $(this).attr('data-id');
            var r = confirm("Are you sure?");
            if (r == true)
            {
                window.location.href = 'delete-job.php?id=' + id;
            }
        })
    });

</script>
<?php include "footer.php"; ?>