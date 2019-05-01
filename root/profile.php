<?php
function HtmlEnd()
{
    return "<input class=\"aabutton\" type=\"button\" value=\"Edit\" onclick=\"EditEnable();\"/>
    <input type=\"button\" value=\"Delete Profile\" class=\"aabutton\" onclick=\"DeleteAsk();\"/>
    <div id=\"delete\">
    </div>
    </div>
</body>
</html>";
}

function HtmlStart()
{
    return "<!DOCTYPE html5><html>
    <head>
        <title>Profile</title>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
        <meta charset=\"UTF-8\" />
    </head>
    <body>
        <script src=\"profile.js\"></script>
        <div class=\"header\">
            <input class=\"hbutton\" type=\"button\" value=\"Main Page\" onclick=\"window.location.href = 'index.php'\" />
            <input class=\"hbutton\" type=\"button\" value=\"Post History\" onclick=\"window.location.href = 'list.php'\" />
            <input class=\"hbutton hrbutton\" type=\"button\"  value=\"Log Out\" onclick=\"window.location.href = 'logout.php'\" />
        </div>
        <br>
        <div class=\"aa\">
        <form action=\"editProfile.php\" enctype=\"multipart/form-data\" name=\"form\" method=\"post\">
            <div class=\"information\">";
}

function HandleUser($user)
{
    $toReturn = "<p><b>Name:</b> " . $user['name'] . "</p>";
    $toReturn .= "<p>Profile Picture:</p><img alt=\"Missing image\" style=\"max-width:10%\" src=\"pic/" . $user['name'] . ".jpg\"/>";
    $toReturn .= "<div id=\"image\"></div><p>Bio:</p><div id=\"text\"></div><p id=\"original\">" . $user['bio'] . "</p>";
    $toReturn .= "</div><div id=\"submitbutton\"></div></form>";
    return $toReturn;
}

function Error($s)
{
    echo($s);
    exit();
}

include 'globalVal.php';
include 'security.php';

session_start();

if (!isset($_SESSION['name']))
{
    header("location: login.php");
    exit();
}
if (VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    header("location: login.php");
    exit();
}

$name = $_SESSION['name'];

$query = "SELECT * FROM users WHERE name=:name";

$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);
//If it can't be prepared
if (!$stmt = $connect->prepare($query))
{
	Error("Couldn't prepare query" . $query);
}
$stmt->bindParam(":name", $name);
//If it can't be executed
if(!$stmt->execute())
{
    Error("Couldn't execure query" . $query);
}

$result = $stmt->fetchAll();

if (empty($result))
{
    Error("The user is already deleted");
}

$toPrint = HtmlStart();
$toPrint .= HandleUser($result[0]);
$toPrint .= HtmlEnd();

echo($toPrint);
?>