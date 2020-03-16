<?php
session_start();
ob_start();
ini_set('memory_limit', '2000M'); //for 2GB
//For no limits
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != '11') {
	header('Location: index.php'); exit;
}
include_once("dbconnect.php");
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
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title sales_user">
						<h2>Data Card</h2>
					</div>
					<div class="clear"></div>
					<form action="" method="post" enctype="multipart/form-data">
					<table class="form_table">
						<tr>
							<td>Document Name <span class="astrick">*</span></td>
							<td>
								<input type="text" required value="" class="txtBx" name="documentName"/>
							</td>
						</tr>
						<tr>
							<td>Attach Document</td>
							<td>
								<div class="subBtn file_btn" style="">Upload
									<input type="file" class="choosefile" required onchange="$('#file_name').html(this.value);" name="supportDoc" value=""/>
								</div>
								<span class="file_name" id="file_name">No files selected</span>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="add_dataCard" value="Submit"/></td>
						</tr>
					</table>
					</form>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>
<?php
if(isset($_POST['add_dataCard'])) {
	$uploadFileName = '';
	$flag = 0;
	if(!empty($_FILES['supportDoc']["tmp_name"])) {
		$uploadDirectory = "uploads/datacard/";
		$allowedExts = array("xls", "xlsx", "csv");
		$temp = explode(".", $_FILES['supportDoc']["name"]);
		$RandNumber  = rand(0, 9999999999);
		$extension = end($temp);
		//if (in_array($extension, $allowedExts)) {
			if ($_FILES['supportDoc']["error"] > 0)
			{
				$flag = 1;
				echo "<script>alert('".$_FILES['supportDoc']["error"]."'); window.location='lead_entry.php'; </script>";
				exit;
			} else {
				$uploadFileName = $RandNumber."_".date('dmY')."_".$_FILES['supportDoc']["name"];
				if(move_uploaded_file($_FILES['supportDoc']["tmp_name"],$uploadDirectory.$uploadFileName)) {
					$flag = 0;
				} else {
					$flag = 1;
					echo "<script>alert('Error: File upload failed'); window.location='add_datacard.php'; </script>";
					exit;
				}
			}
		/*} else {
			echo "<script>alert('Invalid File, Upload xls / xlsx / csv'); window.location='add_datacard.php'; </script>";
			exit;
		}*/
	}
	if($flag == 0) {
		mysql_query("Insert into data_cards (docName,docFile,status,createdDate,modifiedDate) values ('".$_POST['documentName']."','".$uploadFileName."','1','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
		if(mysql_affected_rows() > 0) {
			echo "<script>alert('Data card Added successfully'); window.location='manage_datacard.php'; </script>";
		} else {
			echo "<script>alert('Data card Adding Failed'); window.location='add_datacard.php'; </script>";
		}
	}
}
?>