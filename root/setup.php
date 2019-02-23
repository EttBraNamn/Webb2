<?php

function AddTable($q)
{
	
$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($q)))
{
	echo("Couldn't prepare query: " . $q);
	exit();
}

if (!($stmt->execute()))
{
	echo("Couldn't execute query: " . $q);
	exit();
}

echo ("Executed query: " . $q . "<br>");

	
}

include 'globalVal.php';

$query = "CREATE DATABASE " . DBNAME . " COLLATE utf8_swedish_ci";

$connect = new PDO("mysql:host=" . SERVERNAME, USERNAME, PASSWORD);

if (!($stmt = $connect->prepare($query)))
{
	echo("Couldn't prepare query: " . $query);
	
}

if (!($stmt->execute()))
{
	echo("Couldn't execute query: " . $query);

}

echo ("Executed query: " . $query . "<br>");

$query = "CREATE TABLE users (
  name varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  password varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  bio varchar(500) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci";

AddTable($query);

$query = "CREATE TABLE comments (
  date datetime NOT NULL,
  name varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  body varchar(2000) COLLATE utf8_swedish_ci NOT NULL,
  id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci";

AddTable($query);

$query = "CREATE TABLE post (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  subject varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  body varchar(2000) COLLATE utf8_swedish_ci NOT NULL,
  image varchar(5) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci";

AddTable($query);

echo("Database done!");
?>