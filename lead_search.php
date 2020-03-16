<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
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
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Search Lead</h2>
					</div>
					<form name="myform" action="search_result.php" id="chg_pwd" method="post">
					<table class="form_table">
						<tr>
							<td width="250px;">Field</td>
							<td>Value</td>
						</tr>
						<tr>
							<td>
								<select required class="txtBxSerch" name="searchField1">
									<option value="">Select Field</option>
									<option value="leadCode">Lead Code</option>
									<option value="brandName">Brand Name</option>
									<option value="companyName">Company Name</option>
									<option value="contactPerson">Contact Person</option>
									<option value="email">Email</option>
									<option value="website">Website</option>
									<option value="leadSource">Lead Source</option>
									<option value="service">Service</option>
									<option value="dealCurrency">Deal Currency</option>
									<option value="dealAmount">Deal Amount</option>
									<option value="paymentType">Payment Type</option>
								</select>
							</td>
							<td><input type="text" value="" required class="txtBx" name="searchValue1"/></td>
						</tr>
						<tr>
							<td>
								<select class="txtBxSerch" name="searchField2">
									<option value="">Select Field</option>
									<option value="leadCode">Lead Code</option>
									<option value="brandName">Brand Name</option>
									<option value="companyName">Company Name</option>
									<option value="contactPerson">Contact Person</option>
									<option value="email">Email</option>
									<option value="website">Website</option>
									<option value="leadSource">Lead Source</option>
									<option value="service">Service</option>
									<option value="dealCurrency">Deal Currency</option>
									<option value="dealAmount">Deal Amount</option>
									<option value="paymentType">Payment Type</option>
								</select>
							</td>
							<td><input type="text" value="" class="txtBx" name="searchValue2"/></td>
						</tr>
						<tr>
							<td>
								<select class="txtBxSerch" name="searchField3">
									<option value="">Select Field</option>
									<option value="leadCode">Lead Code</option>
									<option value="brandName">Brand Name</option>
									<option value="companyName">Company Name</option>
									<option value="contactPerson">Contact Person</option>
									<option value="email">Email</option>
									<option value="website">Website</option>
									<option value="leadSource">Lead Source</option>
									<option value="service">Service</option>
									<option value="dealCurrency">Deal Currency</option>
									<option value="dealAmount">Deal Amount</option>
									<option value="paymentType">Payment Type</option>
								</select>
							</td>
							<td><input type="text" value="" class="txtBx" name="searchValue3"/></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="searchLead" value="Submit"/></td>
						</tr>
					</table>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>