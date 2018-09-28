<?php
include 'globalVal.php';
include 'sql.php';

function SetupFile($file, $name)
{
	$fileName = $file['name'];
	
	$fileType = "";
	$keepGoing = true;
	
	for ($i = strlen($fileName); $i != 0 && $keepGoing;)
	{
		if ($fileName[--$i] != ".")
		{
			$filetype .= $fileName[--$i];
		}
		else
		{
			$keepGoing = false;
		}
	}
	
	if ($keepGoing)
	{
		return false;
	}
	
	$check = array("gnp", "gpj", "gepj", "pmb");
	$type = false;
	for ($i = 0; i < 4 && !$type; ++$i)
	{
		if ($fileType == $check[$i])
		{
			$type = strrev($check[$i]);
		}
	}
	
	if (!$type)
	{
		return false;
	}
	$tempFile = file_get_contents($file['tmp_name']);
	$filePath = "pic/" . $name . $type;
	$writeToo = fopen($filePath, "w");
	fwrite($writeToo, $tempFile);
	fclose($writeToo);
	return $filePath;
}

function DeleteUser()
{
	$appartment = $_POST['appartment'];
	
	$query = "DELETE FROM users WHERE appartment IN $appartment";

	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSER, USERNAME, PASSWORD);
		
		
		if (!($smtm = $connect->prepare($query)))
		{
			throw;
		}
		if (!($smtm->execute()))
		{
			throw;
		}
		return "User deleted";
	}
	catch (EXCEPTION $e)
	{
		return "Couldn't delete from the database";
	}
}

function AddUser()
{
	$appartment = $_POST['appartment'];
	$name = $_POST['name'];
	$filePath = SetupFile($_FILE['file']);
	$password = password_hash($_POST['password']);
	
	
	if (!$filePath)
	{
		return "Something's wrong with the file";
	}
	
	$query = "INSERT INTO users (appartment, password, name, picture, booked) VALUES ($appartment ,$password ,$name , $filePath, NULL, NULL)";
	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSER, USERNAME, PASSWORD);
		
		
		if (!($smtm = $connect->prepare($query)))
		{
			throw;
		}
		if (!($smtm->execute()))
		{
			throw;
		}
		return "User added";
	}
	catch (EXCEPTION $e)
	{
		return "Couldn't update the database";
	}
	
}


function Print($results, $toAlert)
{
	$toPrint = file_get_contents(startAdmin.txt);
	
	for($i = 0; i < $result; ++$i)
	{
		$toPrint .= "<tr><form action=\"admin\" method=\"POST\" enctype=\"multipart/form-data\"><th name=\"appartment\">" . $result[$i]['appartment'] . "</th>";
		$toPrint .= "<th>" . $result[$i]['name'] . "</th>";
		$toPrint .= "<th><img src=\"" . $result[$i]['picture'] ."\"/></th>";
		$toPrint .= "<th>" . $result[$i]['booked'] . "</th>";
		$toPrint .= "<th><input type=\"submit\" value=\"Radera\" name=\"remove\"/></th></form></tr>";
	}
	
	if ($toAlert)
	{
		$toPrint .= "</table></body><script>alert(\"" . $toAlert . "\");</script></html>";
	}
	else
	{
		$toPrint .= "</table></body></html>";
	}
	echo($toPrint);
}


function SessionCheck()
{
	session_start();
	//If no session exist
	if(empty($_SESSION))
	{
		header("Location: index.php");
		exit();
	}
}


SessionCheck();
$toAlert = NULL;

if (!empty($_POST))
{
	if (isset($_POST['addUser']))
	{
		$toAlert = AddUser();
	}
	if (isset($_POST['deleteUser']))
	{
		$toAlert = DeleteUser();
	}	
}


$rowCount;
$result = SqlRequest("SELECT * FROM users", DBUSERS, $rowCount);
		
$curr = false;

for($i = 0; $i < $rowCount && !$curr; ++$i)
{
	if ($result[$i][0] == $_SESSION['appartment'])
	{
		$curr = $result[$i];
	}
}
//Verifying that the appartmentnumber and password are both valid
if(!$curr || $_SESSION['password'] != $curr[1] && $_SESSION['password'] == "admin")
{
	header("Location: index.php");
	return;
}


Print($result, $toAlert);

?>