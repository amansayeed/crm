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
if(isset($_GET['clear']) && $_GET['clear'] == 1) {
	unset($_SESSION['fromDate']);
	unset($_SESSION['toDate']);
	unset($_SESSION['searchCode']);
	unset($_SESSION['searchKeyword']);
}
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
						<h2>Manage Data</h2>
					</div>
					<div class="filter_area">
						<table class="filter_table" style="">
							<form action="" method="post" onSubmit="return fromToDate();">
							<tr>
								<td height="50px">
									Search By:<input type="text" value="<?php if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') { echo $_SESSION['searchCode']; } ?>" id="searchCode" class="ftr_txt" name="searchCode" placeholder="CODE" />
									<input type="text" value="<?php if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') { echo $_SESSION['searchKeyword']; } ?>" id="searchKeyword" class="ftr_txt" name="searchKeyword" placeholder="Keyword" />
								</td>
								<td>
									From: <input type="text" name="fromDate" id="fromDate" class="ftr_txt datetimepicker" value="<?php if(isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '') { echo date('d-m-Y',strtotime($_SESSION['fromDate'])); } ?>" readonly placeholder="From Date"/>
									to: <input type="text" name="toDate" id="toDate" class="ftr_txt datetimepicker" value="<?php if(isset($_SESSION['toDate']) && $_SESSION['toDate'] != '') { echo date('d-m-Y',strtotime($_SESSION['toDate'])); } ?>" readonly placeholder="To Date"/>
								</td>
								<td align="right"><input type="submit" class="ftr_sub subBtn frm_sub" name="update_date" value="Filter"/></td>
							</tr>
							</form>
						</table>
					</div>
					<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
						<a href="javascript:void(0);" onclick="exportExcel()" style="padding:10px; float:right; font-size:14px;">Export: <img width="30px" src="images/excel_icon.png" /></a>
					<?php } ?>
					<form action="multi_reasign_lead.php" method="post" onSubmit="return checkvalidate();"> 
					<table id="lead_records" class="data_table">
						<tr>
							<?php if($_SESSION['userType'] == 8) { ?>
							<th></th>
							<?php } ?>
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
						if(isset($_GET['status'])) {
							$statusFilter = " AND status = '".$_GET['status']."'";
						} else {
							$statusFilter = '';
						}
						if(isset($_GET['source'])) {
							$sourceFilter = " AND leadSource = '".$_GET['source']."'";
						} else {
							$sourceFilter = '';
						}
						if(isset($_SESSION['fromDate']) && isset($_SESSION['toDate']) && $_SESSION['fromDate'] != '' &&$_SESSION['fromDate'] != '') {
							$dateBtw = " AND createdDate BETWEEN '".$_SESSION['fromDate']."' AND '".$_SESSION['toDate']."'";
						} else {
							$dateBtw = '';
						}
						if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') {
							$searchCode = " AND Leadcode LIKE '%".$_SESSION['searchCode']."%'";
						} else {
							$searchCode = '';
						}
						if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') {
							$searchKeyword = " AND (Leadcode LIKE '%".$_SESSION['searchKeyword']."%' OR brandName LIKE '%".$_SESSION['searchKeyword']."%' OR companyName LIKE '%".$_SESSION['searchKeyword']."%' OR contactPerson LIKE '%".$_SESSION['searchKeyword']."%' OR userId IN (select userId from admin_login where status = 1 AND name LIKE '%".$_SESSION['searchKeyword']."%'))";
						} else {
							$searchKeyword = '';
						}
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$sortOption = ' ORDER BY '.$_GET['sort'].' '.$_GET['sortType'];
						} else {
							$sortOption = ' ORDER BY createddate DESC';
						}
						if(isset($_GET['source'])) { 
							$url = "?source=".$_GET['source']."&";
						} else {
							$url = "?status=".$_GET['status']."&";
						}
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$url .= "sort=".$_GET['sort']."&sortType=".$_GET['sortType']."&";
						}
						
						$page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
						if ($page <= 0) $page = 1;
						
						$per_page = 50; // Set how many records do you want to display per page.
						$startpoint = ($page * $per_page) - $per_page;
						$statement = "lead_generate where leadId != ''".$whereCase.$statusFilter.$sourceFilter.$dateBtw.$searchCode.$searchKeyword.$sortOption;
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
							<?php if($_SESSION['userType'] == 8) {
								if($row['status'] == 1 || $row['status'] == 2 || $row['status'] == 3 || $row['status'] == 4) { ?>
									<td><input type="checkbox" name="reasignCheck[]" value="<?php echo $row['leadId']; ?>" /></td>
								<?php } else { ?>
									<td></td>
							<?php } } ?>
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
					<?php if($_SESSION['userType'] == 8) { ?>
						<div style="margin-top:20px">
							<input type="submit" class="ftr_sub subBtn frm_sub" name="multi_reasign" value="Multi Reasign"/>
						</div>
					<?php } ?>
					</form>
					<form action="functions/export_excel.php" id="leadExportForm" method="post">
						<input type="hidden" id="exportStatement" value="<?php echo base64_encode($exportStatement); ?>" name="exportStatement" />
						<input type="hidden" id="exportType" value="leadExport" name="exportType" />
					</form>
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
	if(isset($_GET['status'])) { $returnString .= 'status='.$_GET['status'].'&';}
	if(isset($_GET['source'])) { $returnString .= 'source='.$_GET['source'].'&';}
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
function checkvalidate() {
	var flag = 0;
	if($('#lead_records').find('input[type=checkbox]:checked').length == 0)
    {
        alert('Please select atleast one lead to proceed');
		flag = 1;
    }
	if(flag == 0) {
		return true; 
	} else {
		return false;
	}
}
</script>