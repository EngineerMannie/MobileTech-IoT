<?php

// receive the data from dreish as
// data t0000,s00,b?00
// 0123456789012345678 

if(isset($_POST["originator"])) 
{ 
	// The mobile phone number of the patient ehealth kit
	$patient = $_POST["originator"];
}
if(isset($_POST["body"]))
{
	// The SMS message - data from the sensors
	$smsdata = urldecode($_POST["body"]);
}
if(isset($_POST["receivedat"]))
{
	// The date and time the message was received
	$msgrcvd = $_POST["receivedat"];
}
$tpos = (strpos($smsdata, 't', 4))+1;
$spos = (strpos($smsdata, 's'))+1;
$bpos = (strpos($smsdata, 'b'))+1;
// parse the data
// TODO:
$temp = substr($smsdata, $tpos, 4);
$temp = ((intval($temp)) / 100.00);
$spo2 = substr($smsdata, $spos, 2);
$spo2 = intval($spo2);
$bpm = substr($smsdata, $bpos);
$bpm = intval($bpm);

// testing: echo("tsb ". $temp ." ". $spo2 ." ". $bpm);


// store the data
// switch on all errors during testing
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

// sql insert data
$result = $db->exec("INSERT INTO ehealthstuff (patient, temp, bpm, spo2, smsdata, msgrcvd) 
VALUES('$patient', '$temp', '$bpm', '$spo2', '$smsdata', '$msgrcvd')");
$insertId = $db->lastInsertId();
// test...........................
// echo "SQL insert result " . $result . "<br>";
// end test.......................


// set the time zone
$timezone = date_default_timezone_set('UTC');

// get the last 5 data submissions where time == today
$today = date('Y-m-d', time());
$timeList = array(); 
$tempList = array();
$bpmList = array();
$spo2List = array();
$i = 0;
try {
	//connect as appropriate as above
	foreach ($db->query("SELECT * FROM ehealthstuff
						WHERE SUBSTRING( posttime, 1, 10 ) LIKE '$today'
						ORDER BY posttime DESC
						LIMIT 5") as $row){
		// test...........................
		// echo "Row " . $row['id']." ".$row['posttime']." ".$row['temp']." ".$row['bpm']." ".$row['spo2']."<br />";
		// end test.......................
		$timeList[$i] = $row['posttime'];
		$tempList[$i] = $row['temp'];
		$bpmList[$i] = $row['bpm'];
		$spo2List[$i] = $row['spo2'];
		$i++;
	}

} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
	echo($ex->getMessage());
}

// test...............................
// echo "<br>timeList <br>"; foreach($timeList as $item) echo ($item . "<br>");
// echo "<br>tempList <br>"; foreach($tempList as $item) echo ($item . "<br>");
// echo "<br>bpmList <br>"; foreach($bpmList as $item) echo ($item . "<br>");
// echo "<br>spo2List <br>"; foreach($spo2List as $item) echo ($item . "<br>");
// end test...........................

// test the rate of change and levels of temp bpm amd spo2
// TODO: test for ZERO or NULL

// normal temperature 36.2 to 37.2
// temp > 40 can be life-threatening
if($tempList){
	if( min($tempList) < 35.2 ){
		$tempMsg = " Temp LOW ". min($tempList);
		$status = 1;
		if( max($tempList) - min($tempList) > 0.7 ) $status += 1;
	} elseif( max($tempList) > 38.20 ){
		$tempMsg = " Temp HIGH ". max($tempList);
		$status = 2;
		if( max($tempList) > 39.20 ) $status += 1;
		if( max($tempList) - min($tempList) > 0.7 ) $status += 1;
	} else {
		$tempMsg = " Temp Normal ". number_format((array_sum($tempList) / $i), 2);
		$status = 0;
	}
}

// generally (not Medically) bpm should be between 50 and 80 bpm
// dependant on age but if bpm > 180 there is cause for concern
if($bpmList){
	if( min($bpmList) < 50 ){
		$bpmMsg = " BPM LOW ". min($bpmList);
		$status += 1;
	} elseif( max($bpmList) > 95 ){
		$bpmMsg = " BPM HIGH ". max($bpmList);
		$status += 2;
	} else {
		$bpmMsg = " BPM Normal ". number_format((array_sum($bpmList) / $i - 1), 2);
	}
}

// Oxygen Saturation should be above 95% normal levels
// below 80% could compromise organ function
if($spo2List){
	if( min($spo2List) < 85 ){
		$spo2Msg = " OxySats LOW ". min($spo2List);
		$status += 2;
	} else {
		$spo2Msg = " OxySats Normal ". min($spo2List);
	}
}

// if there is concern raise alarm
$raiseAlarm = false;
if( $status > 3 ){
	$riseAlarm = true;
	$conclusion = " You sould now PANIC!! - Call 666";
} elseif( $status > 1 ){
	$riseAlarm = true;
	$conclusion = " Call the patient NOW - 07775609904 ";
} else {
	$riseAlarm = false;
	$conclusion = " Be cool!";
}
$msg = "The patient No.6 Stats Page - http://mayar.abertay.ac.uk/~1304494/ - Currently " . $tempMsg . $bpmMsg . $spo2Msg . $conclusion;
// test........................
// echo "<br>Text Message " . $msg;
// end test....................

// save the message to the server
// sql insert data
$result = $db->exec("INSERT INTO ehealthstatus (status, message) VALUES('$status', '$msg')");
$insertId = $db->lastInsertId();
$statusList = array();
// ...........................

$i = 0;
try {
	//connect as appropriate as above
	foreach ($db->query("SELECT * FROM ehealthstatus
						WHERE SUBSTRING( time, 1, 10 ) LIKE '$today'
						ORDER BY time DESC
						LIMIT 2") as $row){
		
		$statusList[$i] = $row['status'];
		$i++;
	}

} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
	echo($ex->getMessage());
}

// ditch the PDO object
$db = null;
if($statusList[1])
{
	if($statusList[0] != $statusList[1]) $raiseAlarm = true;
}
//----------------------------------------------------------------

// if alarm raised call the assistance & reasure patient
// use cURL to send data to sms server


if($raiseAlarm){

	$URL = "http://driesh.abertay.ac.uk/~g510572/sms/sendsms.cfm";
	$PHONE = "07831433612";
	$SMSTEXT = $msg;
	$USERNAME = "1304494";

	$_POST['mphone'] = $PHONE;
	$_POST['smstext'] = $SMSTEXT;
	$_POST['username'] = $USERNAME;

	$encoded = '';
	// include GET as well as POST variables; your needs may vary.
	foreach($_POST as $name => $value) {
	  $encoded .= urlencode($name).'='.urlencode($value).'&';
	}
	// chop off last ampersand
	$encoded = substr($encoded, 0, strlen($encoded)-1);

	//----------------------------------------------------------------

	// create a new cURL handle resource
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_HEADER, true);
	// grab URL and pass it to the browser
	curl_exec($ch);
	// close cURL resource, and free up system resources
	curl_close($ch);

}

?>