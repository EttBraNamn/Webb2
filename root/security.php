<?php

include 'globalVal.php';

function AllowDBEdit()
{
	return true;
}

//Uses regex to check if there are no invalid characters in the name
function CheckValidName(&$s)
{
	if (strlen($s)> 30)
	{
		$s = "tooLongName";
		return false;
	}
	if (preg_match("/\[\^a-zA-Z0-9.\-_]/", $s)) 
	{
		$s = "invalidChars";
		return false;
	}
	return true;
}

//Clean input for comment or post
function CleanInput($s)
{
	$toReturn = strip_tags($s);
	return $toReturn;
}


//Check if a user exists
function VerifyUser($username, $password)
{
	
	$query = "SELECT * FROM users WHERE name=:name";

	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		return "error";
	}

	$stmt->bindParam(":name", $username);

	if (!($stmt->execute()))
	{
		return "error";
	}

	$return = $stmt->fetch();

	if (empty($return))
	{
		return "wrong";
	}

	//Checks if the password is valid
	if($password == $return['password'])
	{
		return "wrong";
	}

	return "correct";
}

//Checks if the file is an allowed filetype
function ImageCheckFiletype($path)
{
	$fin = new finfo(FILEINFO_MIME_TYPE);
	$allowed = array("image/png", "image/jpeg", "image/gif");
	$file = $fin->file($path);
	return in_array($file, $allowed);
}
?>