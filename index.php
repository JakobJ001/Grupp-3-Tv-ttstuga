<!DOCTYPEHTML>
<html lang="sv">
	<head>
		<meta  charset="UTF-8"/>
		<title>Login</title>
	</head>
	<?php

	function PrintForm()
	{
		echo("<form action=\"\" method=\"POST\">
			<input type=\"text\" value=\"Lägenhetsnummer\"/ name=\"appartment\">
			<input type=\"password\" value=\"Password\" name=\"password\"/>
			<input type=\"submit\"/>
			</form>");
	}
		echo("<body>");
		
		if (isEmpty($_POST))
		{
			PrintForm();
		}
		else 
		{
			

		}
}

		
	?>
	</body>
</html>