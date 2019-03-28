<?php

include 'globalVal.php';
include 'security.php'

if (!isset($_SESSION['name']))
{
    header("location: login.php");
    exit();
}
if (VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    header("location: login.php"):
    exit();
}



?>