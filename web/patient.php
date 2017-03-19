<html>
<title>PRMS - Patient Record</title>

<p align="center"> <a href="main.php">Back to Home Page</a></p>
<body style="margin-left:100px;margin-right:100px">

<script>
function clicked(e)
{
    if(!confirm('Are you sure?'))e.preventDefault();
}
</script>

<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$search = test_input($_POST["search"]);
}
else {
	$pid = $_GET['pid'];
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

function print_add_patient_form() {
	echo '<title>PRMS Add User</title>
		<div style="text-align: center;">
		<form action="save-patient.php" method="post" style="margin:100px;">
		<font size="5" color="blue"> Add Patient\'s Details</font>
		<font size="1px"><br><br></font>
		<input type="text" name="name" placeholder="Full name" style="text-align:center;width:36%;"/>
		<font size="1px"><br><br></font>
		<input type="radio" name="gender" value="female"/>Female
		<input type="radio" name="gender" value="male"/>Male
		<font size="1px"><br><br></font>
		<input type="text" name="phone" placeholder="Phone number" style="text-align:center;width:36%;"/>
		<font size="1px"><br><br></font>
		<input type="text" name="email" placeholder="E-mail" style="text-align:center;width:36%;"/>
		<font size="1px"><br><br></font>
		<input type="submit" name="save" value="Save"/>
		</div>
		</form>';
}

function print_edit_patient_form($pid, $name, $email, $phone, $gender) {
	$patname = htmlentities($name);
	$patemail = htmlentities($email);
	$patphone = htmlentities($phone);
	$patpid = htmlentities($pid);
	if ($gender == "male") {
		$maleradio = ' checked="checked" ';
		$femaleradio = "";
	}
	else {
		$femaleradio = ' checked="checked" ';
		$maleradio = "";
	}
	echo '<title>PRMS Add User</title>
		<div style="text-align: center;">
		<form action="save-patient.php?pid=' . htmlspecialchars($patpid) . '" method="post" style="margin:100px;">
		<font size="5" color="blue"> Edit Patient\'s Details</font>
		<font size="1px"><br><br></font>
		<input type="text" name="name" placeholder="Full name" style="text-align:center;width:36%;" value="' . htmlspecialchars($patname) . '" />
		<font size="1px"><br><br></font>
		<input type="radio" name="gender" value="female" '.$femaleradio.'/>Female
		<input type="radio" name="gender" value="male" '.$maleradio.'/>Male
		<font size="1px"><br><br></font>
		<input type="text" name="phone" placeholder="Phone number" style="text-align:center;width:36%;" value="' . htmlspecialchars($patphone) . '" />
		<font size="1px"><br><br></font>
		<input type="text" name="email" placeholder="E-mail" style="text-align:center;width:36%;" value="' . htmlspecialchars($patemail) . '" />
		<font size="1px"><br><br></font>
		<input type="submit" name="update" value="Update"/>
		<input type="submit" name="delete" value="Delete" onclick="clicked(event)" />
		</div>
		</form>';
}

function collect_patient_details_to_edit($user, $pass, $db, $pid) {
	// connect to mysql
	$link = mysqli_connect(localhost, $user, $pass, $db);

	// get patient details
	$query="SELECT * FROM $db.patientlist WHERE pid='$pid' LIMIT 1";
	$result = mysqli_query($link, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		if ($row) {
			$name = $row["name"];
			$email = $row["email"];
			$phone = $row["phone"];
			$gender = $row["gender"];
		}
		else {
			echo "Fetch Assoc error";
		}
	}
	else {
		echo "Query: $query failed!!";
	}
	mysqli_free_result($result);		
	mysqli_close($link);

	print_edit_patient_form($pid, $name, $email, $phone, $gender);
}

if (isset($_POST['search_btn'])) {
	echo "Search ... $search";
	session_start();
	$_SESSION['search'] = $search;
	header("Location: main.php");
} else if ((isset($_POST['add_btn'])) || (isset($pid))) {
	// Add / Edit patient ...
	session_start();
	$user=$_SESSION['username'];
	$pass=$_SESSION['password'];
	$db=$_SESSION['database'];

	if (isset($pid)) {
		collect_patient_details_to_edit($user, $pass, $db, $pid);
	}
	else {
		print_add_patient_form();
	}

} else {
	echo "Patient handling: Invalid action!";
}

?>

</body>
</html>
