<?php

function DeleteComments($id)
{
    $query = "DELETE FROM comments WHERE id=:id";

    if (!$stmt = $connect->prepare($query))
    {
        Error("Couldn't prepare query" . $query);
    }
    $stmt->bindParam(":id", $id);
    //If it can't be executed
    if(!$stmt->execute())
    {
        Error("Couldn't execure query" . $query);
    }
}

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
if ($_POST['name'] != $_SESSION['name'] && !in_array($_SESSION['name'], ADMIN))
{
    Error("Wrong username");
}

$name = $_POST['name'];

$query = "DELETE FROM comments WHERE name=:name";

$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);
//If it can't be prepared
if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(":name", $name);
//If it can't be executed
if(!$stmt->execute())
{
    Error("Couldn't execure query" . $query);
}

$query = "SELECT id FROM post WHERE name=:name";

if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(":name", $name);
//If it can't be executed
if(!$stmt->execute())
{
    Error("Couldn't execure query" . $query);
}

$idList = $stmt->fetchAll();

if (empty($idList))
{
    header("location: index.php");
    exit();
}

foreach ($isList as $id)
{
    DeleteComments($id);
}

$query = "DELETE FROM post WHERE name=:name";

if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(":name", $name);
//If it can't be executed
if(!$stmt->execute())
{
    Error("Couldn't execure query" . $query);
}

header("location: index.php");
exit();
?>