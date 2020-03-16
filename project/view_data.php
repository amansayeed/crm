<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if(!isset($_GET['type'])) {
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
					<?php
					if($_GET['type'] == 'sales') {
						$addClass = 'sales_user';
						$addTitle = 'Sales View';
						$searchType = "sales_view = '1'";
					} else if($_GET['type'] == 'dm') {
						$addClass = 'dm_user';
						$addTitle = 'Data Manager View';
						$searchType = "dm_view = '1'";
					} else if($_GET['type'] == 'dtl') {
						$addClass = 'dtl_user';
						$addTitle = 'Data Team Lead View';
						$searchType = "dtl_view = '1'";
					} else {
						$addClass = 'dtl_user';
						$addTitle = 'View Details';
						$searchType = "excel_column != ''";
					}
					?>
					<div class="cnt_title <?php echo $addClass; ?>">
						<h2><?php echo $addTitle; ?></h2>
					</div>
					<div class="clear"></div>
					<table class="form_table">
						<?php
						$result1 = mysql_query("select * from project_request where requestId = '".$_GET['id']."'");
						$row1 = mysql_fetch_array($result1);
						$leadResult = mysql_query("select * from lead_generate where status = '5' and leadId = '".$row1['leadId']."'");
						$leadRow = mysql_fetch_array($leadResult);
						$result = mysql_query("select * from coloum_access_project where ".$searchType." ORDER BY sNo");
						while($row = mysql_fetch_array($result)) { 
							$db_coloum = $row['db_column'];?>
							<tr>
								<td><?php echo $row['excel_column']; ?></td>
								<td>
									<?php if($db_coloum == 'date' || $db_coloum == 'deliveryDate') {
										echo date('d/m/Y',strtotime($row1[$db_coloum]));
									} else if($db_coloum == 'createdBy' || $db_coloum == 'assigned_by' || $db_coloum == 'processed_by') {
										$userData = mysql_query("select * from admin_login where userId = '".$row1[$db_coloum]."'");
										$userRow = mysql_fetch_array($userData);
										echo $userRow['name'];
									} else if($db_coloum == 'sample_data_format' || $db_coloum == 'upload_collected') { ?>
										<?php if($db_coloum == 'sample_data_format' && $row1[$db_coloum] == '') { ?>
											<a href="../uploads/sales/<?php echo $leadRow['supportDoc']; ?>" target="_blank"><?php echo $leadRow['supportDoc']; ?></a>
										<?php } else {
											if($db_coloum == 'upload_collected') {
												$uploadQuery = mysql_query("select * from project_outputs where projectId = '".$_GET['id']."'");
												while($uploadedFile = mysql_fetch_array($uploadQuery)) { ?>
													<a href="uploads/sales/<?php echo $uploadedFile['filename']; ?>" target="_blank"><?php echo $uploadedFile['filename']; ?></a><br />
												<?php }
											} else { ?>
												<a href="uploads/sales/<?php echo $row1[$db_coloum]; ?>" target="_blank"><?php echo $row1[$db_coloum]; ?></a>
											<?php }
										} ?>
									<?php } else if($db_coloum == 'comments') {
										$commentsData = mysql_query("select A.*,B.name from project_comments as A JOIN admin_login as B ON A.userId = B.userId where requestId = '".$_GET['id']."'");
										while($commentsRow = mysql_fetch_array($commentsData)) {
											echo $commentsRow['comments']." - by <b>".$commentsRow['name']."</b><br />";
										}
									} else if($db_coloum == 'feedback') {
										if($row1[$db_coloum] == 1) {
											echo $feedbackData = '<span class="edit">GOOD</span>';
										} else if($row1[$db_coloum] == 2){
											echo $feedbackData = '<span class="delete">BAD</span>';
										}
										
									} else if($db_coloum == 'comStatus') {
										if($row1[$db_coloum] == 1) {
											echo 'Completed';
										} else if($row1[$db_coloum] == 2){
											echo 'Partially Completed';
										} else {
											echo '';
										}
									} else {
										echo $row1[$db_coloum];
									}
									?>
								</td>
							</tr>
							<?php if($row['db_column'] == 'date' ) { ?>
								<tr>
									<td>Lead Code</td>
									<td><?php echo $leadRow['leadCode']; ?></td>
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
								<?php if($_SESSION['userType'] == 11) { ?>
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
							<?php } ?>
							<?php if($row['db_column'] == 'sample_data_format') { 
								if($_SESSION['userType'] == 11 || $_SESSION['userType'] == 1) { ?>
									<tr>
										<td>Work Order</td>
										<td><a href="../uploads/sales/<?php echo $leadRow['workOrder']; ?>" target="_blank"><?php echo $leadRow['workOrder'] ?></a></td>
									</tr>
								<?php }
								if($_SESSION['userType'] == 11) { ?>
									<tr>
										<td>Deal Value</td>
										<td><?php echo $row1['dealCurrency']." ".$row1['dealAmount']; ?></td>
									</tr>
									<tr>
										<td>Payment Type</td>
										<td><?php echo $leadRow['paymentType']; ?></td>
									</tr>
								<?php } ?>
								<tr>
									<td>Service</td>
									<td><?php echo $leadRow['service']; if($leadRow['ext_service'] != '') { echo ' - '.$leadRow['ext_service']; } ?></td>
								</tr>
							<?php } ?>
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