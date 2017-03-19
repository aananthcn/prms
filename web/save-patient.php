<html>
<title>PRMS</title>

<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */
$name = $email = $gender = $phone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = test_input($_POST["name"]);
  $email = test_input($_POST["email"]);
  $phone = test_input($_POST["phone"]);
  $gender = test_input($_POST["gender"]);
}

// get arguments
$pid = $_GET['pid'];

// check if update or delete button was pressed
if (isset($_POST['update'])) {
	# update-button was clicked
	$button = "update";
}
elseif (isset($_POST['delete'])) {
	# delete-button was clicked
	$button = "delete";
}
elseif (isset($_POST['save'])) {
	# New record: save-button was clicked
	$button = "new";
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = str_replace("\\", "/", $data);
  $data = str_replace("'", "\'", $data);
  $data = str_replace('"', '\"', $data);
  $data = str_replace("%", "\%", $data);
  $data = str_replace("_", "\_", $data);
  return $data;
}

function create_patient_table($link, $pid) {
	// create query for new history table
	$tbl = "pid_"."$pid"."_tbl";
	$query="CREATE TABLE $tbl (
			vid INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
			date VARCHAR(30) NOT NULL,
			complaint VARCHAR(2048),
			doctor VARCHAR(50),
			prescription VARCHAR(2048)
		)";

	// execute the query
	if (mysqli_query($link, $query)) {
		echo "Table $tbl created successfully";
		$retval = TRUE;
	}
	else {
		echo "Error creating table: " . mysqli_error($link);
		$retval = FALSE;
	}

	return $retval;
}


//main() -----------------------------
session_start();
$username=$_SESSION['username'];
$password=$_SESSION['password'];
$database=$_SESSION['database'];


// connect to mysql
$link = mysqli_connect('localhost',$username,$password,$database);
// check connection
if (!$link) {
	die("Connection failed: " . mysqli_connect_error());
}

// create query: Add / Modify / Delete
if ($button == "update") {
	$query = "UPDATE patientlist SET name='$name', phone='$phone', email='$email', gender='$gender' WHERE pid='$pid' LIMIT 1";
}
elseif ($button == "delete") {
	// first delete the table associated with the patient id to be deleted
	$tbl = "pid_"."$pid"."_tbl";	
	$query = "DROP TABLE IF EXISTS '$tbl'";
	mysqli_query($link, $query);

	// now delete the entry from patient list
	$query = "DELETE FROM patientlist WHERE pid='$pid' LIMIT 1";
}
else if ($button == "new"){
	$query="INSERT INTO patientlist (name, phone, email, gender) VALUES ('$name', '$phone', '$email', '$gender')";
}

// execute query: Add / Modify / Delete
$result = mysqli_query($link, $query);

// if query fails or the change type is update / delete return to main
if (($result != TRUE) || ($button == "update") || ($button == "delete")) {

	mysqli_close($link);
	header("Location: main.php");
}
echo "Edited record successfully";

// if new, create 'pid table' to capture patient consultation records
$patient_id = "";
$query="SELECT pid FROM patientlist WHERE name='$name' AND phone='$phone' LIMIT 1";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	if ($row) {
		$patient_id = $row["pid"];
	}
	else {
		echo "Fetch Assoc error";
	}
}
else {
	echo "Query: $query failed!!";
}
mysqli_free_result($result);		

// create new history table
if (isset($patient_id)) {
	if (create_patient_table($link, $patient_id) == TRUE) {
		// close link before exit
		mysqli_close($link);

		// exit this page
		header("Location: main.php");
	}
}
else {
	echo "Error creating table: " . mysqli_error($link);
}

mysqli_close($link);


?>

</body>
</html>
