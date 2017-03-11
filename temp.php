<?php
echo ":-)";

$username="default";
$password="default";
$search="default";

if (isset($_POST['login'])) {
	$username=$_POST['login'];
}

if (isset($_POST['password'])) {
	$password=$_POST['password'];
}

$database="patientdb";

session_start();
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;
$_SESSION['database'] = $database;
$_SESSION['search'] = $search;

header("Location: main.php");

?>
