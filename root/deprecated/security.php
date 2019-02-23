<?php


//Checks if the file is an allowed filetype
function ImageCheckFiletype($path)
{
	$fin = new finfo(FILEINFO_MIME_TYPE);
	$allowed = array("image/png", "image/jpeg", "image/gif");
	$file = $fin->file($path);
	return in_array($file, $allowed);
}
?>