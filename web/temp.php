<?php
echo ":-)";

$username="default";
$password="default";
$search="default";

if (isset($_POST['login'])) {
	$username=test_input($_POST['login']);
}

if (isset($_POST['password'])) {
	$password=$_POST['password'];
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$database="patientdb";

session_start();
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;
$_SESSION['database'] = $database;
$_SESSION['search'] = $search;

header("Location: main.php");

?>
