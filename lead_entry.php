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
if($_SESSION['userType'] == '3' && $_SESSION['userType'] == '4' && $_SESSION['userType'] == '9') {
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
						<h2>New Lead Form</h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post" enctype="multipart/form-data">
					<table class="form_table">
						<tr>
							<td>Date <span class="astrick">*</span></td>
							<td>
								<input type="text" readonly required value="<?php echo date('d-m-Y'); ?>" class="txtBx" name="date"/>
							</td>
						</tr>
						<tr>
							<td>Company Name <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="" class="txtBx" name="companyName"/>
							</td>
						</tr>
						<tr>
							<td>Contact Person <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="" class="txtBx" name="contactPerson"/>
							</td>
						</tr>
						<tr>
							<td>Email <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="" class="txtBx" name="email"/>
							</td>
						</tr>
						<tr>
							<td>Address 1</td>
							<td>
								<input type="text" value="" class="txtBx" name="address1"/>
							</td>
						</tr>
						<tr>
							<td>Address 2</td>
							<td>
								<input type="text" value="" class="txtBx" name="address2"/>
							</td>
						</tr>
						<tr>
							<td>City</td>
							<td>
								<input type="text" value="" class="txtBx" name="city"/>
							</td>
						</tr>
						<tr>
							<td>state</td>
							<td>
								<input type="text" value="" class="txtBx" name="state"/>
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
											<option value="<?php echo $countryData['id']; ?>"><?php echo $countryData['country']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Zip</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" class="txtBx" name="zip"/>
							</td>
						</tr>
						<tr>
							<td>Sec Email 1</td>
							<td>
								<input type="text" value="" class="txtBx" name="secEmail1"/>
							</td>
						</tr>
						<tr>
							<td>Sec Email 2</td>
							<td>
								<input type="text" value="" class="txtBx" name="secEmail2"/>
							</td>
						</tr>
						<tr>
							<td>Website </td>
							<td>
								<input type="text" value="" class="txtBx" name="website"/>
							</td>
						</tr>
						<tr>
							<td>Phone</td>
							<td>
								<input type="text" style="width:75px;" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" placeholder="Code" class="txtBx" name="countryCode"/>
								<input type="text" style="width:235px;" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" placeholder="Number" class="txtBx" name="phone"/>
								<input type="text" style="width:65px;" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" placeholder="Ext" class="txtBx" name="ext"/>
							</td>
						</tr>
						<tr>
							<td>Sec Phone 1</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" class="txtBx" name="secPhone1"/>
							</td>
						</tr>
						<tr>
							<td>Sec Phone 2</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" class="txtBx" name="secPhone2"/>
							</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td>
								<input type="text" pattern="[0-9-]+" title="Numeric Only (0-9)" value="" class="txtBx" name="fax"/>
							</td>
						</tr>
						<tr>
							<td>Brand <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="brand">
									<option value="">Select Brand</option>
									<option value="1">BM - Blue Mail Media</option>
									<option value="2">TD - Thomson Data</option>
									<!--option value="3">MP - Mail Prospects</option-->
									<option value="4">ED - E-Sales Data</option>
                                                                         <option value="5">IC - InfoClutch</option>
                                                                        <option value="6">MR - MedicoReach</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Sales Projection</td>
							<td>
								<select class="txtBx" style="width:150px;" name="projCurrency">
									<option value="">Select Currency</option>
									<option value="INR">INR</option>
									<option value="USD">USD</option>
									<option value="EUR">EUR</option>
									<option value="AED">AED</option>
									<option value="CHF">CHF</option>
									<option value="SEK">SEK</option>
									<option value="LKR">LKR</option>
									<option value="ZAR">ZAR</option>
									<option value="SAR">SAR</option>
									<option value="RUB">RUB</option>
									<option value="QAR">QAR</option>
									<option value="PHP">PHP</option>
									<option value="OMR">OMR</option>
									<option value="NZD">NZD</option>
									<option value="MYR">MYR</option>
									<option value="KWD">KWD</option>
									<option value="AUD">AUD</option>
									<option value="GBP">GBP</option>
									<option value="CNY">CNY</option>
									<option value="CAD">CAD</option>
								</select>
								<input type="text" style="width:230px;" pattern="[0-9.]+" title="Numeric Only (0-9)" id="projValue" value="" class="txtBx" name="projValue"/></td>
						</tr>
						<tr>
							<td>Lead source <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="leadSource" onchange="return showothers1(this.value);">
									<option value="">Select Lead Source</option>
									<option>Self generated</option>
									<option>Email Campain</option>
									<option>SEO/SEM/SMM</option>
									<option>Website</option>
									<option>referrals</option>
									<option>Live Chat</option>
<option>Nextmark</option>
<option>PPC</option>
<option>Inbound call</option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="hidden" id="ext_leadSource" value="" class="txtBx" name="ext_leadSource"/>
							</td>
						</tr>
						<tr>
							<td>Service <span class="astrick">*</span></td>
							<td>
								<select class="txtBx" required name="service" onchange="return showothers(this.value);">
									<option value="">Select Service</option>
									<option>Custom List</option>
									<option>Email Appending</option>
									<option>Data Appending</option>
									<option>data cleansing</option>
									<option>email campaign</option>
									<option>Other</option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="hidden" id="ext_service" value="" class="txtBx" name="ext_service"/>
							</td>
						</tr>
						<tr>
							<td>Requirement/Criteria</td>
							<td>
								<textarea class="txtArea" name="requirement"></textarea>
							</td>
						</tr>
						<tr>
							<td>No of records</td>
							<td>
								<input type="text" value="" pattern="[0-9]+" title="Numeric Only (0-9)" class="txtBx" name="noOfRecords"/>
							</td>
						</tr>
						<tr>
							<td>Supporting Document</td>
							<td>
								<div class="subBtn file_btn" style="">Upload
									<input type="file" class="choosefile" onchange="$('#file_name').html(this.value);" name="supportDoc" value=""/>
								</div>
								<span class="file_name" id="file_name">No files selected</span>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>Note :  Append file de-dupe file and other client files for project</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="add_lead" value="Submit"/></td>
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
</script>
<?php
if(isset($_POST['add_lead'])) {
	if($_POST['brand'] == '1') {
		$brandName = 'BM';
	} else if($_POST['brand'] == '2') {
		$brandName = 'TD';
	} else if($_POST['brand'] == '3') {
		$brandName = 'MP';
	} else if($_POST['brand'] == '4') {
		$brandName = 'ED';
	}else if($_POST['brand'] == '5') {
		$brandName = 'IC';
	}
	else if($_POST['brand'] == '6') {
		$brandName = 'MR';
	}
	$uploadFileName = '';
	$flag = 0;
	if(!empty($_FILES['supportDoc']["tmp_name"])) {
		$uploadDirectory = "uploads/sales/";
		$allowedExts = array("xls", "xlsx", "csv");
		$temp = explode(".", $_FILES['supportDoc']["name"]);
		$RandNumber  = rand(0, 9999999999);
		$extension = end($temp);
		if (in_array($extension, $allowedExts)) {
			if ($_FILES['supportDoc']["error"] > 0)
			{
				$flag = 1;
				echo "<script>alert('".$_FILES['supportDoc']["error"]."'); window.location='lead_entry.php'; </script>";
				exit;
			} else {
				$uploadFileName = $RandNumber."_".date('dmY')."_".$_FILES['supportDoc']["name"];
				if(move_uploaded_file($_FILES['supportDoc']["tmp_name"],$uploadDirectory.$uploadFileName)) {
					$flag = 0;
				} else {
					$flag = 1;
					echo "<script>alert('Error: File upload failed'); window.location='lead_entry.php'; </script>";
					exit;
				}
			}
		} else {
			echo "<script>alert('Invalid File, Upload xls / xlsx / csv'); window.location='lead_entry.php'; </script>";
			exit;
		}
	}
	if($flag == 0) {
		if($_SESSION['userType'] == 2 || $_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) {
			$coloumsField = ',assignedTo';
			$valueField = ",'".$_SESSION['userId']."'";
			$status = 1;
		} else {
			$coloumsField = '';
			$valueField = "";
			$status = 0;
		}
		mysql_query("Insert into lead_generate (userId,division,date,brandId,brandName,companyName,contactPerson,email,address1,address2,city,state,country,zip,secEmail1,secEmail2,website,phone,countryCode,ext,secPhone1,secPhone2,fax,projCurrency,projValue,leadSource,ext_leadSource,service,ext_service,requirements,noOfRecords,supportDoc,status,createddate,modifiedDate".$coloumsField.") values ('".$_SESSION['userId']."','".$_SESSION['userGroup']."','".date('Y-m-d H:i:s',strtotime($_POST['date']))."','".$_POST['brand']."','".$brandName."','".$_POST['companyName']."','".$_POST['contactPerson']."','".$_POST['email']."','".$_POST['address1']."','".$_POST['address2']."','".$_POST['city']."','".$_POST['state']."','".$_POST['country']."','".$_POST['zip']."','".$_POST['secEmail1']."','".$_POST['secEmail2']."','".$_POST['website']."','".$_POST['phone']."','".$_POST['countryCode']."','".$_POST['ext']."','".$_POST['secPhone1']."','".$_POST['secPhone2']."','".$_POST['fax']."','".$_POST['projCurrency']."','".$_POST['projValue']."','".$_POST['leadSource']."','".$_POST['ext_leadSource']."','".$_POST['service']."','".$_POST['ext_service']."','".$_POST['requirement']."','".$_POST['noOfRecords']."','".$uploadFileName."','".$status."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'".$valueField.")");
		if(mysql_affected_rows() > 0) {
			$lastInsertId = mysql_insert_id();
			$leadCode = "WS".str_pad($lastInsertId, 4, "0", STR_PAD_LEFT);
			mysql_query("update lead_generate set leadCode = '".$leadCode."' where leadId = '".$lastInsertId."'");
			echo "<script>alert('Lead Added successfully'); window.location='dashboard_lead.php?clear=1'; </script>";
		} else {
			echo "<script>alert('Lead Adding Failed'); window.location='lead_entry.php'; </script>";
		}
	}
}
?>