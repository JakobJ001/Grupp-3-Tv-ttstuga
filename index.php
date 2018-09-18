
<html lang="sv">
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
	</head>
	<?php
	
	header('Content-type: text/html; charset=utf-8');
	
	//INCLUDES
	include 'globalVal.php';
	include 'sql.php';
		
	//Prints out the basic loginform
	function PrintForm($error = false)
	{
		if ($error)
		{
			echo ("<p>Fel lägenhetsnummer eller användarnamn</p>");
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
	if (empty($_POST))
	{
		PrintForm();
		return;
	}
			
	$appartment = CleanString($_POST['appartment']);
	$password = CleanString($_POST['password']);
			
	$rowCount;
	//SqlRequest from sql.php		
	$result = SqlRequest("SELECT appartment password FROM users", DBUSERS, $rowCount);
			
	$curr = false;
	for($i = 0; $i < $rowCount && !$curr; ++$i)
	{
		$array = mysql_fetch_array($result[i]);
		if ($username == $result[$i]['username'])
		{
			$curr = $result[$i];
		}
	}
	if (!$curr)
	{
		PrintForm(true);
		return;
	}
?>
</body>
</html>