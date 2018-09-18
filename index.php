<!DOCTYPEHTML>
<html lang="sv">
	<head>
		<meta  charset="UTF-8"/>
		<title>Login</title>
	</head>
	<?php

	//INCLUDES
	include 'globalVal.php';
	include 'sql.php';
		
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
	
	
	/*
	#####################################
	--------START OF GLOBAL CODE---------
	#####################################
	*/
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
			
			//SqlRequest from sql.php
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
</html><!DOCTYPEHTML>
<html lang="sv">
	<head>
		<meta  charset="UTF-8"/>
		<title>Login</title>
	</head>
	<?php

	//INCLUDES
	include 'globalVal.php';
	include 'sql.php';
		
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
	
	/*
	#####################################
	--------START OF GLOBAL CODE---------
	#####################################
	*/
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
			
		//SqlRequest from sql.php
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