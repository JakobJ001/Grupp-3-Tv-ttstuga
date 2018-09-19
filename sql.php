<?php
include 'globalVal.php';

//Function that returns an array requests if succesfull. Otherwise returns ERROR or NOTHING depending
function SqlRequest($query, $db , &$rowCount = "")
{
	
	try
	{
		//SERVERNAME USERNAME and PASSWORD are all values defined in globalVal.php
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=$db", USERNAME, PASSWORD);
		//We do not want to continue if something goes wrong
		if (!($stmt = $connect->prepare($query))) 			{
			return ERROR;
		}
		//We do not want to continue if something goes wrong
		if(!($stmt->execute()))
		{
			return ERROR;
		}
		$rowCount = $stmt->rowCount();
		$toReturn = "";
		//Checking if there's nothing to return
		if(!($toReturn = $stmt->fetchAll()))
		{
			return NOTHING;
		}
		return $toReturn;
	}
	catch (EXCEPTION $e)
	{
		return ERROR;
	}
}
//Removing any odd inputs done by the user
function CleanString($input)
{
	$input = htmlspecialchars($input);
	return $input;
}
?>