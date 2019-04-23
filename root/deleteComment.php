<?php

function Error($s)
{
    echo($s);
    exit();
}

include 'globalVal.php';
include 'security.php';

session_start();

//Checks if all the required values are defined correctly
if (!isset($_POST['id']))
{
    header("location: index.php");
    exit();
}
if (!isset($_SESSION['name']))
{
    header("location: index.php");
    exit();
}
if (VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    header("location: index.php");
    exit();
}




$id = $_POST['id'];
$name = $_SESSION['name'];

$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

$query = "SELECT * FROM comments WHERE id=:id AND name=:name AND date=:date";

if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(":id", $id);
$stmt->bindParam(":name", $name);
$stmt->bindParam("date", $date);
//If it can't be executed
if(!$stmt->execute())
{
    Error("Couldn't execure query" . $query);
}

$ret = $stmt->fetchAll();

//Delets the post with the right id and name. That way nobody can delete something that the user never posted
if (in_array($name, ADMIN))
{
    
    $query = "DELETE FROM comments WHERE id=:id AND date=:date";
}
else
{
	if (empty($ret))
	{
		Error("You can't delete other people's posts");
	}
    $query = "DELETE FROM comments WHERE id=:id AND date=:date";
}


$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);
//If it can't be prepared
if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(":id", $id);
$stmt->bindParam(":date", $date);
//If it can't be executed
if(!$stmt->execute())
{
    Error("Couldn't execute query" . $query);
}

$query = "DELETE FROM comments WHERE id=:id";
if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(':id', $id);
if(!$stmt->execute())
{
    Error("Couldn't execute query" . $query);
}

header("location: post.php?id=" . $id);
exit();
?>