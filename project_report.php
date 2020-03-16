<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 10) {
	header('Location: index.php'); exit;
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
<script type="text/javascript" src="http://code.highcharts.com/stock/modules/exporting.js"></script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Project Report</h2>
					</div>
					<form action="functions/export_excel.php" method="post">
					<table class="form_table">
						<tr>
							<td width="170px;">From Date</td>
							<td><input type="text" value="<?php echo date('d-m-Y'); ?>" required readonly class="txtBx datetimepicker" name="fromDate"/></td>
						</tr>
						<tr>
							<td>To Date</td>
							<td><input type="text" value="<?php echo date('d-m-Y'); ?>" required readonly class="txtBx datetimepicker" name="toDate"/></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" class="subBtn frm_sub" name="generate_report" value="Generate"/>
								<input type="hidden" id="exportType" value="ProjectReport" name="exportType" />
							</td>
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
