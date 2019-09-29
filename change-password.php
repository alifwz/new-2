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

if (isset($_POST['submit'])) {
    $password = md5($_POST['password']);
    $npassword = md5($_POST['npassword']);
    $cpassword = md5($_POST['cpassword']);

    if ($npassword == $cpassword) {

        $login_query = mysql_query("SELECT * from freelancer_mmv_member_master where member_id=$loginid");
        while ($login_res = mysql_fetch_array($login_query)) {
            $db_password = $login_res[member_password];

            if ($password == $db_password) {
                $login_query2 = mysql_query("UPDATE freelancer_mmv_member_master SET member_password='$cpassword' where member_id=$loginid");
                //echo '<script>alert("Password changed successfully!")</script>';
                echo "<script>window.location='change-password.php?status=passsuccess'</script>";
            } else {
                echo "<script>window.location='change-password.php?status=oldpassnotmatch'</script>";
                //echo '<script>alert("Old password do not match!")</script>';
            }
        }
    } else {
        echo "<script>window.location='change-password.php?status=passnotmatch'</script>";
        //echo '<script>alert("Confirm password do not match!")</script>';			
    }
}
?>
<!--start main-->

<div class="main">		
    <section class="contenets-main">
        <!--start post job -->
        <form name="reset" method="post" action="">
            <div class="contenets post-a-job-main">
                <h2 class="text-align-center">Change Password</h2>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox"><input type="password" name="password" required class="job-input" placeholder="Old Password" /></div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox"><input type="password" minlength="8" name="npassword" required class="job-input" placeholder="New Password" /></div>
                    </div>
                </div>
                <div class="row post-a-job-row">
                    <div class="col-12">
                        <div class="job-inputbox"><input type="password" minlength="8" name="cpassword" required class="job-input" placeholder="Confirm Password" /></div>
                    </div>
                </div>
                <div class="row post-a-job-row text-align-center">
                    <div class="col-12 paddingtop10">
                        <button class="button" name="submit" type="submit">Change Password</button>
                    </div>
                </div>
            </div>
        </form>
        <!--end post job -->
    </section>
</div>

<!--end main-->
<?php include "footer.php"; ?>