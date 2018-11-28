<?php
include 'globalVal.php';
include 'sql.php';

//Handles the file creation and saving
function SetupFile($file)
{
	
	$fileName = $file['name'];
	$fileType = "";
	$keepGoing = true;
	
	//loops thorugh the filename to get the file extention. Stops after a "." is found
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
	}
	
	//If no filename was found
	if ($keepGoing)
	{
		return false;
	}
	$check = array("gnp", "gpj", "gepj", "pmb");
	$type = false;
	for ($i = 0; $i < 4 && !$type; ++$i)
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
	$filePath = "pic/" . $fileName;
	$writeToo = fopen(__DIR__ . "/" . $filePath, "w");
	fwrite($writeToo, $tempFile);
	fclose($writeToo);
	return $filePath;
}

//updates the user
function UpdateUser()
{
	$query = "UPDATE users SET ";
	$updateNeeded = false;
	$second = false;
	if (!empty($_POST['name']))
	{
		$query .= "name='" . CleanString($_POST['name'])."' ";
		$updateNeeded = true;
		$second = true;
	}
	if (!empty($_POST['file']))
	{
		if ($second)
		{
			$query .= ", ";
		}
		$filePath = SetupFile($_FILES['file']);
		$query .= "picture='$filePath' ";
		$updateNeeded = true;
		$second = true;
	}
	if (!empty($_POST['password']))
	{
		if ($second)
		{
			$query .= ", ";
		}
		$password = password_hash(CleanString($_POST['password']), PASSWORD_DEFAULT);
		$query .= "password='$password' ";
		$updateNeeded = true;
	}
	
	$appartment = $_POST['appartment'];
	
	$query .= "WHERE appartment='$appartment'";
	
	if (!$updateNeeded)
	{
		return "Nothing to update";
	}
	try
	{
		//Connects to mysql database
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
		
		
		if (!($stmt = $connect->prepare($query)))
		{
			return "Couldn't prepare query";
		}
		if (!($stmt->execute()))
		{
			return "Couldn't execute query $query";
		}
		return "User updated";
	}
	catch (EXCEPTION $e)
	{
		return "Couldn't update the database";
	}
	
}

//Deletes a user
function DeleteUser()
{
	$appartment = CleanString($_POST['appartment']);
	
	$query = "DELETE FROM users WHERE appartment = '$appartment'";

	try
	{
		//Connects to mysql database
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
	$appartment = CleanString($_POST['appartment']);
	$name = CleanString($_POST['name']);
	$filePath = SetupFile($_FILES['file']);
	
	if ("" == $_POST['appartment'])
	{
		return "Missing appartmentnumber";
	}
	else if ("" == $_POST['name'])
	{
		return "Missing name";
	}
	
	//Encrypts the password with standard values
	$password = password_hash(CleanString($_POST['password']), PASSWORD_DEFAULT);
	
	if (!$filePath)
	{
		return "Something's wrong with the file";
	}
	
	//Booked is 0000-00-00 00:00:00
	$query = "INSERT INTO users (appartment, password, name, picture, booked) VALUES ('$appartment' ,'$password', '$name','$filePath', 'NULL');";
	try
	{
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

//Prints the whole site including all the users
function PrintSite($result, $toAlert)
{
	//Start of the document
	$toPrint = file_get_contents("startAdmin.txt");
	
	//Account table
	for($i = 0; $i < count($result); ++$i)
	{
		$toPrint .= "<tr><td style=\"width:20%;height:50px;padding-top:2%;\">" . $result[$i]['appartment']. "</td>";
		$toPrint .= "<td style=\"width:20%;height:50px;padding-top:2%;\">" . $result[$i]['name'] . "</td>"; 
		$toPrint .= "<td style=\"width:20%;height:50px;\"><img style=\"width:75px;height:75px;\" src=\"" . $result[$i]['picture'] ."\"/></td>";
		$toPrint .= "<td style=\"width:20%;height:50px;padding-top:2%;\">" . $result[$i]['booked'] . "</td>";
		$toPrint .= "<td style=\"width:20%;height:50px;padding-top:2%;\"><form action=\"admin.php\" method=\"POST\" ><input type=\"submit\" class=\"btn btn-danger\" value=\"Radera\" name=\"remove\"/>";
		$toPrint .= "<input type=\"HIDDEN\" value=\"" . $result[$i]['appartment'] . "\" name=\"appartment\"/></td></form></tr>";
	}
	if ($toAlert)
	{
		$toPrint .= "</table></body><script>alert(\"" . $toAlert . "\");</script></html>";
	}
	else
	{
		$toPrint .= "</table></div></body></html>";
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
if(!$curr || $_SESSION['password'] != $curr['password'] || $_SESSION['appartment'] != "admin")
{
	header("Location: index.php");
	return;
}

$toAlert = NULL;
if (!empty($_POST))
{
	if (isset($_POST['add']))
	{
		$toAlert = AddUser();
		
	}
	if (isset($_POST['update']))
	{
		$toAlert = UpdateUser();
		

	}
	if (isset($_POST['remove']))
	{
		$toAlert = DeleteUser();
	}
	$result = SqlRequest("SELECT * FROM users", DBUSERS, $rowCount);

}




PrintSite($result, $toAlert);

?>
 
