<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 9) {
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
	function userGroupChange(value) {
		if(value != 12) {
			$.post('functions/ajax_request.php', {assignUser:'YES',userType:value},function(data){
				if(data != '') {
					$('#assignUserId').html('');
					$('#assignUserId').html(data);
				} 
			});
		} else {
			$('#assignUserId').html('');
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
						<h2>Add New User</h2>
					</div>
					<form action="" method="post">
					<table class="form_table">
						<tr>
							<td width="170px;">Name</td>
							<td><input type="text" value="" required class="txtBx" name="name"/></td>
						</tr>
						<tr>
							<td>Username</td>
							<td><input type="text" value="" required class="txtBx" name="emailId"/></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" value="" required class="txtBx" name="password"/></td>
						</tr>
						<tr>
							<td>User Role</td>
							<td>
								<input type="radio" id="radio7" checked name="userType" onclick="return userGroupChange(this.value);" value="8"/><label for="radio7" class="rad_lbl">General Manager</label>
								<input type="radio" id="radio1"  name="userType" onclick="return userGroupChange(this.value);" value="5"/><label for="radio1" class="rad_lbl">BDM</label>
								<input type="radio" id="radio6" name="userType" onclick="return userGroupChange(this.value);" value="7"/><label for="radio6" class="rad_lbl">Lead Generator</label><br />
								<input type="radio" id="radio5" name="userType" onclick="return userGroupChange(this.value);" value="6"/><label for="radio5" class="rad_lbl">Business Analyst</label>
								<input type="radio" id="radio2" name="userType" onclick="return userGroupChange(this.value);" value="2"/><label for="radio2" class="rad_lbl">BDE/BDC</label>
								<input type="radio" id="radio3" name="userType" onclick="return userGroupChange(this.value);" value="3"/><label for="radio3" class="rad_lbl">Data Manager</label> <br/>
								<input type="radio" id="radio4" name="userType" onclick="return userGroupChange(this.value);" value="4"/><label for="radio4" class="rad_lbl">Data Team Leader</label>
								<input type="radio" id="radio8" name="userType" onclick="return userGroupChange(this.value);" value="11"/><label for="radio8" class="rad_lbl">Account Manager</label><br/>
								<input type="radio" id="radio9" name="userType" onclick="return userGroupChange(this.value);" value="12"/><label for="radio9" class="rad_lbl">Project Manager</label>
								<input type="radio" id="radio10" name="userType" onclick="return userGroupChange(this.value);" value="13"/><label for="radio10" class="rad_lbl">Project Team Leader</label><br />
								<input type="radio" id="radio11" name="userType" onclick="return userGroupChange(this.value);" value="14"/><label for="radio11" class="rad_lbl">Marketing co-ordinator</label>
							</td>
						</tr>
						<tr id="assignUserId">
							<td>Assign User</td>
							<td>
								<select required class="txtBx" name="assignUser">
									<option value="">Select User</option>
									<?php
									$userData = mysql_query("select * from admin_login where userType= '10' and status = '1' ORDER BY name");
									while($userRow = mysql_fetch_array($userData)) { ?>
										<option value="<?php echo $userRow['userId']; ?>"><?php echo $userRow['name']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="add_user" value="Submit"/></td>
						</tr>
					</table>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>
<?php
if(isset($_POST['add_user'])) {
	$userGroup = 0;
	$superior1 = 0;
	$superior2 = 0;
	if($_POST['userType'] != '3' && $_POST['userType'] != '12') {
		$superior1 = $_POST['assignUser'];
	}
	
	$userNameQuery = mysql_query("select * from admin_login where emailId = '".$_POST['emailId']."'");
	if(mysql_num_rows($userNameQuery) == 0) {
		mysql_query("insert into admin_login (name,emailId,password,userType,userGroup,superior1,superior2,createdDate,modifiedDate,status) values ('".$_POST['name']."','".$_POST['emailId']."','".$_POST['password']."','".$_POST['userType']."','".$userGroup."','".$superior1."','".$superior2."','".date('y-m-d H:i:s')."','".date('y-m-d H:i:s')."','1')");
		if(mysql_affected_rows() > 0) {
			echo "<script>alert('User Added Successfully'); window.location='manage_user.php'; </script>";
		} else {
			echo "<script>alert('User Adding Failed, Please try again'); </script>";
		}
	} else {
		echo "<script>alert('User Name already exist, Please try again'); </script>";
	}
}
?>