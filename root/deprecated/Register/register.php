<?php

function PasswordSetup(&$password)
{
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
		$name = $filePath;
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

	if (!$stmt = $connect->prepare($query))
	{
		$name = "prepare1";
		return false;
	}
	
	$stmt->bindParam(':name', $name);
	$stmt->execute();

	if (!empty($stmt->fetch()))
	{
		$name = "existingUser";
		return false;
	}
	return true;
}

//Uses regex to check if there are no invalid characters in the name
function CheckValidChars(&$s)
{
	if (preg_match("/\[\^a-zA-Z0-9.\-_]/", $s)) 
	{
		$s = "invalidName";
		return false;
	}
	return true;
}

function Error($e = "")
{
	header("location: signup.php?err=" . $e);
	exit();
}

include 'security.php';
include 'globalVal.php';

//Checking if a POST call has been made, if not return to the signup
if (!isset($_POST['register']))	
{
	Error();
}

$name = $_POST['name'];

//Grab all the necessary variables
if(!CheckValidChars($name))
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
$query = "INSERT INTO users (name, password, image, bio) VALUES (:name, :password, :filepath, \" \")";


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
$stmt->bindParam(":filepath", $filePath);


if (!$stmt->execute())
{
	Error("Execute2");
}

echo("GG");
?>