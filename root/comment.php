<?php

include 'globalVal.php';
include 'security.php';

function Error($s)
{
	$s = "Comment failed: " . $s; 
	echo($s);
	exit();
}


//Makes sure that the user is logged in
session_start();
if (!isset($_SESSION['name']))
{
	Error("Not logged in");
}
if (VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
	Error("Not logged in");
}
//Makes sure that a comment has been sent via post
if(!isset($_POST['comment']))
{
	Error("Your comment is empty, the javascript is most likely broken");
}
//Makes sure that a post id exists
if (!isset($_GET['id']))
{
	Error("No id exists, your javascript is most likely broken");
}

$name = $_SESSION['name'];
$id = $_GET['id'];
$body = CleanInput($_POST['comment']);
$time = date("Y-m-d H:i:s", time());
//"INSERT INTO users (name, password, bio) VALUES (:name, :password, \" \")";

$query = "INSERT INTO comments (date, name, body, id) VALUES (:date, :name, :body, :id)";

$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($query)))
{
	Error("Couldn't prepare query");
}

$stmt->bindParam(":date", $time);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":body", $body);
$stmt->bindParam(":id", $id);

if (!($stmt->execute()))
{
	Error("Couldn't execute query");
}

return "Comment uploaded!";

?>