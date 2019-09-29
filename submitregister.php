<?php
session_start();
include "connection.php";
//require 'PHPMailer.php';
//require 'class.smtp.php';
include "smtpfunction.php";
include "timezone.php";
?>
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
<div class="loader"></div>
<?php
$loginid = end($_SESSION[SESS_MEMBER_ID]);

$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_id='$loginid'");
$about_res = mysql_fetch_array($query);
$usercountry = $about_res[country];
$emailadd  = $about_res[member_user_email];

if(isset($_POST['login']))
{			
	$lusername 			= mysql_real_escape_string(strtolower($_POST['lusername']));		
	$lpassword 			= $_POST['lpassword'];	
	$lnewpass 	        = mysql_real_escape_string(md5($_POST['lpassword']));
	$lats 				= $_POST[lats];	
	$long 				= $_POST[long];	
	$timezone 			= $_POST[timezone];	
	$now 				= $_POST[now];	
	$success_url			= $_POST['success_url'];	
		
	$login_query = mysql_query("SELECT * from freelancer_mmv_member_master where status='1' AND member_user_email='$lusername'");
	$login_res = mysql_fetch_array($login_query);
		
	$db_username = $login_res[member_user_email];
	$db_password = $login_res[member_password];
	$adminid = $login_res[member_id];
					
	if($lusername==$db_username && $lnewpass==$db_password)
	{
		session_start();		
		$_SESSION["SESS_MEMBER_ID"][]=$adminid;		
			
			if($lats!="" && $long!=""){
				mysql_query("UPDATE freelancer_mmv_member_master SET loginlats='$lats', loginlong='$long', lastseeen='$now', timezone='$timezone' where member_id='$adminid'");
			}
			mysql_query("UPDATE freelancer_mmv_work SET lastseen='$now', timezone='$timezone' where member_id='$adminid'");
			
		if($login_res['firstlogin']==1){
                    if(isset($success_url) && $success_url!=''){
                        echo "<script>window.location='".$success_url."'</script>";
                    }else{
			echo "<script>window.location='index.php?status=updateprof'</script>";					
                    }
		} else {			
			echo "<script>window.location='make-profile.php?status=updateprof'</script>";
		}
	} else if($lusername==$db_username && $lnewpass!=$db_password){		
		echo '<script type="text/javascript">window.location = "index.php?status=invalidpass"</script>';
	} else {		
		echo '<script type="text/javascript">window.location = "index.php?status=invalidcred"</script>';			
	}	
}

if(isset($_POST['submitvideo']))
{
	$photoimg = $_FILES['photoimg']['name']; 
	$videolink = $_POST[videolink];
	$pcountry = $_POST[pcountry];
	$freelanceserviceid = $_POST[freelanceserviceid];	
		
	if($_FILES['photoimg']['name']!=""){
		
		$name = $_FILES['photoimg']['name']; 
		$size = $_FILES['photoimg']['size'];
		$tmpe = $_FILES['photoimg']['tmp_name'];
	} else {
		
		$name = $_FILES['videoimg']['name'];
		$size = $_FILES['videoimg']['size'];
		$tmpe = $_FILES['videoimg']['tmp_name'];
	} 
		
	$ppquery = mysql_query("SELECT * FROM freelancer_mmv_paypalsettings WHERE id='1'");
	$ppres = mysql_fetch_array($ppquery);
	$ppamount = $ppres[video];
	if($ppamount=='0' || $ppamount=='0.00'){
		$status = '1';
	} else {
		$status = '0';
	}
	
	/*if(strlen($name))
		{*/
			list($txt, $ext) = explode(".", $name);
			//print_r($ext); die;
			/*if(in_array($ext,$valid_formats))
			{*/
			
			if($size<(30000000) || $size=="")
			{
				$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
				$actual_image = time().substr(str_replace(" ", "_", $txt), 5).".png";
				
				$tmp = $tmpe;
				$blocation = "uploads/images/" . $actual_image_name;
								
				if(move_uploaded_file($tmp,$blocation))
				{				
					$video = $blocation;
					$thumbnail 		= 'uploads/images/'.$actual_image;
					$thumbSize      = '350x350';
					
					// shell command [highly simplified, please don't run it plain on your script!]
					shell_exec("/usr/local/bin/ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");		 				
				//die;
									
					$imgquery = mysql_query("INSERT INTO freelancer_mmv_userimages (id, userid, image, type, countryid, website, date, freelanceserviceid, status) VALUES ('', '".$loginid."','".$blocation."','2','$pcountry','$videolink',NOW(),'$freelanceserviceid','$status')");
					$imid = mysql_insert_id();
					
					//max 9 post code commented by dev
					/*
					$countquery = mysql_query("SELECT * FROM freelancer_mmv_userimages WHERE userid='$loginid'");
					$count_res = mysql_num_rows($countquery);
					if($count_res>9){								
						mysql_query("DELETE FROM freelancer_mmv_userimages WHERE userid='$loginid' ORDER BY id ASC LIMIT 1");
					}*/
					if($ppamount=='0' || $ppamount=='0.00'){
						echo '<script type="text/javascript">window.location = "index.php?status=success"</script>';
					} else {
						echo '<script type="text/javascript">window.location = "paypal.php?type=2&imgid='.$imid.'&uid='.$loginid.'"</script>';
					}					
					
				} else {					
					//echo "<script>alert('Upload Failed!!')</script>";
					//echo '<script type="text/javascript">window.location = "index.php"</script>';
					echo '<script type="text/javascript">window.location = "index.php?status=uploadfail"</script>';
				}								
			}
			else{					
				//echo "<script>alert('Max file size is 2 MB!!')</script>";
				//echo '<script type="text/javascript">window.location = "index.php"</script>';
				echo '<script type="text/javascript">window.location = "index.php?status=maxsize"</script>';
			}						
					
				/*}
				else
				echo $msg="Invalid file format..";	
		}
		
	else
		echo "Please select image..!";*/
		
		
	if($imgquery){
		echo '<script type="text/javascript">window.location = "index.php"</script>';
	}
}


if(isset($_POST['forgot']))
{
	$email = mysql_real_escape_string($_POST[email]);
	
	$query = mysql_query("SELECT * FROM freelancer_mmv_member_master WHERE member_user_email='$email'");
	$about_res = mysql_fetch_array($query);
	$uscount = mysql_num_rows($query);
	$usrid = $about_res[member_id];	
	$firstname = $about_res[first_name];	
	
	if($uscount==1){
	
		$tou = $email; 
		$subjectu = "Freelancer - Forgot password";			
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
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Hello,</td>
				</tr>
				<tr>		
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
						Please click on the below link to change your Password!!
					</td>
				</tr>
				<tr>		
					<td style="padding:10px 20px 15px 20px">
						<a target="_blank" href="'.$urlpath.'forgot-password.php?id='.$usrid.'" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Password reset link</a>
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

		// Always set content-type when sending HTML email
		//$headersu = "MIME-Version: 1.0" . "\r\n";
		//$headersu .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		//$headersu .= 'From: <notifications@meetfreelancers.com>' . "\r\n".
		//"Reply-To: noreply@meetfreelancers.com" . "\r\n" ;
		//$headers .= 'Cc: myboss@example.com' . "\r\n";
		//mail($tou,$subjectu,$messageu,$headersu);	
		
		smtpmailer($tou, $firstname, $from, $from_name, $subjectu, $messageu);
		
		echo "<script>window.location='index.php?status=success'</script>";
	} else {
		echo "<script>window.location='index.php?status=emailnotmatch'</script>";
	}
}
?>
<script type="text/javascript">
$(window).load(function() {
    $(".loader").fadeOut("slow");
});
</script>