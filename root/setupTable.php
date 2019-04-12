<?php

function Error($s)
{
    echo($s);
    exit();
}

function AddTable($q)
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

include 'globalVal.php';
include 'security.php';

if (!AllowDBEdit())
{
    Error("Not allowed");
}

$query = "CREATE TABLE users (
    name varchar(30) COLLATE utf8_swedish_ci NOT NULL,
    password varchar(255) COLLATE utf8_swedish_ci NOT NULL,
    bio varchar(500) COLLATE utf8_swedish_ci NOT NULL,
    list varchar(500) COLLATE utf8_swedish_ci NOT NULL
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
    date datetime NOT NULL,
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(30) COLLATE utf8_swedish_ci NOT NULL,
    subject varchar(100) COLLATE utf8_swedish_ci NOT NULL,
    body varchar(2000) COLLATE utf8_swedish_ci NOT NULL,
    image varchar(5) COLLATE utf8_swedish_ci NOT NULL,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci";
  
  AddTable($query);

?>