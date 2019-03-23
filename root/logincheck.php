<?php

include 'security.php';

session_start();

if (!isset($_POST['name']) || !isset($_POST['password']))
{
	header("location: login.php?error=empty");
	exit();
}

$answer = VerifyUser($_POST['name'], $_POST['password']);

if ($answer != "correct")
{
	header("location: login.php?error=" . $answer);
	exit();
}

$_SESSION['name'] = $_POST['name'];
$_SESSION['password'] = $return['password'];

//Get to last site visited or index.php
if (isset($_SESSION['last']))
{
	header("location: ". $_SESSION['last']);
	exit();
}

header("location: index.php");
exit();
?>