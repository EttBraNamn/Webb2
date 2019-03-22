function Navigate(dif) {
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
    var num = 1;
    if (found) {
        num = parseInt(location.substring(i + 5), 10);
		alert(num);
    }
    if (num >= 1) {
        num += dif;
    }
    var url = "index.php?page=" + num;
	alert(url);
    window.location.href = url;
}
function MakePost() {
    var string = "<form action='upload.php' method='post' enctype='multipart/form-data'>";
    string += "<label>Subject:</label><br/><input type='textbox' style='width:100%'name='subject'/>"
    string += "<br/><label style='margin-top:10px;'>Body:</label><br/>"
    string += "<textarea name='text' class='input' id='comment'></textarea>";
    string += "<br /><input type='file' name='file' style='margin-top:5px;'/><br /><input type='submit' style='margin-top:5px;' name='submit' /></form>";
    document.getElementById("input").innerHTML = string;
}