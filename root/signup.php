<?php

include 'globalVal.php'

function Print($a = "none")
{
	$arr = array();
	
	$arr['badImageFile'] = "There's something wrong with the image";
	$arr['passwordShort'] = "The password is too short";
	$arr['PDO1'] = "The database can't be accessed";
	$arr['prepare1'] = "There's something wrong with the query";
	$arr['existingUser'] = "The username is already in use";
	$arr['tooLongName'] = "The username is too long";
	$arr['invalidChars'] = "The username is using unvalid characters";
	
	
	echo("<html>
<head>
<title>Sign up!</title>
</head>
<div style=\"width:10%;text-align:left;margin-left:45%;margin-top:5%\">
	<form action=\"register.php\" enctype=\"multipart/form-data\" name=\"form\" method=\"post\">
		<p>Username:</p>
		<input type=\"textbox\" name=\"name\"/>
		<br>
		<p>Password</p>
		<input type=\"password\" name=\"password\"/>
		<p>Image:</p>
		<input style=\"margin-bottom:10%\" type=\"file\" name=\"file\" value=\"File\"/>
		<br />
		<div style=\"background-color:red;margin-top:5px;margin-bottom:5px;width:100%\">
				<label>" . $arr[$a] . "</label>
		</div>
		<input type=\"submit\" name=\"register\">
	</form>
</div>
</html>
");
	
	
}


if (isset($_GET['error']))
{
	Print($_GET['error']);
}
else
{
	Print();
}

?>


