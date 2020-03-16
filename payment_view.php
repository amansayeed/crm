<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != '1' && $_SESSION['userType'] != '10') {
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
						$result1 = mysql_query("select * from lead_generate where lead_generate.leadId = '".$_GET['id']."'");
						$row1 = mysql_fetch_array($result1);
						echo mysql_error();
						?>
						<tr>
							<td>Lead code </td>
							<td><?php echo $row1['leadCode']; ?></td>
						</tr>
						<tr>
							<td>Lead Closed Date </td>
							<td><?php echo date('d-M-Y',strtotime($row1['modifiedDate'])); ?></td>
						</tr>
						<tr>
							<td>Lead Closed By </td>
							<td>
								<?php $userData = mysql_query("select * from admin_login where userId = '".$row1['assignedTo']."'");
								$userRow = mysql_fetch_array($userData);
								echo $userRow['name']; ?>
							</td>
						</tr>
						<tr>
							<td>Deal Value </td>
							<td><?php echo $row1['dealCurrency']." ".$row1['dealAmount']; ?></td>
						</tr>
						<tr>
							<td>Company Name</td>
							<td><?php echo $row1['companyName']; ?></td>
						</tr>
						<tr>
							<td>Brand</td>
							<td><?php echo $row1['brandName']; ?></td>
						</tr>
						<tr>
							<td>Work Order</td>
							<td><a href="uploads/sales/<?php echo $row1['workOrder']; ?>"><?php echo $row1['workOrder']; ?></a></td>
						</tr>
						<tr>
							<td>Paid Amount Total</td>
							<td>$ 
								<?php
								$totalPaidQuery = mysql_query("select SUM(payment) as totalPaid from payment_details where leadId = '".$_GET['id']."'"); 
								$totalPaid = mysql_fetch_array($totalPaidQuery);
								echo $totalPaid['totalPaid']; 
								?>
							</td>
						</tr>
					</table>
					<table class="data_table" style="margin:auto; margin-bottom:20px; width:90%;">
						<tr>
							<th>Payment Collection Date</th>
							<th>Payment Type</th>
							<th>Payment Amount</th>
						</tr>
						<?php
						$paymentQuery = mysql_query("select * from payment_details where leadId = '".$_GET['id']."'"); 
						while($payment = mysql_fetch_array($paymentQuery)){ 
						?>
							<tr>
								<td><?php if($payment['createdDate'] != '') { echo date('d-M-Y',strtotime($payment['createdDate'])); }?></td>
								<td><?php echo $payment['paymentType']; ?></td>
								<td>$ <?php echo $payment['payment']; ?></td>
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