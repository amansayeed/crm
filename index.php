<?php
session_start();
ob_start();
if(!(isset($_SESSION['adminuser']))) {
	$message = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Sphere</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/checkbox.css" rel="stylesheet" type="text/css" />
<script>
function frmvalidate()
{
	if(document.myform.emailId .value=='')
	{
		
		alert("Enter the username");
		document.myform.emailId .focus;
		return false;
		
	}
	
	if(document.myform.password.value=='')
	{
		
		alert("Enter the password");
		document.myform.password.focus;
		return false;
		
	}
}
</script>
</head>
<body id="login_page">
	<div id="wrapper">
		<div id="content">
			<div style="height:120px"></div>
			<div class="index">
				<div class="login_title">
					<div class="logo login_logo" style=""><img src="images/logo.png" alt="logo" /></div>
					<h4>Worksphere Technologies</h4>
				</div>
				<?php 
				if(isset($_POST["login"])) {
					$flag=0;
					include_once("dbconnect.php");
					$query = "SELECT * FROM admin_login where status='1'";
					$result=mysql_query($query,$con);
					while($row = mysql_fetch_array($result)) {
						if($row['emailId'] == $_POST['emailId']) {
							if($row['password'] == $_POST['password']) {
								if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
									$ip = $_SERVER['HTTP_CLIENT_IP'];
								} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
									$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
								} else {
									$ip = $_SERVER['REMOTE_ADDR'];
								}
								
								$_SESSION['adminuser'] = $_POST['emailId'];
								$_SESSION['userType'] = $row['userType'];
								$_SESSION['userGroup'] = $row['userGroup'];
								if($row['userGroup'] == 1) {
									$_SESSION['userGroupName'] = 'BM';
								} else {
									$_SESSION['userGroupName'] = 'TD';
								}
								$_SESSION['loggedPerson'] = $row['name'];
								$_SESSION['userId'] = $row['userId'];
								$_SESSION['clientIp'] = $ip;
								$_SESSION['superior1'] = $row['superior1'];
								$flag = 1;
							} else { $flag = 2; }
						}
					}

					if ($flag == 1) {
						if($_SESSION['userType'] == '3' || $_SESSION['userType'] == '4') {
							header('Location:dashboard.php');
						} else if($_SESSION['userType'] == '11' || $_SESSION['userType'] == '12' || $_SESSION['userType'] == '13') {
							header('Location:project/dashboard.php');
						} else {
							header('Location:dashboard_lead.php?clear=1');
						}
					} else if($flag == 2)	{
						$message = 'Username & Password Mismatch';
					} else {
						$message = 'Username Does Not Exit';
					}
					mysql_close($con);
				}
				?>
				<?php
				if(isset($_SESSION['logout'])) {
					$message = $_SESSION['logout'];
					session_destroy();
				}
				?>
				<form name="myform" action="index.php" method="post">
					<div class="error">
						<?php echo $message; ?>
					</div>
					<ul class="form_li">
						<li>
							<input type="text" class="txtBx" required name="emailId" placeholder="Type in your Username"/>
						</li>
						<li>
							<input type="password" class="txtBx" required name="password" placeholder="Password"/>
						</li>
						<li>
							<input type="submit" class="subBtn" name="login" value="Submit" onclick="return frmvalidate();"/>
						</li>
						<li>
							<div class="squaredOne float_l">
								<input type="checkbox" value="None" id="squaredOne" name="check" checked />
								<label for="squaredOne"></label>
							</div>
							<span>Keep me Logged In</span>
							<a href="forgot_password.php?clear=1">Forgot Password?</a>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<?php
} else {
	if($_SESSION['userType'] == 9) {
		header('Location:manage_user.php');
	} else if($_SESSION['userType'] == 3 || $_SESSION['userType'] == 4){
		header('Location:dashboard.php');
	} else if($_SESSION['userType'] == 11 || $_SESSION['userType'] == 12 || $_SESSION['userType'] == 13){
		header('Location:project/dashboard.php');
	}	else {
		header('Location:dashboard_lead.php');
	}
}
?>
