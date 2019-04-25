<?php

function RemoveDead($posts, $list)
{
    $toReturn = array();
    $check = array();
    foreach($posts as $p)
    {
        array_push($check, $p['id']);
    }
    foreach($list as $l)
    {
        if (in_array($l, $check))
        {
            array_push($toReturn, $l);
        }

    }
    return $toReturn;
}

function Error($s)
{
    echo($s);
    exit();
}
function HtmlEnd()
{
    return "</body></html>";
}

function PostHandle($posts, $list)
{
    $toReturn = "<div class=\"postblock\" ><br><div class=\"postrow\" >";

    for ($i =0; $i < sizeof($list); ++$i)
    {
        if ($i % 4 == 0 && $i != 0)
        {
            $toReturn .= "</div><br><div class=\"postrow\" >";
        }
        $toReturn .= "<div class=\"block\"><img src=\"post/" . $list[$i] .".". $posts[$list[$i]]['image'] . "\" class=\"listpic\"/>
        <br/><label><a href=\"post.php?id=" . $list[$i] . "\">" . $posts[$list[$i]]['subject'] . "</a></label></div>";
    } 
    $toReturn .= "</div></div>";
    return $toReturn;
}
function HtmlStart()
{
    return "<html>
    <head>
        <meta charset=\"UTF-8\"/>
        <title>List of posts and comments made</title>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
    </head>
    <body>
        <div class=\"header\">
            <input class=\"hbutton hlbutton\" type=\"button\" style=\"\" value=\"Main Page\" onclick=\"window.location.href = 'index.php'\" />
            <input class=\"hbutton hlbutton\" type=\"button\" style=\"\" value=\"Profile\" onclick=\"window.location.href = 'profile.php'\" />
            <input class=\"hbutton hrbutton\" type=\"button\" style=\"\" value=\"Log Out\" onclick=\"window.location.href = 'logout.php'\" />
        </div>
        <br style=\"margin-top:25px;\">";
}

function GetPosts($list, $sList)
{
    $sList[0] = ' ';
    $s = "(";
    $s .= $sList . ")";

    $query = "SELECT * FROM post WHERE id IN" . $s;


    $connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
        Error("Couldn't prepare query:" . $query);
	}    
	if (!($stmt->execute()))
	{
		Error("Couldn't execute query:" . $query);
	}

    $result = $stmt->fetchAll();
    $toReturn = array();

    foreach ($result as $i)
    {
        $toReturn[$i['id']] = $i;
    }
    return $toReturn;
}

function GetList($name, &$s)
{
    $query = "SELECT list FROM users WHERE name=:name";

    $connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
        Error("Couldn't prepare query:" . $query);
	}

    $stmt->bindParam(":name", $name);
    
	if (!($stmt->execute()))
	{
		Error("Couldn't execute query:" . $query);
	}

	$result = $stmt->fetchAll();
    $result = $result[0]['list'];
    $s = $result;
    $listArr = array();
    $count = -1;

    for ($i = 0; $i < strlen($result); ++$i)
    {
        if ($result[$i] == ',')
        {
            ++$count;
            $listArr[$count] = "";
        } 
        else
        {
            $listArr[$count] .= $result[$i];
        }
    }
	return array_reverse($listArr);
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

$sList = "";

$list = GetList($name, $sList);

$posts = GetPosts($list, $sList);

$list = RemoveDead($posts, $list);

$toPrint = HtmlStart();
$toPrint .= PostHandle($posts, $list);
$toPrint .= HtmlEnd();
echo($toPrint);
?>