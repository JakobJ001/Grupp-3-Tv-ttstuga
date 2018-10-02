<?php
include 'globalVal.php';
include 'sql.php';

function SetupFile($file, $name)
{
	$fileName = $file['name'];
	echo($fileName);
	$fileType = "";
	$keepGoing = true;
	
	for ($i = strlen($fileName); $i != 0 && $keepGoing;)
	{
		--$i;
		if ($fileName[$i] != ".")
		{
			$fileType .= $fileName[$i];
		}
		else
		{
			$keepGoing = false;
		}
		echo($i);
	}

	echo("for loop done");
	if ($keepGoing)
	{
		var_dump($fileName);
		return false;
	}
	echo("keep going true");
	$check = array("gnp", "gpj", "gepj", "pmb");
	$type = false;
	for ($i = 0; $i < 4 && !$type; ++$i)
	{
		if ($fileType == $check[$i])
		{
			$type = strrev($check[$i]);
		}
	}
	echo("second for loop done");
	if (!$type)
	{
		var_dump($fileType);
		return false;
	}
	echo("type is right");
	$tempFile = file_get_contents($file['tmp_name']);
	$filePath = "pic/" . $name . $type;
	$writeToo = fopen($filePath, "w");
	fwrite($writeToo, $tempFile);
	fclose($writeToo);
	return $filePath;
}
//Deletes a user
function DeleteUser()
{
	$appartment = $_POST['appartment'];
	
	$query = "DELETE FROM users WHERE appartment = $appartment";

	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
		
		
		if (!($stmt = $connect->prepare($query)))
		{
			return "Couldn't prepare query";
		}
		if (!($stmt->execute()))
		{
			return "Couldn't execute query $query";
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
	$filePath = SetupFile($_FILES['file']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	
	var_dump($_POST['password']);
	var_dump($password);
	if (!$filePath)
	{
		return "Something's wrong with the file";
	}
	
	$query = "INSERT INTO users (appartment, password, name, picture, booked) VALUES (\'$appartment\' ,\'$password \', \'$name \', \'$filePath\', \'NULL\');";
	try
	{
		echo($query);
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
		
		
		if (!($smtm = $connect->prepare($query)))
		{
			return "Couldn't prepare query";
		}
		if (!($smtm->execute()))
		{
			return "Couldn't prepare query";
		}
		return "User added";
	}
	catch (EXCEPTION $e)
	{
		return "Couldn't update the database";
	}
	
}


function PrintSite($result, $toAlert)
{
	$toPrint = file_get_contents("startAdmin.txt");
	for($i = 0; $i < count($result); ++$i)
	{
		$toPrint .= "<tr><th>" . $result[$i]['appartment']. "</th>";
		$toPrint .= "<th>" . $result[$i]['name'] . "</th>"; 
		$toPrint .= "<th><img src=\"" . $result[$i]['picture'] ."\"/></th>";
		$toPrint .= "<th>" . $result[$i]['booked'] . "</th>";
		$toPrint .= "<th><form action=\"admin.php\" method=\"POST\" ><input type=\"submit\" value=\"Radera\" name=\"remove\"/>";
		$toPrint .= "<input type=\"HIDDEN\" value=\"" . $result[$i]['appartment'] . "\"/></th></form></tr>";
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


//Checking if there's a valid session
function SessionCheck()
{
	session_start();
	//If no session exist
	if(!isset($_SESSION['password']))
	{
		header("Location: index.php");
		exit();
	}
}


/*
	#####################################
	--------START OF GLOBAL CODE---------
	#####################################
*/

SessionCheck();
$toAlert = NULL;

if (!empty($_POST))
{
	if (isset($_POST['add']))
	{
		$toAlert = AddUser();
	}
	
	if (isset($_POST['remove']))
	{
		$toAlert = DeleteUser();
	}	
}


$rowCount = "";
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
if(!$curr || $_SESSION['password'] != $curr['password'] && $_SESSION['password'] == "admin")
{
	header("Location: index.php");
	return;
}

echo("password");
PrintSite($result, $toAlert);

?>
 