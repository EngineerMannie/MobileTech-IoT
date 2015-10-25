<?php

// get the json object and decode
$contents = file_get_contents("php://input");
// test.........................
// echo "Contents " . $contents;
// end test.....................
/*
$obj = json_decode($contents);
// test.........................
echo "Json obj " . $obj;
// end test.....................
$temp = $obj->t;
$bpm = $obj->b;
$spo2 = $obj->s;
*/
$temp = $_POST['temp'];
$bpm = $_POST['bpm'];
$spo2 = $_POST['spo2'];

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

// sql insert data
$result = $db->exec("INSERT INTO ehealthstuff (temp, bpm, spo2) VALUES('$temp', '$bpm', '$spo2')");
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
						WHERE SUBSTRING( time, 1, 10 ) LIKE '$today'
						ORDER BY time DESC
						LIMIT 5") as $row){
		// test...........................
		// echo "Row " . $row['id']." ".$row['time']." ".$row['temp']." ".$row['bpm']." ".$row['spo2']."<br />";
		// end test.......................
		$timeList[$i] = $row['time'];
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
echo "<br>timeList <br>"; foreach($timeList as $item) echo ($item . "<br>");
echo "<br>tempList <br>"; foreach($tempList as $item) echo ($item . "<br>");
echo "<br>bpmList <br>"; foreach($bpmList as $item) echo ($item . "<br>");
echo "<br>spo2List <br>"; foreach($spo2List as $item) echo ($item . "<br>");
// end test...........................

// test the rate of change and levels of temp bpm amd spo2
// TODO: test for ZERO or NULL

// normal temperature 36.2 to 37.2
// temp > 40 can be life-threatening
if( min($tempList) < 35.2 ){
	$tempMsg = " Temp is LOW - ". min($tempList);
	$status = 2;
	if( max($tempList) - min($tempList) > 0.5 ) $status += 1;
} elseif( max($tempList) > 38.20 ){
	$tempMsg = " Temp is HIGH - ". max($tempList);
	$status = 2;
	if( max($tempList) > 39.20 ) $status += 1;
	if( max($tempList) - min($tempList) > 0.5 ) $status += 1;
} else {
	$tempMsg = " Temp is Normal - ". (array_sum($tempList) / $i - 1);
	$status = 0;
}

// generally (not Medically) bpm should be between 50 and 80 bpm
// dependant on age but if bpm > 180 there is cause for concern
if( min($bpmList) < 40 ){
	$bpmMsg = " BPM is LOW - ". min($bpmList);
	$status += 2;
} elseif( max($bpmList) > 180 ){
	$bpmMsg = " BPM is HIGH - ". max($bpmList);
	$status += 2;
} else {
	$bpmMsg = " BPM is Normal - ". (array_sum($bpmList) / $i - 1);
}

// Oxygen Saturation should be above 95% normal levels
// below 80% could compromise organ function
if( min($spo2List) < 85 ){
	$spo2Msg = " OxySats are LOW - ". min($spo2List);
	$status += 3;
} else {
	$spo2Msg = " OxySats are Normal - ". min($spo2List);
}

// if there is concern raise alarm
$raiseAlarm = false;
if( $status > 3 ){
	$riseAlarm = true;
	$conclusion = " You sould now PANIC!! - Call 666";
} elseif( $conclusion > 1 ){
	$riseAlarm = true;
	$conclusion = " There is cause for concern - Call the patient 07831 433612.";
} else {
	$riseAlarm = false;
	$conclusion = " Be cool!";
}
$msg = "The patient functions are as follows." . $tempMsg . $bpmMsg . $spo2Msg . $conclusion;
// test........................
echo "<br>Text Message " . $msg;
// end test....................

// save the message to the server
// sql insert data
$result = $db->exec("INSERT INTO ehealthstatus (status, message) VALUES('$status', '$msg')");
$insertId = $db->lastInsertId();
// ...........................

$i = 0;
try {
	//connect as appropriate as above
	foreach ($db->query("SELECT * FROM ehealthstatus
						WHERE SUBSTRING( time, 1, 10 ) LIKE '$today'
						ORDER BY time DESC
						LIMIT 2") as $row){
		
		$status[$i] = $row['status'];
		$i++;
	}

} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
	echo($ex->getMessage());
}

// ditch the PDO object
$db = null;

if($status[0] != $status[1]) $raiseAlarm = true;

//----------------------------------------------------------------

// if alarm raised call the assistance & reasure patient
// use cURL to send data to sms server


if(raiseAlarm){

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