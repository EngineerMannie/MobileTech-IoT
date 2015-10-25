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

// set the time zone
$timezone = date_default_timezone_set('UTC');
// get the last 10 data submissions where time == today
// $today = date('Y-m-d', time());

$today = '2014-12-02';


try {
	//connect as appropriate as above
	foreach ($db->query("SELECT * FROM ehealthstuff
						WHERE SUBSTRING( posttime, 1, 10 ) LIKE '$today'
						ORDER BY posttime DESC
						LIMIT 10") as $row){
						
		$time = date('H:i:s', strtotime($row['posttime']));
		echo("<tr><td align='center'> ".$time." </td>");
		echo("<td align='center'>".$row['temp']."</td>");
		echo("<td align='center'>".$row['bpm']."</td>");
		echo("<td align='center'>".$row['spo2']."</td></tr>");
		
	}

} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
	echo($ex->getMessage());
}

// ditch the PDO object
$db = null;

?>
