<?php

echo ("<center><br><h2>TEMPERATURE READINGS</h2>");

// Create connection to uni mayar account
$con = mysqli_connect("lochnagar.abertay.ac.uk", "sql1304494", "9gHwFQIX", "sql1304494") 
		or die ("Unable to connect!");

// create query
$query = "SELECT * FROM temperatures
			ORDER BY time DESC
			LIMIT 10";
    
// execute query1
$result = mysqli_query($con, $query) or die ("Error in query: $query. " . mysqli_error());
 
// see if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // if a row was returned
	while ($row = mysqli_fetch_array($result)){
		echo("<tr><td align='center' width='100px'>".$row['location']."</td>");
		// $dateTime = new DateTime($row['time']);
		$time = date('H:i:s', strtotime($row['time']));
		echo("<td align='center'> ".$time." </td>");
		echo("<td align='center'>".$row['temp']."</td></tr>");
	}
}
else {
    // no result
	echo "ERROR:";
	
	// free result set memory
    mysqli_free_result($result);
 
    // close connection
    mysqli_close($con);
}

mysqli_free_result($result);
mysqli_close($con);
?>