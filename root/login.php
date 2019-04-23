<?php

function Write($a = "n")
{
	$arr = array();
	
	$arr['wrong'] = "Incorrect username or password.";
	$arr['empty'] = "You need to enter both fields.";
	$arr['error'] = "Something went wrong with the database";
	$arr['n'] = "";
	echo("<html>

	<head>
        <meta charset=\"utf-8\" />
        <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
	    <title>Login</title>
	</head>

    <body>
        <div class=\"login\" >
            <form action=\"logincheck.php\" method=\"post\">
                <p>Username:</p>
                <input type=\"text\" style=\"width:100%\" onchange=\"Valid()\" name=\"name\" id=\"name\"/>
                <p>Password:</p>
                <input type=\"password\" onchange=\"Valid()\" style=\"width:100%\" name=\"password\" id=\"password\" />
                <br />
				<div style=\"background-color:red;margin-top:5px;margin-bottom:5px\">
				<label>". $arr[$a] . "</label>
                </div>
                <p style=\"font-size:15px;\">Don't have an account?<br/> <a href=\"signup.php\">Sign up!</a></p>
                <input type=\"submit\" disabled=\"true\" name=\"submit\" id=\"submit\"/>
            </form>
        </div>
        <script>

            function Valid()
            {
                document.getElementById(\"submit\").disabled = false;
                var name = document.getElementById(\"name\").value;

                if (name.length < 1)
                {
                    document.getElementById(\"submit\").disabled = true;
                }

                var password = document.getElementById(\"password\").value;

                if (password.length < 1)
                {
                    document.getElementById(\"submit\").disabled = true;
                }
            }

        </script>
    </body>

</html>");
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