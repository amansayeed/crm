<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] == '3' || $_SESSION['userType'] == '4' || $_SESSION['userType'] == '9') {
	header('Location: dashboard.php'); exit;
} else if($_SESSION['userType'] == '11' && $_SESSION['userType'] == '12' && $_SESSION['userType'] == '13') {
	header('Location: project/dashboard.php'); exit;
}
include_once("dbconnect.php");
include_once("functions/pagination.php");
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
<script type="text/javascript" src="tableExport.js"></script>
<script type="text/javascript" src="jquery.base64.js"></script>
<script type="text/javascript" src="jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="jspdf/jspdf.js"></script>
<script type="text/javascript" src="jspdf/libs/base64.js"></script>
<script>
function delete_data(id) {
	var r = confirm("Are you sure want to Delete?");
    if (r == true) {
        $.post('functions/ajax_request.php', {delete_data:'Yes', id:id},function(data){
			if(data == 'success') {
				alert("Deleted Successfully");
				var getDatas = window.location.search;
				window.location = "manage_data.php"+getDatas;
			} 
		});
    }
}
function fromToDate() {
	$flag = 0;
	if($('#fromDate').val() == '' && $('#toDate').val() != '') {
		alert('Please choose From Date');
		$flag = 1;
		return false;
	}
	if($('#fromDate').val() != '' && $('#toDate').val() == '') {
		alert('Please choose To Date');
		$flag = 1;
		return false;
	}
	if($flag == 0) {
		$.post('functions/ajax_request.php', {update_date:'YES',fromDate:$('#fromDate').val(),toDate:$('#toDate').val(),searchCode:$('#searchCode').val(),searchKeyword:$('#searchKeyword').val()},function(data){
			if(data == 'success') {
				var str = window.location.href;
				var finalUrl = str.replace('clear=1', '-');
				window.location=finalUrl;
			} 
		});
	}
	return false;
}
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Search Result</h2>
					</div>
					<table id="lead_records" class="data_table">
						<tr>
							<th>S.No</th>
							<th><a href="<?php sortUrl('leadCode'); ?>">Lead Code</a></th>
							<th><a href="<?php sortUrl('date'); ?>">Date</a></th>
							<th><a href="<?php sortUrl('brandName'); ?>">Brand</a></th>
							<th><a href="<?php sortUrl('companyName'); ?>">Company Name</a></th>
							<th><a href="<?php sortUrl('status'); ?>">Status</a></th>
							<?php if($_SESSION['userType'] == 2) { ?>
								<th><a href="<?php sortUrl('nextFollowup'); ?>">Followup Date</a></th>
							<?php } else if($_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
								<th><a href="<?php sortUrl('userId'); ?>">Created by</a></th>
							<?php } else { ?>
								<th>Assigned to</th>
							<?php } ?>
							<th width="140px;">Action</th>
						</tr>
						<?php
						$whereCase = '';
						if($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) {
							$whereCase = " And userId = '".$_SESSION['userId']."'";
						} else if($_SESSION['userType'] == 2) {
							$whereCase = " And assignedTo = '".$_SESSION['userId']."'";
						} else if($_SESSION['userType'] == 5) {
							$whereCase = " AND (userId IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
						} else if($_SESSION['userType'] == 8) {
							$whereCase = " AND (userId IN (select userId from admin_login where superior1 IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."')) OR userId IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo = '".$_SESSION['userId']."'  OR userId = '".$_SESSION['userId']."')";
						}
						if(isset($_POST['searchLead'])) {
							if(isset($_POST['searchField1']) && $_POST['searchField1'] != '') {
								$_SESSION['searchField1'] = $_POST['searchField1'];
								$_SESSION['searchValue1'] = $_POST['searchValue1'];
							} else {
								$_SESSION['searchField1'] = '';
								$_SESSION['searchValue1'] = '';
							}
							if(isset($_POST['searchField2']) && $_POST['searchField2'] != '') {
								$_SESSION['searchField2'] = $_POST['searchField2'];
								$_SESSION['searchValue2'] = $_POST['searchValue2'];
							} else {
								$_SESSION['searchField2'] = '';
								$_SESSION['searchValue2'] = '';
							}
							if(isset($_POST['searchField3']) && $_POST['searchField3'] != '') {
								$_SESSION['searchField3'] = $_POST['searchField3'];
								$_SESSION['searchValue3'] = $_POST['searchValue3'];
							} else {
								$_SESSION['searchField3'] = '';
								$_SESSION['searchValue3'] = '';
							}
						}
						if(isset($_SESSION['searchField1']) && $_SESSION['searchField1'] != '') {
							$searchCode1 = " AND `".$_SESSION['searchField1']."` LIKE '%".$_SESSION['searchValue1']."%'";
						} else {
							$searchCode1 = '';
						}
						if(isset($_SESSION['searchField2']) && $_SESSION['searchField2'] != '') {
							$searchCode2 = " AND `".$_SESSION['searchField2']."` LIKE '%".$_SESSION['searchValue2']."%'";
						} else {
							$searchCode2 = '';
						}
						if(isset($_SESSION['searchField3']) && $_SESSION['searchField3'] != '') {
							$searchCode3 = " AND `".$_SESSION['searchField3']."` LIKE '%".$_SESSION['searchValue3']."%'";
						} else {
							$searchCode3 = '';
						}
						
						$url = '?';
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$sortOption = ' ORDER BY '.$_GET['sort'].' '.$_GET['sortType'];
							$url .= "sort=".$_GET['sort']."&sortType=".$_GET['sortType']."&";
						} else {
							$sortOption = ' ORDER BY createddate DESC';
						}
						
						
						$page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
						if ($page <= 0) $page = 1;
						
						$per_page = 50; // Set how many records do you want to display per page.
						$startpoint = ($page * $per_page) - $per_page;
						$statement = "lead_generate where leadId != ''".$whereCase.$searchCode1.$searchCode2.$searchCode3.$sortOption;
						$exportStatement = "select * from {$statement}";
						$result = mysql_query("select * from {$statement} LIMIT {$startpoint} , {$per_page}");
						$i = (($page - 1)*$per_page) + 1;
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
							} ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $row['leadCode']; ?></td>
							<td><?php echo date('d/m/Y',strtotime($row['date'])); ?></td>
							<td><?php echo $row['brandName']; ?></td>
							<td><?php echo $row['companyName']; ?></td>
							<td><span class="<?php echo $addClass; ?>"><?php echo $statusName; ?></span></td>
							<?php if($_SESSION['userType'] == 2) { ?>
								<td><?php
									if($row['nextFollowup'] != '0000-00-00 00:00:00') {
										echo date('d-M-Y',strtotime($row['nextFollowup']));
									} ?>
								</td>
							<?php } else if($_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
								<td><?php
									$userData = mysql_query("select * from admin_login where userId = '".$row['userId']."'");
									$userRow = mysql_fetch_array($userData);
									echo $userRow['name'];
									?>
								</td>
							<?php } else { ?>
								<td><?php
									$userData = mysql_query("select * from admin_login where userId = '".$row['assignedTo']."'");
									$userRow = mysql_fetch_array($userData);
									echo $userRow['name'];
									?>
								</td>
							<?php } ?>
							<td>
								<a href="view_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="view">View</a>
								<?php if((($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) && $row['status'] == 0) || (($_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) && (($row['status'] == 0 || $row['status'] == 1 || $row['assignedTo'] == $_SESSION['userId']) && $row['status'] != 5)) || ( $_SESSION['userType'] == 2 && $row['assignedTo'] == $_SESSION['userId'] && $row['status'] != 5)) { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="edit_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="edit">Edit</a>
								<?php } ?>
								<?php
								if(($_SESSION['userType'] == 8) && ($row['status'] == 1 || $row['status'] == 2 || $row['status'] == 3 || $row['status'] == 4)) { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="reassign_lead.php?id=<?php echo $row['leadId']; ?>&type=<?php echo $_SESSION['userType']; ?>" class="delete">Reassign</a>
								<?php } ?>
							</td>
						</tr>
						<?php 
						$i = $i + 1;
						} ?>
					</table>
					<form action="functions/export_excel.php" id="leadExportForm" method="post">
						<input type="hidden" id="exportStatement" value="<?php echo base64_encode($exportStatement); ?>" name="exportStatement" />
						<input type="hidden" id="exportType" value="leadExport" name="exportType" />
					<form>
					<div style="float:right; margin:10px;"><?php echo pagination($statement,$per_page,$page,$url); ?></div>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
	<script>
		jQuery('.datetimepicker').datetimepicker({
			timepicker:false,
			format:'d-m-Y',
			maxDate:'+1970/01/01',
			scrollInput: false
		});
	</script>
</body>
</html>
<?php
function sortUrl($sort) {
	$returnString = $_SERVER['PHP_SELF'].'?';
	$returnString .= 'sort='.$sort.'&';
	if(isset($_GET['sortType']) && isset($_GET['sort']) && $_GET['sortType'] == 'ASC' && $_GET['sort'] == $sort) {
		$sortType = 'DESC';
	} else {
		$sortType = 'ASC';
	}
	$returnString .= 'sortType='.$sortType;
	echo $returnString;
}
?>
<script>
function exportExcel() {
	$('#leadExportForm').submit();
}
</script>