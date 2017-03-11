<html>
<title>PRMS - Patient Record</title>

<p align="center"> <a href="main.php">Back to Home Page</a></p>
<body style="margin-left:100px;margin-right:100px">

<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search = test_input($_POST["search"]);
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

if (isset($_POST['search_btn'])) {
   echo "Search ... $search";
   session_start();
   $_SESSION['search'] = $search;
   header("Location: main.php");
} else if (isset($_POST['add_btn'])) {
   echo '<title>PRMS Add User</title>
	<div style="text-align: center;">
	<form action="save-record.php" method="post" style="margin:100px;">
	<font size="5" color="blue"> Add Patient\'s Details</font>
	<font size="1px"><br><br></font>
	<input type="text" name="name" placeholder="Full name" style="text-align:center;"/>
	<font size="1px"><br><br></font>
	<input type="radio" name="gender" value="female"/>Female
	<input type="radio" name="gender" value="male"/>Male
	<font size="1px"><br><br></font>
	<input type="text" name="phone" placeholder="Phone number" style="text-align:center;/>
	<font size="1px"><br><br></font>
	<input type="text" name="email" placeholder="E-mail" style="text-align:center;/>
	<font size="1px"><br><br></font>
	<input type="submit" value="Save"/>
	</div>
	</form>';
} else {
    //invalid action!
}

?>

</body>
</html>
