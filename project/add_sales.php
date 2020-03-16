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
if($_SESSION['userType'] != '11' ) {
	header('Location: dashboard.php'); exit;
}
include_once("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Sphere</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="../js/jquery.datetimepicker.js"></script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title sales_user">
						<h2>Project Initiation Process</h2>
					</div>
					<div class="clear"></div>
					<?php
					$leadResult = mysql_query("select * from lead_generate where status = '5' and leadId = '".$_GET['id']."'");
					$leadRow = mysql_fetch_array($leadResult);
					?>
					<form action="" method="post" enctype="multipart/form-data">
					<table class="form_table">
						<?php
						if($_SESSION['userGroup'] == 1) { 
							$result1 = mysql_query("select * from project_request where salesDivision='1' ORDER BY createdDate DESC LIMIT 0,1");
							$row1 = mysql_fetch_array($result1);
							$previousCode = str_replace($_SESSION['userGroupName'].' ', '', $row1['code']);
							$newCode = $previousCode + 1;
						} else {
							$result1 = mysql_query("select * from project_request where salesDivision='2' ORDER BY createdDate DESC LIMIT 0,1");
							$row1 = mysql_fetch_array($result1);
							$previousCode = str_replace($_SESSION['userGroupName'].' ', '', $row1['code']);
							$newCode = $previousCode + 1;
						}
						$result = mysql_query("select * from coloum_access_project where sales_add = '1' ORDER BY sNo");
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
							
							$fieldValue = '';
							if($row['db_column'] == 'code' ) {
								$fieldValue = $_SESSION['userGroupName'].' '.str_pad($newCode, 4, "0", STR_PAD_LEFT);
							} else if($row['db_column'] == 'sample_criteria' ) {
								$fieldValue = $leadRow['requirements'];
							} else if($row['db_column'] == 'no_of_sample_request' ) {
								$fieldValue = $leadRow['noOfRecords'];
							} else if($row['db_column'] == 'date') {
								$fieldValue = date('d-m-Y');
							} else if($row['db_column'] == 'deliveryDate') {
								$fieldValue = date('d-m-Y',strtotime($leadRow['nextFollowup']));
							} else if($row['db_column'] == 'salesContact') {
								$userData = mysql_query("select * from admin_login where userId = '".$leadRow['assignedTo']."'");
								$userRow = mysql_fetch_array($userData);
								$fieldValue = $userRow['name'];
							}
						?>
							<tr>
								<td><?php echo $row['excel_column']; ?></td>
								<td>
									<?php if($row['column_type'] == 2) { ?>
										<select class="txtBx" <?php echo $required; ?> name="<?php echo $row['db_column']; ?>">
											<?php
											if($row['db_column'] == 'category') { ?>
												<option>B2B</option>
												<option>Tech</option>
											<?php } else if($row['db_column'] == 'orderType') { ?>
												<option>New</option>
												<option>Repeat</option>
											<?php } ?>
										</select>
									<?php } else if($row['column_type'] == 3){ ?>
										<input type="text" class="txtBx <?php if($row['db_column'] == 'deliveryDate') { echo 'datetimepicker'; }?>" <?php echo $required; ?> readonly value="<?php echo $fieldValue; ?>" name="<?php echo $row['db_column']; ?>" />
									<?php } else if($row['column_type'] == 5){ ?>
										<div class="subBtn file_btn" style="">Upload
											<input type="file" class="choosefile" <?php echo $required; ?> onchange="$('#file_name').html(this.value);" name="<?php echo $row['db_column']; ?>" value=""/>
										</div>
										<span class="file_name" id="file_name">No files selected</span>
										<?php if($row['db_column'] == 'sample_data_format' && $leadRow['supportDoc'] != '') { ?>
											<br /><br /><br /><a href="../uploads/sales/<?php echo $leadRow['supportDoc']; ?>" target="_blank"><?php echo $leadRow['supportDoc'] ?></a>
										<?php } ?>
									<?php } else if($row['column_type'] == 6) { ?>
										<textarea <?php echo $pattern; ?> <?php echo $required; ?> class="txtArea" name="<?php echo $row['db_column']; ?>"><?php echo $fieldValue; ?></textarea>
									<?php } else { ?>
										<input type="text" <?php echo $pattern; echo $required; if($row['column_type'] == 0) { echo ' readonly'; }?> value="<?php echo $fieldValue; ?>" class="txtBx" name="<?php echo $row['db_column']; ?>"/>
									<?php } ?>
								</td>
							</tr>
							<?php if($row['db_column'] == 'date' ) { ?>
								<tr>
									<td>Lead Code</td>
									<td><input type="hidden" value="<?php echo $leadRow['leadId']; ?>" name="hidLeadId" /><?php echo $leadRow['leadCode']; ?></td>
								</tr>
								<tr>
									<td>Sample Code</td>
									<td>
										<?php  $sampleCodes = mysql_query("select code from sales_request where leadId = '".$leadRow['leadId']."'");
										while($sampleRow = mysql_fetch_array($sampleCodes)) {
											echo $sampleRow['code'].", "; 
										} ?>
									</td>
								</tr>
								<tr>
									<td>Company</td>
									<td><?php echo $leadRow['companyName']; ?></td>
								</tr>
								<tr>
									<td>Contact Person</td>
									<td><?php echo $leadRow['contactPerson']; ?></td>
								</tr>
								<tr>
									<td>Email</td>
									<td><?php echo $leadRow['email']; ?></td>
								</tr>
								<tr>
									<td>Phone Number</td>
									<td><?php echo $leadRow['phone']; ?></td>
								</tr>
							<?php } ?>
							<?php if($row['db_column'] == 'sample_data_format' ) { ?>
								<tr>
									<td>Work Order</td>
									<td><a href="../uploads/sales/<?php echo $leadRow['workOrder']; ?>" target="_blank"><?php echo $leadRow['workOrder'] ?></td>
								</tr>
								<tr>
									<td>Deal Value</td>
									<td>
										<select class="txtBx" style="width:150px;" required name="dealCurrency">
											<option value="">Select Currency</option>
											<option value="INR" <?php if($leadRow['dealCurrency'] == 'INR') { echo 'selected'; } ?>>INR</option>
											<option value="USD" <?php if($leadRow['dealCurrency'] == 'USD') { echo 'selected'; } ?>>USD</option>
											<option value="EUR" <?php if($leadRow['dealCurrency'] == 'EUR') { echo 'selected'; } ?>>EUR</option>
											<option value="AED" <?php if($leadRow['dealCurrency'] == 'AED') { echo 'selected'; } ?>>AED</option>
											<option value="CHF" <?php if($leadRow['dealCurrency'] == 'CHF') { echo 'selected'; } ?>>CHF</option>
											<option value="SEK" <?php if($leadRow['dealCurrency'] == 'SEK') { echo 'selected'; } ?>>SEK</option>
											<option value="LKR" <?php if($leadRow['dealCurrency'] == 'LKR') { echo 'selected'; } ?>>LKR</option>
											<option value="ZAR" <?php if($leadRow['dealCurrency'] == 'ZAR') { echo 'selected'; } ?>>ZAR</option>
											<option value="SAR" <?php if($leadRow['dealCurrency'] == 'SAR') { echo 'selected'; } ?>>SAR</option>
											<option value="RUB" <?php if($leadRow['dealCurrency'] == 'RUB') { echo 'selected'; } ?>>RUB</option>
											<option value="QAR" <?php if($leadRow['dealCurrency'] == 'QAR') { echo 'selected'; } ?>>QAR</option>
											<option value="PHP" <?php if($leadRow['dealCurrency'] == 'PHP') { echo 'selected'; } ?>>PHP</option>
											<option value="OMR" <?php if($leadRow['dealCurrency'] == 'OMR') { echo 'selected'; } ?>>OMR</option>
											<option value="NZD" <?php if($leadRow['dealCurrency'] == 'NZD') { echo 'selected'; } ?>>NZD</option>
											<option value="MYR" <?php if($leadRow['dealCurrency'] == 'MYR') { echo 'selected'; } ?>>MYR</option>
											<option value="KWD" <?php if($leadRow['dealCurrency'] == 'KWD') { echo 'selected'; } ?>>KWD</option>
											<option value="AUD" <?php if($leadRow['dealCurrency'] == 'AUD') { echo 'selected'; } ?>>AUD</option>
											<option value="GBP" <?php if($leadRow['dealCurrency'] == 'GBP') { echo 'selected'; } ?>>GBP</option>
											<option value="CNY" <?php if($leadRow['dealCurrency'] == 'CNY') { echo 'selected'; } ?>>CNY</option>
											<option value="CAD" <?php if($leadRow['dealCurrency'] == 'CAD') { echo 'selected'; } ?>>CAD</option>
										</select>
										<input type="" required style="width:230px;" pattern="[0-9.]+" title="Numeric Only (0-9)" id="dealValue" value="<?php echo $leadRow['dealAmount']; ?>" class="txtBx" name="dealValue"/>
									</td>
								</tr>
								<tr>
									<td>Payment Type</td>
									<td><?php echo $leadRow['paymentType']; ?></td>
								</tr>
								<tr>
									<td>Service</td>
									<td><?php echo $leadRow['service']; if($leadRow['ext_service'] != '') { echo ' - '.$leadRow['ext_service']; } ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
						<tr>
							<td></td>
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
			minDate:'-1970/01/01',
			scrollInput: false
		});
</script>
<?php
if(isset($_POST['add_sales'])) {
	$db_fields = '';
	$db_values = '';
	$coloumsResult = mysql_query("select * from coloum_access_project where sales_add = '1'");
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
	$db_fields .= "leadId,dealCurrency,dealAmount,salesDivision,createdBy,status,createdDate,modifiedDate";
	$db_values .= "'".$_POST['hidLeadId']."','".$_POST['dealCurrency']."','".$_POST['dealValue']."','".$_SESSION['userGroup']."','".$_SESSION['userId']."','1','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'";
	mysql_query("Insert into project_request (".$db_fields.") values (".$db_values.")");
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('Project Initiation Added Successfully'); window.location='dashboard.php'; </script>";
	} else {
		echo "<script>alert('Project Initiation Adding Failed'); window.location='add_sales.php'; </script>";
	}
}
?>