<?php
//Checking if a session exists. Will direct take the user to index.php if false
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


//All the global variables that we'll need
if(!defined("PASSWORD"))
{
	define("PASSWORD", "aa");
	define("USERNAME", "a");
	define("SERVERNAME", "localhost");
	define("DBUSERS", "appdb");
	define("ERROR", "err");
	define("NOTHING", "nothing");
}
?>
