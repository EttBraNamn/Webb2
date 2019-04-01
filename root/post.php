<?php
function Error($s)
{
	echo($s);
	exit();
}

function HtmlEnd($comments)
{
	$toReturn = "";
	$toReturn .= "<!--END OF POSTS AND COMMENTS--><div class=\"comment\"><h3>Comment:</h3><label class=\"error\" id=\"error\"></label><br /><textarea class=\"input\" name=\"comment\"id=\"comment\"></textarea>
        <input class=\"button\" type=\"button\" value=\"Post\" onclick=\"A(document.getElementById('comment').value);\" /><input style=\"margin-left:10px;\" class=\"button\" type=\"button\" value=\"Update!\" onclick=\"Update();\"/></div><!--USED FOR THE UPDATE FUNCTION-->";
	if (sizeof($comments) == 0)
	{
		$val = "0";
	}
	else 
	{
		$val = $comments[sizeof($comments) - 1]['date'];
	}
	$toReturn .= "<input type=\"hidden\" id=\"date\" value=\"" . $val . "\" />";
	$toReturn .= "</body></html>";
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
function HandlePost($op, $loggedIn = false)
{
	$op = $op[0];
	//Get's the right bio
	
	$query = "SELECT bio FROM users WHERE name=\"" .  $op['name'] . "\"";

	$error = Select($query, $bio, true);

	if ($error)
	{
		Error($error);
	}
	if (empty($bio))
	{
		$bio = array();
		$bio[0]['bio'] = "User deleted :(";
	}

	$toPrint = "<h3>" . $op['subject'] . "</h3><hr/>";
	$toPrint .= "<div class=\"profile\"><img class=\"profilepic\" src=\"pic/" . $op['name'] . ".jpg\"/>";
	$toPrint .= "<br/><label>" . $op['name']. "</label><br/>";
	$toPrint .= "<label>" . $bio[0]['bio'] . "</label><br/>";
	$toPrint .= "<time>" . $op['date'] . "</time>";
	if ($loggedIn && ($_SESSION['name'] == $op['name'] || in_array($_SESSION['name'], ADMIN)))
	{
		$toPrint .= "<form action=\"deletePost.php\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"";
		$toPrint .= $_GET['id'] . "\"/><input type=\"submit\" name=\"submit\" value=\"Delete\"/></form>";
	}
	$toPrint .= "</div><div class=\"text\"><label style=\"width:60%;float:left;\">";
	$toPrint .= $op['body'] . "</label>";
	$toPrint .= "<img src=\"post/" .  $_GET['id'] . ".".  $op['image'] ."\" onclick=\"window.location.href = 'post/" .  $_GET['id'] . ".".  $op['image'] ."'\"style=\"float:right;max-height:100%\" />";
	$toPrint .= "</div><hr/></div>";

	return $toPrint;
}

function HandleComments($comments, $loggedIn = false)
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
		if ($loggedIn && ($_SESSION['name'] == $comment['name'] || in_array($_SESSION['name'], ADMIN)))
		{
			$toReturn .= "<form action=\"deleteComment.php\" method=\"post\">
			<input type=\"hidden\" name=\"id\" value=\"". $_GET['id'] . "\"/>
			<input type=\"hidden\" name=\"date\" value=\"" . $comment['date']. "\"/>
			<input type=\"submit\" name=\"submit\" value=\"Delete\"/>
			</form>";
		}
		$toReturn .= " </div><div class=\"text ctext\"><label>" . $comment['body'] . "</label>";
		$toReturn .= "</div><hr /></div>";
	}
	$toReturn .= "</div>";
	return $toReturn;
}

//Everything html related before the post
function HtmlStart()
{
	$toReturn =  "<html>
<head>
    <title>
        Posts
    </title>
    <meta charset=\"utf-8\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
</head>
<body>
<script type=\"text/javascript\" src=\"post.js\"></script>";
	if (isset($_SESSION['name']) && VerifyUser($_SESSION['name'], $_SESSION['password']))
	{
		$toReturn .= "<div class=\"header\">
		<input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Main Page\" onclick=\"window.location.href = 'index.php'\" />
		<input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Profile\" onclick=\"window.location.href = 'profile.php'\" />
		<input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Post History\" onclick=\"window.location.href = 'list.php'\" />
		<input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Log Out\" onclick=\"window.location.href = 'logout.php'\" />
	</div>";
	}
	else
	{
		$toReturn .= "<div class=\"header\" id=\"not\">
        <h2>You're not logged in. Do you want to do it now?</h2>
        <input type=\"button\" onclick=\"window.location.href = 'login.php';\" style=\"width:10%\"value=\"Yes\"/>
        <input type=\"button\" onclick=\"window.location.href = 'signup.php';\" style=\"width:10%\" value=\"Sign up\" />
        <input type=\"button\" onclick=\"document.getElementById('not').innerHTML = '';\"style=\"width:10%\" value=\"No\"/>
    </div>";
	}
	$toReturn .= "<!---ALL POSTS AND COMMENTS--->
    <div class=\"uploads\" id=\"uploads\">
		<div class=\"post\" id=\"op\">";
		
	return $toReturn;
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
	
	return false;
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


if (!isset($_SESSION['name']))
{
	$toPrint .= HandlePost($post);
	$toPrint .= HandleComments($comments);
}
else
{
	$val = (VerifyUser($_SESSION['name'], $_SESSION['password']) == "correct");
	$toPrint .= HandlePost($post, $val);
	$toPrint .= HandleComments($comments, $val);
}
$toPrint .= HtmlEnd($comments);
echo($toPrint);
?>