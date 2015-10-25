<?php

echo ("<center><br><h2>Door Position</h2>");

// Create connection to uni mayar account
$con = mysqli_connect("lochnagar.abertay.ac.uk", "sql1304494", "9gHwFQIX", "sql1304494") 
		or die ("Unable to connect!");

// create query
$query = "SELECT * FROM doorstate
			ORDER BY time DESC
			LIMIT 2";
    
// execute query1
$result = mysqli_query($con, $query) or die ("Error in query: $query. " . mysqli_error());
 
// see if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // if a row was returned
	while ($row = mysqli_fetch_array($result)){
		// $time = date('H:i:s', strtotime($row['time']));
		echo("<td align='center'> ".$row['time']." </td>");
		echo("<td align='center'>".$row['state']."</td></tr>");
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