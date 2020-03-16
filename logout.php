<?php
session_start();
if(isset($_SESSION['adminuser']))
{
	session_destroy();
	session_start();
	$_SESSION['logout']="Logged Out Successful";
	header('Location:index.php');
}else header('Location:index.php');
?>