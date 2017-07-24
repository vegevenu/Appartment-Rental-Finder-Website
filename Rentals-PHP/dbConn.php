<?php
$server="localhost";
$db_user="student";
$db_pass="pw";
$db_name="rentals";

// Connect to server and select databse.
$conn = mysqli_connect($server,$db_user, $db_pass)or die("cannot connect");
mysqli_select_db($conn, $db_name)or die("cannot select DB");

?>