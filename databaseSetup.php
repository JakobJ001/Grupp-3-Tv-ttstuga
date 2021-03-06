<?php

include "globalVal.php";

function Database($query)
{
	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME,
		USERNAME, PASSWORD);
		
		if (!($stmt = $connect->prepare($query)))
		{
			echo("Couldn't prepare query: " . $query);
			exit();
		}
		if (!($stmt->execute()))
		{
			echo("Couldn't execute query: " . $query);
			exit();
		}
	}
	catch (EXCEPTION $e)
	{
		echo("Query: " . $query . " failed<br>" . $e);
		exit();
	}
	
	
}


function ExecuteQuery($query)
{
	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS,
		USERNAME, PASSWORD);
		
		if (!($stmt = $connect->prepare($query)))
		{
			echo("Couldn't prepare query: " . $query);
			exit();
		}
		if (!($stmt->execute()))
		{
			echo("Couldn't execute query: " . $query);
			exit();
		}
	}
	catch (EXCEPTION $e)
	{
		echo("Query: " . $query . " failed<br>" . $e);
		exit();
	}
	
	
}


//Database
$query = "CREATE DATABASE " . DBUSERS . " COLLATE utf8_swedish_ci;";

Database($query);

//Users table
$query = "CREATE TABLE users(
		appartment varchar(6) UNIQUE COLLATE utf8_swedish_ci, 
		password varchar(72) COLLATE utf8_swedish_ci,
		name varchar(100) COLLATE utf8_swedish_ci, 
		picture varchar(100) COLLATE utf8_swedish_ci,
		booked datetime);";

ExecuteQuery($query);

//booked table
$query = "CREATE TABLE booked(
	id int(11) unsigned NOT NULL AUTO_INCREMENT,
	date datetime,
	appartment varchar(6) COLLATE utf8_swedish_ci,
	PRIMARY KEY (id)
	);";

ExecuteQuery($query);

//Makes the admin user
header("Location: adminSetup.php");

return;
?>