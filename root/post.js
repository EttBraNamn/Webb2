var bios = new Array();

function Not()
{
    document.getElementById("not").innerHTML = "<input class=\"hbutton\" type=\"button\" style=\"width:40%\" value=\"Main Page\" onclick=\"window.location.href = 'index.php'\" />";
}

function A(s) {
    //Checks if the comment is too short
    document.getElementById("error").innerHTML = "";
    if (s.length < 5) {
        document.getElementById("error").innerHTML = "The comment is too short.";
        return;
    }

    //Values to send via post
    var post = "comment=" + document.getElementById("comment").value;
    Ajax("comment.php", post, AjaxDone, function () {
        document.getElementById("error").innerHTML = "Couldn't upload comment";
    });
}

//Handles the ajax part, done and error are both functions that should be called if need be
function Ajax(url, post, done, error) {
    var ajax = new XMLHttpRequest();

    //Function that runs when the state changes
    ajax.onreadystatechange = function () {
        //Status == 200 and readystate == 4 means that it's done.
        if (this.status == 200 && this.readyState == 4) {
            done(ajax);
        }
    }
    ajax.onerror = error;

    //Getting the GET
    var location = window.location.href;
    var found = false;
    var i = 0;
    //Loops untill end of string or finding of "?"
    for (; i < location.length && !found; ++i) {
        if (location[i] == "?") {
            found = true;
        }
    }
    url += "?" + location.substring(i);
    ajax.open("POST", url, true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    ajax.send(post);
}

function AjaxDone(response) {
    //Returns null if there was no response

    if (response == null) {
        document.getElementById("error").innerHTML = "Couldn't upload comment";
        return;
    }
    var text = response.responseText;
    if (text != "Comment uploaded!")
    {
        document.getElementById("error").innerHTML = text;
    }
    else
    {
        Update();
    }
}
//Handles the values returned from a valid update
function UpdateHandle(ajax) {
    var xml = ajax.responseText;
    if (xml[0] != '<')
    {
        document.getElementById("error").innerHTML = "There was nothing to update";
        return;
    }
  
    var inner = document.getElementById("uploads").innerHTML;
    inner += xml;
    document.getElementById("uploads").innerHTML = inner;

    //Shifts the old date to the new one
     var arr = document.getElementsByTagName("time");
     var highest = "0";
     for (ob in arr)
     {
        if (highest < arr[ob].innerHTML)
        {
            highest = arr[ob].innerHTML;
        }
     }
     document.getElementById("date").value = highest;
}
//Called when an update is requested
function Update() {
    var post = "";
    post = "date=" + document.getElementById("date").value;
    
    //Calls UpdateHandle if it succeeds, alert if not
    Ajax("update.php", post, UpdateHandle, function () { alert("Couldn't update") });
}