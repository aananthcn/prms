<html>
<title>PRMS History</title>

<body>

<div id="history">
<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */

function print_pat_history() {
	session_start();
	$username=$_SESSION['username'];
	$password=$_SESSION['password'];
	$database=$_SESSION['database'];

	$link = mysqli_connect(localhost,$username,$password, $database);

	$patient_id = isset($_GET['content']) ? $_GET['content'] : '';
	$tbl = "pid_"."$patient_id"."_tbl";
	$_SESSION['pid_table']=$tbl;

	// get patient details
	$query="SELECT pid FROM $database.patientlist WHERE pid='$patient_id' LIMIT 1";
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
	$query="select * from $database.$tbl";
	$pat_history=mysqli_query($link, $query);

	$rows=mysqli_num_rows($pat_history);
	$cols=mysqli_num_fields($pat_history);

	/* print table title */
	echo "<br><font size='4px' color=#ef4f00>";
	echo "<b>Patient name: $patient_name</b><br>";
	echo "<b>Gender: $patient_gender</b><br>";
	echo "</font>";
	echo "<br>No of previous visits: $rows<br><br>";

	/* print patients - HEADER */
	echo "<table border=1 cellpadding=3><tr>";
	$i=0;while ($i < $cols) {
		$meta = mysqli_fetch_field($pat_history);
		echo "<th bgcolor=#efefef height=40>$meta->name</th>";
		$i++;
	}
	echo "</tr>";

	/* print patients - DATA */
	$i=0;while ($i < $rows) {
		$row=mysqli_fetch_row($pat_history);
		echo "<tr>";
		if($i & 1)
			$bgc = "#ffffff";
		else
			$bgc = "#dfffff";

		$j=0;while ($j < $cols) {
			echo "<td bgcolor=$bgc ><font face=tahoma size=3>$row[$j]</font></td>";
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
