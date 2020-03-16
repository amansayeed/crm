<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != '1' && $_SESSION['userType'] != '10') {
	header('Location: index.php'); exit;
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
						<h2>Payments</h2>
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
					<table id="lead_records" class="data_table">
						<tr>
							<th>S.No</th>
							<th><a href="<?php sortUrl('leadCode'); ?>">Lead Code</a></th>
							<th><a href="<?php sortUrl('date'); ?>">Date</a></th>
							<th><a href="<?php sortUrl('companyName'); ?>">Company Name</a></th>
							<th>Status</th>
							<th>Payment Status</th>
							<th>Deal Amount</th>
							<th width="170px;">Action</th>
						</tr>
						<?php
						$whereCase = " AND lead_generate.status = '5'";
						
						if(isset($_SESSION['fromDate']) && isset($_SESSION['toDate']) && $_SESSION['fromDate'] != '' &&$_SESSION['fromDate'] != '') {
							$dateBtw = " AND lead_generate.createdDate BETWEEN '".$_SESSION['fromDate']."' AND '".$_SESSION['toDate']."'";
						} else {
							$dateBtw = '';
						}
						if(isset($_SESSION['searchCode']) && $_SESSION['searchCode'] != '') {
							$searchCode = " AND lead_generate.Leadcode LIKE '%".$_SESSION['searchCode']."%'";
						} else {
							$searchCode = '';
						}
						if(isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] != '') {
							$searchKeyword = " AND (lead_generate.Leadcode LIKE '%".$_SESSION['searchKeyword']."%' OR lead_generate.companyName LIKE '%".$_SESSION['searchKeyword']."%' OR lead_generate.userId IN (select userId from admin_login where status = 1 AND name LIKE '%".$_SESSION['searchKeyword']."%'))";
						} else {
							$searchKeyword = '';
						}
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$sortOption = ' ORDER BY lead_generate.'.$_GET['sort'].' '.$_GET['sortType'];
						} else {
							$sortOption = ' ORDER BY lead_generate.createddate DESC';
						}
						
						$page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
						if ($page <= 0) $page = 1;
						
						$per_page = 25; // Set how many records do you want to display per page.
						$startpoint = ($page * $per_page) - $per_page;
						$statement = "lead_generate where lead_generate.leadId != ''".$whereCase.$dateBtw.$searchCode.$searchKeyword.$sortOption;

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
							}
							$lastPaidQuery = mysql_query("select * from payment_details where leadId = '".$row['leadId']."' ORDER BY createdDate DESC LIMIT 0,1"); 
							$lastPaid = mysql_fetch_array($lastPaidQuery);
							?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $row['leadCode']; ?></td>
							<td><?php echo date('d/m/Y',strtotime($row['date'])); ?></td>
							<td><?php echo $row['companyName']; ?></td>
							<td><span class="<?php echo $addClass; ?>"><?php echo $statusName; ?></span></td>
							<td><?php echo $lastPaid['paymentType']; ?></td>
							<td><?php echo $row['dealAmount']; ?></td>
							<td>
								<a href="payment_view.php?id=<?php echo $row['leadId']; ?>" class="view">View</a>
								<?php if($lastPaid['paymentType'] == '') { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="payment_add.php?id=<?php echo $row['leadId']; ?>" class="edit">Add Payment</a>
								<?php } else { ?>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="payment_add.php?id=<?php echo $row['leadId']; ?>" class="delete">Edit</a>
								<?php } ?>
							</td>
						</tr>
						<?php 
						$i = $i + 1;
						} ?>
					</table>
					<div style="float:right; margin:10px;"><?php echo pagination($statement,$per_page,$page); ?></div>
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