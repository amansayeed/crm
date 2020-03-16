<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 9) {
	//header('Location: dashboard.php'); exit;
}
include_once("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Sphere</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
function delete_card(id) {
	var r = confirm("Are you sure want to Delete?");
    if (r == true) {
        $.post('functions/ajax_request.php', {delete_card:'Yes', id:id},	function(data){
			if(data == 'success') {
				alert("Deleted Successfully");
				window.location = "manage_datacard.php";
			} 
		});
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
					<div class="cnt_title mng_user">
						<h2>Manage Data Cards</h2>
					</div>
					<table class="data_table">
						<tr>
							<th>S.no</th>
							<th><a href="<?php sortUrl('docName'); ?>">Document Name</a></th>
							<th></th>
							<?php if($_SESSION['userType'] == 11) { ?>
								<th width="150px;">Actions</th>
							<?php } ?>
						</tr>
						<?php
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$sortOption = ' ORDER BY '.$_GET['sort'].' '.$_GET['sortType'];
						} else {
							$sortOption = ' ORDER BY createdDate DESC';
						}
						$result = mysql_query("select * from data_cards where status='1'".$sortOption);
						$i = 1;
						while($row = mysql_fetch_array($result)) { ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $row['docName']; ?></td>
							<td><a href="uploads/datacard/<?php echo $row['docFile']; ?>"><span>DOWNLOAD</span></a></td>
							<?php if($_SESSION['userType'] == 11) { ?>
								<td>
									<a href="edit_datacard.php?id=<?php echo $row['dataCardId']; ?>" class="edit">Edit</a>
									<img src="images/dot.png" alt="" class="dot"/>
									<a href="javascript:void(0);" onclick="return delete_card(<?php echo $row['dataCardId']; ?>);" class="delete">Delete</a>
								</td>
							<?php } ?>
						</tr>
						<?php } ?>
					</table>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
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