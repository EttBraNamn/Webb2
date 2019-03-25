var bios = new Array();


function A(s) {
    //Checks if the comment is too short
    document.getElementById("error").innerHTML = "";
    if (s.length < 5) {
        document.getElementById("error").innerHTML = "The comment is too short.";
        return;
    }

    //Values to send via post
    var post = "comment=" + document.getElementById("comment").innerHTML;

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
    alert(url);
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
    Update();
}
//Handles the values returned from a valid update
function UpdateHandle(ajax) {
    var xml = ajax.responseText;
    if (xml.length < 10)
    {
        alert("WHY");
        return true;
    }
    alert(xml.length);
    xml = ajax.responseXml;

    var posts = xml.getElementsByTagName("base");
    posts = posts[0];
    var inner = document.getElementById("uploads").innerHTML;
    for (let post of posts) {
        inner += post.innerHTML;
    }
    document.getElementById("uploads").innerHTML = inner;

    //Shifts the old date to the new one
    var last = posts[posts.length - 1];
    document.getElementById("date").innerHTML = last.getElementByTagName("time").innerHTML;

}
//Called when an update is requested
function Update() {
    var post = "";
    post = "date=" + document.getElementById("date").value;
    
    //Calls UpdateHandle if it succeeds, alert if not
    Ajax("update.php", post, UpdateHandle, function () { alert("Couldn't update") });
}