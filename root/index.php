	<?php
function EndHtml()
{
	return "</body></html>";
}

function Error($s)
{
	echo($s);
	exit();
}
//Function used to sort comments
function sPosts($lhs, $rhs)
{
	if ($lhs['id'] > $rhs['id'])
	{
		return -1;
	}
	else if ($lhs['id'] < $rhs['id'])
	{
		return 1;
	}
	return 0;
}

//Returns an array of posts
function GetPosts($end, $start)
{
	$query = "SELECT * FROM post WHERE id<" . $start . " AND id>" . $end;
	
	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection." . $query);
	}

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query." . $query);
	}

	$toReturn = $stmt->fetchAll();

	usort($toReturn, "sPosts");

	return $toReturn;
}

//returns the amount of posts in database
function GetPostCount()
{
	$query = "SELECT max(id) FROM post ";

	
	$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		Error("Couldn't prepare query, something might be wrong with the sql server connection." . $query);
	}

	if (!($stmt->execute()))
	{
		Error("Couldn't execute query, something might be wrong with the query." . $query);
	}

	return $stmt->fetch();
}
//Returns the post part of the site
function Posts($page)
{
	//Gets the end and start id's
	$page *= 2;
	$count = GetPostCount();
	$count = $count[0];
	echo($count);
	$dif = $count - $page;
	echo($dif);
	if (0 > $dif)
	{
		$end = 0;
		$start = 2;
	}
	else
	{
		$end = $count - $page;
		$start = $end + 20;
	}
	
	$end -= 500;
	
	$posts = GetPosts($end, $start);

	$toReturn = "";
	$toReturn .= "<div>";
	$i = 0;
	foreach ($posts as $p)
	{
		if ($i < 2)
		{
			$toReturn .= "<div class=\"post indexpost\" onclick=\"window.location.href = 'post.php?id=" . $p['id'] . "'\"><h3 class=\"subject\">";
			$toReturn .= $p['subject'] . "</h3><div class=\"profile\">";
			$toReturn .= "<img alt=\"Deleted profileimage\" class=\"profilepic\" src=\"pic/" . $p['name'] . ".jpg\"/><br/>";
			$toReturn .= "<label class=\"name\">" . $p['name'] . "</label></div>";
			$toReturn .= "<div class=\"text\"><label style=\"width:60%;float:left;\">" . $p['body'] . "</label>";
			$toReturn .= "<img alt=\"Deleted image\" class=\"postImage\" src=\"post/" . $p['id'] . "." . $p['image'] . "\" />";
			$toReturn .= "</div></div>";
			++$i;
		}
		else
		{
			break;
		}
	}

	$toReturn .= "</div>";
	return $toReturn;
}

//Responsible for the Navigator
function Navigator($page)
{
	$start = $page - 2;
	$numbers = "";

	//If page is less than 3
	if ($start < 1)
	{
		if ($start < 0)
		{
			$numbers .= "<b>  <a onclick=\"window.location.href = 'index.php?page=1'\">[1]</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=2'\">2</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page='3'\">3</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=4'\">4</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=5'\">5</a>  </b>";
		}
		else
		{
			$numbers .= "<b>  <a onclick=\"window.location.href = 'index.php?page=1'\">1</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=2'\">[2]</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page='3'\">3</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=4'\">4</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=5'\">5</a>  </b>";
		}
	}
	//If page is more than 3
	else
	{
		$numbers .= "<b>";
		for ($i = 0; $i < 5; ++$i, ++$start)
		{
		//If it's the middle one
			if ($i == 2)
			{
				$numbers .= "<a onclick=\"window.location.href = 'index.php?page=" . $start . "'\">[". $start . "]</a> - ";
			}
			else
			{
				$numbers .= "<a onclick=\"window.location.href = 'index.php?page=" . $start . "'\">". $start . "</a> - ";
			}
		}
		//removes the trailing " - ";
		$numbers = substr($numbers, 0, strlen($numbers) - 3);
		$numbers .= "</b>";
	}
	//Wraps $numbers around the neccesary strings
	$toReturn = "<div class=\"navigation\"><img alt=\"Left\" src=\"left.png\" onclick=\"Navigate(-1)\" /><label>" . $numbers;
	$toReturn .= "</label><img alt=\"Right\" src=\"right.png\" onclick=\"Navigate(1)\" /></div>";
	return $toReturn;
}

//The start if the user isn't logged in
function NotLoggedStart()
{
	$toReturn = "<html>
<head>
    <meta charset=\"UTF-8\" />
    <title>AHHH</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
</head>
<body>
    <script src=\"index.js\"></script>


    <div class=\"header\" id=\"not\">
        <input class=\"hbutton hlbutton\" type=\"button\" onclick=\"window.location.href = 'login.php';\" value=\"Login\"/>
        <input class=\"hbutton hlbutton\" type=\"button\" onclick=\"window.location.href = 'signup.php';\"  value=\"Sign up\" />
    </div>

    <div id=\"input\" style=\"width:45%;margin-top:10px;\">

    </div>";
	return $toReturn;
}
//The start if the user is logged in
function LoggedStart()
{
	$toReturn = "<html>
<head>
    <meta charset=\"UTF-8\" />
    <title>Homepage</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
</head>
<body>
    <script src=\"index.js\"></script>


    <div class=\"header\">
        <input class=\"hbutton hlbutton\" type=\"button\"  value=\"Make Post\" onclick=\"MakePost()\" />
        <input class=\"hbutton hlbutton\" type=\"button\"  value=\"Profile\" onclick=\"window.location.href = 'profile.php'\" />
        <input class=\"hbutton hlbutton\" type=\"button\"  value=\"Post History\" onclick=\"window.location.href = 'list.php'\" />
        <input class=\"hbutton hrbutton\" type=\"button\"  value=\"Log Out\" onclick=\"window.location.href = 'logout.php'\" />
    </div>

    <div id=\"input\" style=\"width:45%;margin-top:10px;\">
    </div>";
	return $toReturn;
}

//Returs the first part of the html
function StartHtml($loggedIn)
{
	if ($loggedIn)
	{
		return LoggedStart();
	}

	return NotLoggedStart();
}

include 'globalVal.php';
include 'security.php';
session_start();

//Checks if there exists any GET
if (isset($_GET['page']))
{
	$page = intval($_GET['page']);
	
}
else 
{
	$page = 1;
}

$toPrint = "<!DOCTYPE html5>";
if (isset($_SESSION['name']))
{
	$toPrint .= StartHtml((VerifyUser($_SESSION['name'], $_SESSION['password'])  == "correct"));
}
else
{
	$toPrint .= StartHtml(false);
}

$toPrint .= Navigator($page);
if (isset($_GET['error']))
{

	$ar = array();
	$ar['database'] = "Couldn't upload post, somethings wrong with the database";
	$ar['image'] = "Couldn't upload post, somethings wrong with the uploaded image. Makes sure that it's a jpeg, png or gif file";
	$ar['user'] = "Couldn't upload post, you need to log in before trying to post";
	$ar['input'] = "Couldn't upload post, somethings wrong with the values given";
	$ar['empty'] = "Couldn't upload post, you need to fill out all fields";

	$toPrint .= "<div style=\"background-color:red;margin-top:5px;margin-bottom:5px;width:20%;\"><label>". $ar[$_GET['error']] . "</label></div>";
}
$toPrint .= Posts($page);


$toPrint .= EndHtml();

echo($toPrint);
?>