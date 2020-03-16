<?php

$con = mysql_connect("localhost","prodata_WSTech","WorkSphere@14");
if (!$con)
{
die('Could not connect: ' . mysql_error());
}
mysql_select_db("prodata_crm", $con);

?>