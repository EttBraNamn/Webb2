<?php

include 'globalVal.php';

session_start();

if (!isset($_POST['name']) || !isset($_POST['password']))
{
	header("location: login.php?error=empty");
	exit();
}

$query = "SELECT * FROM users WHERE name=:name"

$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($q)))
{
	header("location: login.php?error=error");
	exit();
}

$stmt->bindParam(":name", $_POST['name']);

if (!($stmt->execute()))
{
	header("location: login.php?error=error");
	exit();
}

$return = $stmt->fetch();

if (empty($return))
{
	header("location: login.php?error=wrong");
	exit();
}

//Checks if the password is valid
if(!password_verify($_POST['password'], $return['password']))
{
	header("location: login.php?error=wrong");
	exit();
}


$_SESSION['name'] = $_POST['name'];
$_SESSION['password'] = $return['password'];

//Get to last site visited or index.php
if (isset($_SESSION['last']))
{
	header("location: ". $_SESSION['last']);
	exit();
}

header("location: index.php");
exit();
?>