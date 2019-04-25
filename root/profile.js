//Enables the input fields needed for the editing of values
function EditEnable()
{
    document.getElementById("image").innerHTML = "<p>New Image:</p><input type='file' name='file'/>";

    var s = document.getElementById("original").innerHTML;
    document.getElementById("text").innerHTML = "<textarea name='text' style='width:20%;height:10%;'>" + s + "</textarea>"
    document.getElementById("original").style.visibility = "hidden";
    document.getElementById("submitbutton").innerHTML = "<input class='aabutton' type='submit' name='submit'/>"
}
//Asking for confirmation before a users account is deleted
function DeleteAsk()
{
    document.getElementById("delete").innerHTML = "<form action='deleteUser.php' method='post'><p>Enter your username to confirm (this is going to delete all posts and comments aswell)</p><input type='text' name='name'/><input class='inputbutton' type='submit' name='submit' value='Delete'/></form>";
}