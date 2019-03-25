<?php

function GetList($name)
{
    $query = "SELECT list FROM users WHERE "
}


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

$name = $_SESSION['name'];

$list = GetList($name);

?>