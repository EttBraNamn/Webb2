function Navigate(dif) {
    //Getting the GET
    var location = window.location.href;
    var found = false;
    var i = 0;
    var search = "?";
    if (location.includes("&"))
    {
        search = "&";
    }

    //Loops untill end of string or finding of "?"
    for (; i < location.length && !found; ++i) {
        if (location[i] == search) {
            found = true;
        }
    }
    var num = 1;
    if (found) {
        num = parseInt(location.substring(i + 5), 10);
    }
    if (num >= 1) {
        num += dif;
    }
    var url = "index.php?page=" + num;
    window.location.href = url;
}
function MakePost() {
    var string = "<form action='upload.php' method='post' enctype='multipart/form-data'>";
    string += "<label>Subject:</label><br/><input type='text' style='width:100%'name='subject'/>"
    string += "<br/><label style='margin-top:10px;'>Body:</label><br/>"
    string += "<textarea name='text' class='input' id='comment'></textarea>";
    string += "<br /><input type='file' name='file' style='margin-top:5px;'/><br /><input class='indexbutton' type='submit' name='submit' /></form>";
    document.getElementById("input").innerHTML = string;
}