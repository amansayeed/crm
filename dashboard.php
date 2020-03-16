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
					<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) { ?>
						<div id="container1"></div>
						<div id="container2"></div>
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
		$searchCode = " AND code LIKE '%".$_SESSION['searchCode']."%'";
	} else {
		$searchCode = '';
	}
	if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') {
		$searchKeyword = " AND code LIKE '%".$_SESSION['searchKeyword']."%'";
	} else {
		$searchKeyword = '';
	}
	$statusQuery = mysql_query("SELECT (SELECT count(*) FROM sales_request WHERE status='1'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS Pending, (SELECT count(*) FROM sales_request WHERE status='2'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS Assigned, (SELECT count(*) FROM sales_request WHERE status='3'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS Completed, (SELECT count(*) FROM sales_request WHERE status='4'".$whereCase.$dateBtw.$searchCode.$searchKeyword.") AS Closed FROM sales_request LIMIT 0,1");
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
				text: 'Status Wise'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					events:{
						click: function (event, i) {
							if(event.point.name == 'Pending') {
								pointValue = 1;
							} else if(event.point.name == 'Assigned') {
								pointValue = 2;
							} else if(event.point.name == 'Completed') {
								pointValue = 3;
							} else if(event.point.name == 'Closed') {
								pointValue = 4;
							}
							window.location='manage_data.php?list='+pointValue+'&listType=1';
						}
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ <?php if($_SESSION['userType'] != 4 && $statusRow['Pending'] > 0) { ?>
							['Pending', <?php echo $statusRow['Pending']; ?>],
						<?php } ?>
						<?php if($statusRow['Assigned'] > 0) { ?>
							['Assigned', <?php echo $statusRow['Assigned']; ?>],
						<?php } ?>
						<?php if($statusRow['Completed'] > 0) { ?>
							['Completed', <?php echo $statusRow['Completed']; ?>],
						<?php } ?>
						<?php if(($_SESSION['userType'] == 2 || $_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) && $statusRow['Closed'] > 0) { ?>
							['Closed', <?php echo $statusRow['Closed']; ?>],
						<?php } ?>
				]
			}]
		});
	});
	<?php
	if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) { 
		$divisionQuery = mysql_query("SELECT (SELECT count(*) FROM sales_request WHERE status != '0' and salesDivision = '1'".$dateBtw.$searchCode.$searchKeyword.") AS BM, (SELECT count(*) FROM sales_request WHERE status != '0' and salesDivision = '2'".$dateBtw.$searchCode.$searchKeyword.") AS TD FROM sales_request LIMIT 0,1");
		$divisionRow = mysql_fetch_array($divisionQuery);
		$feedbackQuery = mysql_query("SELECT (SELECT count(*) FROM sales_request WHERE status != '0' and feedback = '1'".$dateBtw.$searchCode.$searchKeyword.") AS good, (SELECT count(*) FROM sales_request WHERE status != '0' and feedback = '2'".$dateBtw.$searchCode.$searchKeyword.") AS bad FROM sales_request LIMIT 0,1");
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
	$(function () {
		$('#container2').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45
				}
			},
			title: {
				text: 'Division Wise'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					events:{
						click: function (event, i) {
							if(event.point.name == 'BM') {
								pointValue = 1;
							} else if(event.point.name == 'TD') {
								pointValue = 2;
							}
							window.location='manage_data.php?list='+pointValue+'&listType=3';
						}
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ 
					<?php if($divisionRow['BM'] > 0) { ?>
						['BM', <?php echo $divisionRow['BM']; ?>],
					<?php } ?>
					<?php if($divisionRow['TD'] > 0) { ?>
						['TD', <?php echo $divisionRow['TD']; ?>]
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