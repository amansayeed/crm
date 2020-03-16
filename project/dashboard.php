<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if(isset($_GET['clear']) && $_GET['clear'] == 1) {
	unset($_SESSION['fromDate']);
	unset($_SESSION['toDate']);
	unset($_SESSION['searchCode']);
	unset($_SESSION['searchKeyword']);
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
<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-3d.js"></script>
<script>
function fromToDate() {
	$flag = 0;
	if($('#fromDate').val() == '' && $('#toDate').val() != '') {
		alert('Please choose From Date');
		$flag = 1;
		return false;
	}
	if($('#fromDate').val() != '' && $('#toDate').val() == '') {
		alert('Please choose To Date');
		$flag = 1;
		return false;
	}
	if($flag == 0) {
		$.post('functions/ajax_request.php', {update_date:'YES',fromDate:$('#fromDate').val(),toDate:$('#toDate').val(),searchCode:$('#searchCode').val(),searchKeyword:$('#searchKeyword').val()},function(data){
			if(data == 'success') {
				var str = window.location.href;
				var finalUrl = str.replace('clear=1', '-');
				window.location=finalUrl;
			} 
		});
	}
	return false;
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
						<h2>Dashboard</h2>
					</div>
					<div class="filter_area">
						<table class="filter_table" style="">
							<form action="" method="post" onSubmit="return fromToDate();">
							<tr>
								<td height="50px">
									Search By:<input type="text" value="<?php if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') { echo $_SESSION['searchCode']; } ?>" id="searchCode" class="ftr_txt" name="searchCode" placeholder="CODE" />
									<input type="text" value="<?php if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') { echo $_SESSION['searchKeyword']; } ?>" id="searchKeyword" class="ftr_txt" name="searchKeyword" placeholder="Keyword" />
								</td>
								<td>
									From: <input type="text" name="fromDate" id="fromDate" class="ftr_txt datetimepicker" value="<?php if(isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '') { echo date('d-m-Y',strtotime($_SESSION['fromDate'])); } ?>" readonly placeholder="From Date"/>
									to: <input type="text" name="toDate" id="toDate" class="ftr_txt datetimepicker" value="<?php if(isset($_SESSION['toDate']) && $_SESSION['toDate'] != '') { echo date('d-m-Y',strtotime($_SESSION['toDate'])); } ?>" readonly placeholder="To Date"/>
								</td>
								<td align="right"><input type="submit" class="ftr_sub subBtn frm_sub" name="update_date" value="Filter"/></td>
							</tr>
							</form>
						</table>
					</div>
					<div class="clear"></div>
					<div id="container"></div>
					<?php if($_SESSION['userType'] == 1) { ?>
						<div id="container1"></div>
					<?php } ?>
					
					<?php if($_SESSION['userType'] == 11) { ?>
					<h3>New Deals</h3>
					<table class="data_table">
						<tr>
							<th>S.No</th>
							<th>Lead Code</th>
							<th>Date</th>
							<th>Brand</th>
							<th>Company Name</th>
							<th>Status</th>
							<th width="140px;">Action</th>
						</tr>
						<?php
						$searchQuery = " AND (userId IN (select userId from admin_login where superior1 IN (select userId from admin_login where superior1 = '".$_SESSION['superior1']."')) OR assignedTo IN (select userId from admin_login where superior1 IN (select userId from admin_login where superior1 = '".$_SESSION['superior1']."')) OR userId IN (select userId from admin_login where superior1 = '".$_SESSION['superior1']."') OR assignedTo IN (select userId from admin_login where superior1 = '".$_SESSION['superior1']."') OR userId = '".$_SESSION['superior1']."' OR assignedTo = '".$_SESSION['superior1']."')";					
						$result = mysql_query("select * from lead_generate where status = '5' AND leadId NOT IN (select leadId from project_request)".$searchQuery);
						$i = 1;
						while($row = mysql_fetch_array($result)) { 
							if($row['status'] == 0) {
								$addClass = 'delete';
								$statusName = 'Leads to Assign';
							} else if($row['status'] == 1) {
								$addClass = 'view';
								$statusName = 'Newly Assigned lead';
							} else if($row['status'] == 2) {
								$addClass = 'edit';
								$statusName = 'Initial Stage';
							} else if($row['status'] == 3) {
								$addClass = 'edit';
								$statusName = 'Pricing stage';
							} else if($row['status'] == 4) {
								$addClass = 'edit';
								$statusName = 'Followup';
							} else if($row['status'] == 5) {
								$addClass = 'edit';
								$statusName = 'Deal';
							} else if($row['status'] == 6) {
								$addClass = 'delete';
								$statusName = 'Dropped';
							} else if($row['status'] == 7) {
								$addClass = 'delete';
								$statusName = 'Disqualified';
							} ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $row['leadCode']; ?></td>
							<td><?php echo date('d/m/Y',strtotime($row['date'])); ?></td>
							<td><?php echo $row['brandName']; ?></td>
							<td><?php echo $row['companyName']; ?></td>
							<td><span class="<?php echo $addClass; ?>"><?php echo $statusName; ?></span></td>
							<td>
								<a href="add_sales.php?id=<?php echo $row['leadId']; ?>" class="view">Initiate Project</a>
							</td>
						</tr>
						<?php 
						$i = $i + 1;
						} ?>
					</table>
					<?php } ?>
					
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
	<?php
	if(isset($_SESSION['fromDate']) && isset($_SESSION['toDate']) && $_SESSION['fromDate'] != '' &&$_SESSION['fromDate'] != '') {
		$dateBtw = " AND createdDate BETWEEN '".$_SESSION['fromDate']."' AND '".$_SESSION['toDate']."'";
	} else {
		$dateBtw = '';
	}
	if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') {
		$searchCode = " AND leadId IN (select leadId from lead_generate where leadCode LIKE '%".$_SESSION['searchCode']."%')";
	} else {
		$searchCode = '';
	}
	if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') {
		$searchKeyword = " AND leadId IN (select leadId from lead_generate where leadCode LIKE '%".$_SESSION['searchKeyword']."%')";
	} else {
		$searchKeyword = '';
	}
	$statusQuery = mysql_query("SELECT (SELECT count(*) FROM project_request WHERE status='1'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Pending, (SELECT count(*) FROM project_request WHERE status='2'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Assigned, (SELECT count(*) FROM project_request WHERE status='3'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Completed, (SELECT count(*) FROM project_request WHERE status='4'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Closed, (SELECT count(*) FROM project_request WHERE status='5'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Approval, (SELECT count(*) FROM project_request WHERE status='6'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Reassigned, (SELECT count(*) FROM project_request WHERE status='7'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS Rejected, (SELECT count(*) FROM project_request WHERE status='8'".$whereCase2.$dateBtw.$searchCode.$searchKeyword.") AS PartiallyCompleted FROM project_request LIMIT 0,1");
	$statusRow = mysql_fetch_array($statusQuery);
	?>
	<script type="text/javascript">
	$(function () {
		$('#container').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45
				}
			},
			title: {
				text: 'Project Status'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					events:{
						click: function (event, i) {
							if(event.point.name == 'Unassigned Projects') {
								pointValue = 1;
							} else if(event.point.name == 'Assigned Projects') {
								pointValue = 2;
							} else if(event.point.name == 'Awaiting Approval') {
								pointValue = 5;
							} else if(event.point.name == 'Completed') {
								pointValue = 3;
							} else if(event.point.name == 'Closed') {
								pointValue = 4;
							} else if(event.point.name == 'Reassigned') {
								pointValue = 6;
							} else if(event.point.name == 'Rejected') {
								pointValue = 7;
							} else if(event.point.name == 'Partially Completed') {
								pointValue = 8;
							}
							window.location='manage_data.php?list='+pointValue+'&listType=1';
						}
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ <?php if($_SESSION['userType'] != 13 && $statusRow['Pending'] > 0) { ?>
							['Unassigned Projects', <?php echo $statusRow['Pending']; ?>],
						<?php } ?>
						<?php if($statusRow['Assigned'] > 0) { ?>
							['Assigned Projects', <?php echo $statusRow['Assigned']; ?>],
						<?php } ?>
						<?php if($statusRow['Approval'] > 0) { ?>
							['Awaiting Approval', <?php echo $statusRow['Approval']; ?>],
						<?php } ?>
						<?php if($statusRow['Completed'] > 0) { ?>
							['Completed', <?php echo $statusRow['Completed']; ?>],
						<?php } ?>
						<?php if($statusRow['PartiallyCompleted'] > 0) { ?>
							['Partially Completed', <?php echo $statusRow['PartiallyCompleted']; ?>],
						<?php } ?>
						<?php if(($_SESSION['userType'] == 11 || $_SESSION['userType'] == 1) && $statusRow['Closed'] > 0) { ?>
							['Closed', <?php echo $statusRow['Closed']; ?>],
						<?php } ?>
						<?php if(($_SESSION['userType'] == 12 || $_SESSION['userType'] == 13 || $_SESSION['userType'] == 1) && $statusRow['Reassigned'] > 0) { ?>
							['Reassigned', <?php echo $statusRow['Reassigned']; ?>],
						<?php } ?>
						<?php if(($_SESSION['userType'] == 12 || $_SESSION['userType'] == 13 || $_SESSION['userType'] == 1) && $statusRow['Rejected'] > 0) { ?>
							['Rejected', <?php echo $statusRow['Rejected']; ?>],
						<?php } ?>
				]
			}]
		});
	});
	<?php
	if($_SESSION['userType'] == 1) { 
		$feedbackQuery = mysql_query("SELECT (SELECT count(*) FROM project_request WHERE status != '0' and feedback = '1'".$dateBtw.$searchCode.$searchKeyword.") AS good, (SELECT count(*) FROM project_request WHERE status != '0' and feedback = '2'".$dateBtw.$searchCode.$searchKeyword.") AS bad FROM project_request LIMIT 0,1");
		$feedbackRow = mysql_fetch_array($feedbackQuery);
	?>
	$(function () {
		$('#container1').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45
				}
			},
			title: {
				text: 'Feedback Wise'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					events:{
						click: function (event, i) {
							if(event.point.name == 'GOOD') {
								pointValue = 1;
							} else if(event.point.name == 'BAD') {
								pointValue = 2;
							}
							window.location='manage_data.php?list='+pointValue+'&listType=2';
						}
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ 
					<?php if($feedbackRow['good'] > 0) { ?>
						['GOOD', <?php echo $feedbackRow['good']; ?>],
					<?php } ?>
					<?php if($feedbackRow['bad'] > 0) { ?>
						['BAD', <?php echo $feedbackRow['bad']; ?>]
					<?php } ?>
				]
			}]
		});
	});
	<?php } ?>
	</script>
	<script>
		jQuery('.datetimepicker').datetimepicker({
			timepicker:false,
			format:'d-m-Y',
			maxDate:'+1970/01/01',
			scrollInput: false
		});
	</script>
</body>
</html>