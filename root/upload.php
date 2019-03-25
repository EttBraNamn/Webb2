<?php
function FileHandling($name)
{
   
	$tempFile = file_get_contents($_FILES['file']['tmp_name']);
	$filePath = "post/" . $name;
	$writeToo = fopen(__DIR__ . "/" . $filePath, "w");
	fwrite($writeToo, $tempFile);
	fclose($writeToo);

}

function Error($s)
{
    header("location: index.php?error=" . $s . "&page=1");
    exit();
}

include 'security.php';
include 'globalVal.php';

session_start();
//Makes sure that a post has been made
if (!isset($_POST['text']) || !isset($_POST['subject']) || !isset($_FILES['file']))
{
    Error("empty");
}
//Makes sure that the user is logged in
if (!isset($_SESSION['name']) || VerifyUser($_SESSION['name'], $_SESSION['password']) != "correct")
{
    Error("user");
}

if (!ImageCheckFiletype($_FILES['file']['tmp_name']))
{
    Error("image");
}
//Variables need for the upload
$name = $_SESSION['name'];

$fin = new finfo(FILEINFO_MIME_TYPE);
$fileType = $fin->file($_FILES['file']['tmp_name']);
$i = 0;
for ($stop = false; $i < strlen($fileType) && !$stop; ++$i)
{
	if ($fileType[$i] == '/')
	{
		$stop = true;
	}
}
$fileType = substr($fileType, $i);

$subject = CleanInput($_POST['subject']);
$body = CleanInput($_POST['text']);
$time = date("Y-m-d H:i:s", time());

if (strlen($subject) < 1 || strlen($body) < 1)
{
    Error("input");
}

//Uploads to database
$query = "INSERT INTO post (date ,name, subject, body, image) VALUES (:date,:name, :subject, :body, :image)";
if (!$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD))
{
	Error("database");
}

if(!$stmt = $connect->prepare($query))
{
	Error("database");
}

$stmt->bindParam(":name", $name);
$stmt->bindParam(":subject", $subject);
$stmt->bindParam(":date", $time);
$stmt->bindParam(":image", $fileType);
$stmt->bindParam(":body", $body);

if (!$stmt->execute())
{
	Error("database");
}

//Gets the id of the post
$query = "SELECT id FROM post WHERE date=:date";

if(!$stmt = $connect->prepare($query))
{
	Error("database");
}
$stmt->bindParam(":date", $time);
if (!$stmt->execute())
{
	Error("database");
}

$result = $stmt->fetchAll();

if(empty($result))
{
    Error("database");
}

$id = $result[0]['id'];
//Saves the image
FileHandling($id . ".". $fileType);

//Get's the preexisting list
$query = "SELECT list from users WHERE name=:name";

if(!$stmt = $connect->prepare($query))
{
	Error("database");
}
$stmt->bindParam(":name", $time);
if (!$stmt->execute())
{
	Error("database");
}

$list = $stmt->fetchAll();
if (empty($list))
{
	$list = "";
}
else
{
	$list = $list[0];
}
$list .= "," . $id;
//Updates the list to feature the new post aswell
$query = "UPDATE users SET list=\"" . $list .  "\" WHERE name=:name";
if(!$stmt = $connect->prepare($query))
{
	Error($query);
}

$stmt->bindParam(":name", $name);
if (!$stmt->execute())
{
	Error($query);
}

//Open the specific post
header("location: post.php?id=" . $id);
exit();
?>  