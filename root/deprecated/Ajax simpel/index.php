<html>
<head>
<title>Test</title>
<meta charset="UTF-8"/>
</head>

<body>
<p id="t">AHHH</p>
<input type="button" onclick="load()" value="Click me"></input>
</body>
<script>

function load()
{
	alert("what");
	var req = new XMLHttpRequest();
	req.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      handle(this);
    }};
	req.open("GET", "test.php", true);
	req.send();

}

function handle(req)
{
	document.getElementById("t").innerHTML = req.responseText;
	alert(req.responseText);
}
</script>
</html>
