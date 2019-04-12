<?php

function Error($s)
{
    echo($s);
    exit();
}

include 'globalVal.php';
include 'security.php';

session_start();

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

$name = $_SESSION['name'];

if (isset($_POST['text']))
{
    $bio = CleanInput($_POST['text']);
}
else
{
    $bio = "none";
}

$query = "UPDATE users SET bio=:bio WHERE name=:name";

$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($query)))
{
	Error("Couldn't prepare query");
}

$stmt->bindParam(":bio", $bio);
$stmt->bindParam(":name", $name);

if (!($stmt->execute()))
{
	Error("Couldn't execute query");
}

if (empty($_FILES['file']['tmp_name']))
{
    header("location: profile.php");
    exit();
}

if (!ImageCheckFiletype($_FILES['file']['tmp_name']))
{
	Error("Something wrong with the image");
}
$tempFile = file_get_contents($_FILES['file']['tmp_name']);
$filePath = "pic/" . $name . ".jpg";
$writeToo = fopen(__DIR__ . "/" . $filePath, "w");
fwrite($writeToo, $tempFile);
fclose($writeToo);
header("location: profile.php");
exit();
?>