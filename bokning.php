<?php
include 'globalVal.php';
include 'sql.php';


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



?>