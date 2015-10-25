<?php

// switch on all errors
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

// this opens the connection to the database you need to change this for your database
$myHost = "lochnagar.abertay.ac.uk";
$myUname = "sql1304494";
$myDBname = "sql1304494";
$myPword = "9gHwFQIX";

// create PDO and connect to 'sql1304494' database - 'ehealthstuff' table
try {
	
	$db = new PDO("mysql:host=$myHost; dbname=$myDBname; charset=utf8", "$myUname", "$myPword");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
} catch(PDOException $e) {
	// test message..................
	echo 'ERROR: ' . $e->getMessage();
}

try {
	//connect as appropriate as above
	foreach ($db->query("SELECT MAX(time) AS 'change', status, message
								FROM  `ehealthstatus` 
								GROUP BY status
								ORDER BY MAX(time) DESC
								LIMIT 3") as $row){
		$message = $row['message'];
		$find = "OxySats";
		$posSats = strpos($message, $find) + 8;
		$letr = substr($message,$posSats,1);
		if($letr == 'L') $add = 6;
		if($letr == 'N') $add = 9;
		$stringl = $posSats + $add - 80;
		$msg = substr($message,80,$stringl);
		$chDate = substr($row['change'], 0, -9);
		echo("<tr><td align='center'>".$chDate."</td>");
		echo("<td align='center'>".$row['status']."</td>");
		echo("<td align='center'>".$msg."</td></tr>");
		
	}

} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
	echo($ex->getMessage());
}

// ditch the PDO object
$db = null;

?>
