<?php
//Returns OP:s post in html format
function HandlePost($post)
{
	//Get's the right bio
	$query = "SELECT bio FROM users WHERE name=:bind";

	Select($query, $bio, $post['name']);

	$toPrint = "<h3>" . $post['subject'] . "</h3><hr/>";
	$toPrint .= "<div class=\"profile\"><img class=\"profilepic\" src=\"" . $post['name'] . ".jpg\"/>";
	$toPrint .= "<br/><label>" . $post['name']. "</label><br/>";
	$toPrint .= "<label>" . $bio['bio'] . "</label><br/>";
	$toPrint .= "<time>" . $post['date'] . "</time></div>";
	$toPrint .= "<div class=\"text\"><label style=\"width:60%;float:left;\">";
	$toPrint .= $post['body'] . "</label>";
	$toPrint .= "<img src=\"" .  $_GET['id'] . $post['image'] ."\" style=\"float:right;height:90%\" />";
	$toPrint .= "</div><hr/>";
}

//Everything html related before the post
function HtmlStart()
{
	return "<html>
<head>
    <title>
        Posts
    </title>
    <meta charset=\"utf-8\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"post.css\">
</head>
<body>
    
    <!---ALL POSTS AND COMMENTS--->
    <div class=\"uploads\" id=\"uploads\">
        <div class=\"post\" id=\"op\">";
}

//Function used to sort comments
function sComments($lhs, $rhs)
{
	if ($lhs['date'] < $rhs['date'])
	{
		return -1;
	}
	else if ($lhs['date'] > $rhs['date'])
	{
		return 1;
	}
	return 0;
}

//the sql reqest that will be needed for this page
function Select($query, &$result, $bind)
{
	
	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection.");
	}

	$stmt->bindParam(":bind", $bind);

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query.");
	}

	$result = $stmt->fetch();

	if(empty($return))
	{
		Error("Couldn't find the requested post, something must be wrong with the link.");
	}

}

include 'globalVal.php';
include 'security.php'

session_start();

//VerifyUser();

if (!isset($_GET['id']))
{
	Error("Couldn't find the requested post, something must be wrong with the link.");
}

//Get the right uploaded post as decided by ID

$query = "SELECT * FROM posts WHERE id=:bind";

Select($query, $post, $_GET['id']);

//Get all the comments that related to said post

$query = "SELECT * FROM comments WHERE id=:bind";

Select($query, $comments, $_GET['id']);

//Sorts all the comments by date using the comparison function sComments
usort($comments, "sComments");

//The string that's supposed to be echoed later
$toPrint = HtmlStart();
?>