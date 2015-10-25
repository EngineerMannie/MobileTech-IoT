<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$item1 = $_POST["originator"]; // The mobile phone number of the originator, the sender of the SMS message
$item2 = $_POST["body"]; // The contents of the SMS message
$item3 = $_POST["receivedat"]; // The date and time the message was received


// this opens the connection to the database you need to change this for your database
// $myHost = "lochnagar.abertay.ac.uk";
// $myUname = "sql1304494";
// $myDBname = "sql1304494";
// $myPword = ".....removed.....";

try {
	
	$db = new PDO('mysql:host=lochnagar.abertay.ac.uk; dbname=sql1304494; charset=utf8', 'sql1304494', '...........');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
} catch(PDOException $e) {
	
	echo 'ERROR: ' . $e->getMessage();
}

$result = $db->exec("INSERT INTO smsmsg (today, rcvfrom, message) VALUES('$item3', '$item1', '$item2')");
$insertId = $db->lastInsertId();

$db = null;
echo('message sent');
?>