<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if(!isset($_GET['type']) || $_GET['type'] == '' || !isset($_GET['id']) || $_GET['id'] == '') {
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
					$buttonValue = 'Submit';
					$result1 = mysql_query("select * from sales_request where requestId = '".$_GET['id']."'");
					$row1 = mysql_fetch_array($result1);
					if($_GET['type'] == 'sales') {
						$addClass = 'sales_user';
						$addTitle = 'Feedback Form';
						$searchType = "sales_view = '1' OR sales_edit = '1'";
					} else if($_GET['type'] == 'dm') {
						$addClass = 'dm_user';
						$addTitle = 'Data Manager Form';
						$searchType = "dm_view = '1' OR dm_edit = '1'";
						if($row1['feedback'] == 2) {
							$buttonValue = 'Re-assign';
							$addTitle = 'Data Tracking Form';
						}
					} else if($_GET['type'] == 'dtl') {
						$addClass = 'dtl_user';
						$addTitle = 'Data Team Lead Form';
						$searchType = "dtl_view = '1' OR dtl_edit = '1'";
					}
					?>
					<div class="cnt_title <?php echo $addClass; ?>">
						<h2><?php echo $addTitle; ?></h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post" enctype="multipart/form-data">
					<table class="form_table">
						<?php
						$result = mysql_query("select * from coloum_access where ".$searchType);
						while($row = mysql_fetch_array($result)) {
							$db_coloum = $row['db_column'];
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
									<?php if(($_GET['type'] == 'sales' && $row['sales_edit'] == '1') || ($_GET['type'] == 'dm' && $row['dm_edit'] == '1') || ($_GET['type'] == 'dtl' && $row['dtl_edit'] == '1')) {
										if($row['column_type'] == 2) { ?>
											<select class="txtBx" <?php echo $required; ?> name="<?php echo $db_coloum; ?>">
												<?php
												if($db_coloum == 'processed_by') { 
													$userData = mysql_query("select * from admin_login where superior1 = '".$_SESSION['userId']."' and userType= '4' and status = '1' ORDER BY name");
													while($userRow = mysql_fetch_array($userData)) { ?>
														<option value="<?php echo $userRow['userId']; ?>" <?php if($userRow['userId'] == $row1[$db_coloum]) { echo 'selected'; }?>><?php echo $userRow['name']; ?></option>
													<?php }
												} else if($db_coloum == 'executive') {
													$userData = mysql_query("select * from admin_login where userType= '2' and status = '1' ORDER BY name");
													while($userRow = mysql_fetch_array($userData)) { ?>
														<option value="<?php echo $userRow['userId']; ?>" <?php if($userRow['userId'] == $row1[$db_coloum]) { echo 'selected'; }?>><?php echo $userRow['name']; ?></option>
													<?php }
												} ?>
											</select>
										<?php } else if($row['column_type'] == 3){ ?>
											<input type="text" class="txtBx" <?php echo $required; ?> readonly value="<?php if($row1[$db_coloum] != '' && $row1[$db_coloum] != '0000-00-00 00:00:00') { echo date('d-m-Y',strtotime($row1[$db_coloum])); } else { echo date('d-m-Y'); }?>" name="<?php echo $db_coloum; ?>" />
										<?php } else if($row['column_type'] == 5){ ?>
											<div class="subBtn file_btn" style="">Upload
												<input type="file" class="choosefile" <?php echo $required; ?> onchange="$('#file_name').html(this.value);" name="<?php echo $db_coloum; ?>" value=""/>
											</div>
											<span class="file_name" id="file_name">No files selected</span>
										<?php } else if($row['column_type'] == 6) { 
											if($db_coloum == 'feedback') { ?>
												<input type="radio" id="radioxt1" onclick="$('#feed_reasign').hide();" required <?php if($row1[$db_coloum] == 1) { echo 'checked'; } ?> name="<?php echo $db_coloum; ?>" value="1"/><label for="radioxt1" class="rad_lbl">GOOD</label>
												<input type="radio" id="radioxt2" onclick="$('#feed_reasign').show();" required <?php if($row1[$db_coloum] == 2) { echo 'checked'; } ?> name="<?php echo $db_coloum; ?>" value="2"/><label for="radioxt2" class="rad_lbl">BAD</label>
												<div id="feed_reasign" <?php if($row1[$db_coloum] != 2) { echo 'style="display:none"'; }?>>
													<input type="radio" id="radiobt1" checked name="reassign_type" value="1"/><label for="radiobt1" class="rad_lbl">RE-ASSIGN</label>
													<input type="radio" id="radiobt2" name="reassign_type" value="2"/><label for="radiobt2" class="rad_lbl">CLOSE</label>
												</div>
											<?php } else {?>
												<textarea <?php echo $pattern; ?> <?php echo $required; ?> class="txtArea" name="<?php echo $db_coloum; ?>"><?php if($db_coloum != 'comments') { echo $row1[$db_coloum]; }?></textarea>
										<?php }
											} else { ?>
											<input type="text" <?php echo $pattern; echo $required; if($row['column_type'] == 0) { echo ' readonly'; }?> value="<?php echo $row1[$db_coloum]; ?>" class="txtBx" name="<?php echo $db_coloum; ?>"/>
										<?php } 
									} else { 
										if($db_coloum == 'date') {
											echo date('d/m/Y',strtotime($row1[$db_coloum]));
										} else if($db_coloum == 'executive' || $db_coloum == 'assigned_by' || $db_coloum == 'processed_by') {
											$userData = mysql_query("select * from admin_login where userId = '".$row1[$db_coloum]."'");
											$userRow = mysql_fetch_array($userData);
											echo $userRow['name'];
										} else if($db_coloum == 'sample_data_format' || $db_coloum == 'upload_collected') { ?>
											<a href="uploads/sales/<?php echo $row1[$db_coloum]; ?>" target="_blank"><?php echo $row1[$db_coloum]; ?></a>
										<?php } else if($db_coloum == 'comments') {
											$commentsData = mysql_query("select * from comments where requestId = '".$_GET['id']."'");
											while($commentsRow = mysql_fetch_array($commentsData)) {
												echo $commentsRow['comments']."<br />";
											}
										} else if($db_coloum == 'feedback') {
											if($row1[$db_coloum] == 1) {
												echo $feedbackData = '<span class="edit">GOOD</span>';
											} else if($row1[$db_coloum] == 2){
												echo $feedbackData = '<span class="delete">BAD</span>';
											}
											
										} else {
											echo $row1[$db_coloum];
										}
									} ?>
								</td>
							</tr>
						<?php } ?>
						
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="update_data" value="<?php echo $buttonValue; ?>"/></td>
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
<?php
if(isset($_POST['update_data'])) {
	$db_update = '';
	$queryUpdate ='';
	$querySearch = '';
	if($_SESSION['userType'] == 2 || $_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) {
		$queryUpdate = "status='4',CompletedDate='".date('Y-m-d H:i:s')."',";
		$querySearch = 'sales_edit';
		$alertSuccess = 'Feedback send sucessfully';
		$alertFailed = 'Feedback sending failed';
	} else if($_SESSION['userType'] == 3) {
		$queryUpdate = "status='2',assignedDate='".date('Y-m-d H:i:s')."',";
		$querySearch = 'dm_edit';
		$alertSuccess = 'Sales request assigned sucessfully';
		$alertFailed = 'Sales request assiging failed';
	} else if($_SESSION['userType'] == 4) {
		$queryUpdate = "status='3',";
		$querySearch = 'dtl_edit';
		$alertSuccess = 'Sales request completed sucessfully';
		$alertFailed = 'Sales request complete failed';
	}
	$coloumsResult = mysql_query("select * from coloum_access where ".$querySearch." = '1'");
	while($coloumsRow = mysql_fetch_array($coloumsResult)) {
		echo $db_variable = $coloumsRow['db_column'];
		if($coloumsRow['column_type'] == 5) {
			if(!empty($_FILES[$db_variable]["tmp_name"])) {
				$uploadDirectory = "uploads/sales/";
				$allowedExts = array("pdf", "xls", "xlsx", "csv");
				$temp = explode(".", $_FILES[$db_variable]["name"]);
				$RandNumber  = rand(0, 9999999999);
				$extension = end($temp);
				if ($_FILES[$db_variable]["error"] > 0)
				{
					echo "<script>alert('".$_FILES[$db_variable]["error"]."'); window.location='add_sales.php'; </script>";
					exit;
				} else {
					$uploadFileName = $RandNumber."_".date('dmY')."_".$_FILES[$db_variable]["name"];
					if(move_uploaded_file($_FILES[$db_variable]["tmp_name"],$uploadDirectory.$uploadFileName)) {
						$db_update .= $db_variable."='".$uploadFileName."',";
					} else {
						echo "<script>alert('Error: File upload failed'); window.location='add_sales.php'; </script>";
						exit;
					}
				}
			}
		} else if($coloumsRow['column_type'] == 3) {
			$fromDate = date('Y-m-d',strtotime($_POST[$db_variable]))." 00:00:00";
			$db_update .= $db_variable."='".$fromDate."',";
		} else {
			if($db_variable != 'comments') {
				$db_update .= $db_variable."='".$_POST[$db_variable]."',";
			}
		}
	}
	$db_update .= $queryUpdate."modifiedDate='".date('Y-m-d H:i:s')."'";
	
	mysql_query("update sales_request set ".$db_update." where requestId = '".$_GET['id']."'");
	if(mysql_affected_rows() > 0) {
		if(isset($_POST['feedback']) && $_POST['feedback'] == 2) {
			if(isset($_POST['reassign_type']) && $_POST['reassign_type'] == 1) {
				mysql_query("update sales_request set status = '1' where requestId = '".$_GET['id']."'");
			}
		}
		if(isset($_POST['comments']) && $_POST['comments'] != '') {
			mysql_query("insert into comments (userId,requestId,comments,createdDate) values ('".$_SESSION['userId']."','".$_GET['id']."','".$_POST['comments']."','".date('y-m-d H:i:s')."')");
		}
		echo "<script>alert('".$alertSuccess."'); window.location='dashboard.php'; </script>";
	} else {
		echo "<script>alert('".$alertFailed."'); window.location='add_sales.php'; </script>";
	}
}
?>