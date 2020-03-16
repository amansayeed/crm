<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] == '3' && $_SESSION['userType'] == '4' && $_SESSION['userType'] == '9') {
	header('Location: dashboard.php'); exit;
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
					<table class="form_table">
						<?php
						$result1 = mysql_query("select lead_generate.*,country_master.country as countryName from lead_generate LEFT JOIN country_master ON lead_generate.country=country_master.id where leadId = '".$_GET['id']."'");
						$row1 = mysql_fetch_array($result1);
						?>
						<tr>
							<td>Lead code </td>
							<td><?php echo $row1['leadCode']; ?></td>
						</tr>
						<tr>
							<td>Date</td>
							<td><?php echo date('d-M-Y',strtotime($row1['date'])); ?></td>
						</tr>
						<tr>
							<td>Company Name</td>
							<td><?php echo $row1['companyName']; ?></td>
						</tr>
						<tr>
							<td>Contact Person</td>
							<td><?php echo $row1['contactPerson']; ?></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><?php echo $row1['email']; ?></td>
						</tr>
						<tr>
							<td>Address 1</td>
							<td><?php echo $row1['address1']; ?></td>
						</tr>
						<tr>
							<td>Address 2</td>
							<td><?php echo $row1['address2']; ?></td>
						</tr>
						<tr>
							<td>City</td>
							<td><?php echo $row1['city']; ?></td>
						</tr>
						<tr>
							<td>State</td>
							<td><?php echo $row1['state']; ?></td>
						</tr>
						<tr>
							<td>Country</td>
							<td><?php echo $row1['countryName']; ?></td>
						</tr>
						<tr>
							<td>Zip</td>
							<td><?php echo $row1['zip']; ?></td>
						</tr>
						<tr>
							<td>Sec Email 1</td>
							<td><?php echo $row1['secEmail1']; ?></td>
						</tr>
						<tr>
							<td>Sec Email 2</td>
							<td><?php echo $row1['secEmail2']; ?></td>
						</tr>
						<tr>
							<td>Phone</td>
							<td>Code: <?php echo $row1['countryCode']; ?><br />Number: <?php echo $row1['phone']; ?><br />EXT: <?php echo $row1['ext']; ?><br /></td>
						</tr>
						<tr>
							<td>Sec Phone 1</td>
							<td><?php echo $row1['secPhone1']; ?></td>
						</tr>
						<tr>
							<td>Sec Phone 2</td>
							<td><?php echo $row1['secPhone2']; ?></td>
						</tr>
						<tr>
							<td>Fax</td>
							<td><?php echo $row1['fax']; ?></td>
						</tr>
						<tr>
							<td>Brand</td>
							<td><?php echo $row1['brandName']; ?></td>
						</tr>
						<tr>
							<td>Sales Projection</td>
							<td><?php echo $row1['projCurrency']." ".$row1['projValue']; ?></td>
						</tr>
						<tr>
							<td>Lead source</td>
							<td><?php echo $row1['leadSource'];  if($row1['ext_leadSource'] != '') { echo ' - '.$row1['ext_leadSource']; } ?></td>
						</tr>
						<tr>
							<td>Service</td>
							<td><?php echo $row1['service']; if($row1['ext_service'] != '') { echo ' - '.$row1['ext_service']; } ?></td>
						</tr>
						<tr>
							<td>Requirement/Criteria</td>
							<td><?php echo $row1['requirements']; ?></td>
						</tr>
						<tr>
							<td>No of Records</td>
							<td><?php echo $row1['noOfRecords']; ?></td>
						</tr>
						<tr>
							<td>Supporting Document</td>
							<td><a href="uploads/sales/<?php echo $row1['supportDoc']; ?>"><?php echo $row1['supportDoc']; ?></a></td>
						</tr>
						<tr>
							<td>Created by</td>
							<td><?php
								$userData = mysql_query("select * from admin_login where userId = '".$row1['userId']."'");
								$userRow = mysql_fetch_array($userData);
								echo $userRow['name'];
								?></a></td>
						</tr>
						<?php
						if($_SESSION['userType'] != '6' && $row1['status'] != '0') { ?>
							<tr>
								<td>Assigned to</td>
								<td><?php
								$userData = mysql_query("select * from admin_login where userId = '".$row1['assignedTo']."'");
								$userRow = mysql_fetch_array($userData);
								echo $userRow['name'];
								?></td>
							</tr>
						<?php }	?>
						<?php
						if($row1['status'] != '0' && $row1['status'] != '1') { ?>
							<tr>
								<td>Activity/comments</td>
								<td>
									<?php 
									$commentsData = mysql_query("select A.*,B.name from lead_comments as A JOIN admin_login as B ON A.userId = B.userId where leadId = '".$row1['leadId']."'");
									while($commentsRow = mysql_fetch_array($commentsData)) {
										echo $commentsRow['comments']." - by <b>".$commentsRow['name']."</b> ".date('d/m/Y',strtotime($commentsRow['createdDate']))."<br />";
									}
									?>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<td>Status</td>
							<td>
								<?php 
								if($row1['status'] == 0) {
									echo 'Leads to Assign';
								} else if($row1['status'] == 1){
									echo 'Newly Assigned Lead';
								} else if($row1['status'] == 2){
									echo 'Initial Stage';
								} else if($row1['status'] == 3){
									echo 'Pricing Stage';
								} else if($row1['status'] == 4){
									echo 'Follow up';
								} else if($row1['status'] == 5){
									echo 'Deal';
								} else if($row1['status'] == 6) {
									echo 'Dropped';
								} else if($row1['status'] == 7) {
									echo 'Disqualified';
								}
								?>
							</td>
						</tr>
						<?php if($row1['status'] == 5){ ?>
							<tr>
								<td>Deal Value</td>
								<td><?php echo $row1['dealCurrency']." ".$row1['dealAmount']; ?></td>
							</tr>
							<tr>
								<td>Payment Type</td>
								<td><?php echo $row1['paymentType']; ?></td>
							</tr>
							<?php if($_SESSION['userType'] == '1' || $_SESSION['userType'] == '2' || $_SESSION['userType'] == '5' || $_SESSION['userType'] == '8' || $_SESSION['userType'] == '10') { ?>
								<tr>
									<td>Work Order</td>
									<td><a href="uploads/sales/<?php echo $row1['workOrder']; ?>"><?php echo $row1['workOrder']; ?></a></td>
								</tr>
							<?php } ?>
						<?php } else if($row1['status'] == 6 || $row1['status'] == 7){ ?>
							<tr>
								<td>Drop/Disqualify Note</td>
								<td><?php echo $row1['dropNote']; ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td><?php if($row1['status'] == 5) { echo 'Delivery Date'; } else { echo 'Next Followup Date'; } ?></td>
							<td><?php if($row1['nextFollowup'] != '0000-00-00 00:00:00') { echo date('d-m-Y',strtotime($row1['nextFollowup'])); } ?></td>
						</tr>
						<?php
						$sampleQuery = mysql_query("select * from sales_request where leadId = '".$row1['leadId']."'");
						if(mysql_num_rows($sampleQuery) > 0) { ?>
							<tr>
								<td>Requested Sample Id's</td>
								<td>
									<?php while($sampleRow = mysql_fetch_array($sampleQuery)) {
										echo $sampleRow['code']."<br />";
									} ?>
								</td>
							</tr>
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