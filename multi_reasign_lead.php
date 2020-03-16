<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != '8') {
	header('Location: dashboard.php'); exit;
}
include_once("dbconnect.php");

if(isset($_POST['reassign_user'])) {
	mysql_query("update sales_request set executive='".$_POST['reassignTo']."' where leadId IN (".implode(",",$_POST['hidreasignCheck']).")");
	mysql_query("update lead_generate set assignedTo='".$_POST['reassignTo']."' where leadId IN (".implode(",",$_POST['hidreasignCheck']).")");
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('Lead Reassign successful'); window.location='dashboard_lead.php'; </script>";
	} else {
		echo "<script>alert('Lead Reassign Failed'); window.location='dashboard_lead.php'; </script>";
	}
}
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
					<div class="cnt_title dtl_user">
						<h2>View Details</h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post">
					<table class="form_table">
						<tr>
							<td>Lead code </td>
							<td>
							<?php
							$result1 = mysql_query("select leadId,leadCode from lead_generate where leadId IN (".implode(",",$_POST['reasignCheck']).")");
							while ($row = mysql_fetch_array($result1)) {
								echo $row['leadCode'].","; ?>
								<input type="hidden" name="hidreasignCheck[]" value="<?php echo $row['leadId']; ?>" />
							<?php
							} ?>
							</td>
						</tr>
						<tr>
							<td>Reassign to</td>
							<td>
								<input type="hidden" name="hiddenLeadId" value="<?php echo $row1['leadId']; ?>" />
								<select class="txtBx" required name="reassignTo">
									<?php
										if($_SESSION['userType'] == '5') {
											$userGroupQuery = " and (superior1 = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
										} else if($_SESSION['userType'] == '8') {
											$userGroupQuery = " and (superior1 = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
										} else if($_SESSION['userType'] == '10') {
											$userGroupQuery = " and (superior1 = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
										} else {
											$userGroupQuery = '';
										}
										$userListQuery = mysql_query("select * from admin_login where userType IN ('2','5','8') and status = '1'".$userGroupQuery." ORDER BY name");
										while($userList = mysql_fetch_array($userListQuery)) {
									?>
										<option value="<?php echo $userList['userId']; ?>"><?php echo $userList['name']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="reassign_user" value="Reassign"/></td>
						</tr>
					</table>
					</form>
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