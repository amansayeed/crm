<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if(!isset($_GET['type']) || $_GET['type'] == '' || $_SESSION['userType'] != 1) {
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
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Add New Field</h2>
					</div>
					<form action="" method="post">
					<table class="form_table">
						<tr>
							<td width="170px;">New Field Name</td>
							<td><input type="text" value="" pattern="[a-zA-Z ]+" title="Text only (a-z,A-Z)" required class="txtBx" name="fieldName"/></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="add_field" value="Submit"/></td>
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
<?php
if(isset($_POST['add_field'])) {
	if($_GET['type'] == 'sales') {
		$sql = "insert into coloum_access (excel_column,db_column,column_type,validation_type,mandatory,sales_add,sales_view,sales_edit,dm_view,dm_edit,dtl_view,dtl_edit) values ('".$_POST['fieldName']."','".str_replace(" ","_",$_POST['fieldName'])."','1','0','0','1','1','1','0','0','0','0')";
	} else if($_GET['type'] == 'dm') {
		$sql = "insert into coloum_access (excel_column,db_column,column_type,validation_type,mandatory,sales_add,sales_view,sales_edit,dm_view,dm_edit,dtl_view,dtl_edit) values ('".$_POST['fieldName']."','".str_replace(" ","_",$_POST['fieldName'])."','1','0','0','0','0','0','1','1','0','0')";
	} else {
		$sql = "insert into coloum_access (excel_column,db_column,column_type,validation_type,mandatory,sales_add,sales_view,sales_edit,dm_view,dm_edit,dtl_view,dtl_edit) values ('".$_POST['fieldName']."','".str_replace(" ","_",$_POST['fieldName'])."','1','0','0','0','0','0','0','0','1','1')";
	}
	mysql_query($sql);
	mysql_query("ALTER TABLE `sales_request` ADD `".str_replace(" ","_",$_POST['fieldName'])."` VARCHAR( 110 ) NOT NULL");
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('New Field Added Successfully'); window.location='define_roles.php'; </script>";
	} else {
		echo "<script>alert('New Field Adding Failed, Please try again'); </script>";
	}
}
?>