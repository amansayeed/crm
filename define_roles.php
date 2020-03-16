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
<link href="css/checkbox.css" rel="stylesheet" type="text/css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
function showFields(id,type) {
	$('.roles_table').hide();
	if(type == 'sales') {
		$('#sales_roles').show();
	} else if(type == 'dm') {
		$('#dm_roles').show();
	} else if(type == 'dtl') {
		$('#dtl_roles').show();
	}
	$(".user_link").removeClass("user_act");
	//$(".user_link").addClass("edit");
	//$("#"+id).removeClass("edit");
	$("#"+id).addClass("user_act");
}
function update_role(type,id,coloumId) {
	if($('#'+id).prop("checked") == true){
		var checkValue = '1';
	}
	else if($('#'+id).prop("checked") == false){
		var checkValue = '0';
	}
	$.post('functions/ajax_request.php', {updateColoums:'Yes', coloumName:type, coloumValue:checkValue, coloumId:coloumId},	function(data){
		if(data == 'success') {
			
		} 
	});
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
					<div class="cnt_title dfn_role">
						<h2>Define User Roles</h2>
					</div>
					<div class="roles_tab clear">
						<a href="javascript:void(0);" id="role_1" onclick="showFields(this.id,'sales');" class="user_link l_radius"> Sales</a>
						<a href="javascript:void(0);" id="role_2" onclick="showFields(this.id,'dm');" class="user_link"> Data Manager</a>
						<a href="javascript:void(0);" id="role_3" onclick="showFields(this.id,'dtl');" class="user_link r_radius"> Data Team Lead</a>
					</div>
					<div class="clear"></div>
					<table class="data_table roles_table" id="sales_roles">
						<tr class="top_border">
							<th>Role</th>
							<th>Input field name</th>
							<th width="180px;">Actions</th>
						</tr>
						<?php
						$i = 0;
						$result1 = mysql_query("select * from coloum_access");
						while($row1 = mysql_fetch_array($result1)) { ?>
						<tr>
							<?php if($i == 0) { ?>
							<td valign="top" rowspan="<?php echo mysql_num_rows($result1); ?>">Sales</td>
							<?php } ?>
							<td><?php echo $row1['excel_column']; ?></td>
							<td>
								<div class="squaredOne float_l">
									<input type="checkbox" value="1" id="<?php echo 'sales_1_'.$i; ?>" onclick="update_role('sales_view',this.id,'<?php echo $row1['sno']?>');" name="check" <?php if($row1['sales_view'] == '1') { echo 'checked'; } ?> />
									<label for="<?php echo 'sales_1_'.$i; ?>"></label>
								</div>
								<span class="role_chkBx">View</span>
								<div class="squaredOne float_l">
									<input type="checkbox" value="1" id="<?php echo 'sales_2_'.$i; ?>" onclick="update_role('sales_edit',this.id,'<?php echo $row1['sno']?>');" name="check" <?php if($row1['sales_edit'] == '1') { echo 'checked'; } ?> />
									<label for="<?php echo 'sales_2_'.$i; ?>"></label>
								</div>
								<span class="role_chkBx">Edit</span>
							</td>
						</tr>
						<?php $i = $i+1; } ?>
					</table>
					<table class="data_table roles_table disp_none" id="dm_roles">
						<tr class="top_border">
							<th>Role</th>
							<th>Input field name</th>
							<th width="180px;">Actions</th>
						</tr>
						<?php
						$i = 0;
						$result1 = mysql_query("select * from coloum_access");
						while($row1 = mysql_fetch_array($result1)) { ?>
						<tr>
							<?php if($i == 0) { ?>
							<td valign="top" rowspan="<?php echo mysql_num_rows($result1); ?>">Data Manager</td>
							<?php } ?>
							<td><?php echo $row1['excel_column']; ?></td>
							<td>
								<div class="squaredOne float_l">
									<input type="checkbox" value="1" id="<?php echo 'dm_1_'.$i; ?>" onclick="update_role('dm_view',this.id,'<?php echo $row1['sno']?>');" name="check" <?php if($row1['dm_view'] == '1') { echo 'checked'; } ?> />
									<label for="<?php echo 'dm_1_'.$i; ?>"></label>
								</div>
								<span class="role_chkBx">View</span>
								<div class="squaredOne float_l">
									<input type="checkbox" value="1" id="<?php echo 'dm_2_'.$i; ?>" onclick="update_role('dm_edit',this.id,'<?php echo $row1['sno']?>');" name="check" <?php if($row1['dm_edit'] == '1') { echo 'checked'; } ?> />
									<label for="<?php echo 'dm_2_'.$i; ?>"></label>
								</div>
								<span class="role_chkBx">Edit</span>
							</td>
						</tr>
						<?php $i = $i+1; } ?>
					</table>
					<table class="data_table roles_table disp_none" id="dtl_roles">
						<tr class="top_border">
							<th>Role</th>
							<th>Input field name</th>
							<th width="180px;">Actions</th>
						</tr>
						<?php
						$i = 0;
						$result1 = mysql_query("select * from coloum_access");
						while($row1 = mysql_fetch_array($result1)) { ?>
						<tr>
							<?php if($i == 0) { ?>
							<td valign="top" rowspan="<?php echo mysql_num_rows($result1); ?>">Data Team Lead</td>
							<?php } ?>
							<td><?php echo $row1['excel_column']; ?></td>
							<td>
								<div class="squaredOne float_l">
									<input type="checkbox" value="1" id="<?php echo 'dtl_1_'.$i; ?>" onclick="update_role('dtl_view',this.id,'<?php echo $row1['sno']?>');" name="check" <?php if($row1['dtl_view'] == '1') { echo 'checked'; } ?> />
									<label for="<?php echo 'dtl_1_'.$i; ?>"></label>
								</div>
								<span class="role_chkBx">View</span>
								<div class="squaredOne float_l">
									<input type="checkbox" value="1" id="<?php echo 'dtl_2_'.$i; ?>" onclick="update_role('dtl_edit',this.id,'<?php echo $row1['sno']?>');" name="check" <?php if($row1['dtl_edit'] == '1') { echo 'checked'; } ?> />
									<label for="<?php echo 'dtl_2_'.$i; ?>"></label>
								</div>
								<span class="role_chkBx">Edit</span>
							</td>
						</tr>
						<?php $i = $i+1; } ?>
					</table>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>