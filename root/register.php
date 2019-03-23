<?php

//INCLUDES
include 'security.php';
include 'globalVal.php';

function PasswordSetup(&$password)
{
	//If the password is too short
	if (strlen($password) < 5)
	{
		$password = "passwordShort";
		return false;
	}
	$password = password_hash($password, PASSWORD_BCRYPT);
	return true;
}

function FileHandling(&$name)
{
//Makes sure that a file exists
	if (isset($_FILES['file']))
	{
		if (!ImageCheckFiletype($_FILES['file']['tmp_name']))
		{
			$name = "badImageFile";
			return false;
		}
		$tempFile = file_get_contents($_FILES['file']['tmp_name']);
		$filePath = "pic/" . $name . ".jpg";
		$writeToo = fopen(__DIR__ . "/" . $filePath, "w");
		fwrite($writeToo, $tempFile);
		fclose($writeToo);
		return true;
	}
	$name = "badImageFile";
	return false;
}

//Make sure the username isn't already taken
function ExistingUsers(&$name)
{
	
	$query = "SELECT * FROM users WHERE name=:name";

	
	if (!$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD))
	{
		$name = "PDO1";
		return false;
	}
	//If it can't be prepared
	if (!$stmt = $connect->prepare($query))
	{
		$name = "prepare1";
		return false;
	}
	
	$stmt->bindParam(':name', $name);
	$stmt->execute();
	//If it found a
	if (!empty($stmt->fetch()))
	{
		$name = "existingUser";
		return false;
	}
	return true;
}

function Error($e = "")
{
	header("location: signup.php?error=" . $e);
	exit();
}



//Checking if a POST call has been made, if not return to the signup
if (!isset($_POST['register']))	
{
	Error();
}

$name = $_POST['name'];

//Grab all the necessary variables

//Function found in security.php
if(!CheckValidName($name))
{
	Error($name);
}

if (!ExistingUsers($name))
{
	Error($name);
}

$password = $_POST['password'];

if(!PasswordSetup($password))
{
	Error($password);
}

$filePath = $name;

if(!FileHandling($filePath))
{
	Error($filePath);
}


//Uploads user to database
$query = "INSERT INTO users (name, password, bio) VALUES (:name, :password, \"None\")";


if (!$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD))
{
	Error("PDO2");
}

if(!$stmt = $connect->prepare($query))
{
	Error("prepare2");
}

$stmt->bindParam(":name", $name);
$stmt->bindParam(":password", $password);

if (!$stmt->execute())
{
	Error("Execute2");
}

session_start();


$_SESSION["name"] = $name;
$_SESSION["password"] = $password;

header("location: login.php");
exit();
?>