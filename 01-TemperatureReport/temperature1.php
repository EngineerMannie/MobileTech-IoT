<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
<title>My Connection PHP page</title>
</head>
<body body{width:90%; margin:auto; min-width:150px; max-width:300px} >

<?php

echo "<center><br><h2>TEMPERATURE LOCATIONS</h2><br>";

// Create connection local or uni
$con = mysqli_connect("lochnagar.abertay.ac.uk", "sql1304494", "9gHwFQIX", "sql1304494") 
		or die ("Unable to connect!");
		
// $con = mysqli_connect("localhost","root" ,"" , "mobiletech") or die ("Unable to connect!");

// create query
$query = "SELECT eidescription, eilat, eilong FROM eiimpee";

// execute query
$result = mysqli_query($con, $query) or die ("Error in query: $query1. " . mysqli_error());

// see if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // if a row was returned
	echo("<table border=1>");
	echo("<th>SENSOR LOCATION</th><th>MAP POSITION</th>");
	while ($row = mysqli_fetch_array($result)){
		echo("<tr><td><a href='temperature2.php?loc=".$row['eidescription']."'>".$row['eidescription']."</a></td>");
		echo("<td><a href='temperature3.php?pos1=".$row['eilat']."&pos2=".$row['eilong']."'>".$row['eilat']."&nbsp;&#47;&nbsp;".$row['eilong']."</a></td></tr>");
	}
	echo ("</table>");
} else {
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

</body>
</html>