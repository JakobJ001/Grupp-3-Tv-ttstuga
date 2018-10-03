<?php
include 'globalVal.php';
include 'sql.php';

$rowCount = "";
$result = SqlRequest("SELECT appartment FROM users", DBUSERS, $rowCount);

$found = false;
for ($i = 0; $i < $rowCount && !$found; ++$i)
{
	if ($rowCount['appartment'] == "admin")
	{
		$found = true;
	}
}
if ($found)
{
	header("Location: index.php");
	return;
}

$appartment = "admin";
$name = "admin";
$password = password_hash("admin", PASSWORD_DEFAULT);
$filePath = "pic/admin.jpg";

$query = "INSERT INTO users (appartment, password, name, picture, booked) VALUES ('$appartment' ,'$password', '$name','$filePath', 'NULL');";
	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
		
		
		if (!($smtm = $connect->prepare($query)))
		{
			echo("Couldn't prepare query");
			return;
		}
		if (!($smtm->execute()))
		{
			echo("Couldn't prepare query");
			return;
		}
		return "User added";
	}
	catch (EXCEPTION $e)
	{
		echo("Couldn't update the database");
		return;
	}
header("Location: index.php");
return;

?>