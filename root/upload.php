<?php

include 'security.php';
include 'globalVal.php';

session_start();
//Makes sure that a post has been made
if (!isset($_POST['text']) || !isset($_POST['subject']) || !isset($_FILES['file']))
{
    header("location: index.php?error=empty&page=1");
    exit();
}
//Makes sure that the user is logged in
if (!isset($_SESSION['name']) || VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    header("location: index.php?error=user&page=1");
    exit();
}

if (!ImageCheckFiletype($_FILES['file']['tmp_name']))
{
    header("location: index.php?error=image&page=1");
    exit();
}
$name = $_SESSION['name'];
$subject = $_
?>  