<?php

function AllowDBEdit()
{
	return true;
}

//Uses regex to check if there are no invalid characters in the name
function CheckValidName(&$s)
{
	if (strlen($s)> 30)
	{
		$s = "tooLongName";
		return false;
	}
	if (preg_match("/\[\^a-zA-Z0-9.\-_]/", $s)) 
	{
		$s = "invalidChars";
		return false;
	}
	return true;
}


//Checks if the file is an allowed filetype
function ImageCheckFiletype($path)
{
	$fin = new finfo(FILEINFO_MIME_TYPE);
	$allowed = array("image/png", "image/jpeg", "image/gif");
	$file = $fin->file($path);
	return in_array($file, $allowed);
}
?>