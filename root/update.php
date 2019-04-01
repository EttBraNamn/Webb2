<?php

function Error($s)
{
	echo($s);
	exit();
}
//Get all the needed bios
function GetBios($comments)
{
	//Prepares the "IN" part of the query
	$users = array();
	//Get all the different users into an array
	foreach ($comments as $comment)
	{
		$users[$comment['name']] = $comment['name'];
	}
	//Write it all out in a stringformat "('', '[name1]', '[name2]', ...)"
	$sUsers = "(''";
	foreach ($users as $s)
	{
		$sUsers .= ",'" . $s . "'";
	}
	$sUsers .= ")";

	$query = "SELECT * FROM users WHERE name IN" . $sUsers;

	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection." . $query);
	}

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query." . $query);
	}

	$result = $stmt->fetchAll();

	//Makes an associative array with all the needed names an bios
	$toReturn = array();
	foreach ($result as $user)
	{
		$toReturn[$user['name']] = $user['bio'];
	}

	return $toReturn;
}
//Converts all the comments to a managable string one can return
function HandleComments($comments, $loggedIn = false)
{
	
	$bios = GetBios($comments);
	$toReturn = "";
	//Goes through all the made comments in their sorted order
	foreach ($comments as $comment)
	{
		$toReturn .= "<div class=\"post\"><div class=\"profile cprofile\"><img class=\"profilepic\" src=\"pic/" . $comment['name'] . ".jpg\"/>";
		$toReturn .= "<br /><label>" . $comment['name'] . "</label>";
		$toReturn .= "<br /><label>" . $bios[$comment['name']] . "</label>";
		$toReturn .= "<br /><time>" . $comment['date'] . "</time>";
		if ($loggedIn && ($_SESSION['name'] == $comment['name'] || in_array($_SESSION['name'], ADMIN)))
		{
			$toReturn .= "<form action=\"deleteComment.php\" method=\"post\">
			<input type=\"hidden\" name=\"id\" value=\"". $_GET['id'] . "\"/>
			<input type=\"hidden\" name=\"date\" value=\"" . $comment['date']. "\"/>
			<input type=\"submit\" name=\"submit\" value=\"Delete\"/>
			</form>";
		}
		$toReturn .= " </div><div class=\"text ctext\"><label>" . $comment['body'] . "</label>";
		$toReturn .= "</div><hr /></div>";
	}
	return $toReturn;
}

//Gets all of the needed comments
function GetComments($date, $id)
{
	$query = "SELECT * FROM comments WHERE date>:date AND id=:id";

	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		return "error";
	}

	$stmt->bindParam(":date", $date);
	$stmt->bindParam(":id", $id);

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query");
	}

	$return = $stmt->fetchAll();

	if (empty($return))
	{
		Error("No comments were recived");
	}
	return $return;
}


//Function used to sort comments
function sComments($lhs, $rhs)
{
	if ($lhs['date'] < $rhs['date'])
	{
		return -1;
	}
	else if ($lhs['date'] > $rhs['date'])
	{
		return 1;
	}
	return 0;
}


include 'globalVal.php';
include 'security.php';

session_start();

//Makes sure that all the required variabels exist
if(!isset($_GET['id']))
{
	Error("Missing id");
}
if(!isset($_POST['date']))
{
	Error("Missing date");
}


if (!isset($_SESSION['name']))
{
	$comments = GetComments($_POST['date'], $_GET['id']);
}
else
{
	$comments = GetComments($_POST['date'], $_GET['id']);
}

usort($comments, "sComments");

if (isset($_SESSION['name']))
{
	echo(HandleComments($comments, VerifyUser($_SESSION['name'], $_SESSION['password'] == "correct")));
}
else
{
	echo(HandleComments($comments));
}


?>