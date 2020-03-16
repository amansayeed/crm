<?php
session_start();
ob_start();
ini_set('memory_limit', '2000M'); //for 2GB
//For no limits
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] == '3' || $_SESSION['userType'] == '4' || $_SESSION['userType'] == '9') {
	header('Location: dashboard.php'); exit;
} else if($_SESSION['userType'] == '11' && $_SESSION['userType'] == '12' && $_SESSION['userType'] == '13') {
	header('Location: project/dashboard.php'); exit;
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
function showothers(value) {
	$('#ext_service').val('');
	if(value == 'Other') {
		$('#ext_service').attr('type','text');
		$('#ext_service').attr('required','required');
	} else {
		$('#ext_service').attr('type','hidden');
	}
}
function showothers1(value) {
	$('#ext_leadSource').val('');
	if(value == 'Self generated' || value == 'referrals') {
		$('#ext_leadSource').attr('type','text');
		$('#ext_leadSource').attr('required','required');
	} else {
		$('#ext_leadSource').attr('type','hidden');
	}
}
function statusChange(value) {
	if(value == '5') {
		var htmlValue = '<td>Deal Value <span class="astrick">*</span></td><td><select class="txtBx" style="width:150px;" required name="dealCurrency"><option value="">Select Currency</option><option value="INR">INR</option><option value="USD">USD</option><option value="EUR">EUR</option><option value="AED">AED</option><option value="CHF">CHF</option><option value="SEK">SEK</option><option value="LKR">LKR</option><option value="ZAR">ZAR</option><option value="SAR">SAR</option><option value="RUB">RUB</option><option value="QAR">QAR</option><option value="PHP">PHP</option><option value="OMR">OMR</option><option value="NZD">NZD</option><option value="MYR">MYR</option><option value="KWD">KWD</option><option value="AUD">AUD</option><option value="GBP">GBP</option><option value="CNY">CNY</option><option value="CAD">CAD</option></select><input type="" required style="width:230px;"  pattern="[0-9.]+" title="Numeric Only (0-9)"  id="dealValue" value="" class="txtBx" name="dealValue"/></td>';
		var htmlValue1 = '<td>Work Order</td><td><div class="subBtn file_btn" style="background:#FF0000;">Work Order<input type="file" class="choosefile" onchange="$(\'#file_name1\').html(this.value);" required id="workOrder" name="workOrder" value=""/></div><span class="file_name" id="file_name1">No files selected</span></td>';
		var htmlValue2 = '<td>Payment Type <span class="astrick">*</span></td><td><select class="txtBx" required name="paymentType"><option value="">Select payment Type</option><option value="100% Advance">100% Advance</option><option value="Partial Advance & Balance after completion">Partial Advance & Balance after completion</option><option value="Against delivery">Against delivery</option></select></td>';
		$('#dealValueId').html(htmlValue);
		$('#dealValueId').show();
		$('#workOrderId').html(htmlValue1);
		$('#workOrderId').show();
		$('#paymentId').html(htmlValue2);
		$('#paymentId').show();
		$('#dropNoteId').html('');
		$('#dropNoteId').hide();
		$('#nextFollowId').html('Delivery Date <span class="astrick">*</span>');
	} else if(value == '6' || value == '7') {
		var htmlValue = '<td>Drop / Disqualify Note <span class="astrick">*</span></td><td><textarea required class="txtArea" name="dropNote"></textarea></td>';
		$('#dropNoteId').html(htmlValue);
		$('#dropNoteId').show();
		$('#dealValueId').html('');
		$('#dealValueId').hide();
		$('#workOrderId').html('');
		$('#workOrderId').hide();
		$('#paymentId').html('');
		$('#paymentId').hide();
		$('#nextFollowId').html('Next Followup Date <span class="astrick">*</span>');
	} else {
		$('#dealValueId').html('');
		$('#dealValueId').hide();
		$('#dropNoteId').html('');
		$('#dropNoteId').hide();
		$('#workOrderId').html('');
		$('#workOrderId').hide();
		$('#nextFollowId').html('Next Followup Date <span class="astrick">*</span>');
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
					<div class="cnt_title sales_user">
						<h2>Lead Form</h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post" enctype="multipart/form-data">
					<table class="form_table">
						<?php
							$result1 = mysql_query("select * from lead_generate where leadId ='".$_GET['id']."'");
							$row1 = mysql_fetch_array($result1);
						?>
						<tr>
							<td>Lead code </td>
							<td>
								<input type="text" readonly required value="<?php echo $row1['leadCode']; ?>" class="txtBx" name="leadCode"/>
								<input type="hidden" value="<?php echo $row1['leadId']; ?>" name="leadId" />
							</td>
						</tr>
						<tr>
							<td>Date <span class="astrick">*</span></td>
							<td>
								<input type="text" readonly required value="<?php echo date('d-m-Y',strtotime($row1['date'])); ?>" class="txtBx" name="date"/>
							</td>
						</tr>
						<tr>
							<td>Company Name <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="<?php echo $row1['companyName']; ?>" class="txtBx" name="companyName"/>
							</td>
						</tr>
						<tr>
							<td>Contact Person <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="<?php echo $row1['contactPerson']; ?>" class="txtBx" name="contactPerson"/>
							</td>
						</tr>
						<tr>
							<td>Email <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="<?php echo $row1['email']; ?>" class="txtBx" name="email"/>
							</td>
						</tr>
						<tr>
							<td>Address 1</td>
							<td>
								<input type="text" value="<?php echo $row1['address1']; ?>" class="txtBx" name="address1"/>
							</td>
						</tr>
						<tr>
							<td>Address 2</td>
							<td>
								<input type="text" value="<?php echo $row1['address2']; ?>" class="txtBx" name="address2"/>
							</td>
						</tr>
						<tr>
							<td>City</td>
							<td>
								<input type="text" value="<?php echo $row1['city']; ?>" class="txtBx" name="city"/>
							</td>
						</tr>
						<tr>
							<td>State</td>
							<td>
								<input type="text" value="<?php echo $row1['state']; ?>" class="txtBx" name="state"/>
							</td>
						</tr>
						<tr>
							<td>Country <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="country">
									<option value="">Select Country</option>
									<?php 
										$countryQuery = mysql_query("select * from country_master");
										while($countryData = mysql_fetch_array($countryQuery)) { ?>
											<option value="<?php echo $countryData['id']; ?>" <?php if($countryData['id'] == $row1['country']) { echo 'selected'; } ?>><?php echo $countryData['country']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Zip</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['zip']; ?>" class="txtBx" name="zip"/>
							</td>
						</tr>
						<tr>
							<td>Sec Email 1</td>
							<td>
								<input type="text" value="<?php echo $row1['secEmail1']; ?>" class="txtBx" name="secEmail1"/>
							</td>
						</tr>
						<tr>
							<td>Sec Email 2</td>
							<td>
								<input type="text" value="<?php echo $row1['secEmail2']; ?>" class="txtBx" name="secEmail2"/>
							</td>
						</tr>
						<tr>
							<td>Website </td>
							<td>
								<input type="text" value="<?php echo $row1['website']; ?>" title="Enter a valid URL" class="txtBx" name="website"/>
							</td>
						</tr>
						<tr>
							<td>Phone</td>
							<td>
								<input type="text" style="width:75px;" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['countryCode']; ?>" placeholder="Code" class="txtBx" name="countryCode"/>
								<input type="text" style="width:235px;" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['phone']; ?>" placeholder="Number" class="txtBx" name="phone"/>
								<input type="text" style="width:65px;" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['ext']; ?>" placeholder="Ext" class="txtBx" name="ext"/>
							</td>
						</tr>
						<tr>
							<td>Sec Phone 1</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['secPhone1']; ?>" class="txtBx" name="secPhone1"/>
							</td>
						</tr>
						<tr>
							<td>Sec Phone 2</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['secPhone2']; ?>" class="txtBx" name="secPhone2"/>
							</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="<?php echo $row1['fax']; ?>" class="txtBx" name="fax"/>
							</td>
						</tr>
						<tr>
							<td>Brand <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="brand">
									<option value="">Select Brand</option>
									<option value="1" <?php if($row1['brandId'] == '1') { echo 'selected'; } ?>>BM - Blue Mail Media</option>
									<option value="2" <?php if($row1['brandId'] == '2') { echo 'selected'; } ?>>TD - Thomson Data</option>
									<!--option value="3" <?php if($row1['brandId'] == '3') { echo 'selected'; } ?>>MP - Mail Prospects</option-->
									<option value="4" <?php if($row1['brandId'] == '4') { echo 'selected'; } ?>>ED - E-Sales Data</option>
									<option value="5" <?php if($row1['brandId'] == '5') { echo 'selected'; } ?>>IC - InfoClutch</option>
									<option value="6" <?php if($row1['brandId'] == '6') { echo 'selected'; } ?>>MR - MedicoReach</option>


								</select>
							</td>
						</tr>
						<tr>
							<td>Sales Projectio</td>
							<td>
								<select class="txtBx" style="width:150px;" name="projCurrency">
									<option value="">Select Currency</option>
									<option value="INR" <?php if($row1['projCurrency'] == 'INR') { echo 'selected'; } ?>>INR</option>
									<option value="USD" <?php if($row1['projCurrency'] == 'USD') { echo 'selected'; } ?>>USD</option>
									<option value="EUR" <?php if($row1['projCurrency'] == 'EUR') { echo 'selected'; } ?>>EUR</option>
									<option value="AED" <?php if($row1['projCurrency'] == 'AED') { echo 'selected'; } ?>>AED</option>
									<option value="CHF" <?php if($row1['projCurrency'] == 'CHF') { echo 'selected'; } ?>>CHF</option>
									<option value="SEK" <?php if($row1['projCurrency'] == 'SEK') { echo 'selected'; } ?>>SEK</option>
									<option value="LKR" <?php if($row1['projCurrency'] == 'LKR') { echo 'selected'; } ?>>LKR</option>
									<option value="ZAR" <?php if($row1['projCurrency'] == 'ZAR') { echo 'selected'; } ?>>ZAR</option>
									<option value="SAR" <?php if($row1['projCurrency'] == 'SAR') { echo 'selected'; } ?>>SAR</option>
									<option value="RUB" <?php if($row1['projCurrency'] == 'RUB') { echo 'selected'; } ?>>RUB</option>
									<option value="QAR" <?php if($row1['projCurrency'] == 'QAR') { echo 'selected'; } ?>>QAR</option>
									<option value="PHP" <?php if($row1['projCurrency'] == 'PHP') { echo 'selected'; } ?>>PHP</option>
									<option value="OMR" <?php if($row1['projCurrency'] == 'OMR') { echo 'selected'; } ?>>OMR</option>
									<option value="NZD" <?php if($row1['projCurrency'] == 'NZD') { echo 'selected'; } ?>>NZD</option>
									<option value="MYR" <?php if($row1['projCurrency'] == 'MYR') { echo 'selected'; } ?>>MYR</option>
									<option value="KWD" <?php if($row1['projCurrency'] == 'KWD') { echo 'selected'; } ?>>KWD</option>
									<option value="AUD" <?php if($row1['dealCurrency'] == 'AUD') { echo 'selected'; } ?>>AUD</option>
									<option value="GBP" <?php if($row1['dealCurrency'] == 'GBP') { echo 'selected'; } ?>>GBP</option>
									<option value="CNY" <?php if($row1['dealCurrency'] == 'CNY') { echo 'selected'; } ?>>CNY</option>
									<option value="CAD" <?php if($row1['dealCurrency'] == 'CAD') { echo 'selected'; } ?>>CAD</option>
								</select>
								<input type="" style="width:230px;" pattern="[0-9.]+" title="Numeric Only (0-9)" id="projValue" value="<?php echo $row1['projValue']; ?>" class="txtBx" name="projValue"/></td>
						</tr>
						<tr>
							<td>Lead source <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="leadSource" onchange="return showothers1(this.value);">
									<option value="">Select Lead Source</option>
									<option <?php if($row1['leadSource'] == 'Self generated') { echo 'selected'; } ?>>Self generated</option>
									<option <?php if($row1['leadSource'] == 'Email Campain') { echo 'selected'; } ?>>Email Campain</option>
									<option <?php if($row1['leadSource'] == 'SEO/SEM/SMM') { echo 'selected'; } ?>>SEO/SEM/SMM</option>
									<option <?php if($row1['leadSource'] == 'Website') { echo 'selected'; } ?>>Website</option>
									<option <?php if($row1['leadSource'] == 'referrals') { echo 'selected'; } ?>>referrals</option>
									<option <?php if($row1['leadSource'] == 'Live Chat') { echo 'selected'; } ?>>Live Chat</option>
<option <?php if($row1['leadSource'] == 'Nextmark') { echo 'selected'; } ?>>Nextmark</option>
<option <?php if($row1['leadSource'] == 'PPC') { echo 'selected'; } ?>>PPC</option>
<option <?php if($row1['leadSource'] == 'Inbound call') { echo 'selected'; } ?>>Inbound call</option>

								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="<?php if($row1['leadSource'] == 'Self generated' || $row1['leadSource'] == 'referrals') { echo 'text'; } else { echo 'hidden'; } ?>" id="ext_leadSource" value="<?php echo $row1['ext_leadSource']; ?>" class="txtBx" name="ext_leadSource"/>
							</td>
						</tr>
						<tr>
							<td>Service <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="service" onchange="return showothers(this.value);">
									<option value="">Select Service</option>
									<option <?php if($row1['service'] == 'Custom List') { echo 'selected'; } ?>>Custom List</option>
									<option <?php if($row1['service'] == 'Email Appending') { echo 'selected'; } ?>>Email Appending</option>
									<option <?php if($row1['service'] == 'Data Appending') { echo 'selected'; } ?>>Data Appending</option>
									<option <?php if($row1['service'] == 'data cleansing') { echo 'selected'; } ?>>data cleansing</option>
									<option <?php if($row1['service'] == 'email campaign') { echo 'selected'; } ?>>email campaign</option>
									<option <?php if($row1['service'] == 'Other') { echo 'selected'; } ?>>Other</option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="<?php if($row1['ext_service'] == 'Other') { echo 'text'; } else { echo 'hidden'; } ?>" id="ext_service" value="<?php echo $row1['ext_service']; ?>" class="txtBx" name="ext_service"/>
							</td>
						</tr>
						<tr>
							<td>Requirement/Criteria</td>
							<td>
								<textarea class="txtArea" name="requirement"><?php echo $row1['requirements']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td>No of records</td>
							<td>
								<input type="text" value="<?php echo $row1['noOfRecords']; ?>" pattern="[0-9]+" title="Numeric Only (0-9)" class="txtBx" name="noOfRecords"/>
							</td>
						</tr>
						<tr>
							<td>Supporting Document</td>
							<td>
								<div class="subBtn file_btn" style="">Upload
									<input type="file" class="choosefile" onchange="$('#file_name').html(this.value);" id="supportDoc" name="supportDoc" value=""/>
								</div>
								<span class="file_name" id="file_name">No files selected</span>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>Note :  Append file de-dupe file and other client files for project</td>
						</tr>
						<?php
						if(($_SESSION['userType'] == '5' || $_SESSION['userType'] == '8' || $_SESSION['userType'] == '10' || $_SESSION['userType'] == '1')) { ?>
							<tr>
								<td>Assign to <span class="astrick">*</span></td>
								<td>
									<select class="txtBx" required name="assignTo">
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
						<?php } else { ?>
							<input type="hidden" value="<?php echo $row1['assignedTo']; ?>" name="assignTo" />
						<?php } ?>
						<?php
						if(($_SESSION['userType'] == '2' || $_SESSION['userType'] == '5' || $_SESSION['userType'] == '8' || $_SESSION['userType'] == '10' || $_SESSION['userType'] == '1') && ($row1['status'] != '0') && $row1['assignedTo'] == $_SESSION['userId']) { ?>
							<tr>
								<td>Activity/comments <span class="astrick">*</span></td>
								<td>
									<textarea required class="txtArea" name="comments"></textarea><br /><br />
									<?php 
									$commentsData = mysql_query("select A.*,B.name from lead_comments as A JOIN admin_login as B ON A.userId = B.userId where leadId = '".$_GET['id']."'");
									while($commentsRow = mysql_fetch_array($commentsData)) {
										echo $commentsRow['comments']." - by <b>".$commentsRow['name']."</b> ".date('d/m/Y',strtotime($commentsRow['createdDate']))."<br />";
									}
									?>
								</td>
							</tr>
							<tr>
								<td>Status <span class="astrick">*</span></td>
								<td>
									<select class="txtBx" required name="status" onchange="return statusChange(this.value);">
										<option value="">Select Status</option>
										<option value="2" <?php if($row1['status'] == '2') { echo 'selected'; } ?>>Initial Stage</option>
										<option value="3" <?php if($row1['status'] == '3') { echo 'selected'; } ?>>Pricing stage</option>
										<option value="4" <?php if($row1['status'] == '4') { echo 'selected'; } ?>>Followup</option>
										<option value="5" <?php if($row1['status'] == '5') { echo 'selected'; } ?>>Deal</option>
										<option value="6" <?php if($row1['status'] == '6') { echo 'selected'; } ?>>Dropped</option>
										<option value="7" <?php if($row1['status'] == '7') { echo 'selected'; } ?>>Disqualified</option>
									</select>
								</td>
							</tr>
							<tr id="dealValueId" <?php if($row1['dealCurrency'] == '' && $row1['dealAmount'] == '') { echo 'style="display:none"'; } ?>>
								<?php if($row1['dealCurrency'] != '' && $row1['dealAmount'] != '') { ?>
									<td>Deal Value <span class="astrick">*</span></td>
									<td>
										<select class="txtBx" style="width:150px;" required name="dealCurrency">
											<option value="">Select Currency</option>
											<option value="INR" <?php if($row1['dealCurrency'] == 'INR') { echo 'selected'; } ?>>INR</option>
											<option value="USD" <?php if($row1['dealCurrency'] == 'USD') { echo 'selected'; } ?>>USD</option>
											<option value="EUR" <?php if($row1['dealCurrency'] == 'EUR') { echo 'selected'; } ?>>EUR</option>
											<option value="AED" <?php if($row1['dealCurrency'] == 'AED') { echo 'selected'; } ?>>AED</option>
											<option value="CHF" <?php if($row1['dealCurrency'] == 'CHF') { echo 'selected'; } ?>>CHF</option>
											<option value="SEK" <?php if($row1['dealCurrency'] == 'SEK') { echo 'selected'; } ?>>SEK</option>
											<option value="LKR" <?php if($row1['dealCurrency'] == 'LKR') { echo 'selected'; } ?>>LKR</option>
											<option value="ZAR" <?php if($row1['dealCurrency'] == 'ZAR') { echo 'selected'; } ?>>ZAR</option>
											<option value="SAR" <?php if($row1['dealCurrency'] == 'SAR') { echo 'selected'; } ?>>SAR</option>
											<option value="RUB" <?php if($row1['dealCurrency'] == 'RUB') { echo 'selected'; } ?>>RUB</option>
											<option value="QAR" <?php if($row1['dealCurrency'] == 'QAR') { echo 'selected'; } ?>>QAR</option>
											<option value="PHP" <?php if($row1['dealCurrency'] == 'PHP') { echo 'selected'; } ?>>PHP</option>
											<option value="OMR" <?php if($row1['dealCurrency'] == 'OMR') { echo 'selected'; } ?>>OMR</option>
											<option value="NZD" <?php if($row1['dealCurrency'] == 'NZD') { echo 'selected'; } ?>>NZD</option>
											<option value="MYR" <?php if($row1['dealCurrency'] == 'MYR') { echo 'selected'; } ?>>MYR</option>
											<option value="KWD" <?php if($row1['dealCurrency'] == 'KWD') { echo 'selected'; } ?>>KWD</option>
											<option value="AUD" <?php if($row1['dealCurrency'] == 'AUD') { echo 'selected'; } ?>>AUD</option>
											<option value="GBP" <?php if($row1['dealCurrency'] == 'GBP') { echo 'selected'; } ?>>GBP</option>
											<option value="CNY" <?php if($row1['dealCurrency'] == 'CNY') { echo 'selected'; } ?>>CNY</option>
											<option value="CAD" <?php if($row1['dealCurrency'] == 'CAD') { echo 'selected'; } ?>>CAD</option>
										</select>
										<input type="" required style="width:230px;" pattern="[0-9.]+" title="Numeric Only (0-9)" id="dealValue" value="<?php echo $row1['dealAmount']; ?>" class="txtBx" name="dealValue"/></td>
								<?php } ?>
							</tr>
							<tr id="paymentId" <?php if($row1['paymentType'] == '' && $row1['paymentType'] == '') { echo 'style="display:none"'; } ?>>
								<?php if($row1['paymentType'] != '' && $row1['paymentType'] != '') { ?>
									<td>Payment Type <span class="astrick">*</span></td>
									<td>
										<select class="txtBx" required name="paymentType">
											<option value="">Select Payment Type</option>
											<option value="100% Advance" <?php if($row1['paymentType'] == '100% Advance') { echo 'selected'; } ?>>100% Advance</option>
											<option value="Partial Advance & Balance after completion" <?php if($row1['paymentType'] == 'Partial Advance & Balance after completion') { echo 'selected'; } ?>>Partial Advance & Balance after completion</option>
											<option value="Against delivery" <?php if($row1['paymentType'] == 'Against delivery') { echo 'selected'; } ?>>Against delivery</option>
										</select>
									</td>
								<?php } ?>
							</tr>
							<tr id="workOrderId" <?php if($row1['workOrder'] == '' && $row1['workOrder'] == '') { echo 'style="display:none"'; } ?>>
								<?php if($row1['workOrder'] != '' && $row1['workOrder'] != '') { ?>
									<td>Work Order</td>
									<td>
										<div class="subBtn file_btn" style="">Work Order
											<input type="file" class="choosefile" style="background:#FF0000;" onchange="$('#file_name1').html(this.value);" <?php if($row1['status'] == '5') { echo 'required'; } ?> id="workOrder" name="workOrder" value=""/>
										</div>
										<span class="file_name" id="file_name1">No files selected</span>
									</td>
								<?php } ?>
							</tr>
							<tr id="dropNoteId" <?php if($row1['status'] != '6' && $row1['status'] != '7') { echo 'style="display:none"'; } ?>>
								<?php if($row1['status'] == '6' && $row1['status'] == '7') { ?>
									<td>Drop / Disqualify Note <span class="astrick">*</span></td>
									<td><textarea required class="txtArea" name="dropNote"><?php echo $row1['dropNote']; ?></textarea></td>
								<?php } ?>
							</tr>
							<tr>
								<td id="nextFollowId">Next Followup Date <span class="astrick">*</span></td>
								<td>
									<input type="text" readonly required value="<?php if($row1['nextFollowup'] != '0000-00-00 00:00:00') { echo date('d-m-Y',strtotime($row1['nextFollowup'])); } else { echo date('d-m-Y');} ?>" class="txtBx datetimepicker1" name="nextFollowup"/>
								</td>
							</tr>
						<?php }	?>
						<?php if(($row1['status'] != '0') &&  $row1['assignedTo'] == $_SESSION['userId']) { ?>
							<tr>
								<td></td>
								<td>
									<input type="submit" class="subBtn frm_sub" style="width:350px;" name="requestSample" value="Submit & Request New Sample"/>
									<br />
									</br />
									OR
								</td>
							</tr>
						<?php } ?>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="update_lead" value="Submit"/></td>
						</tr>
						<tr>
							<td colspan="2">** Note: Symbols / Special characters should not be used on the platform</td>
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
	jQuery('.datetimepicker1').datetimepicker({
		timepicker:false,
		format:'d-m-Y',
		minDate:'-1970/01/01',
		scrollInput: false
	});
</script>
<?php
if(isset($_POST['update_lead']) || isset($_POST['requestSample'])) {
	if($_POST['brand'] == '1') {
		$brandName = 'BM';
	} else if($_POST['brand'] == '2') {
		$brandName = 'TD';
	} else if($_POST['brand'] == '3') {
		$brandName = 'MP';
	} else if($_POST['brand'] == '4') {
		$brandName = 'ED';
	}
	else if($_POST['brand'] == '5') {
		$brandName = 'IC';
	}
	else if($_POST['brand'] == '6') {
		$brandName = 'MR';
	}
	
	$updateFile = '';
	$status = '';
	if(isset($_POST['status'])) {
		$status = $_POST['status'];
	} else {
		if($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) {
			$status = 0;
		} else {
			$status = 1;
		}
	}
	if(isset($_POST['nextFollowup'])) {
		$followUp = ",nextFollowup = '".date('Y-m-d H:i:s',strtotime($_POST['nextFollowup']))."'";
	} else {
		$followUp = '';
	}
	if(isset($_POST['dealCurrency']) && isset($_POST['dealValue'])) {
		$dealvalue = ",dealCurrency = '".$_POST['dealCurrency']."',dealAmount = '".$_POST['dealValue']."',paymentType = '".$_POST['paymentType']."'";
		if(!empty($_FILES['workOrder']["tmp_name"])) {
			$uploadDirectory = "uploads/sales/";
			$allowedExts = array("pdf", "xls", "xlsx", "csv", "doc", "docx");
			$temp = explode(".", $_FILES['workOrder']["name"]);
			$RandNumber  = rand(0, 9999999999);
			$extension = end($temp);
			if ($_FILES['workOrder']["error"] > 0)
			{
				$flag = 1;
				echo "<script>alert('".$_FILES['workOrder']["error"]."'); window.location='lead_entry.php'; </script>";
				exit;
			} else {
				$uploadFileName1 = $RandNumber."_".date('dmY')."_".$_FILES['workOrder']["name"];
				if(move_uploaded_file($_FILES['workOrder']["tmp_name"],$uploadDirectory.$uploadFileName1)) {
					$workFile = ",workOrder = '".$uploadFileName1."'";
					$flag = 0;
				} else {
					$flag = 1;
					echo "<script>alert('Error: File upload failed'); window.location='lead_entry.php'; </script>";
					exit;
				}
			}
		}
	} else {
		$dealvalue = '';
		$workFile = '';
	}
	if(isset($_POST['dropNote']) && $_POST['dropNote'] != '') {
		$dropNote = ",dropNote = '".$_POST['dropNote']."'";
	} else {
		$dropNote = '';
	}
	$flag = 0;
	if(!empty($_FILES['supportDoc']["tmp_name"])) {
		$uploadDirectory = "uploads/sales/";
		$allowedExts = array("pdf", "xls", "xlsx", "csv", "doc", "docx");
		$temp = explode(".", $_FILES['supportDoc']["name"]);
		$RandNumber  = rand(0, 9999999999);
		$extension = end($temp);
		if ($_FILES['supportDoc']["error"] > 0)
		{
			$flag = 1;
			echo "<script>alert('".$_FILES['supportDoc']["error"]."'); window.location='lead_entry.php'; </script>";
			exit;
		} else {
			$uploadFileName = $RandNumber."_".date('dmY')."_".$_FILES['supportDoc']["name"];
			if(move_uploaded_file($_FILES['supportDoc']["tmp_name"],$uploadDirectory.$uploadFileName)) {
				$updateFile = ",supportDoc = '".$uploadFileName."'";
				$flag = 0;
			} else {
				$flag = 1;
				echo "<script>alert('Error: File upload failed'); window.location='lead_entry.php'; </script>";
				exit;
			}
		}
	}
	
	if($flag == 0) {	
		mysql_query("update lead_generate set leadCode = '".$_POST['leadCode']."',date = '".date('Y-m-d H:i:s',strtotime($_POST['date']))."',brandId = '".$_POST['brand']."',brandName = '".$brandName."',companyName = '".$_POST['companyName']."',contactPerson = '".$_POST['contactPerson']."',email = '".$_POST['email']."',address1 = '".$_POST['address1']."',address2 = '".$_POST['address2']."',city = '".$_POST['city']."',state = '".$_POST['state']."',country = '".$_POST['country']."',zip = '".$_POST['zip']."',secEmail1 = '".$_POST['secEmail1']."',secEmail2 = '".$_POST['secEmail2']."',website = '".$_POST['website']."',phone = '".$_POST['phone']."',countryCode = '".$_POST['countryCode']."',ext = '".$_POST['ext']."',secPhone1 = '".$_POST['secPhone1']."',secPhone2 = '".$_POST['secPhone2']."',fax = '".$_POST['fax']."',projCurrency = '".$_POST['projCurrency']."',projValue = '".$_POST['projValue']."',leadSource = '".$_POST['leadSource']."',ext_leadSource = '".$_POST['ext_leadSource']."',service = '".$_POST['service']."',ext_service = '".$_POST['ext_service']."',requirements = '".$_POST['requirement']."',noOfRecords = '".$_POST['noOfRecords']."',assignedTo = '".$_POST['assignTo']."',status = '".$status."',modifiedDate = '".date('Y-m-d H:i:s')."'".$updateFile.$workFile.$followUp.$dealvalue.$dropNote." where leadId = '".$_POST['leadId']."'");
		if(mysql_affected_rows() > 0) {
			if(isset($_POST['comments']) && $_POST['comments'] != '') {
				mysql_query("insert into lead_comments (userId,leadId,comments,createdDate) values ('".$_SESSION['userId']."','".$_POST['leadId']."','".$_POST['comments']."','".date('y-m-d H:i:s')."')");
			}
			if(isset($_POST['requestSample'])) {
				echo "<script>window.open('add_sales.php?id=".$_POST['leadId']."','_blank');</script>";
				echo "<script> window.location='view_lead.php?id=".$_POST['leadId']."&type=".$_SESSION['userType']."';</script>";
			} else {
				if($status == '1') {
					$alertMessage = 'Lead assigned Successfully';
				} else {
					$alertMessage = 'Lead updated Successfully';
				}
				echo "<script>alert('".$alertMessage."'); window.location='dashboard_lead.php?clear=1'; </script>";
			}
		} else {
			echo "<script>alert('Lead updation Failed'); window.location='dashboard_lead.php'; </script>";
		}
	}
}
?>