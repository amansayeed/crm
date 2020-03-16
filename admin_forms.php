<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if(!isset($_GET['type']) || $_GET['type'] == '' || $_SESSION['userType'] != 1 ) {
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
					<?php
					if($_GET['type'] == 'sales') {
						$addClass = 'sales_user';
						$addTitle = 'Sales Request Form';
						$buttonType = 'button';
						$searchType = "sales_add = '1'";
					} else if($_GET['type'] == 'dm') {
						$addClass = 'dm_user';
						$addTitle = 'Data Manager Form';
						$buttonType = 'button';
						$searchType = "dm_view = '1' OR dm_edit = '1'";
					} else if($_GET['type'] == 'dtl') {
						$addClass = 'dtl_user';
						$addTitle = 'Data Team Lead Form';
						$buttonType = 'button';
						$searchType = "dtl_view = '1' OR dtl_edit = '1'";
					}
					?>
					<div class="cnt_title <?php echo $addClass; ?>">
						<h2><?php echo $addTitle; ?></h2>
					</div>
					<a class="field_add" href="add_new_field.php?type=<?php echo $_GET['type']; ?>">Add New Field</a>
					<div class="clear"></div>
					<form action="" method="post">
					<table class="form_table">
						<?php
						$result = mysql_query("select * from coloum_access where ".$searchType);
						while($row = mysql_fetch_array($result)) {
							if($row['validation_type'] == 1) {
								$pattern = 'pattern="[0-9]+" title="Numeric Only (0-9)"';
							} else if($row['validation_type'] == 2) {
								$pattern = 'pattern="[a-zA-Z ]+" title="Text only (a-z,A-Z)"';
							} else if($row['validation_type'] == 3) {
								$pattern = 'pattern="[a-zA-Z0-9 ]+" title="Alpha Numeric only (a-z,A-Z,0-9)"';
							} else {
								$pattern = '';
							}
							if($row['mandatory'] == 1) { 
								$required = 'required';
							} else {
								$required = '';
							}
						?>
							<tr>
								<td><?php echo $row['excel_column']; ?></td>
								<td>
									<?php if($_GET['type'] == 'sales' || ($_GET['type'] == 'dm' && $row['dm_edit'] == '1') || ($_GET['type'] == 'dtl' && $row['dtl_edit'] == '1')) {
										if($row['column_type'] == 2) { ?>
											<select class="txtBx" <?php echo $required; ?> name="<?php echo $row['db_column']; ?>">
											</select>
										<?php } else if($row['column_type'] == 3){ ?>
											<input type="text" class="txtBx datetimepicker" <?php echo $required; ?> readonly value="<?php echo date('d-m-Y'); ?>" name="<?php echo $row['db_column']; ?>" />
										<?php } else if($row['column_type'] == 5){ ?>
											<div class="subBtn file_btn" style="">Upload
												<input type="file" class="choosefile" <?php echo $required; ?> onchange="$('#file_name').html(this.value);" name="<?php echo $row['db_column']; ?>" value=""/>
											</div>
											<span class="file_name" id="file_name">No files selected</span>
										<?php } else if($row['column_type'] == 6) { ?>
											<textarea <?php echo $pattern; ?> <?php echo $required; ?> class="txtArea" name="<?php echo $row['db_column']; ?>"></textarea>
										<?php } else { ?>
											<input type="text" <?php echo $pattern; echo $required; if($row['column_type'] == 0) { echo ' readonly'; }?> value="<?php if($row['db_column'] == 'code' ) { echo 'BM '.str_pad('1', 4, "0", STR_PAD_LEFT); } ?>" class="txtBx" name="<?php echo $row['db_column']; ?>"/>
										<?php } 
									} else { ?>
										<input type="text" <?php echo $pattern; echo $required; if($row['column_type'] == 0) { echo ' readonly'; }?> value="<?php if($row['db_column'] == 'code' ) { echo 'BM '.str_pad('1', 4, "0", STR_PAD_LEFT); } ?>" disabled class="txtBx" name="<?php echo $row['db_column']; ?>"/>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<td></td>
							<td><input type="<?php echo $buttonType; ?>" class="subBtn frm_sub" name="add_user" value="Submit"/></td>
						</tr>
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
if(isset($_POST['add_user'])) {
	if(isset($_POST['userGroup'])) {
		$userGroup = $_POST['userGroup'];
	} else {
		$userGroup = 0;
	}
	mysql_query("insert into admin_login (name,emailId,password,userType,userGroup,createdDate,modifiedDate,status) values ('".$_POST['name']."','".$_POST['emailId']."','".$_POST['password']."','".$_POST['userType']."','".$userGroup."','".date('y-m-d H:i:s')."','".date('y-m-d H:i:s')."','1')");
	
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('User Added Successfully'); window.location='manage_user.php'; </script>";
	} else {
		echo "<script>alert('User Adding Failed, Please try again'); </script>";
	}
}
?>