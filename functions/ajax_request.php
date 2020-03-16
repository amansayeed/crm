<?php
date_default_timezone_set("Asia/Kolkata"); 
require_once("../dbconnect.php");
session_start();

if(isset($_POST['delete_user'])) {
	$result = mysql_query("update admin_login set status = '0' where userId = '".$_POST['id']."'");
	if(mysql_affected_rows() == 1)
	{
		echo "success";
	}
}
if(isset($_POST['delete_card'])) {
	$result = mysql_query("update data_cards set status = '0' where dataCardId = '".$_POST['id']."'");
	if(mysql_affected_rows() == 1)
	{
		echo "success";
	}
}
if(isset($_POST['updateColoums'])) {
	$result = mysql_query("update coloum_access set ".$_POST['coloumName']." = '".$_POST['coloumValue']."' where sno = '".$_POST['coloumId']."'");
	echo mysql_error();
	if(mysql_affected_rows() == 1)
	{
		echo "success";
	}
}

if(isset($_POST['delete_data'])) {
	$result = mysql_query("update sales_request set status = '0' where requestId = '".$_POST['id']."'");
	if(mysql_affected_rows() == 1)
	{
		echo "success";
	}
}

if(isset($_POST['update_date'])) {
	if(isset($_POST['fromDate']) && isset($_POST['toDate'])) {
		if($_POST['fromDate'] != '') {
			$_SESSION['fromDate'] = date('Y-m-d',strtotime($_POST['fromDate'])).' 00:00:00';
		} else {
			$_SESSION['fromDate'] = '';
		}
		if($_POST['toDate'] != '') {
			$_SESSION['toDate'] = date('Y-m-d',strtotime($_POST['toDate'])).' 23:59:59';
		} else {
			$_SESSION['toDate'] = '';
		}
	}
	if(isset($_POST['searchCode'])) {
		$_SESSION['searchCode'] = $_POST['searchCode'];
	}
	if(isset($_POST['searchKeyword'])) {
		$_SESSION['searchKeyword'] = $_POST['searchKeyword'];
	}
	echo "success";
}

if(isset($_POST['report_by'])) {
	if($_POST['value'] == 'group') { ?>
		<td></td>
		<td>
			<select required name="reportByType" class="txtBx">
				<option value="">Select Group</option>
				<option value="1">BM</option>
				<option value="2">TD</option>
			</select>
		</td>
	<?php } else if($_POST['value'] == 'user') { ?>
		<td></td>
		<td>
			<select required name="reportByType" class="txtBx" onchange="return userlist(this.value);">
				<option value="">Select User Type</option>
				<?php if($_POST['reportType'] == '1' || $_POST['reportType'] == '2') { ?>
					<option value="6">Business Analysit</option>
					<option value="7">Marketing Co-ordinator</option>
					<option value="2">BDE/BDC</option>
					<option value="5">BDM</option>
				<?php } else { ?>
					<option value="2">BDE/BDC</option>
					<option value="4">DTL</option>
				<?php } ?>
			</select>
		</td>
	<?php } else if($_POST['value'] == 'brand') { ?>
		<td></td>
		<td>
			<select required name="reportByType" class="txtBx">
				<option value="">Select Brand</option>
				<option value="1">BM - Blue Mail Media</option>
				<option value="2">TD - Thomson Data</option>
				<option value="3">MP - Mail Prospects</option>
				<option value="4">ED - E-Sales Data</option>
                                <option value="5">IC - InfoClutch</option>
                                <option value="6">MR - MedicoReach</option>
			</select>
		</td>
	<?php } else if($_POST['value'] == 'lead_source') { ?>
		<td></td>
		<td>
			<select required name="reportByType" class="txtBx">
				<option value="">Select Lead Source</option>
				<option>Self generated</option>
				<option>Email Campain</option>
				<option>SEO/SEM/SMM</option>
				<option>Website</option>
				<option>referrals</option>
				<option>Live Chat</option>
			</select>
		</td>
	<?php } else if($_POST['value'] == 'service') { ?>
		<td></td>
		<td>
			<select required name="reportByType" class="txtBx">
				<option value="">Select Service</option>
				<option>Custom List</option>
				<option>Email Appending</option>
				<option>Data Appending</option>
				<option>data cleansing</option>
				<option>email campaign</option>
				<option>Other</option>
			</select>
		</td>
	<?php }
}
if(isset($_POST['userlist'])) { ?>
	<td></td>
	<td>
		<select required name="userId" class="txtBx">
		<?php
		$result = mysql_query("select * from admin_login where userType = '".$_POST['userType']."' AND status !='0' ORDER BY name");
		while($row = mysql_fetch_array($result)) { ?>
			<option value="<?php echo $row['userId']; ?>"><?php echo $row['name']; ?></option>
		<?php } ?>
		</select>
	</td>
<?php } 
if(isset($_POST['assignUser'])) {
	if($_POST['userType'] == '5') {
		$userType = '8';
	} else if($_POST['userType'] == '7') {
		$userType = '8';
	} else if($_POST['userType'] == '6') {
		$userType = '5';
	} else if($_POST['userType'] == '2') {
		$userType = '5';
	} else if($_POST['userType'] == '3') {
		$userType = '8';
	} else if($_POST['userType'] == '4') {
		$userType = '3';
	} else if($_POST['userType'] == '11') {
		$userType = '8';
	} else if($_POST['userType'] == '12') {
		$userType = '11';
	} else if($_POST['userType'] == '13') {
		$userType = '12';
	} else {
		$userType = '10';
	}
	?>
		<td>Assign User</td>
		<td>
			<select required class="txtBx" name="assignUser">
				<option value="">Select User</option>
				<?php
				$userData = mysql_query("select * from admin_login where userType= '".$userType."' and status = '1' ORDER BY name");
				while($userRow = mysql_fetch_array($userData)) { ?>
					<option value="<?php echo $userRow['userId']; ?>"><?php echo $userRow['name']; ?></option>
				<?php } ?>
			</select>
		</td>
	<?php
} ?>
