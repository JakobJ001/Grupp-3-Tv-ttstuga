<?php

include "globalVal.php";

$query = "DROP DATABASE " . DBUSERS;

try
{
	$connect = new PDO("mysql:host=" . SERVERNAME,
	USERNAME, PASSWORD);
		
	if (!($stmt = $connect->prepare($query)))
	{
		echo("Couldn't prepare drop");
		exit();
	}
	if (!($stmt->execute()))
	{
		echo("Couldn't execute drop");
		exit();
	}
}
catch (EXCEPTION $e)
{
	echo("Drop failed<br>" . $e);
	exit();
}

echo("Database dropped");


?>