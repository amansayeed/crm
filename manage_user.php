<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 9) {
	header('Location: dashboard.php'); exit;
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
function delete_user(id) {
	var r = confirm("Are you sure want to Delete?");
    if (r == true) {
        $.post('functions/ajax_request.php', {delete_user:'Yes', id:id},	function(data){
			if(data == 'success') {
				alert("Deleted Successfully");
				window.location = "manage_user.php";
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
						<h2>Manage Users</h2>
					</div>
					<table class="data_table">
						<tr>
							<th><a href="<?php sortUrl('name'); ?>">Name</a></th>
							<th><a href="<?php sortUrl('userType'); ?>">Role</a></th>
							<th><a href="<?php sortUrl('emailId'); ?>">Username</a></th>
							<th><a href="<?php sortUrl('superior1'); ?>">User Group</a></th>
							<th width="150px;">Actions</th>
						</tr>
						<?php
						if(isset($_GET['type']) && $_GET['type'] != '') {
							$filter = " AND userType = '".$_GET['type']."'";
						} else {
							$filter = '';
						}
						if(isset($_GET['sort']) && isset($_GET['sortType'])) {
							$sortOption = ' ORDER BY '.$_GET['sort'].' '.$_GET['sortType'];
						} else {
							$sortOption = ' ORDER BY createdDate DESC';
						}
						if($_SESSION['userType'] == 1) {
							$userTypeFilter = "userType NOT IN ('1')";
						} else {
							$userTypeFilter = "userType NOT IN ('1','9','10')";
						}
						$result = mysql_query("select A.*,B.type from admin_login as A JOIN user_type_master as B on A.userType = B.userTypeId where ".$userTypeFilter." AND status='1'".$filter.$sortOption);
						while($row = mysql_fetch_array($result)) { ?>
						<tr>
							<td><?php echo $row['name']; ?></td>
							<td><a href="?type=<?php echo $row['userType']; ?>"><span><?php echo $row['type']; ?></span></a></td>
							<td><?php echo $row['emailId']; ?></td>
							<td>
								<?php
									$userData = mysql_query("select * from admin_login where userId = '".$row['superior1']."'",$con);
									$userRow = mysql_fetch_array($userData);
									echo $userRow['name'];
								?>
							</td>
							<td>
								<a href="edit_user.php?id=<?php echo $row['userId']; ?>" class="edit">Edit</a>
								<img src="images/dot.png" alt="" class="dot"/>
								<a href="javascript:void(0);" onclick="return delete_user(<?php echo $row['userId']; ?>);" class="delete">Disable</a>
							</td>
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