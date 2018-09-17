<!DOCTYPEHTML>
<html lang="sv">
	<head>
		<meta  charset="UTF-8"/>
		<title>Login</title>
	</head>
	<?php
	include 'globalVal.php'
	function CleanString($input)
	{
		$input = htmlspecialchars($input);
		return $input;
	}

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
			$username = CleanString($_POST('appartment'));
			$password = CleanString($_POST('password'));
			
		}
}

		
	?>
	</body>
</html>