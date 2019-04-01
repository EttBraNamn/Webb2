<?php

include 'globalVal.php';
include 'security.php';

session_start();

if (!isset($_SESSION['name']))
{
    header("location: index.php");
    exit();
}
else if (VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    header("location: index.php");
    exit();
}
else if (!isset($_POST['date']))
{
    header("location: index.php");
    exit();
}

$date = $_POST['date'];
$name = $_SESSION['name'];
$id = $_POST['id'];

if (in_array($name, ADMIN))
{
    $query = "DELETE FROM comments WHERE id=:id AND date=:date";
}
else 
{
    $query = "DELETE FROM comments WHERE id=:id AND date=:date AND name=\"" . $name . "\"";
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
    Error("Couldn't execure query" . $query);
}


header("location: post.php?id=" . $id);
exit();
?>