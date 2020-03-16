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
if($_SESSION['userType'] == 9) {
	header('Location: manage_user.php'); exit;
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
<script src="js/highcharts.js"></script>
<script src="js/highcharts-3d.js"></script>
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
					<?php if($_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
						<div id="container1"></div>
					<?php } ?>
					<?php if($_SESSION['userType'] == 2 || $_SESSION['userType'] == 5) { ?>
					<h3>Today's Followup</h3>
					<table class="data_table">
						<tr>
							<th>S.No</th>
							<th>Lead Code</th>
							<th>Date</th>
							<th>Brand</th>
							<th>Company Name</th>
							<th>Status</th>
							<th>Assigned to</th>
							<th width="140px;">Action</th>
						</tr>
						<?php
						$result = mysql_query("select * from lead_generate where assignedTo = '".$_SESSION['userId']."' AND status != '5' AND nextFollowup BETWEEN '".date('Y-m-d')." 00:00:00' AND '".date('Y-m-d')." 23:59:59'");
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
							<td><?php
								$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
								$userRow = mysql_fetch_array($userData);
								echo $userRow['name'];
								?>
							</td>
							<td>
								<a href="view_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="view">View</a>
								<?php if((($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) && $row['status'] == 0) || (($_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) && (($row['status'] == 0 || $row['status'] == 1 || $row['assignedTo'] == $_SESSION['userId']) && $row['status'] != 5)) || ( $_SESSION['userType'] == 2 && $row['assignedTo'] == $_SESSION['userId'] && $row['status'] != 5)) { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="edit_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="edit">Edit</a>
								<?php } ?>
							</td>
						</tr>
						<?php 
						$i = $i + 1;
						} ?>
					</table>
					<?php } ?>
					<?php if($_SESSION['userType'] == 8) { ?>
					<h3>Lead Generator leads</h3>
					<table class="data_table">
						<tr>
							<th>S.No</th>
							<th>Lead Code</th>
							<th>Date</th>
							<th>Brand</th>
							<th>Company Name</th>
							<th>Status</th>
							<th>Assigned to</th>
							<th width="140px;">Action</th>
						</tr>
						<?php
						$result = mysql_query("select * from lead_generate where userId IN (select userId from admin_login where userType = '7' AND superior1 = '".$_SESSION['userId']."') ORDER BY createddate DESC");
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
							<td><?php
								$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
								$userRow = mysql_fetch_array($userData);
								echo $userRow['name'];
								?>
							</td>
							<td>
								<a href="view_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="view">View</a>
								<?php if((($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) && $row['status'] == 0) || (($_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) && (($row['status'] == 0 || $row['status'] == 1 || $row['assignedTo'] == $_SESSION['userId']) && $row['status'] != 5)) || ( $_SESSION['userType'] == 2 && $row['assignedTo'] == $_SESSION['userId'] && $row['status'] != 5)) { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="edit_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="edit">Edit</a>
								<?php } ?>
							</td>
						</tr>
						<?php 
						$i = $i + 1;
						} ?>
					</table>
					<?php } ?>
					<?php if($_SESSION['userType'] == 10) { ?>
					<h3>Marketing Co-ordinator leads</h3>
					<table class="data_table">
						<tr>
							<th>S.No</th>
							<th>Lead Code</th>
							<th>Date</th>
							<th>Brand</th>
							<th>Company Name</th>
							<th>Status</th>
							<th>Assigned to</th>
							<th width="140px;">Action</th>
						</tr>
						<?php
						$result = mysql_query("select * from lead_generate where userId IN (select userId from admin_login where userType = '14' AND superior1 = '".$_SESSION['userId']."') ORDER BY createddate DESC");
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
							<td><?php
								$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
								$userRow = mysql_fetch_array($userData);
								echo $userRow['name'];
								?>
							</td>
							<td>
								<a href="view_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="view">View</a>
								<?php if((($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) && $row['status'] == 0) || (($_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) && (($row['status'] == 0 || $row['status'] == 1 || $row['assignedTo'] == $_SESSION['userId']) && $row['status'] != 5)) || ( $_SESSION['userType'] == 2 && $row['assignedTo'] == $_SESSION['userId'] && $row['status'] != 5)) { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="edit_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="edit">Edit</a>
								<?php } ?>
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
	$whereCase = '';
	if($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) {
		$whereCase = " And userId = '".$_SESSION['userId']."'";
	} else if($_SESSION['userType'] == 2) {
		$whereCase = " And assignedTo = '".$_SESSION['userId']."'";
	} else if($_SESSION['userType'] == 5) {
		$whereCase = " AND (userId IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
	} else if($_SESSION['userType'] == 8) {
		$whereCase = " AND (userId IN (select userId from admin_login where superior1 IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."')) OR userId IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo = '".$_SESSION['userId']."'  OR userId = '".$_SESSION['userId']."')";
	}
	if(isset($_SESSION['fromDate']) && isset($_SESSION['toDate']) && $_SESSION['fromDate'] != '' &&$_SESSION['fromDate'] != '') {
		$dateBtw = " AND createdDate BETWEEN '".$_SESSION['fromDate']."' AND '".$_SESSION['toDate']."'";
	} else {
		$dateBtw = '';
	}
	if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') {
		$searchCode = " AND Leadcode LIKE '%".$_SESSION['searchCode']."%'";
	} else {
		$searchCode = '';
	}
	if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') {
		$searchKeyword = " AND (Leadcode LIKE '%".$_SESSION['searchKeyword']."%' OR brandName LIKE '%".$_SESSION['searchKeyword']."%' OR companyName LIKE '%".$_SESSION['searchKeyword']."%' OR contactPerson LIKE '%".$_SESSION['searchKeyword']."%')";
	} else {
		$searchKeyword = '';
	}
	$statusQuery = mysql_query("SELECT (SELECT count(*) FROM lead_generate WHERE status='2'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS initial, (SELECT count(*) FROM lead_generate WHERE status='3'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS pricing, (SELECT count(*) FROM lead_generate WHERE status='4'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS followup, (SELECT count(*) FROM lead_generate WHERE status='5'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS complete, (SELECT count(*) FROM lead_generate WHERE status='0'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS newLead, (SELECT count(*) FROM lead_generate WHERE status='1'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS assigned, (SELECT count(*) FROM lead_generate WHERE status='6'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS dropped, (SELECT count(*) FROM lead_generate WHERE status='7'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS disqualified FROM lead_generate LIMIT 0,1");
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
				text: 'Lead Status'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					dataLabels: {
						enabled: true,
						format: '{point.name} ({point.y})',
					},
					events:{
						click: function (event, i) {
							if(event.point.name == 'Leads to Assign') {
								pointValue = 0;
							} else if(event.point.name == 'Newly Assigned lead') {
								pointValue = 1;
							} else if(event.point.name == 'Initial Stage') {
								pointValue = 2;
							} else if(event.point.name == 'Pricing stage') {
								pointValue = 3;
							} else if(event.point.name == 'Followup') {
								pointValue = 4;
							} else if(event.point.name == 'Deal') {
								pointValue = 5;
							} else if(event.point.name == 'Dropped') {
								pointValue = 6;
							} else if(event.point.name == 'Disqualified') {
								pointValue = 7;
							}
							window.location='manage_lead.php?status='+pointValue;
						}
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ <?php if($statusRow['newLead'] > 0 && ($_SESSION['userType'] == '5' || $_SESSION['userType'] == '6' || $_SESSION['userType'] == '7' || $_SESSION['userType'] == '14' || $_SESSION['userType'] == '1' || $_SESSION['userType'] == '8' || $_SESSION['userType'] == '10')) { ?>
							['Leads to Assign', <?php echo $statusRow['newLead']; ?>],
						<?php } ?>
						<?php if($statusRow['assigned'] > 0) { ?>
							['Newly Assigned lead', <?php echo $statusRow['assigned']; ?>],
						<?php } ?>
						<?php if($statusRow['initial'] > 0) { ?>
							['Initial Stage', <?php echo $statusRow['initial']; ?>],
						<?php } ?>
						<?php if($statusRow['pricing'] > 0) { ?>
							['Pricing stage', <?php echo $statusRow['pricing']; ?>],
						<?php } ?>
						<?php if($statusRow['followup'] > 0) { ?>
							['Followup', <?php echo $statusRow['followup']; ?>],
						<?php } ?>
						<?php if($statusRow['complete'] > 0) { ?>
							['Deal', <?php echo $statusRow['complete']; ?>],
						<?php } ?>
						<?php if($statusRow['dropped'] > 0) { ?>
							['Dropped', <?php echo $statusRow['dropped']; ?>],
						<?php } ?>
						<?php if($statusRow['disqualified'] > 0) { ?>
							['Disqualified', <?php echo $statusRow['disqualified']; ?>],
						<?php } ?>
				]
			}]
		});
	});
	</script>
	<?php if($_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) {
		$sourceQuery = mysql_query("SELECT (SELECT count(*) FROM lead_generate WHERE leadSource='Self generated'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS selfGenerated,(SELECT count(*) FROM lead_generate WHERE leadSource='Email Campain'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS emailCampain,(SELECT count(*) FROM lead_generate WHERE leadSource='SEO/SEM/SMM'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS seo,(SELECT count(*) FROM lead_generate WHERE leadSource='Website'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS website,(SELECT count(*) FROM lead_generate WHERE leadSource='referrals'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS referrals,(SELECT count(*) FROM lead_generate WHERE leadSource='Live Chat'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS live FROM lead_generate LIMIT 0,1");
		$sourceRow = mysql_fetch_array($sourceQuery);
	?>
	<script type="text/javascript">
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
					text: 'Lead Source Wise'
				},
				plotOptions: {
					pie: {
						innerSize: 100,
						cursor: 'pointer',
						depth: 45,
						dataLabels: {
							enabled: true,
							format: '{point.name} ({point.y})',
						},
						events:{
							click: function (event, i) {
								if(event.point.name == 'Self Generated') {
									pointValue = 'Self generated';
								} else if(event.point.name == 'Email Campain') {
									pointValue = 'Email Campain';
								} else if(event.point.name == 'SEO/SEM/SMM') {
									pointValue = 'SEO/SEM/SMM';
								} else if(event.point.name == 'Website') {
									pointValue = 'Website';
								} else if(event.point.name == 'Referrals') {
									pointValue = 'referrals';
								} else if(event.point.name == 'Live Chat') {
									pointValue = 'Live Chat';
								}
								window.location='manage_lead.php?source='+pointValue;
							}
						}
					}
				},
				series: [{
					name: 'Total',
					data: [
							<?php if($sourceRow['selfGenerated'] > 0) { ?>
								['Self Generated', <?php echo $sourceRow['selfGenerated']; ?>],
							<?php } ?>
							<?php if($sourceRow['emailCampain'] > 0) { ?>
								['Email Campain', <?php echo $sourceRow['emailCampain']; ?>],
							<?php } ?>
							<?php if($sourceRow['seo'] > 0) { ?>
								['SEO/SEM/SMM', <?php echo $sourceRow['seo']; ?>],
							<?php } ?>
							<?php if($sourceRow['website'] > 0) { ?>
								['Website', <?php echo $sourceRow['website']; ?>],
							<?php } ?>
							<?php if($sourceRow['referrals'] > 0) { ?>
								['Referrals', <?php echo $sourceRow['referrals']; ?>],
							<?php } ?>
							<?php if($sourceRow['live'] > 0) { ?>
								['Live Chat', <?php echo $sourceRow['live']; ?>],
							<?php } ?>
					]
				}]
			});
		});
		</script>
	<?php } ?>
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