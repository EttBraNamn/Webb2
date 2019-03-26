<?php

function GetList($name)
{
    $query = "SELECT list FROM users WHERE name=:name";

    $connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);

	if (!($stmt = $connect->prepare($query)))
	{
		return "error";
	}

    $stmt->bindParam(":name", $name);
    
	if (!($stmt->execute()))
	{
		Error("Couldn't execute query");
	}

	$result = $stmt->fetchAll();
    $result = $result[0]['list'];

    $listArr = array();
    $count = -1;
    for ($i = 0; $i < strlen($result); ++$i)
    {
        if ($result[$count] == ',')
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

$list = GetList($name);

?>