<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if(!isset($_GET['id']) || $_GET['id'] == '' || ($_SESSION['userType'] != 1 && $_SESSION['userType'] != 9)) {
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#squaredOne").click(function () {
            if ($("#password").attr("type")=="password") {
                $("#password").attr("type", "text");
            }
            else{
                $("#password").attr("type", "password");
            }
     
        });
    });
	
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
						<h2>Edit User</h2>
					</div>
					<?php
						$result = mysql_query("select * from admin_login where userId = '".$_GET['id']."'");
						$row = mysql_fetch_array($result);
					?>
					<form action="" method="post">
					<table class="form_table">
						<tr>
							<td width="170px;">Name</td>
							<td><input type="text" value="<?php echo $row['name']; ?>" required class="txtBx" name="name"/></td>
						</tr>
						<tr>
							<td>Username</td>
							<td><input type="text" value="<?php echo $row['emailId']; ?>" required class="txtBx" name="emailId"/></td>
						</tr>
						<tr>
							<td>Password</td>
							<td>
								<input type="password" id="password" value="<?php echo $row['password']; ?>" required class="txtBx" name="password"/><br />
								<div class="squaredOne float_l">
									<input type="checkbox" value="None" id="squaredOne" name="check" />
									<label for="squaredOne"></label>
								</div>
								<span class="role_chkBx">Show Password</span>
							</td>
						</tr>
						<tr>
							<?php if($row['userType'] != '9' && $row['userType'] != '10') { ?>
							<td>User Role</td>
							<td>
								<input type="radio" id="radio7" <?php if($row['userType'] == '8') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="8"/><label for="radio7" class="rad_lbl">General Manager</label>
								<input type="radio" id="radio1" <?php if($row['userType'] == '5') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="5"/><label for="radio1" class="rad_lbl">BDM</label>
								<input type="radio" id="radio6" <?php if($row['userType'] == '7') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="7"/><label for="radio6" class="rad_lbl">Lead Generator</label><br />
								<input type="radio" id="radio5" <?php if($row['userType'] == '6') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="6"/><label for="radio5" class="rad_lbl">Business Analyst</label>
								<input type="radio" id="radio2" <?php if($row['userType'] == '2') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="2"/><label for="radio2" class="rad_lbl">BDE/BDC</label>
								<input type="radio" id="radio3" <?php if($row['userType'] == '3') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="3"/><label for="radio3" class="rad_lbl">Data Manager</label><br/>
								<input type="radio" id="radio4" <?php if($row['userType'] == '4') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="4"/><label for="radio4" class="rad_lbl">Data Team Leader</label>
								<input type="radio" id="radio8" <?php if($row['userType'] == '11') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="11"/><label for="radio8" class="rad_lbl">Account Manager</label><br/>
								<input type="radio" id="radio9" <?php if($row['userType'] == '12') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="12"/><label for="radio9" class="rad_lbl">Project Manager</label>
								<input type="radio" id="radio10" <?php if($row['userType'] == '13') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="13"/><label for="radio10" class="rad_lbl">Project Team Leader</label><br />
								<input type="radio" id="radio11" <?php if($row['userType'] == '14') { echo 'checked';}?> name="userType" onclick="return userGroupChange(this.value);" value="14"/><label for="radio11" class="rad_lbl">Marketing co-ordinator</label>
							</td>
							<?php } else { ?>
								<input type="hidden" name="userType" value="<?php echo $row['userType']; ?>" />
							<?php } ?>
						</tr>
						<tr id="assignUserId">
							<?php if($row['userType'] != '12' && $row['userType'] != '9' && $row['userType'] != '10') { 
							if($row['userType'] == '5') {
								$userType = '8';
							} else if($row['userType'] == '7') {
								$userType = '8';
							} else if($row['userType'] == '6') {
								$userType = '5';
							} else if($row['userType'] == '2') {
								$userType = '5';
							} else if($row['userType'] == '3') {
								$userType = '8';
							} else if($row['userType'] == '4') {
								$userType = '3';
							} else if($row['userType'] == '11') {
								$userType = '8';
							} else if($row['userType'] == '12') {
								$userType = '11';
							} else if($row['userType'] == '13') {
								$userType = '12';
							} else {
								$userType = '10';
							} ?>
							<td>Assign User</td>
							<td>
								<select required class="txtBx" name="assignUser">
									<option value="">Select User</option>
									<?php
									$userData = mysql_query("select * from admin_login where userType = '".$userType."' and status = '1' ORDER BY name");
									while($userRow = mysql_fetch_array($userData)) { ?>
										<option value="<?php echo $userRow['userId']; ?>" <?php if($userRow['userId'] == $row['superior1'] ) { echo 'selected';} ?>><?php echo $userRow['name']; ?></option>
									<?php } ?>
								</select>
							</td>
							<?php } else { ?>
								<input type="hidden" name="assignUser" value="<?php echo $row['superior1']; ?>" />
							<?php }?>
						</tr>
						<tr>
							<td><input type="hidden" name="hiddenId" value="<?php echo $row['userId']; ?>" /></td>
							<td><input type="submit" class="subBtn frm_sub" name="edit_user" value="Submit"/></td>
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
if(isset($_POST['edit_user'])) {
	$userGroup = 0;
	$superior1 = 0;
	$superior2 = 0;
	if($_POST['userType'] != '3' && $_POST['userType'] != '12') {
		$superior1 = $_POST['assignUser'];
	}
	
	$userNameQuery = mysql_query("select * from admin_login where emailId = '".$_POST['emailId']."' and userId != '".$_POST['hiddenId']."'");
	if(mysql_num_rows($userNameQuery) == 0) {
		mysql_query("update admin_login set name = '".$_POST['name']."',emailId = '".$_POST['emailId']."',password = '".$_POST['password']."',userType = '".$_POST['userType']."',userGroup = '".$userGroup."',superior1 = '".$superior1."',superior2 = '".$superior2."',modifiedDate = '".date('y-m-d H:i:s')."' where userId = '".$_POST['hiddenId']."'");
		if(mysql_affected_rows() > 0) {
			echo "<script>alert('User details updated successfully'); window.location='manage_user.php'; </script>";
		} else {
			echo "<script>alert('User details update Failed, Please try again'); </script>";
		}
	} else {
		echo "<script>alert('User Name already exist, Please try again'); </script>";
	}
}
?>