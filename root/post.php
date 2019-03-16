<?php
function Error($s)
{
	echo($s);
	exit();
}

function HtmlEnd($comments)
{
	$toReturn = "";
	$toReturn .= "<!--END OF POSTS AND COMMENTS--><div class=\"comment\"><h5>Comment:</h5><label class=\"error\" id=\"error\"></label><br /><textarea class=\"input\" id=\"comment\"></textarea>
        <input class=\"button\" type=\"button\" value=\"Post\" onclick=\"Post(document.getElementById('comment').value)\" /></div><!--USED FOR THE UPDATE FUNCTION-->";

	$toReturn .= "<input type=\"hidden\" id=\"date\" value=\"" . $comments[sizeof($comments) - 1]['date'] . " />";
	$toReturn .= "<script type=\"text/javascript\" src=\"post.js\"></script></body></html>";
	return $toReturn;
}

//Get all the needed bios
function GetBios($comments)
{
	//Prepares the "IN" part of the query
	$users = array();
	//Get all the different users into an array
	foreach ($comment as $comments)
	{
		$users[$comment['name']] = $comment['name'];
	}
	//Write it all out in a stringformat "('', '[name1]', '[name2]', ...)"
	$sUsers = "(\'\'";
	foreach ($s as $users)
	{
		$sUsers .= ",\'" . $s . "\'";
	}
	$sUsers .= ")";

	$query = "SELECT * FROM users WHERE name IN" . $sUsers;

	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection.");
	}

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query.");
	}

	$result = $stmt->fetch();

	//Makes an associative array with all the needed names an bios
	$toReturn = array();
	foreach ($user as $result)
	{
		$toReturn[$user['name']] = $user['bio'];
	}

	return $toReturn;
}

//Returns OP:s post in html format
function HandlePost($post)
{
	//Get's the right bio
	$query = "SELECT bio FROM users WHERE name=\"" .  $post['name'] . "\"";

	Select($query, $bio);

	$toPrint = "<h3>" . $post['subject'] . "</h3><hr/>";
	$toPrint .= "<div class=\"profile\"><img class=\"profilepic\" src=\"pic/" . $post['name'] . ".jpg\"/>";
	$toPrint .= "<br/><label>" . $post['name']. "</label><br/>";
	$toPrint .= "<label>" . $bio['bio'] . "</label><br/>";
	$toPrint .= "<time>" . $post['date'] . "</time></div>";
	$toPrint .= "<div class=\"text\"><label style=\"width:60%;float:left;\">";
	$toPrint .= $post['body'] . "</label>";
	$toPrint .= "<img src=\"" .  $_GET['id'] . $post['image'] ."\" style=\"float:right;height:90%\" />";
	$toPrint .= "</div><hr/>";

	return $toPrint;
}

function HandleComments($comments)
{
	$bios = GetBios($comments);
	
	$toReturn = "";
	//Goes through all the made comments in their sorted order
	foreach ($comment as $comments)
	{
		$toReturn .= "<div class=\"post\"><div class=\"profile cprofile\"><img class=\"profilepic\" src=\"pic/" . $comment['name'] . ".jpg\"/>";;
		$toReturn .= "<br /><label>" . $comment['name'] . "</label>";
		$toReturn .= "<br /><label>" . $bios['name'] . "</label>";
		$toReturn .= "<br /><time>" . $comment['date'] . "</time>";
		$toReturn .= " </div><div class=\"text ctext\"><label>" . $comment['body'] . "</label>";
		$toReturn .= "</div><hr /></div>";
	}

	return $toReturn;
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
function Select($query, &$result)
{
	
	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection." . $query);
	}

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query." . $query);
	}

	$result = $stmt->fetch();

	if(empty($result))
	{
		Error("Couldn't find the requested post, something must be wrong with the link." . $query);
	}
	
	return $result;
}

include 'globalVal.php';
include 'security.php';

session_start();

//VerifyUser();

if (!isset($_GET['id']))
{
	Error("Couldn't find the requested post, something must be wrong with the link.");
}

//Get the right uploaded post as decided by ID

$query = "SELECT * FROM post WHERE id=\"" .  $_GET['id'] . "\"";

Select($query, $post);

//Get all the comments that related to said post

$query = "SELECT * FROM comments WHERE id=\"" .  $_GET['id'] . "\"";

Select($query, $comments, $_GET['id']);

var_dump($comments);
//Sorts all the comments by date using the comparison function sComments
usort($comments, "sComments");

//The string that's supposed to be echoed later
$toPrint = HtmlStart();
//Takes care of post and comment part
$toPrint .= HandlePost($post);
$toPrint .= HandleComments($comments);
$toPrint .= HtmlEnd($comments);
echo($toPrint);
?>