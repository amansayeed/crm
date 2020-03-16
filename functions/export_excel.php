<?php
require_once('../dbconnect.php');
if($_POST['exportType'] == 'leadExport') {
	$query = base64_decode($_POST['exportStatement']);
	$result = mysql_query($query);
	$i = 1;
	$exportValue = "Sno\tLead Code\tLead Date\tBrand\tCompany Name\tStatus\tCreated By\tAssigned to\tContact Person\tEmail Id\tPhone\tWebsite\tSample Codes\n";
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
		} 
		$samplesCode = '';
		$sampleQuery = mysql_query("select * from sales_request where leadId = '".$row['leadId']."'");
		if(mysql_num_rows($sampleQuery) > 0) {
			while($sampleRow = mysql_fetch_array($sampleQuery)) {
				$samplesCode = $sampleRow['code'].",";
			}
		}
		
		$exportValue .= $i."\t".$row['leadCode']."\t".date('d/m/Y',strtotime($row['date']))."\t".$row['brandName']."\t".$row['companyName']."\t".$statusName."\t";

		$userData = mysql_query("select * from admin_login where userId = '".$row['userId']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $userRow['name']."\t";

		$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $userRow['name']."\t";
		$exportValue .= $row['contactPerson']."\t".$row['email']."\t".$row['phone']."\t".$row['website']."\t".$samplesCode."\t\n";
		$i = $i + 1;
	} 
	$filename = "Activity_Report_lead.xls";
}
if($_POST['exportType'] == 'bookingReport') {
	$result = mysql_query("select lead_generate.*,payment_details.paymentType as payType,payment_details.payment,payment_details.paymentId,payment_details.createdDate as payDate from lead_generate LEFT JOIN payment_details ON payment_details.leadId = lead_generate.leadId where lead_generate.status = '5' AND lead_generate.modifiedDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
	$exportValue = "Sno\tLead Code\tDeal Closed Date\tCompany Name\tBrand\tPayment status\tCurrency\tDeal Amount\tAmount Received\tPayment Received Date\tPending\tDeal Closed By\tLead Created By\n";
	echo mysql_error();
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
		} 
		if($row['payDate'] != '') {
			$payDate = date('d/m/Y',strtotime($row['payDate']));
		} else {
			$payDate = '';
		}
		$exportValue .= $i."\t".$row['leadCode']."\t".date('d/m/Y',strtotime($row['modifiedDate']))."\t".$row['companyName']."\t".$row['brandName']."\t".$row['payType']."\t".$row['dealCurrency']."\t".$row['dealAmount']."\t".$row['payment']."\t".$payDate."\t".($row['dealAmount']-$row['payment'])."\t";
		$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $userRow['name']."\t";
		$userData = mysql_query("select * from admin_login where userId = '".$row['userId']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $userRow['name']."\t\n";
		$i = $i + 1;
	}
	$filename = "Activity_Report_booking.xls";
}
if($_POST['exportType'] == 'paymentCollection') {
	$result = mysql_query("select lead_generate.*,payment_details.paymentType as payType,payment_details.payment,payment_details.paymentId,payment_details.paymentDate as payDate,(select SUM(a.payment) from payment_details as a where a.leadId = payment_details.leadId) as totalAmount from payment_details LEFT JOIN lead_generate ON payment_details.leadId = lead_generate.leadId where payment_details.paymentDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
	$exportValue = "Sno\tLead Code\tDeal Closed Date\tCompany Name\tBrand\tPayment status\tCurrency\tDeal Amount\tAmount Received\tPayment Received Date\tTotal Amount Received\tPending\tDeal Closed By\tLead Created By\n";
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
		} 
		if($row['payDate'] != '') {
			$payDate = date('d/m/Y',strtotime($row['payDate']));
		} else {
			$payDate = '';
		}
		$exportValue .= $i."\t".$row['leadCode']."\t".date('d/m/Y',strtotime($row['modifiedDate']))."\t".$row['companyName']."\t".$row['brandName']."\t".$row['payType']."\t".$row['dealCurrency']."\t".$row['dealAmount']."\t".$row['payment']."\t".$payDate."\t".$row['totalAmount']."\t".($row['dealAmount']-$row['totalAmount'])."\t";
		$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $userRow['name']."\t";
		$userData = mysql_query("select * from admin_login where userId = '".$row['userId']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $userRow['name']."\t\n";
		$i = $i + 1;
	}
	$filename = "Activity_Report_payment.xls";
}
if($_POST['exportType'] == 'ProjectReport') {
	$filename = "Activity_Report_project.xls";
	$result = mysql_query("select * from admin_login where userType = '13'");
	$i = 1;
	$exportValue = "Sno\tPTL User\tProject Initicated\tProject Completed\tRework\t\n";
	while($row = mysql_fetch_array($result)) { 
		$exportValue .= $i."\t".$row['name']."\t";

		$result1 = mysql_query("select count(*) as projectCount from project_request where processed_by = '".$row['userId']."' AND assignedDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
		$row1 = mysql_fetch_array($result1);
		$exportValue .= $row1['projectCount']."\t";

		$result2 = mysql_query("select count(*) as projectCount from project_request where status = '3' AND processed_by = '".$row['userId']."' AND completedDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
		$row2 = mysql_fetch_array($result2);
		$exportValue .= $row2['projectCount']."\t"; 
			
		$result2 = mysql_query("select count(*) as projectCount from project_request where reworkFlag = '1' AND processed_by = '".$row['userId']."' AND completedDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
		$row2 = mysql_fetch_array($result2);
		$exportValue .= $row2['projectCount']."\t\n"; 

		$i = $i + 1;
	}
}
if($_POST['exportType'] == 'GMReport') {
	$filename = "Activity_Report_GM.xls";
	
	$userData = mysql_query("SELECT * FROM admin_login WHERE (userType IN ('5','7') AND superior1 = '".$_POST['gm_user']."') OR superior1 IN (select userId from admin_login where superior1 = '".$_POST['gm_user']."' and userType = '5')");
	$exportValue = "Sno\tExecutive Name\tUser Roles\tTotal Leads\tLeads to Assign\tNewly Assigned lead\tInitial Stage\tPricing stage\tFollowup\tDeal\tDropped\tDisqualified\t\n";
	$i = 1;
	while($userRow = mysql_fetch_array($userData)){
		$whereCase = " AND createddate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'";
		if($userRow['userType'] == '2') {
			$userTypeName = 'BDE/BDC';
			$whereCase .= " And assignedTo = '".$userRow['userId']."'";
		} else if($userRow['userType'] == '5') {
			$userTypeName = 'BDM';
			$whereCase .= " AND ((userId IN (select userId from admin_login where superior1 = '".$userRow['userId']."')) OR (assignedTo = '".$userRow['userId']."') OR (assignedTo IN (select userId from admin_login where superior1 = '".$userRow['userId']."')))";
		} else if($userRow['userType'] == '6') {
			$userTypeName = 'Business Analysit';
			$whereCase .= " And userId = '".$userRow['userId']."'";
		} else if($userRow['userType'] == '7') {
			$userTypeName = 'Lead Generator';
			$whereCase .= " And userId = '".$userRow['userId']."'";
		}
		$statusQuery = mysql_query("SELECT (SELECT count(*) FROM lead_generate WHERE status='2'".$whereCase.") AS initial, (SELECT count(*) FROM lead_generate WHERE status='3'".$whereCase.") AS pricing, (SELECT count(*) FROM lead_generate WHERE status='4'".$whereCase.") AS followup, (SELECT count(*) FROM lead_generate WHERE status='5'".$whereCase.") AS complete, (SELECT count(*) FROM lead_generate WHERE status='0'".$whereCase.") AS newLead, (SELECT count(*) FROM lead_generate WHERE status='1'".$whereCase.") AS assigned, (SELECT count(*) FROM lead_generate WHERE status='6'".$whereCase.") AS dropped, (SELECT count(*) FROM lead_generate WHERE status='7'".$whereCase.") AS disqualified FROM lead_generate LIMIT 0,1");
		$statusRow = mysql_fetch_array($statusQuery);
		$totalLeads = $statusRow['newLead'] + $statusRow['assigned'] + $statusRow['initial'] + $statusRow['pricing'] + $statusRow['followup'] + $statusRow['complete'] + $statusRow['dropped'] + $statusRow['disqualified'];
		$exportValue .= $i."\t".$userRow['name']."\t".$userTypeName."\t".$totalLeads."\t".$statusRow['newLead']."\t".$statusRow['assigned']."\t".$statusRow['initial']."\t".$statusRow['pricing']."\t".$statusRow['followup']."\t".$statusRow['complete']."\t".$statusRow['dropped']."\t".$statusRow['disqualified']."\t\n";
		$i = $i + 1;
	}
}
if($_POST['exportType'] == 'leadCount') {
	$result = mysql_query("select DISTINCT(userId) from lead_generate where createdDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
	$exportValue = "Sno\tCreated By\tLead Count\t\n";
	$i = 1;
	while($row = mysql_fetch_array($result)) { 
		$result1 = mysql_query("select count(*) as LeadCount from lead_generate where userId = '".$row['userId']."' AND createdDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'");
		$row1 = mysql_fetch_array($result1);
		
		$userData = mysql_query("select * from admin_login where userId = '".$row['userId']."'");
		$userRow = mysql_fetch_array($userData);
		$exportValue .= $i."\t".$userRow['name']."\t".$row1['LeadCount']."\t\n";
		$i = $i + 1;
	}
	$filename = "Activity_Report_leadcount.xls";
}
$contents = $exportValue;
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
echo $contents;
?>