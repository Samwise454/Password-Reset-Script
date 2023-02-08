<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "pass_reset";

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
	//you can create how to handle your exceptions here
}

