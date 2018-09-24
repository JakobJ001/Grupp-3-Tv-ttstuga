<?php
	session_start();
	header('Content-type: text/html; charset=utf-8');
	
	//INCLUDES
	include 'globalVal.php';
	include 'sql.php';
		
	//Prints out the basic loginform
	function PrintForm($error = false)
	{
		$toPrint = "<html lang=\"sv\"><head><meta charset=\"UTF-8\"><title>Login</title></head><body>";
		if ($error)
		{
			$toPrint .= "<p>Fel lägenhetsnummer eller användarnamn</p>";
		}
		$toPrint .= "<form action=\"\" method=\"POST\"><input type=\"text\" value=\"Lägenhetsnummer\"/ name=\"appartment\"><input type=\"password\" value=\"Password\" name=\"password\"/><input type=\"submit\"/></form></body></html>";
		
		echo($toPrint);
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
		           
		if ($result == ERROR)
		{
			echo("Någonting blev fel");
			return;
		}
		else if ($result == NOTHING)
		{
			echo("Någonting fel med databasen:(");
			return;
		}
		
		$curr = false;
		for($i = 0; $i < $rowCount && !$curr; ++$i)
		{
			if ($appartment == $result[$i][0])
			{
				$curr = $result[$i];
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
			header("Location: admin.php");
			return;
		}
		else
		{
			session_unset();
			$_SESSION['appartment'] = $appartment;
			$_SESSION['password'] = $curr['password'];
			header("Location: bokning.php");
			return;
		}
	}		
	
?>
