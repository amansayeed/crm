<?php
session_start();
ob_start();
ini_set('memory_limit', '2000M'); //for 2GB
//For no limits
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != '2' && $_SESSION['userType'] != '5' && $_SESSION['userType'] != '8' && $_SESSION['userType'] != '10' && $_SESSION['userType'] != '1') {
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
					<div class="cnt_title sales_user">
						<h2>Sample Request Form</h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post" enctype="multipart/form-data">
					<table class="form_table">
						<tr>
							<td>Lead Code</td>
							<td>
								<?php 
									$leadCodeQuery = mysql_query("select * from lead_generate where leadId = '".$_GET['id']."'");
									$leadCode = mysql_fetch_array($leadCodeQuery);
									echo $leadCode['leadCode']; 
								?>
							</td>
						</tr>
						<?php
						if($_SESSION['userGroup'] == 1) { 
							$result1 = mysql_query("select * from sales_request where salesDivision='1' ORDER BY createdDate DESC LIMIT 0,1");
							$row1 = mysql_fetch_array($result1);
							$previousCode = str_replace($_SESSION['userGroupName'].' ', '', $row1['code']);
							$newCode = $previousCode + 1;
						} else {
							$result1 = mysql_query("select * from sales_request where salesDivision='2' ORDER BY createdDate DESC LIMIT 0,1");
							$row1 = mysql_fetch_array($result1);
							$previousCode = str_replace($_SESSION['userGroupName'].' ', '', $row1['code']);
							$newCode = $previousCode + 1;
						}
						$result = mysql_query("select * from coloum_access where sales_add = '1'");
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
									<?php if($row['column_type'] == 2) { ?>
										<select class="txtBx" <?php echo $required; ?> name="<?php echo $row['db_column']; ?>">
										</select>
									<?php } else if($row['column_type'] == 3){ ?>
										<input type="text" class="txtBx" <?php echo $required; ?> readonly value="<?php echo date('d-m-Y'); ?>" name="<?php echo $row['db_column']; ?>" />
									<?php } else if($row['column_type'] == 5){ ?>
										<div class="subBtn file_btn" style="">Upload
											<input type="file" class="choosefile" <?php echo $required; ?> onchange="$('#file_name').html(this.value);" name="<?php echo $row['db_column']; ?>" value=""/>
										</div>
										<span class="file_name" id="file_name">No files selected</span>
									<?php } else if($row['column_type'] == 6) { ?>
										<textarea <?php echo $pattern; ?> <?php echo $required; ?> class="txtArea" name="<?php echo $row['db_column']; ?>"></textarea>
									<?php } else { ?>
										<input type="text" <?php echo $pattern; echo $required; if($row['column_type'] == 0) { echo ' readonly'; }?> value="<?php if($row['db_column'] == 'code' ) { echo $_SESSION['userGroupName'].' '.str_pad($newCode, 4, "0", STR_PAD_LEFT); } ?>" class="txtBx" name="<?php echo $row['db_column']; ?>"/>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<td></td>
							<input type="hidden" value="<?php echo $_GET['id']; ?>" name="leadId" />
							<td><input type="submit" class="subBtn frm_sub" name="add_sales" value="Submit"/></td>
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
if(isset($_POST['add_sales'])) {
	$db_fields = '';
	$db_values = '';
	$coloumsResult = mysql_query("select * from coloum_access where sales_add = '1'");
	while($coloumsRow = mysql_fetch_array($coloumsResult)) {
		$db_variable = $coloumsRow['db_column'];
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
						$db_fields .= $db_variable.",";
						$db_values .= "'".$uploadFileName."',";
					} else {
						echo "<script>alert('Error: File upload failed'); window.location='add_sales.php'; </script>";
						exit;
					}
				}
			}
		} else if($coloumsRow['column_type'] == 3) {
			$db_fields .= $db_variable.",";
			$fromDate = date('Y-m-d',strtotime($_POST[$db_variable]))." 00:00:00";
			$db_values .= "'".$fromDate."',";
		} else {
			$db_fields .= $db_variable.",";
			$db_values .= "'".$_POST[$db_variable]."',";
		}
	}
	if(isset($_POST['leadId'])) {
		$db_fields .= "leadId,";
		$db_values .= "'".$_POST['leadId']."',";
	}
	$db_fields .= "salesDivision,executive,status,createdDate,modifiedDate";
	$db_values .= "'".$_SESSION['userGroup']."','".$_SESSION['userId']."','1','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'";
	mysql_query("Insert into sales_request (".$db_fields.") values (".$db_values.")");
	if(mysql_affected_rows() > 0) {
		$lastInsertId = mysql_insert_id();
		$leadCode = "SM".str_pad($lastInsertId, 4, "0", STR_PAD_LEFT);
		mysql_query("update sales_request set code = '".$leadCode."' where requestId = '".$lastInsertId."'");
		echo "<script>alert('Samples requested successfully'); window.location='dashboard.php'; </script>";
	} else {
		echo "<script>alert('Samples request failed'); window.location='add_sales.php'; </script>";
	}
}
?>