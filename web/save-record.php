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
	$button = "save";
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

if ($pid > 0)  {
	// edit current patient
	$edit = 1; 
	if ($button == "update") {
		$query = "UPDATE patientlist SET name='$name', phone='$phone', email='$email', gender='$gender' WHERE pid='$pid' LIMIT 1";
	}
	elseif ($button == "delete") {
		$tbl = "pid_"."$pid"."_tbl";	
		$query = "DROP TABLE IF EXISTS '$tbl'";
		mysqli_query($link, $query);
		$query = "DELETE FROM patientlist WHERE pid='$pid' LIMIT 1";
	}
}
else {
	// add new patient
	$edit = 0;
	$query="INSERT INTO patientlist (name, phone, email, gender) VALUES ('$name', '$phone', '$email', '$gender')";
}

// execute query
$result = mysqli_query($link, $query);

// if query is successful and if the previous query is not an edit!!
if (($result == TRUE) && (edit != 1)) {
	echo "Edited record successfully";

	mysqli_close($link);
	header("Location: main.php");
}
else if (($result == TRUE) && (edit != 1)) {
	echo "New record created successfully";

	// get patient_id
	$patient_id = "";
	$pid_fetch = 0;
	$query="SELECT pid FROM $database.patientlist WHERE name='$name' AND phone='$phone' LIMIT 1";
	$result = mysqli_query($link, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		if ($row) {
			$patient_id = $row["pid"];
			$pid_fetch = 1;
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
	if ($pid_fetch == 1) {
		$tbl = "pid_"."$patient_id"."_tbl";
		$query="CREATE TABLE $database.$tbl (
			vid INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
			date VARCHAR(30) NOT NULL,
			complaint VARCHAR(2048),
			doctor VARCHAR(50),
			prescription VARCHAR(2048)
		)";
		if (mysqli_query($link, $query)) {
			echo "Table $tbl created successfully";
			// close link before exit
			mysqli_close($link);
			// exit this page
			header("Location: main.php");
		} else {
			echo "Error creating table: " . mysqli_error($link);
		}
	}
} else {
	echo "Error: " . $query . "<br>" . mysqli_error($link);
}

mysqli_close($link);


?>

</body>
</html>
