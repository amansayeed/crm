<?php
session_start();
ob_start();

if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 10) {
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
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title sales_user">
						<h2>Transfer User</h2>
					</div>
					<div class="clear"></div>
					
					<table class="form_table">
						<form action="" id="usertypeForm" method="post" enctype="multipart/form-data">
						<tr>
							<td>User Type</td>
							<td>
								<select class="txtBx" required name="userType" onchange="if(this.value != '') { $('#usertypeForm').submit(); }">
									<option value="">Select User Type</option>
									<option value="5" <?php if(isset($_POST['userType']) && $_POST['userType'] == '5') { echo  "selected"; } ?>>BDM</option>
									<option value="2" <?php if(isset($_POST['userType']) && $_POST['userType'] == '2') { echo  "selected"; } ?>>BDE/BDC</option>
									<option value="4" <?php if(isset($_POST['userType']) && $_POST['userType'] == '4') { echo  "selected"; } ?>>Data Team Leader</option>
								</select>
							</td>
						</tr>
						</form>
						<?php if(isset($_POST['userType'])) { ?>
						<form action="" method="post">
							<input type="hidden" value="<?php echo $_POST['userType']; ?>" name="transferUserType" />
							<tr>
								<td>From User</td>
								<td>
									<select class="txtBx" required name="fromUser">
										<option value="">Select From user</option>
										<?php 
										$fromUserQuery = mysql_query("select * from admin_login where userType = '".$_POST['userType']."' ORDER BY name");
										while($fromUser = mysql_fetch_array($fromUserQuery)) { ?>
											<option value="<?php echo $fromUser['userId']; ?>"><?php echo $fromUser['name']; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>To User</td>
								<td>
									<select class="txtBx" required name="toUser">
										<option value="">Select From user</option>
										<?php 
										$fromUserQuery = mysql_query("select * from admin_login where userType = '".$_POST['userType']."' ORDER BY name");
										while($fromUser = mysql_fetch_array($fromUserQuery)) { ?>
											<option value="<?php echo $fromUser['userId']; ?>"><?php echo $fromUser['name']; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" class="subBtn frm_sub" name="transfer_user" value="Submit"/></td>
							</tr>
						</form>
						<?php } ?>
					</table>
					
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>
<script>
	jQuery('.datetimepicker').datetimepicker({
		timepicker:false,
		format:'d-m-Y',
		maxDate:'+1970/01/01',
		scrollInput: false
	});
</script>
<?php
if(isset($_POST['transfer_user'])) {
	if($_POST['transferUserType'] == '4') {
		mysql_query("update sales_request set processed_by='".$_POST['toUser']."' where processed_by='".$_POST['fromUser']."'");
	} else {
		mysql_query("update sales_request set executive='".$_POST['toUser']."' where executive='".$_POST['fromUser']."'");
		mysql_query("update lead_generate set assignedTo='".$_POST['toUser']."' where assignedTo='".$_POST['fromUser']."'");
	}
	
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('User Transfer successful'); window.location='transfer_user.php'; </script>";
	} else {
		echo "<script>alert('User Transfer Failed'); window.location='transfer_user.php'; </script>";
	}
}
?>