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
//If the user has already booked
function AlreadyBooked($booked)
{
	$date = new datetime($booked['date']);
	$toPrint = "<html lang=\"sv\"><head><meta charset=\"UTF-8\"><title>Login</title></head><body>" . 
	"<p>Du har redan bokat en tvättid</p><p>Tiden du har bokad är:</p>" . $date->format("D-m-d H:i'");
	$toPrint .= "<p>Vill du avboka?</p><form action=\"bokning.php\" method=\"POST\"><input type=\"submit\" value=\"Avboka\"/></body></html>";
	echo($toPrint);
}

/*
#####################################
--------START OF GLOBAL CODE---------
#####################################
*/

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
if(!$curr || $_SESSION['password'] != $curr[1])
{
	header("Location: index.php");
	return;
}

$date = new DateTime();
$bookedDates = SqlRequest("SELECT * FROM booked", DBUSERS, $rowCount);

if ($bookedDates == ERROR)
		{
			echo("Någonting blev fel");
			return;
		}

$toDelete = array();
$dates = array();
$alreadyBooked = false;
for ($i = 0; $i < $rowCount; ++$i)
{
	if (!$alreadyBooked)
	{
		$bDate = new datetime($bookedDates[$i]['date']);
		if ($date > $bDate)
		{
			$toDelete[count($toDelete)] = $bookedDates[$i]['id'];
		}
		else if ($bookedDates[$i]['appartment'] == $_SESSION['appartment'])
		{
			$alreadyBooked = $bookedDates[$i];
		}
	}
}
$worked = SqlDelete("DELETE FROM Booked WHERE id IN ", DBUSER, $toDelete);
if (!$worked)
{
	echo("Någonting blev fel med databasen!");
	return;
}
if ($alreadyBooked)
{
	AlreadyBooked($alreadyBooked);
	return;
}

$day = $date->format("W");



?>