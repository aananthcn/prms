<html>
<title>PRMS History</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body style="margin-left:1px;margin-right:1px">
<p align="center"> <a href="main.php">Home Page</a></p>
<form action="save-treatment.php" method="post">
<h2 style="color:#0f4fe0;"> New consultation </h2>
<table style="text-align:center;width:100%;">
  <tr>
      <td><textarea name="date" cols="18" rows="1" style="width:100%;"><?php echo date("Y-m-d");?></textarea></td>
      <td><textarea name="doctor" cols="24" rows="1" style="width:100%;">Dr. <?php session_start(); echo $_SESSION['username'];?></textarea></td>
  </tr>
  <tr> </tr>
  <tr>
      <td><textarea name="complaint" cols="18" rows="4" style="width:100%;" placeholder="Patient's complaints..."></textarea></td>
      <td><textarea name="prescription" cols="24" rows="4" style="width:100%;" placeholder="Doctor's prescription..."></textarea></td>
  </tr>
  <tr> </tr>
</table>
<div align="center">
<input type="submit" value="Save treatment" name="hist_btn" style="width:50%;"/>
</div>
<h3 style="text-decoration:underline;color:#70a0b0"> History </h3>

</form>

<div id="history">
<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */
//      <td style="vertical-align:middle;"><input type="submit" value="Save treatment" name="hist_btn" style="width:100%;"/></td>

function print_pat_history() {
	session_start();
	$username=$_SESSION['username'];
	$password=$_SESSION['password'];
	$database=$_SESSION['database'];

	$link = mysqli_connect(localhost,$username,$password,$database);

	// arg 'pid' will be non-null only if user clicks the pid from main.php
	$patient_id = isset($_GET['pid']) ? $_GET['pid'] : '';
	if ($patient_id == "") {
		$patient_id=$_SESSION['pid'];
	}
	$tbl = "pid_"."$patient_id"."_tbl";
	$_SESSION['pid']=$patient_id;
	$_SESSION['pidtable']=$tbl;

	// get patient details
	$query="SELECT pid,name,gender FROM $database.patientlist WHERE pid='$patient_id' LIMIT 1";
	$result = mysqli_query($link, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		if ($row) {
			$patient_name = $row["name"];
			$patient_gender = $row["gender"];
		}
		else {
			echo "Fetch Assoc error";
		}
	}
	else {
		echo "Query: $query failed!!";
	}
	mysqli_free_result($result);		

	// get patient history
	$query="select * from $database.$tbl ORDER BY vid DESC";
	$pat_history=mysqli_query($link, $query);

	$rows=mysqli_num_rows($pat_history);
	$cols=mysqli_num_fields($pat_history);

	/* print table title */
	echo "<font size='3px' color=#df3f00>";
	echo "<b>Patient: </b>";
	echo "<font size='4px' color=#0f4fe0>";
	echo "<b> $patient_name</b>";
	echo "<font size='3px' color=#df3f00>";
	echo "<b> | Gender: </b>";
	echo "<font size='4px' color=#0f4fe0>";
	echo "<b> $patient_gender</b>";
	echo "<font size='3px' color=#df3f00>";
	echo "<b> | Previous treatments: </b>";
	echo "<font size='4px' color=#0f4fe0>";
	echo "<b> $rows</b><br><br>";
	echo "</font>";

	/* print patients - HEADER */
	echo "<table border=1 cellpadding=3 style='text-align:center;width:100%;'><tr>";
	$i=0;while ($i <= $cols) {
		$meta = mysqli_fetch_field($pat_history);
		if ($i == $cols) {
			echo "<th bgcolor=#efefef height=40>edit</th>";
		}
		else {
			echo "<th bgcolor=#efefef height=40>$meta->name</th>";
		}
		$i++;
	}
	echo "</tr>";

	/* print patients - DATA */
	$i=0;while ($i < $rows) {
		// fetch a row
		$row=mysqli_fetch_row($pat_history);
		$vid=$row[0];
		echo "<tr>";
		if($i & 1)
			$bgc = "#ffffff";
		else
			$bgc = "#dfffff";

		// loop through column
		$j=0;while ($j <= $cols) {
			// left align cells from 3rd column
			if ($j == $cols){
				echo "<td bgcolor=$bgc style='text-align:center;'><a href='edit-history.php?vid=$vid&pid=$patient_id'>ஃ</a></td>";
			}
			else if ($j >= 2) {
				echo "<td bgcolor=$bgc style='padding-left:5px;' align='left'><font face=tahoma size=3>$row[$j]</font></td>";
			}
			else {
				echo "<td bgcolor=$bgc ><font face=tahoma size=3>$row[$j]</font></td>";
			}
			$j++;
		}
		echo "</tr>";
		$i++;
	}
	echo "</table>";

	mysqli_free_result($pat_history);
	mysqli_close($link);
}


print_pat_history(); 

?>
</div>

</body>
</html>
