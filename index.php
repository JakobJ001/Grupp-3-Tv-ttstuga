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
		$toPrint .= "<form action=\"index.php\" method=\"POST\"><input type=\"text\" value=\"Lägenhetsnummer\"/ name=\"appartment\"><input type=\"password\" value=\"Password\" name=\"password\"/><input type=\"submit\"/></form></body></html>";
		
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
		//PASSWORD START
		$appartment = CleanString($_POST['appartment']);
		$password = CleanString($_POST['password']);
		
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=appdb", USERNAME, PASSWORD);
		if (!($stmt = $connect->prepare("SELECT * FROM users WHERE appartment = '$appartment'"))) 			
		{	
			return;
		}
		if (!$stmt->execute())
		{
			return;
		}
		$user =  $stmt->fetch();
		
		if (!password_verify($password , $user['password']))
		{
			var_dump($user);
			echo($password);
			echo($curr["password"]);
			PrintForm(true);
			return;
		}
		//PASSWORD END
		if ($appartment == "admin")
		{
			session_unset();
			$_SESSION['appartment'] = $appartment;
			$_SESSION['password'] = $user['password'];
			header("Location: admin.php");
			return;
		}
		else
		{
			session_unset();
			$_SESSION['appartment'] = $appartment;
			$_SESSION['password'] = $user['password'];
			header("Location: bokning.php");
			return;
		}
	}		
	
?>

