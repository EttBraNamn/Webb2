<?php

include 'globalVal.php';

function Write($a = "none")
{
	$arr = array();
	
	$arr['badImageFile'] = "There's something wrong with the image";
	$arr['passwordShort'] = "The password is too short";
	$arr['PDO1'] = "The database can't be accessed";
	$arr['prepare1'] = "There's something wrong with the query";
	$arr['existingUser'] = "The username is already in use";
	$arr['tooLongName'] = "The username is too long";
	$arr['invalidChars'] = "The username is using invalid characters";
	$arr['none'] = "";
	$arr[''] = '';
	
	echo("<!DOCTYPE html><html>
<head>
<title>Sign up!</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
</head>
<body>
<div class=\"login\">
	<form action=\"register.php\" enctype=\"multipart/form-data\" name=\"form\" method=\"post\">
		<p>Username:</p>
		<input type=\"text\" name=\"name\"/>
		<br>
		<p>Password</p>
		<input type=\"password\" name=\"password\"/>
		<p>Image:</p>
		<input style=\"margin-bottom:10%\" type=\"file\" name=\"file\" />
		<br />
		<div style=\"background-color:red;margin-top:5px;margin-bottom:5px;width:100%\">
				<label>" . $arr[$a] . "</label>
		</div>
		<input class=\"loginbutton\" type=\"submit\" value=\"Register\" name=\"register\">
	</form>
</div>
</body>
</html>
");
	
	
}


if (isset($_GET['error']))
{
	Write($_GET['error']);
}
else
{
	Write();
}

?>


