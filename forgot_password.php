<?php
ob_start();
include_once("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Sphere</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
function frmvalidate()
{
	var flag = 1;
	if(document.myform.newpassword.value=='')
	{
		
		alert("Enter the new password");
		document.myform.newpassword.focus;
		return false;
		flag = 0;
	}
	if(document.myform.retypepassword.value=='')
	{
		
		alert("Retype the new password");
		document.myform.retypepassword.focus;
		return false;
		flag = 0;
	}
	if(document.myform.retypepassword.value!=document.myform.newpassword.value)
	{
		
		alert("New password and Confirm password mismatching");
		return false;
		flag = 0;
	}
}
</script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<div class="logo inner_logo"><span class="span_name" style="margin: 30px 0 0 -134px;">Worksphere</span><img src="images/logo.png" alt="logo" /><span class="span_name" style="margin: 30px 0 0 5px;">Technologies</span></div>
			<h6 class="logged_user" style="color:#fff; float:left; padding:25px;"></h6>
			<div class="nav">
				<ul class="nagigation">
					
				</ul>
			</div>
			<div class="clear"></div>
			<div class="inner_content">
				<div class="left_container" style="margin:auto; float:inherit;">
					<?php if(isset($_GET['forgot']) && isset($_GET['type'])) { 
						$query = mysql_query("select * from admin_login where userId = '".base64_decode($_GET['type'])."'");
						$currentUser = mysql_fetch_array($query);
						if(mysql_num_rows($query) == 0) {
							echo "<script>alert('Entered Wrong URL for Password reset'); window.location='index.php'; </script>";
						}
					?>
						<div class="cnt_title">
							<h2>Change Password</h2>
						</div>
						<?php
						if(isset($_POST['change_pass']))
						{
							$newpassword=$_POST['newpassword'];
							$retypepassword=$_POST['retypepassword'];
							$userId=$_POST['hiddenUserId'];
							
							$query2="update admin_login set password='$newpassword' where userId='$userId'";
							mysql_query("$query2",$con);
							if(mysql_affected_rows() > 0) {
								echo "<script> alert('Password Changed successfully'); window.location='index.php'; </script>";
							}
						}
						?>
						<form name="myform" action="" id="chg_pwd" method="post" onsubmit="return frmvalidate();">
						<table class="form_table">
							<input type="hidden" name="hiddenUserId" value="<?php echo $currentUser['userId']; ?>"/>
							<tr>
								<td>User</td>
								<td><?php echo $currentUser['name']; ?></td>
							</tr>
							<tr>
								<td>New Password</td>
								<td><input type="password" value="" required class="txtBx" name="newpassword"/></td>
							</tr>
							<tr>
								<td>Confirm Password</td>
								<td><input type="password" value="" required class="txtBx" name="retypepassword"/></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" class="subBtn frm_sub" name="change_pass" value="Submit"/></td>
							</tr>
						</table>
					<?php } ?>
					<?php if(isset($_GET['clear']) && $_GET['clear'] == 1) { ?>
						<div class="cnt_title">
						<h2>Forgot Password</h2>
					</div>
					<?php
					if(isset($_POST['forgot_pass']))
					{
						$userType=$_POST['userType'];
						$link = "http://50.62.134.137/~prodata/lead/forgot_password.php?forgot=yes&type=".base64_encode($userType);
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: no-reply@worksphere.com'."\r\n" .
						'X-Mailer: PHP/' . phpversion();
						$to  = 'varghese.t@workspheretechnologies.com,vijay.t@workspheretechnologies.com';
						//$to  = 'balajidce1989@gmail.com';
						$subject =  "Password Reset";
						$message  = "Please click the below link to reset the password" . '<br /><br /><br />';
						$message  .= $link;
						if(mail($to,$subject,$message,$headers)) {
							echo '<script> alert("Password reset link has been mailed to Admin"); window.location="index.php";</script>';
						}
					}
					?>
					<form name="myform" action="" id="chg_pwd" method="post">
					<table class="form_table">
						<input type="hidden" name="hiddenUserId" value=""/>
						<tr>
							<td>User Type</td>
							<td>
								<select class="txtBx" required name="userType">
									<?php
										$userListQuery = mysql_query("select * from admin_login where userType IN ('1','9','10') and status = '1' ORDER BY name");
										while($userList = mysql_fetch_array($userListQuery)) {
									?>
										<option value="<?php echo $userList['userId']; ?>"><?php echo $userList['name']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="forgot_pass" value="Submit"/></td>
						</tr>
					</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>