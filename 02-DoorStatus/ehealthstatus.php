<?php

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
	foreach ($db->query("SELECT * FROM ehealthstuff
						WHERE SUBSTRING( time, 1, 10 ) LIKE '$today'
						ORDER BY time DESC
						LIMIT 10") as $row){
						
		$time = date('H:i:s', strtotime($row['time']));
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
