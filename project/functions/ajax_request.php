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

if(isset($_POST['updateColoums'])) {
	$result = mysql_query("update coloum_access_project set ".$_POST['coloumName']." = '".$_POST['coloumValue']."' where sno = '".$_POST['coloumId']."'");
	echo mysql_error();
	if(mysql_affected_rows() == 1)
	{
		echo "success";
	}
}

if(isset($_POST['delete_data'])) {
	$result = mysql_query("update project_request set status = '0' where requestId = '".$_POST['id']."'");
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
?>