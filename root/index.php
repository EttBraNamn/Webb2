<?php
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
			$numbers .= "<b>  <a onclick=\"window.location.href = 'index.php?page=1'\">[1]</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=2'\">2</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page='3'\">3</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=4'\">4</a> - ";
			$numbers .= "<a onclick=\"window.location.href = 'index.php?page=5'\">5</a>  </b>";
		}
	}
	//If page is more than 3
	else
	{
		for ($i = 0; $i < 5; ++$i ++$start)
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
		$numbers = substr(0, $numbers.lenght - 3);
		$numbers .= "</b>";
	}
	//Wraps $numbers around the neccesary strings
	$toReturn = "<div class=\"navigation\"><img src=\"left.png\" onclick=\"Navigate(-1)\" /><label>" . $numbers;
	$toReturn .= "</label><img src=\"right.png\" onclick=\"Navigate(1)\" /></div>";
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
    <script type=\"text/javascript\" src=\"index.js\"></script>


    <div class=\"header\" id=\"not\">
        <h2>You're not logged in. Do you want to do it now?</h2>
        <input type=\"button\" onclick=\"window.location.href = 'login.php';\" style=\"width:10%\"value=\"Yes\"/>
        <input type=\"button\" onclick=\"window.location.href = 'signup.php';\" style=\"width:10%\" value=\"Sign up\" />
        <input type=\"button\" onclick=\"document.getElementById('not').innerHTML = '';\"style=\"width:10%\" value=\"No\"/>
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
    <script type=\"text/javascript\" src=\"post.js\"></script>


    <div class=\"header\">
        <input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Make Post\" onclick=\"MakePost()\" />
        <input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Profile\" href=\"profile.php\" />
        <input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Notifications\" href=\"alert.php\" />
        <input class=\"hbutton\" type=\"button\" style=\"width:24.8%\" value=\"Log Out\" href=\"logout.php\" />
    </div>

    <div id=\"input\" style=\"width:45%;margin-top:10px;\">
    </div>";
	return $toReturn;
}

//Returs the first part of the html
function Start($loggedIn)
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
	$page = $_GET['page'];
}
else 
{
	$page = 1;
}

$toPrint = "";
if (isset($_SESSION['name']))
{
	$toPrint .= Start(VerifyUser($_SESSION['name'], $_SESSION['password']));
}
else
{
	$toPrint .= Start(false);
}

$toPrint .= Navigator($page);
?>