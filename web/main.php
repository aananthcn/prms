<html>
<title>PRMS</title>


<body style="margin-left:100px;margin-right:100px">

<form action="patient.php" method="post">
<table style="text-align:center;width:100%;">
  <tr>
      <td><input type="text" name="search" placeholder="Type name or phone number..." style="width:100%;"/></td>
      <td><input type="submit" value="Search" name="search_btn" style="width:60%;"/></td>
      <td><input type="submit" value="Add user" name="add_btn" style="width:60%;"/></td>
  </tr>
</table>

</form>
<font size="1px"><br></font>
<div id="patientlist">
<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */
session_start();
$username=$_SESSION['username'];
$password=$_SESSION['password'];
$database=$_SESSION['database'];
$search =$_SESSION['search'];


$link = mysqli_connect('localhost',$username,$password, $database);

// search variable will initialized when someon presses search button
if ($search == "default") {
	$query="SELECT * FROM patientdb.patientlist ORDER BY pid DESC";
}
else {
	$query="SELECT * FROM patientdb.patientlist WHERE name LIKE '%$search%' OR phone LIKE '%$search%'";
	echo "<p align='center'> <a href='main.php'>Reset Search</a></p>";
}
$_SESSION['search'] = "default";

$patientlist=mysqli_query($link, $query);

// create patientlist if not exists
if (empty($patientlist)) {
	$cr_query="CREATE TABLE patientdb.patientlist ( pid INT NOT NULL AUTO_INCREMENT, name VARCHAR(100), phone VARCHAR(30), email VARCHAR(60), gender VARCHAR(20), PRIMARY KEY (pid) )";
	$result=mysqli_query($link, $cr_query);
	// re-run the patient list query
	$patientlist=mysqli_query($link, $query);
}	

$rows=mysqli_num_rows($patientlist);
$cols=mysqli_num_fields($patientlist);

/* print patients - HEADER */
echo '<font size="5" color="blue"> Patient Records</font>';
echo '<font size="1px"><br><br></font>';
echo "<table border=1 width=100%><tr>";
$i=0;while ($i < $cols) {
	$meta = mysqli_fetch_field($patientlist);
	echo "<th bgcolor=#bfbfef height=40>$meta->name</th>";
	$i++;
}
echo "</tr>";

/* print patients - DATA */
$i=0;while ($i < $rows) {
	// fetch a row
	$row=mysqli_fetch_row($patientlist);
	echo "<tr>";
	if($i & 1)
		$bgc = "#ffffff";
	else
		$bgc = "#eeeeef";

	// print columns
	$j=0;while ($j < $cols) {
		if ($j == 0) {
			echo "<td bgcolor=$bgc align='center'><font face=tahoma size=3>$row[$j]</font></td>";
		}
		// hyperlink column1 (patient name) but pass column0 (patient id) as argument if clicked!!
		else if ($j == 1) {
			echo "<td bgcolor=$bgc style='padding-left:3px;'>" . "<font face=tahoma size=3>" . '<a href="view-history.php?content='. $row[0] . '">' . $row[$j] . '</a>' . "</font>" . "</td>";
		}
		else
			echo "<td bgcolor=$bgc style='padding-left:3px;'><font face=tahoma size=3>$row[$j]</font></td>";

		$j++;
	}
	echo "</tr>";
	$i++;
}
echo "</table>";

mysqli_free_result($patientlist);
mysqli_close($link);


if ($rows == 0) {
	echo "<br>Patients list is empty! Probably a fresh system!";
}

?>
</div>

</body>
</html>
