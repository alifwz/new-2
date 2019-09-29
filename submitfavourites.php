<?php

session_start();
include "connection.php";
include "functions.php";
include "auth.php";
require 'smtpfunction.php';

$loginid = end($_SESSION[SESS_MEMBER_ID]);
if (!$loginid) {
    header("Location: index.php#login");
    die;
}

$clientid = $_REQUEST[id];
$workid = $_REQUEST[workid];

$query = mysql_query("SELECT * FROM freelancer_mmv_favourites WHERE memberid='$clientid' AND userid=$loginid AND workid=$workid");
$about_res = mysql_fetch_array($query);
$count = mysql_num_rows($query);

//echo $_SERVER['HTTP_REFERER']; die;

if ($count >= 1) {
    header("location:$_SERVER[HTTP_REFERER]&status=success");
} else {
    $query2 = mysql_query("INSERT INTO freelancer_mmv_favourites (`id`,`memberid`,`userid`,`workid`,`date`) VALUES ('','$clientid','$loginid','$workid',NOW())");
    add_member_notification($loginid, $clientid, 'favourites');
    $clieinfo = getUserinfo($clientid);
    $userinfo = getUserinfo($loginid);

    $tou = $clieinfo[1];
    $fullname = $clieinfo[3] . ' ' . $clieinfo[4];
    $subjectu = "Freelancer Interested";
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
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size:20px;line-height: 25px;color: #ac5e2a;border-collapse: collapse;padding: 15px;padding:10px 20px">Hello ' . $clieinfo[3] . ' ' . $clieinfo[4] . ',</td>
				</tr>
				<tr>		
					<td style="-moz-hyphens: none;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;word-break: break-word;-webkit-hyphens: none;-ms-text-size-adjust: 100%;hyphens: none;font-family:Open Sans,Arial,Helvetica,sans-serif;font-size: 15px;line-height: 25px;color: #000;border-collapse: collapse;padding:10px 20px">
						<h3>' . $userinfo[3] . ' ' . $userinfo[4] . '</h3> is interested with the Job. Please login and check for more information.
					</td>
				</tr>
				<tr>		
					<td style="padding:10px 20px 15px 20px">
						<a href="' . $urlpath . 'viewclient.php?id=' . $clientid . '" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">Freelancer profile</a>
					</td>
				</tr>
				<tr>		
					<td style="padding:10px 20px 15px 20px">
						<a target="_blank" href="' . $urlpath . 'favourite.php" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Poppins,Open Sans,Arial,Helvetica,sans-serif;color:#ffffff;padding-top: 7px;padding-right:18px;padding-bottom:10px;padding-left:18px;display:inline-block;border:1px solid #red;text-decoration:none;cursor:pointer;background:red;font-size:16px">View Interested</a>
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
    smtpmailer($tou, $fullname, $from, $from_name, $subjectu, $messageu);
}

if ($query2) {
    //header("location:favourite.php?status=success");
    header("location:$_SERVER[HTTP_REFERER]&status=isuccess");
}
?>