<!DOCTYPEHTML>
<html lang="sv">
	<head>
		<meta  charset="UTF-8"/>
		<title>Login</title>
	</head>
	<?php
	include 'globalVal.php'
	
	//Function that returns an array requests if succesfull. Otherwise returns ERROR or NOTHING depending
	function SqlRequest($query, $db)
	{
		try
		{
			//SERVERNAME USERNAME and PASSWORD are all values defined in globalVal.php
			$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=$db", USERNAME, PASSWORD);

			//We do not want to continue if something goes wrong
			if (!($stmt = $connect->prepare($query))) 			{
				return ERROR;
			}

			//We do not want to continue if something goes wrong
			if(!($stmt->execute()) 
			{
				return ERROR;
			}
			$toReturn = "";
			//Checking if there's nothing to return
			if(!($toReturn = stmt->fetchAll())
			{
				return NOTHING;
			}
			return $toReturn;
		}
		catch
		{
			return ERROR;
		}
	}
	//Removing any odd inputs done by the user
	function CleanString($input)
	{
		$input = htmlspecialchars($input);
		return $input;
	}
	
	//Prints out the basic loginform
	function PrintForm($error = false)
	{
		if ($error)
		{
			echo ("<p>Fel lägenhetsnummer eller användarnamn</p>")
		}
		echo("<form action=\"\" method=\"POST\">
			<input type=\"text\" value=\"Lägenhetsnummer\"/ name=\"appartment\">
			<input type=\"password\" value=\"Password\" name=\"password\"/>
			<input type=\"submit\"/>
			</form>");
	}
	
	
	//START OF GLOBALCODE//
	echo("<body>");
		
	//If no post call has been made
	if (isEmpty($_POST))
	{
			PrintForm();
	}
	else 
	{
			$appartment = CleanString($_POST('appartment'));
			$password = CleanString($_POST('password'));
	
			$result = SqlRequest("SELECT appartment password FROM users", DBUSERS);
			
			$curr = false;
			for($i = 0; $i < count($result) && !$curr; ++$i)
			{
				if ($username == $result[$i] => "appartment")
				{
					$curr = $result[$i];
				}
			}
			if (!$curr)
			{
				PrintForm(true);
				return;
			}
			
	}	
	?>
	</body>
</html>