﻿<?php
		
	//Prints out the basic loginform
	function PrintForm($error = false)
	{
		$toPrint = file_get_contents("startIndex.txt");
		if ($error)
		{
			$toPrint .= "<label style=\"color:red;margin-top:5%;\">Fel lägenhetsnummer eller lösenord</label>";
		}
		$toPrint .= file_get_contents("endIndex.txt");
		
		echo($toPrint);
	}
	
	
	/*
	#####################################
	--------START OF GLOBAL CODE---------
	#####################################
	 */



	//If no post call has been made
	
	session_start();
	
	//INCLUDES
	include 'globalVal.php';
	include 'sql.php';
	
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
		
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
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

