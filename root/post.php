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
	foreach ($comments as $comment)
	{
		$users[$comment['name']] = $comment['name'];
	}
	//Write it all out in a stringformat "('', '[name1]', '[name2]', ...)"
	$sUsers = "(''";
	foreach ($users as $s)
	{
		$sUsers .= ",'" . $s . "'";
	}
	$sUsers .= ")";

	$query = "SELECT * FROM users WHERE name IN" . $sUsers;

	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection." . $query);
	}

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query." . $query);
	}

	$result = $stmt->fetchAll();

	//Makes an associative array with all the needed names an bios
	$toReturn = array();
	foreach ($result as $user)
	{
		$toReturn[$user['name']] = $user['bio'];
	}

	return $toReturn;
}

//Returns OP:s post in html format
function HandlePost($op)
{
	$op = $op[0];
	//Get's the right bio
	
	$query = "SELECT bio FROM users WHERE name=\"" .  $op['name'] . "\"";

	Select($query, $bio);

	$toPrint = "<h3>" . $op['subject'] . "</h3><hr/>";
	$toPrint .= "<div class=\"profile\"><img class=\"profilepic\" src=\"pic/" . $op['name'] . ".jpg\"/>";
	$toPrint .= "<br/><label>" . $op['name']. "</label><br/>";
	$toPrint .= "<label>" . $bio[0]['bio'] . "</label><br/>";
	$toPrint .= "<time>" . $op['date'] . "</time></div>";
	$toPrint .= "<div class=\"text\"><label style=\"width:60%;float:left;\">";
	$toPrint .= $op['body'] . "</label>";
	$toPrint .= "<img src=\"" .  $_GET['id'] . $op['image'] ."\" style=\"float:right;height:90%\" />";
	$toPrint .= "</div><hr/>";

	return $toPrint;
}

function HandleComments($comments)
{
	$bios = GetBios($comments);
	
	
	$toReturn = "";
	//Goes through all the made comments in their sorted order
	foreach ($comments as $comment)
	{
		$toReturn .= "<div class=\"post\"><div class=\"profile cprofile\"><img class=\"profilepic\" src=\"pic/" . $comment['name'] . ".jpg\"/>";;
		$toReturn .= "<br /><label>" . $comment['name'] . "</label>";
		$toReturn .= "<br /><label>" . $bios[$comment['name']] . "</label>";
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
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
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
function Select($query, &$result, $allowEmpty = false)
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

	$result = $stmt->fetchAll();

	if(empty($result) && !$allowEmpty)
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

Select($query, $comments, $_GET['id'], true);

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