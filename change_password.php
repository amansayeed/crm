<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 10 && $_SESSION['userType'] != 9 && $_SESSION['userType'] != 8) {
	header('Location: dashboard.php'); exit;
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
<script>
function frmvalidate()
{
	var flag = 1;
	if(document.myform.currentpassword.value=='')
	{
		
		alert("Enter the current password");
		document.myform.currentpassword.focus;
		return false;
		flag = 0;
	}
	
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
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Change Password</h2>
					</div>
					<?php
					if(isset($_POST['change_pass']))
					{
						$currentpassword=$_POST['currentpassword'];
						$newpassword=$_POST['newpassword'];
						$retypepassword=$_POST['retypepassword'];
						$userId=$_SESSION['userId'];
						
						$query1="select password from admin_login where userId='$userId'";
						$result1=mysql_query("$query1",$con);
						while($row=mysql_fetch_array($result1))
						{
							$password=$row['password'];
						}

						if($currentpassword==$password)
						{
							$query2="update admin_login set password='$newpassword' where userId='$userId'";
							mysql_query("$query2",$con);
							echo '<script> alert("Password Changed successfully")</script>';
						}else echo '<script>alert("Error: Current password you entered is wrong")</script>';
					}
					?>
					<form name="myform" action="change_password.php" id="chg_pwd" method="post" onsubmit="return frmvalidate();">
					<table class="form_table">
						<tr>
							<td width="170px;">Current Password</td>
							<td><input type="password" value="" required class="txtBx" name="currentpassword"/></td>
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
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>