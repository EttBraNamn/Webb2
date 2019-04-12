<?php

function Error($s)
{
    echo($s);
    exit();
}

include 'globalVal.php';
include 'security.php';

if (!AllowDBEdit())
{
    Error("Not allowed");
}

$query = "CREATE DATABASE " . DBNAME . " COLLATE utf8_swedish_ci";

$connect = new PDO("mysql:host=" . SERVERNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($query)))
{
	Error("Couldn't prepare query: " . $query);
	
}

if (!($stmt->execute()))
{
	Error("Couldn't execute query: " . $query);

}

header("location: setupTable.php");
exit();
?>