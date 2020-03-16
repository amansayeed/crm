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
					<form action="" method="post">
					<table class="form_table">
						<?php
						$result1 = mysql_query("select * from lead_generate where leadId = '".$_GET['id']."'");
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
							<td>Sec Email 1</td>
							<td><?php echo $row1['secEmail1']; ?></td>
						</tr>
						<tr>
							<td>Sec Email 2</td>
							<td><?php echo $row1['secEmail2']; ?></td>
						</tr>
						<tr>
							<td>Phone</td>
							<td><?php echo $row1['phone']; ?></td>
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
						<?php } ?>
						<tr>
							<td>Next Followup Date</td>
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
										<option value="<?php echo $userList['userId']; ?>" <?php if($row1['assignedTo'] == $userList['userId']) { echo 'selected'; } ?>><?php echo $userList['name']; ?></option>
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
<?php
if(isset($_POST['reassign_user'])) {
	mysql_query("update sales_request set executive='".$_POST['reassignTo']."' where leadId='".$_POST['hiddenLeadId']."'");
	mysql_query("update lead_generate set assignedTo='".$_POST['reassignTo']."' where leadId='".$_POST['hiddenLeadId']."'");
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('Lead Reassign successful'); window.location='dashboard_lead.php'; </script>";
	} else {
		echo "<script>alert('Lead Reassign Failed'); window.location='dashboard_lead.php'; </script>";
	}
}
?>