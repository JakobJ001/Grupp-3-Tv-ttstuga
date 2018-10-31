<?php
include 'globalVal.php';
include 'sql.php';

//Book a time
function Book()
{
	$time = new DateTime($_POST['date']);
	$appartment = $_SESSION['appartment'];
	
	$query = "INSERT INTO booked (date, appartment) VALUES ('" . $time->format("Y-m-d H:i:s")."', '$appartment')";
	
	try
	{
		
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
		
		
		if (!($smtm = $connect->prepare($query)))
		{
			return "Kunde inte ansluta till sql server";
		}
		if (!($smtm->execute()))
		{
			return "Kunde inte lägga till";
		}
		return "Tid bookad";
	}
	catch (EXCEPTION $e)
	{
		return "Kunde inte booka";
	}
}

//Removes a booked date
function RemoveBooking()
{
	if(!isset($_POST['id']))
	{
		return "Fel med post";
	}
	
	$toDelete = $booking['id'];
	
	$query = "DELETE FROM booked WHERE id=\"". $toDelete . "\"";
	
	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
	
		if (!($stmt = $connect->prepare($query))) 				
		{	
			return false;
		}
		//We do not want to continue if something goes wrong
		if(!($stmt->execute()))
		{
			return false;
		}
		
		return true;
	}
	catch (EXCEPTION $e)
	{
		return false;
	}
}

//Checking for a session
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
	$toPrint = "<html lang=\"sv\"><head><meta charset=\"UTF-8\"><title>Login</title></head><body>"; 
	$toPrint .= "<p>Du har redan bokat en tvättid</p><p>Tiden du har bokad är:</p>" . $date->format("Y-m-d H:i:s");
	$toPrint .= "<p>Vill du avboka?</p><form action=\"bokning.php\" method=\"POST\"><input type=\"HIDDEN\" value=\"" . $booked['id']. "\" name=\"id\"/>";
	$toPrint .= "<input type=\"submit\" name=\"unbook\" value=\"Avboka\"/></body></html>";
	echo($toPrint);
}

/*
#####################################
--------START OF GLOBAL CODE---------
#####################################
*/

SessionCheck();

if (isset($_POST['unbook']))
{
	$value = RemoveBooking();
	if ($value != true)
	{
		echo($value);
		return;
	}
}
else if (isset($_POST['book']))
{
	$value = Book();
	if ($value != true)
	{
		echo($value);
		return;
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
if(!$curr || $_SESSION['password'] != $curr[1])
{
	header("Location: index.php");
	return;
}

//Current date
$date = new DateTime();

//Gets a list of all dates
$bookedDates = SqlRequest("SELECT * FROM booked", DBUSERS, $rowCount);
if ($bookedDates == ERROR)
{
	echo("Någonting blev fel");
	return;
}

$toDelete = array();
$dates = array();
$alreadyBooked = false;
//Checks if any of the booked dates have already been
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
//Removes the booked dates in need of deletion
if (count($toDelete) > 0)
{
	$worked = SqlDelete("DELETE FROM booked WHERE id IN ", DBUSERS, $toDelete);
	if (!$worked)
	{
		echo("Någonting blev fel med databasen!");
		return;
	}
}

if ($alreadyBooked)
{
	AlreadyBooked($alreadyBooked);
	return;
}

$toPrint = file_get_contents("startBook.txt");

$toPrint .= "<script>var booked = [\"";

for ($i = 0; $i < count($bookedDates) - 1; ++$i)
{
	$toPrint .= $bookedDates[$i]['date'];
	$toPrint .= "\",\"";
}
if (count($bookedDates) > 0)
{
	$toPrint .= $bookedDates[count($bookedDates) - 1]['date'] ."\"";
}
$toPrint .= "];</script>";
$toPrint .= file_get_contents("endBook.txt");
echo($toPrint);
?>
