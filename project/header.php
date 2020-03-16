<div class="logo inner_logo"><span class="span_name" style="margin: 30px 0 0 -134px;">Worksphere</span><img src="../images/logo.png" alt="logo" /><span class="span_name" style="margin: 30px 0 0 5px;">Technologies</span></div>
<h6 class="logged_user" style="color:#fff; float:left; padding:25px;">Welcome <?php echo $_SESSION['loggedPerson']; ?></h6>
<div class="nav">
	<ul class="nagigation">
		<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 9) { ?>
		<li>
			<img src="../images/icon_setting.png" />
			<a href="../change_password.php"> Change Password</a>
		</li>
		<?php } ?>
		<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10 || $_SESSION['userType'] == 2 || $_SESSION['userType'] == 5 || $_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14 || $_SESSION['userType'] == 8) { ?>
		<li>
			<img src="../images/icon_setting.png" />
			<a href="../lead_search.php"> Search Lead</a>
		</li>
		<?php } ?>
		<li>
			<img src="../images/icon_setting.png" />
			<a href="../support.php"> Support</a>
		</li>
		<li>
			<img src="../images/icon_logout.png" /> 
			<a href="../logout.php"> Logout</a>
		</li>
	</ul>
</div>
<?php 
$whereCase = '';
if($_SESSION['userType'] == 2 || $_SESSION['userType'] == 5) {
	$whereCase = " And executive = '".$_SESSION['userId']."'";
} else if($_SESSION['userType'] == 4) {
	$whereCase = " And processed_by = '".$_SESSION['userId']."'";
} else if($_SESSION['userType'] == 8) {
	$whereCase = " AND (executive = '".$_SESSION['userId']."' OR executive IN (select userId from admin_login where superior1 IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."')))";
}

$pendQuery = mysql_query("select requestId from sales_request where status='1'".$whereCase);
$assQuery = mysql_query("select requestId from sales_request where status='2'".$whereCase);
$comQuery = mysql_query("select requestId from sales_request where status='3'".$whereCase);
$closeQuery = mysql_query("select requestId from sales_request where status='4'".$whereCase);

$whereCase1 = '';
if($_SESSION['userType'] == 6 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 14) {
	$whereCase1 = " And userId = '".$_SESSION['userId']."'";
} else if($_SESSION['userType'] == 2) {
	$whereCase1 = " And assignedTo = '".$_SESSION['userId']."'";
} else if($_SESSION['userType'] == 5) {
	$whereCase1 = " AND (userId IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."') OR assignedTo = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
} else if($_SESSION['userType'] == 8) {
	$whereCase1 = " AND (userId IN (select userId from admin_login where superior1 IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."')) OR assignedTo = '".$_SESSION['userId']."' OR userId = '".$_SESSION['userId']."')";
}
$statusCountQuery = mysql_query("SELECT (SELECT count(*) FROM lead_generate WHERE status='2'".$whereCase1.") AS initial, (SELECT count(*) FROM lead_generate WHERE status='3'".$whereCase1.") AS pricing, (SELECT count(*) FROM lead_generate WHERE status='4'".$whereCase1.") AS followup, (SELECT count(*) FROM lead_generate WHERE status='5'".$whereCase1.") AS complete, (SELECT count(*) FROM lead_generate WHERE status='0'".$whereCase1.") AS newLead, (SELECT count(*) FROM lead_generate WHERE status='1'".$whereCase1.") AS assigned, (SELECT count(*) FROM lead_generate WHERE status='6'".$whereCase1.") AS dropped, (SELECT count(*) FROM lead_generate WHERE status='7'".$whereCase1.") AS disqualified FROM lead_generate LIMIT 0,1");
$statusCount = mysql_fetch_array($statusCountQuery);

?>
<?php
$whereCase2 = '';
if($_SESSION['userType'] == 11) {
	$whereCase2 = " And createdBy = '".$_SESSION['userId']."'";
} else if($_SESSION['userType'] == 13) {
	$whereCase2 = " And processed_by = '".$_SESSION['userId']."'";
} else if($_SESSION['userType'] == 8) {
	$whereCase2 = " And createdBy IN (select userId from admin_login where superior1 = '".$_SESSION['userId']."')";
}

$pro_pendQuery = mysql_query("select requestId from project_request where status='1'".$whereCase2);
$pro_assQuery = mysql_query("select requestId from project_request where status='2'".$whereCase2);
$pro_comQuery = mysql_query("select requestId from project_request where status='3'".$whereCase2);
$pro_closeQuery = mysql_query("select requestId from project_request where status='4'".$whereCase2);
$pro_approvalQuery = mysql_query("select requestId from project_request where status='5'".$whereCase2);
$pro_reassignQuery = mysql_query("select requestId from project_request where status='6'".$whereCase2);
$pro_rejectedQuery = mysql_query("select requestId from project_request where status='7'".$whereCase2);
$pro_parComQuery = mysql_query("select requestId from project_request where status='8'".$whereCase2);
?>