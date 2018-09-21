<?php
	session_start();
	header('Content-type: text/html; charset=utf-8');
	
	//INCLUDES
	include 'globalVal.php';
	include 'sql.php';
		
	//Prints out the basic loginform
	function PrintForm($error = false)
	{
		echo("<html lang=\"sv\">
			<head>
			<meta charset=\"UTF-8\">
			<title>Login</title>
			</head><body>");
		if ($error)
		{
			echo ("<p>Fel lägenhetsnummer eller användarnamn</p>");
		}
		echo("<form action=\"\" method=\"POST\">
			<input type=\"text\" value=\"Lägenhetsnummer\"/ name=\"appartment\">
			<input type=\"password\" value=\"Password\" name=\"password\"/>
			<input type=\"submit\"/>
			</form></body></html>");
	}
	
	
	/*
	#####################################
	--------START OF GLOBAL CODE---------
	#####################################
	*/
	//If no post call has been made
	if (empty($_POST))
	{
		PrintForm();
		return;
	}
	else
	{
		
		$appartment = CleanString($_POST['appartment']);
		$password = CleanString($_POST['password']);
				
		$rowCount;
		//SqlRequest from sql.php		
		$result = SqlRequest("SELECT appartment password FROM users", DBUSERS, $rowCount);
		
		$curr = false;
		for($i = 0; $i < $rowCount && !$curr; ++$i)
		{
			var_dump($reslut[$i]["appartment"]);
			if ($appartment == $result[$i][0])
			{
				$curr = $result[$i];
				echo("found");
			}
		}
		if (!$curr)
		{
			PrintForm(true);
			return;
		}
		if ($password != $curr["password"])
		{
			PrintForm(true);
			return;
		}
		if ($appartment == "admin")
		{
			session_unset();
			$_SESSION['appartment'] = $appartment;
			$_SESSION['password'] = $curr['password'];
			header('Location: admin.php');
			exit();
		}
		else
		{
			session_unset();
			$_SESSION['username'] = $appartment;
			$_SESSION['password'] = $curr['password'];
			header('Location: bokning.php');
			exit();
		}

		
	}		
	
?>
