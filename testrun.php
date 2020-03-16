<?php
include_once("dbconnect.php");
$result1 = mysql_query("select * from sales_request");
$i = 1;
while($row1 = mysql_fetch_array($result1)) {
	$code = 'SM'.str_pad($i, 4, "0", STR_PAD_LEFT);
	mysql_query("update sales_request set code = '".$code."' where requestId='".$row1['requestId']."'");
	$i = $i + 1;
}
echo 'Completed';
?>