<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 8) {
	header('Location: dashboard.php'); exit;
}
include_once("dbconnect.php");
if(isset($_POST['data_export'])) {
	if($_POST['report_By'] == '1') {
		$result = mysql_query("Select * from lead_generate ORDER BY createddate ASC");
		$outputData = "Lead Code \t Lead Date \t Created By \t Brand Name \t Company Name \t Contact Person \t Email \t  Sec Email1 \t Sec Email2 \t Website \t Phone \t Sec Phone1 \t Sec Phone2 \t Fax \t Lead Source \t Lead Source Others \t Service \t Service Others \t  Requirements \t No Of Reocrds \t Support Document \t Work Order \t Assigned To \t Status \t Next Followup / Delivery Date \t Deal Currency \t Deal Amount \t \n";
		while($row = mysql_fetch_array($result)) {
			$outputData .= $row['leadCode']."\t";
			if($row['date'] != '0000-00-00 00:00:00') {
				$outputData .= date('d/m/Y',strtotime($row['date']))."\t";
			} else {
				$outputData .= " \t";
			}
			$userData = mysql_query("select * from admin_login where userId = '".$row['userId']."'");
			$userRow = mysql_fetch_array($userData);
			$outputData .= $userRow['name']."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['brandName']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['companyName']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['contactPerson']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['email']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['secEmail1']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['secEmail2']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['website']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['phone']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['secPhone1']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['secPhone2']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['fax']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['leadSource']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['ext_leadSource']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['service']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['ext_service']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['requirements']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['noOfRecords']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['supportDoc']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['workOrder']))))."\t";
			$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
			$userRow = mysql_fetch_array($userData);
			$outputData .= $userRow['name']."\t";
			if($row['status'] == 0) {
				$status = 'Leads to Assign';
			} else if($row['status'] == 1){
				$status = 'Newly Assigned Lead';
			} else if($row['status'] == 2){
				$status = 'Initial Stage';
			} else if($row['status'] == 3){
				$status = 'Pricing Stage';
			} else if($row['status'] == 4){
				$status = 'Follow up';
			} else if($row['status'] == 5){
				$status = 'Deal';
			} else if($row['status'] == 6) {
				$status = 'Dropped';
			} else if($row['status'] == 7) {
				$status = 'Disqualified';
			}
			$outputData .= $status."\t";
			if($row['nextFollowup'] != '0000-00-00 00:00:00') {
				$outputData .= date('d/m/Y',strtotime($row['nextFollowup']))."\t";
			} else {
				$outputData .= " \t";
			}
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['dealCurrency']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['dealAmount']))))."\t";
			$outputData .= "\n";
		}
	} else if($_POST['report_By'] == '2') {
		$result = mysql_query("Select * from sales_request ORDER BY createdDate ASC");
		$outputData = "Sample Code \t Sample Date \t Lead Code \t Sample Criteria \t Sales Executive \t Sample Data Format \t No of Sample Request \t Assigned By \t Processed By \t  No of Sample Collected \t Upload Collected \t Feedback \t Status \t\n";
		while($row = mysql_fetch_array($result)) {
			$outputData .= $row['code']."\t";
			if($row['date'] != '0000-00-00 00:00:00') {
				$outputData .= date('d/m/Y',strtotime($row['date']))."\t";
			} else {
				$outputData .= " \t";
			}
			$leadCodeQuery = mysql_query("select * from lead_generate where leadId = '".$row['leadId']."'");
			$leadCode = mysql_fetch_array($leadCodeQuery);
			$outputData .= $leadCode['leadCode']."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['sample_criteria']))))."\t";
			$userData = mysql_query("select * from admin_login where userId = '".$row['executive']."'");
			$userRow = mysql_fetch_array($userData);
			$outputData .= $userRow['name']."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['sample_data_format']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['no_of_sample_request']))))."\t";
			$userData = mysql_query("select * from admin_login where userId = '".$row['assigned_by']."'");
			$userRow = mysql_fetch_array($userData);
			$outputData .= $userRow['name']."\t";
			$userData = mysql_query("select * from admin_login where userId = '".$row['processed_by']."'");
			$userRow = mysql_fetch_array($userData);
			$outputData .= $userRow['name']."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['no_of_sample_collected']))))."\t";
			$outputData .= trim(str_replace("\t",' ',str_replace("\n",' ',str_replace("\r",' ',$row['upload_collected']))))."\t";
			if($row['feedback'] == 1){
				$feedback = 'GOOD';
			} else if($row['feedback'] == 2){
				$feedback = 'BAD';
			} else {
				$feedback = '';
			}
			$outputData .= $feedback."\t";
			if($row['status'] == 1){
				$status = 'Pending';
			} else if($row['status'] == 2){
				$status = 'Assigned';
			} else if($row['status'] == 3){
				$status = 'Completed';
			} else if($row['status'] == 4){
				$status = 'Closed';
			} else {
				$status = '';
			}
			$outputData .= $status."\t";
			$outputData .= "\n";
		}
	}
	$filename = "Activity_Report.xls";
	$contents = $outputData;
	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $contents;
}
?>