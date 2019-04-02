<?php

function Delete($q)
{
	
$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($q)))
{
	Error("Couldn't prepare query: " . $q);
	
}

if (!($stmt->execute()))
{
	Error("Couldn't execute query: " . $q);
}

}



include 'security.php';
include 'globalVal.php';

session_start();

if (!isset($_SESSION['name']))
{
    header("location: index.php");
    exit();
}
if (!in_array($_SESSION['name'], ADMIN))
{
    header("location: index.php");
    exit();
}
if (VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    header("location: index.php");
    exit();
}
if (!AllowDBEdit())
{
    header("location: index.php");
    exit();
}


$query = "DELETE FROM users";

Delete($query);

$query = "DELETE FROM comments";

Delete($query);

$query = "DELETE FROM post";

Delete($query);

header("location: index.php");
?>