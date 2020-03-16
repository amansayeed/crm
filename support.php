<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
include_once("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Sphere</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Support</h2>
					</div>
					<?php
					if(isset($_POST['supportReq']))
					{
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: no-reply@worksphere.com'."\r\n" .
						'CC: varghese.t@workspheretechnologies.com'."<br/>".
						'X-Mailer: PHP/' . phpversion();
						$to  = 'vijay.t@workspheretechnologies.com';
						$subject =  "Support Requirement";
						$message  = "USER :" . $_SESSION['loggedPerson'] . '<br />';
						$message  .= "Support Requirement :" . '<br />';
						$message  .= $_POST['support'];
						if(mail($to,$subject,$message,$headers)) {
							echo '<script> alert("Requirement mailed to Admin successfully"); window.location="support.php";</script>';
						}
					}
					?>
					<form name="myform" action="support.php" id="chg_pwd" method="post">
					<table class="form_table">
						<tr>
							<td width="170px;">Support Required</td>
							<td><textarea class="txtArea" name="support"></textarea></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="supportReq" value="Submit"/></td>
						</tr>
					</table>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>