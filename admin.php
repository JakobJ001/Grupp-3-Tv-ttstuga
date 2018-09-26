<?php
include 'globalVal.php';
include 'sql.php';


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


if (!empty($_POST))
{
	if (isset($_POST['updateUser']))
	{
		
		return;
	}
	if (isset($_POST['addUser']))
	{
		return;
	}
	if (isset($_POST['deleteUser']))
	{
		
		return;
	}	
}

$toPrint = "<html><meta charset=\"UTF-8\"><head><title>Admin</title>";




?>