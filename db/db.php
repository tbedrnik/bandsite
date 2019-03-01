<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
	die("Nelze se připojit k databázi.");
}

mysqli_query($db, "SET NAMES 'utf8'");

function query($sql) {
	global $db;
	return mysqli_query($db, $sql);
}

function mres($string) {
	global $db;
	return mysqli_real_escape_string($db,$string);
}

function getValue($name) {
	$sql = "SELECT value FROM settings WHERE name='$name'";
	$result = query($sql);
	if(mysqli_num_rows($result)==1) {
		return mysqli_fetch_assoc($result)["value"];
	}
	else {
		global $db;
		return mysqli_error($db);
	}
}
