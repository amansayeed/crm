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
<script>
function paymentValue (value) {
	if(value == 'Not Received') {
		$('#payAmountId').hide();
		$('#payment').val('0');
	} else {
		$('#payAmountId').show();
		$('#payment').val('');
	}
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
					<div class="cnt_title dtl_user">
						<h2>View Details</h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post">
					<table class="form_table">
						<?php
						$result1 = mysql_query("select lead_generate.*,payment_details.paymentType as payType,payment_details.payment,payment_details.paymentId from lead_generate LEFT JOIN payment_details ON payment_details.leadId = lead_generate.leadId where lead_generate.leadId = '".$_GET['id']."'");
						$row1 = mysql_fetch_array($result1);
						?>
						<tr>
							<td>Lead code </td>
							<td><?php echo $row1['leadCode']; ?></td>
							<input type="hidden" name="leadId" value="<?php echo $row1['leadId']; ?>" />
							<input type="hidden" name="paymentId" value="<?php echo $row1['paymentId']; ?>" />
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
							<td>Work Order</td>
							<td><a href="uploads/sales/<?php echo $row1['workOrder']; ?>"><?php echo $row1['workOrder']; ?></a></td>
						</tr>
						<tr>
							<td>payment Type <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="paymentType" onchange="return paymentValue(this.value);">
									<option value="">Select Payment Type</option>
									<option <?php if($row1['payType'] == 'Received') { echo "selected"; } ?> value="Received">Received</option>
									<option <?php if($row1['payType'] == 'Partially Received') { echo "selected"; } ?> value="Partially Received">Partially Received</option>
									<option <?php if($row1['payType'] == 'Not Received') { echo "selected"; } ?> value="Not Received">Not Received</option>
								</select>
							</td>
						</tr>
						<tr id="payAmountId">
							<td>payment Amount <span class="astrick">*</span></td>
							<td><input type="text" id="payment" pattern="[0-9-]+" title="Numeric Only (0-9)" required value="<?php echo $row1['payment']; ?>" class="txtBx" name="payment"/></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="update_payment" value="Submit"/></td>
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
<?php if(isset($_POST['update_payment'])) {
	mysql_query("update payment_details set paymentType = '".$_POST['paymentType']."',Payment = '".$_POST['payment']."' where paymentId = '".$_POST['paymentId']."'");
	if(mysql_affected_rows() > 0) {
		echo "<script>alert('Payment detail updated successfully'); window.location='payment_collection.php'; </script>";
	} else {
		echo "<script>alert('Payment detail updates failed'); window.location='payment_collection.php'; </script>";
	}
} ?>