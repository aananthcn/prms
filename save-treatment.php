<html>
<title>PRMS</title>

<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */
$date = $complaint = $doctor = $prescription = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $date = test_input($_POST["date"]);
  $complaint = test_input($_POST["complaint"]);
  $doctor = test_input($_POST["doctor"]);
  $prescription = test_input($_POST["prescription"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = str_replace("'", "", $data);
  return $data;
}

session_start();
$username=$_SESSION['username'];
$password=$_SESSION['password'];
$database=$_SESSION['database'];
$pidtable=$_SESSION['pidtable'];


// connect to mysql
$link = mysqli_connect('localhost',$username,$password,$database);
// check connection
if (!$link) {
	die("Connection failed: " . mysqli_connect_error());
}

// add new patient
$query="INSERT INTO patientdb.$pidtable (date, complaint, doctor, prescription)
VALUES ('$date', '$complaint', '$doctor', '$prescription')";
if (mysqli_query($link, $query)) {
	echo "New record created successfully";
	mysqli_close($link);
	header("Location: view-history.php");
} else {
	echo "Error: " . $query . "<br>" . mysqli_error($link);
}

mysqli_close($link);


?>

</body>
</html>
