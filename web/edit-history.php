<html>
<title>PRMS History</title>

<body style="margin-left:1px;margin-right:1px">
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
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<form action='edit-history.php?vid=$vid&pid=$pid' method='post'>
<h2 style='color:#0f4fe0;'> Edit Treatment </h2>
<table style='text-align:center;width:100%;'>
  <tr>
      <td><textarea name='date' cols='18' rows='1'>$date</textarea></td>
      <td><textarea name='doctor' cols='24' rows='1'>$doctor</textarea></td>
  </tr>
  </tr>
  <tr> </tr>
  <tr>
      <td><textarea name='complaint' cols='18' rows='10' >$complaint</textarea></td>
      <td><textarea name='prescription' cols='24' rows='10' >$prescription</textarea></td>
  </tr>
  <tr> </tr>
</table>
<input type='submit' value='Save treatment' name='hist_btn' style='width:100%;'/>

</form>
<p align='center'> <a href='main.php'>Home Page</a></p>
";

mysqli_close($link);

?>
</div>

</body>
</html>
