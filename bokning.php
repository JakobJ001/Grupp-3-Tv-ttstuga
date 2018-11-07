<?php
include 'globalVal.php';
include 'sql.php';

//Book a time
function Book()
{
	$time = new DateTime($_POST['date']);
	$appartment = $_SESSION['appartment'];
	
	
	try
	{
		
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
		
		$query = "INSERT INTO booked (date, appartment) VALUES ('" . $time->format("Y-m-d H:i:s")."', '$appartment')";
	
		if (!($smtm = $connect->prepare($query)))
		{
			return "Kunde inte förbereda förfrågan";
		}
		if (!($smtm->execute()))
		{
			return "Kunde inte lägga till";
		}
		
		$query = "UPDATE users SET booked=\"". $time->format("Y-m-d H:i:s") ."\" WHERE appartment=\"" . $_SESSION['appartment'] . "\"";
		
		
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
	//If there's no Id with the post request
	if(!isset($_POST['id']))
	{
		return "Fel med post";
	}
	
	$toDelete = $_POST['id'];
	
	//Delets the tuple from booked
	$query = "DELETE FROM booked WHERE id=\"". $toDelete . "\"";
	
	try
	{
		$connect = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBUSERS, USERNAME, PASSWORD);
	
		if (!($stmt = $connect->prepare($query))) 				
		{	
			return "Kunde inte förbereda förfrågan";
		}
		if(!($stmt->execute()))
		{
			return "Kunde inte ta bort bokning";
		}
		
		//Updates the users table so that booked = "0000-00-00 00:00:00"
		$query = "UPDATE users SET booked=\"0000-00-00 00:00:00\" WHERE appartment=\"" . $_SESSION['appartment'] . "\"";
		
		
		if (!($smtm = $connect->prepare($query)))
		{
			return "Kunde inte förereda förfrågan";
		}
		if (!($smtm->execute()))
		{
			return "Kunde inte ta bort bokning";
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
	$toPrint = file_get_contents("startAvboka.txt");
	$date = new datetime($booked['date']);
	$toPrint .= $date->format("Y-m-d H:i:s");
	$toPrint .= "</p><p>Vill du avboka?</p><form action=\"bokning.php\" method=\"POST\"><input type=\"HIDDEN\" value=\"" . $booked['id']. "\" name=\"id\"/>";
	$toPrint .= file_get_contents("endAvboka.txt");
	echo($toPrint);
}

/*
#####################################
--------START OF GLOBAL CODE---------
#####################################
*/

SessionCheck();


//Checks if any post calls have been made
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

//User verification
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
if (!$bookedDates)
{
	echo("Någonting blev fel");
	return;
}



//Checks if the user has already booked a date
$alreadyBooked = false;
for ($i = 0; $i < $rowCount; ++$i)
{
	if (!$alreadyBooked)
	{
		$bDate = new datetime($bookedDates[$i]['date']);
		if ($bookedDates[$i]['appartment'] == $_SESSION['appartment'] && $date < $bDate)
		{
			$alreadyBooked = $bookedDates[$i];
		}
	}
}

if ($alreadyBooked)
{
	AlreadyBooked($alreadyBooked);
	return;
}


$toPrint = file_get_contents("startBook.txt");

//echoes all of the booked times 
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
