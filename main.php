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

<br>
<div id="patientlist">
<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */
session_start();
$username=$_SESSION['username'];
$password=$_SESSION['password'];
$database=$_SESSION['database'];


$link = mysqli_connect('localhost',$username,$password, $database);
$query="select * from patientdb.patientlist ORDER BY pid DESC";
$patientlist=mysqli_query($link, $query);

$rows=mysqli_num_rows($patientlist);
$cols=mysqli_num_fields($patientlist);

/* print patients - HEADER */
//echo "<table border=1 cellpadding=3><tr>";
echo '<font size="5" color="blue"> Patient Records</font>';
echo '<font size="1px"><br><br></font>';
echo "<table border=1 width=100%><tr>";
$i=0;while ($i < $cols) {
$meta = mysqli_fetch_field($patientlist);
echo "<th bgcolor=#bfbfef height=40>$meta->name</th>";
$i++;
}
echo "</tr>";

$i=0;while ($i < $rows) {
$row=mysqli_fetch_row($patientlist);
echo "<tr>";
if($i & 1)
	$bgc = "#ffffff";
else
	$bgc = "#eeeeef";

$j=0;while ($j < $cols) {
if($j == 0) {
	echo "<td bgcolor=$bgc>" . "<font face=tahoma size=3>" . '<a href="view-history.php?content='. $row[$j] . '">' . $row[$j] . '</a>' . "</font>" . "</td>";
}
else
	echo "<td bgcolor=$bgc ><font face=tahoma size=3>$row[$j]</font></td>";

$j++;
	}
echo "</tr>";
$i++;
}
echo "</table>";

mysqli_free_result($patientlist);
mysqli_close($link);


?>
</div>

</body>
</html>