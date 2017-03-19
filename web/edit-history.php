<html>
<title>PRMS History</title>

<body style="margin-left:100px;margin-right:100px">
<div id="history">
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
  $function = "save-edits";
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

$pid = $_GET["pid"];
$vid = $_GET["vid"];
$tbl = "pid_"."$pid"."_tbl";

$link = mysqli_connect(localhost,$username,$password,$database);

if ($function == "save-edits") {
	$query ="UPDATE $tbl SET date='$date', complaint='$complaint', doctor='$doctor', prescription='$prescription' WHERE vid='$vid' LIMIT 1";
	mysqli_query($link, $query);
	mysqli_close($link);
	header("Location: view-history.php");
}


// get patient details
$query="SELECT * FROM $tbl WHERE vid='$vid' LIMIT 1";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	if ($row) {
		$date = $row["date"];
		$complaint = $row["complaint"];
		$doctor = $row["doctor"];
		$prescription = $row["prescription"];
	}
	else {
		echo "Fetch Assoc error";
	}
}
else {
	echo "Query: $query failed!!";
}
mysqli_free_result($result);


echo "
<form action='edit-history.php?vid=$vid&pid=$pid' method='post'>
<h2 style='color:#0f4fe0;'> New consultation </h2>
<table style='text-align:center;width:100%;'>
  <tr>
      <td><textarea name='date' cols='20' rows='3'>$date</textarea></td>
      <td><textarea name='doctor' cols='20' rows='3'>$doctor</textarea></td>
      <td><textarea name='complaint' cols='40' rows='3' >$complaint</textarea></td>
      <td><textarea name='prescription' cols='40' rows='3' >$prescription</textarea></td>
      <td style='vertical-align:middle;'><input type='submit' value='Save treatment' name='hist_btn' style='width:100%;'/></td>
  </tr>
</table>

</form>";

mysqli_close($link);

?>
</div>

</body>
</html>
