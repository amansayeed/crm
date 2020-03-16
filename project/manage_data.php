<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
include_once("dbconnect.php");
if($_SESSION['userType'] == 11) {
	$type = "sales";
} else if($_SESSION['userType'] == 12) {
	$type = "dm";
} else if($_SESSION['userType'] == 13) {
	$type = "dtl";
} else {
	$type = "";
}
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
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="../js/jquery.datetimepicker.js"></script>
<script>
function delete_data(id) {
	var r = confirm("Are you sure want to Delete?");
    if (r == true) {
        $.post('functions/ajax_request.php', {delete_data:'Yes', id:id},	function(data){
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
					<div class="cnt_title mng_user">
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
						<a href="javascript:void(0);" onclick="tableToExcel('lead_records', 'Activity_report')" style="padding:10px; float:right; font-size:14px;">Export: <img width="30px" src="../images/excel_icon.png" /></a>
					<?php } ?>
					<table id="lead_records" class="data_table">
						<tr>
							<th><a href="<?php sortUrl('date'); ?>">Date</a></th>
							<th><a href="<?php sortUrl('leadId'); ?>">Lead Code</a></th>
							<?php if($_SESSION['userType'] == 11) { ?>
							<th>Company Name</th>
							<?php } ?>
							<?php if($_SESSION['userType'] == 12) { ?>
								<th><a href="<?php sortUrl('processed_by'); ?>">Assigned to</a></th>
							<?php } ?>
							<th><a href="<?php sortUrl('status'); ?>">Form Status</a></th>
							<th width="180px;">Actions</th>
						</tr>
						<?php
						$whereCase = '';
						if($_SESSION['userType'] == 11) {
							$whereCase = " And createdBy = '".$_SESSION['userId']."'";
						} else if($_SESSION['userType'] == 13) {
							$whereCase = " And processed_by = '".$_SESSION['userId']."'";
						} else if($_SESSION['userType'] == 8) {
							$whereCase = " And createdBy IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."')";
						}
						if(isset($_SESSION['fromDate']) && isset($_SESSION['toDate']) && $_SESSION['fromDate'] != '' &&$_SESSION['fromDate'] != '') {
							$dateBtw = " AND createdDate BETWEEN '".$_SESSION['fromDate']."' AND '".$_SESSION['toDate']."'";
						} else {
							$dateBtw = '';
						}
						if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') {
							$searchCode = " AND leadId IN (select leadId from lead_generate where leadCode LIKE '%".$_SESSION['searchCode']."%')";
						} else {
							$searchCode = '';
						}
						if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') {
							$searchKeyword = " AND leadId IN (select leadId from lead_generate where leadCode LIKE '%".$_SESSION['searchKeyword']."%')";
						} else {
							$searchKeyword = '';
						}
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$sortOption = ' ORDER BY '.$_GET['sort'].' '.$_GET['sortType'];
						} else {
							$sortOption = ' ORDER BY createdDate DESC';
						}
						if($_GET['listType'] == 1) {
							$result = mysql_query("select * from project_request where status = '".$_GET['list']."'".$whereCase.$dateBtw.$searchCode.$searchKeyword.$sortOption);
						} else if($_GET['listType'] == 2) {
							$result = mysql_query("select * from project_request where status != '0' and feedback = '".$_GET['list']."'".$dateBtw.$searchCode.$searchKeyword.$sortOption);
						} else if($_GET['listType'] == 3) {
							$result = mysql_query("select * from project_request where status != '0' and salesDivision = '".$_GET['list']."'".$dateBtw.$searchCode.$searchKeyword.$sortOption);
						}
						while($row = mysql_fetch_array($result)) { 
							$leadResult = mysql_query("select * from lead_generate where status = '5' and leadId = '".$row['leadId']."'");
							$leadRow = mysql_fetch_array($leadResult);
							if($row['status'] == 1) {
								$addClass = 'delete';
								$statusName = 'Pending';
							} else if($row['status'] == 2) {
								$addClass = 'view';
								$statusName = 'Assigned';
							} else if($row['status'] == 3) {
								$addClass = 'edit';
								$statusName = 'Completed';
							} else if($row['status'] == 8) {
								$addClass = 'edit';
								$statusName = 'Partially Completed';
							}else if($row['status'] == 4) {
								$addClass = 'edit';
								$statusName = 'Closed';
							} else if($row['status'] == 5) {
								$addClass = 'delete';
								$statusName = 'Approval';
							}  else if($row['status'] == 6) {
								$addClass = 'delete';
								$statusName = 'Reassigned';
							}  else if($row['status'] == 7) {
								$addClass = 'delete';
								$statusName = 'Rejected';
							}  else if($row['status'] == 8) {
								$addClass = 'delete';
								$statusName = 'Partially Completed';
							} else {
								$addClass = '';
								$statusName = '';
							}?>
						<tr>
							<td><?php echo date('d/m/Y',strtotime($row['date'])); ?></td>
							<td><?php echo $leadRow['leadCode']; ?></td>
							<?php if($_SESSION['userType'] == 11) { ?>
								<td><?php echo $leadRow['companyName']; ?></td>
							<?php } ?>
							<?php if($_SESSION['userType'] == 12) { ?>
								<td>
								<?php
									$userData = mysql_query("select * from admin_login where userId = '".$row['processed_by']."'");
									$userRow = mysql_fetch_array($userData);
									echo $userRow['name'];
								?>
								</td>
							<?php } ?>
							<td><span class="<?php echo $addClass; ?>"><?php echo $statusName; ?></span></td>
							<td>
								<a href="view_data.php?id=<?php echo $row['requestId']; ?>&type=<?php echo $type; ?>" class="view">View</a>
								<?php if(($_SESSION['userType'] == 13 && ($_GET['list'] == 2 || $_GET['list'] == 6 || $_GET['list'] == 8)) || ($_SESSION['userType'] == 12 && $_GET['list'] == 1) || ($_SESSION['userType'] == 11 && ($_GET['list'] == 3 || $_GET['list'] == 8))) { ?>
									<img src="../images/dot.png" alt="" class="dot"/>
									<a href="edit_form.php?id=<?php echo $row['requestId']; ?>&type=<?php echo $type; ?>" class="edit">Edit</a>
								<?php } ?>
								<?php if(($_GET['list'] == 1 && $_SESSION['userType'] == 11) || $_SESSION['userType'] == 1) { ?>
									<!--img src="../images/dot.png" alt="" class="dot"/>
									<a href="javascript:void(0);" onclick="return delete_data('<?php echo $row['requestId']; ?>');" class="delete">Delete</a-->
								<?php } ?>
								<?php if(($_GET['list'] == 5 && $_SESSION['userType'] == 12)) { ?>
									<img src="../images/dot.png" alt="" class="dot"/>
									<a href="approve_data.php?id=<?php echo $row['requestId']; ?>&type=<?php echo $type; ?>" class="delete">Approve</a>
								<?php } ?>
								<?php if(($_GET['list'] == 4 && $_SESSION['userType'] == 11)) { ?>
									<img src="../images/dot.png" alt="" class="dot"/>
									<a href="rework_data.php?id=<?php echo $row['requestId']; ?>&type=<?php echo $type; ?>" class="delete">Rework</a>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</table>
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
	if(isset($_GET['list'])) { $returnString .= 'list='.$_GET['list'].'&';}
	if(isset($_GET['listType'])) { $returnString .= 'listType='.$_GET['listType'].'&';}
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