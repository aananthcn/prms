<html>
<title>PRMS - Patient Record</title>

<p align="center"> <a href="main.php">Back to Home Page</a></p>
<body style="margin-left:100px;margin-right:100px">

<?php
/*************************************************************************
 * PHP CODE STARTS HERE
 */
if (isset($_POST['search_btn'])) {
   echo "Search ...";
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
