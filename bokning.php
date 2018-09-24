<?php
include 'globalVal.php';
include 'sql.php';


function AlreadyBooked($booked)
{
	$date = $booked['date'];
	$toPrint = "<html lang=\"sv\"><head><meta charset=\"UTF-8\"><title>Login</title></head><body>" . 
	"<p>Du har redan bokat en tvättid</p><p>Tiden du har bokad är:</p>"
}

/*
#####################################
--------START OF GLOBAL CODE---------
#####################################
*/

//globalVal.php
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
$bookedDates = SqlRequest("SELECT * FROM Booked", DBUSERS, $rowCount);

if ($bookedDates == ERROR)
		{
			echo("Någonting blev fel");
			return;
		}
else if ($bookedDates == NOTHING)
{
	echo("Någonting fel med databasen:(");
	return;
}

$toDelete = array();
$dates = array();
$alreadyBooked = false;
for ($i = 0; $i < $rowCount; ++$i)
{
	if (!$alreadyBooked)
	{
		$bDate = $bookedDates[$i]['date'];
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
?>