<?php
session_start();
include "connection.php";
include "header.php";

function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

$denied_hostnames = array("yopmail.com");

if(isset($_POST[submit])){	
	$emailid 	= mysql_real_escape_string($_POST[emailid]);
	$password 	= $_POST[pasword];
	$cpassword	= $_POST[cpasword];
	$timezone	= $_POST[timezone];
	$newpass 	= mysql_real_escape_string(md5($_POST[pasword]));
	
	$_SESSION['pasword'] = $_POST['pasword'];
	$_SESSION['cpasword'] = $_POST['cpasword'];
	
	$checkquery = mysql_query("SELECT * from freelancer_mmv_member_master where member_user_email='$emailid'");
	$checkres = mysql_fetch_array($checkquery);	
	
	foreach ($denied_hostnames as $hn)
    {
        if (strstr($_POST['emailid'], "@" . $hn))
        {
            $errors[] = "Sorry bud! We don't accept " . $hn . " email addresses";
        }
    }
	
	if(!empty($errors)){
		echo '<script type="text/javascript">
				   window.location = "register.php?status=fakeemail"
			  </script>';
	} else {
	
	if($checkres['member_user_email'] == $emailid)
	{		
		echo '<script type="text/javascript">
				   window.location = "register.php?status=userexists"
			  </script>';		
	} else {
	
		if($password == $cpassword){		
			$query = mysql_query("INSERT INTO freelancer_mmv_member_master (member_user_email, member_password, status, lastseeen, timezone) VALUES ('$emailid','$newpass',0,'$now','$timezone')");
			$regisid = mysql_insert_id();
							
			$encrypted = my_simple_crypt( $regisid, 'e' );					
			$tou = $emailid;
			$subjectu = "Freelancer Account activation mail";			
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
								Thank you for registering at Freelancer. Your account is created and must be activated before you can use it. To activate the account click on the below button:
							</td>
						</tr>	
						<tr>		
							<td style="padding:10px 20px 15px 20px">
								<a href="'.$urlpath.'accountactivation.php?id='.$encrypted.'" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Activate Your Account</a>
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
				smtpmailer($tou, ' ', $from, $from_name, $subjectu, $messageu);
				/*unset($_SESSION["firstname"]);
				unset($_SESSION["lastname"]);*/
				unset($_SESSION["pasword"]);
				unset($_SESSION["cpasword"]);	
				
				echo '<script type="text/javascript">
				   window.location = "index.php?status=regsuccess"
			  </script>';						
				
			} else {			
				echo '<script type="text/javascript">
					   window.location = "register.php?status=confpass"
				  </script>';
			}	
		}
	}	
}
?>
<!--start main-->
<div class="main">		
	<section class="contenets-main">
		<!--start form-->
		<form name="register" method="post" action="" autocomplete="off">
		<input type="hidden" name="timezone" value="<?php echo $timezone ?>">
		<input type="hidden" name="now" value="<?php echo $now ?>">
		<div class="container">
			<div class="row form-main text-align-center">
			
				<div class="col-12"><h2>Register</h2></div>
				<!--<div class="col-12">
					<div class="form-group">						
						<input type="text" name="firstname" id="firstname" value="<?php echo $_SESSION[firstname] ?>" required class="form-control" placeholder="First Name" >
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">						
						<input type="text" name="lastname" id="lastname" value="<?php echo $_SESSION[lastname] ?>" required class="form-control" placeholder="Last Name" >
					</div>
				</div>-->
				<div class="col-12">
					<div class="form-group">						
						<input type="email" name="emailid"  id="emailid" required class="form-control" placeholder="Email ID" >
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">						
						<input type="password" name="pasword" minlength="8" value="<?php echo $_SESSION[pasword] ?>" class="form-control" required placeholder="Password" title="8 characters minimum">
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">						
						<input type="password" name="cpasword" minlength="8" value="<?php echo $_SESSION[cpasword] ?>" class="form-control" required placeholder="Confirm password" title="8 characters minimum">
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<div class="termscondition">
							<label><input type="checkbox" required class="" /> Accept Terms & Condition.</label>
							<a href="#TermsCondition" class="popuplink">View T&C</a>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group register-main">						
						<!--<a href="#makeProfile" id="makeProfile" class="button popuplink">Register</a>-->
						<button name="submit" class="button" type="submit">Register</button>
					</div>
				</div>
			
			</div>
		</div>	
		</form>		
		<!--end form-->		 
	</section>
</div>
<!--end main-->
<a href="#makeProfile" id="makeProfile" class="button popuplink">
<div class="popbox">
	<div id="makeProfile" class="make-profile text-align-center">
		<div class="map-icon"><img src="images/user-dark.png" alt="user" /></div>
		<div class="make-profile-div"><a href="make-profile.php" class="button">Upload Your Profile</a></div>
		<div class="skip-div"><a href="index.php" class="skip-btn">Later</a></div>
	</div>
</div>
</a>
<?php
	$about_query = mysql_query("SELECT * from freelancer_mmv_aboutus where id=2");
	$about_res = mysql_fetch_array($about_query);
?>	
<div class="popbox">
	<div class="popupbox" id="TermsCondition">
		<h2>Terms & Condition</h2>
		<p><?php echo $about_res[content] ?></p>
	</div>
</div>

<a data-fancybox="" data-type="inline" data-src="#userExists" href="javascript:void(0);" class="alertuserexist none"></a>
<div class="popbox">
	<div id="userExists" class="popupbox text-align-center abuseOption" style="background:white;">	
		
		<p style="color:red; font-weight:bold;">User Already exists with the email!</p>		
	</div>
</div>

<a data-fancybox="" data-type="inline" data-src="#regsuccess" href="javascript:void(0);" class="alertregsuccess none"></a>
<div class="popbox">
	<div id="regsuccess" class="popupbox text-align-center abuseOption" style="background:white;">	
		
		<p style="color:#00b159; font-weight:bold;">Account activation link has been sent to your registered Email  successfully</p>		
	</div>
</div>

<a data-fancybox="" data-type="inline" data-src="#confpassfail" href="javascript:void(0);" class="alertconfpass none"></a>
<div class="popbox">
	<div id="confpassfail" class="popupbox text-align-center abuseOption" style="background:white;">	
		
		<p style="color:red; font-weight:bold;">Confirm password does not match!</p>		
	</div>
</div>

<a data-fancybox="" data-type="inline" data-src="#fakeemail" href="javascript:void(0);" class="fakeemail none"></a>
<div class="popbox">
	<div id="fakeemail" class="popupbox text-align-center abuseOption" style="background:white;">	
		
		<p style="color:red; font-weight:bold;">Fake e-mails are not allowed!</p>		
	</div>
</div>

<?php include "footer.php"; ?>
<script>

$(window).load(function() {
	"use strict";	
	adjustWidthHeight();	
	<?php if($_REQUEST[status]=='userexists'){ ?>		
		$('.alertuserexist').trigger('click');
	<?php } else if($_REQUEST[status]=='regsuccess'){ ?>	
		$('.alertregsuccess').trigger('click');
	<?php } else if($_REQUEST[status]=='confpass'){ ?>	
		$('.alertconfpass').trigger('click');
	<?php } else if($_REQUEST[status]=='fakeemail'){ ?>	
		$('.fakeemail').trigger('click');
	<?php } ?>
});
</script>