<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
<title>My Connection PHP page</title>
</head>
<body body{width:90%; margin:auto; min-width:150px; max-width:300px} >

<?php
// get the location from the url
$location = htmlspecialchars($_GET["loc"]);

echo ("<center><br><h2>TEMPERATURE READINGS</h2>");
echo ("<center><h3>$location</h3>");

// Create connection local or uni
$con = mysqli_connect("lochnagar.abertay.ac.uk", "sql1304494", "9gHwFQIX", "sql1304494") 
		or die ("Unable to connect!");
		
// $con = $con = mysqli_connect("localhost","root" ,"" , "mobiletech") or die ("Unable to connect!");

// create query
$query = "SELECT r.eitime, r.eireading
			FROM eireading r 
			JOIN eiimpee i
			ON r.eiid = i.eiimpeeid
			WHERE i.eidescription = '$location'
			ORDER BY r.eitime DESC
			LIMIT 10";
    
// execute query1
$result = mysqli_query($con, $query) or die ("Error in query: $query. " . mysqli_error());
 
// see if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // if a row was returned
	echo("<table border=1 align='center'>");
	echo("<th>TIME</th><th>TEMPERATURE</th>");
	while ($row = mysqli_fetch_array($result)){
		echo("<tr><td>".$row['eitime']."</td>");
		echo("<td align='center'>".$row['eireading']."</td></tr>");
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

mysqli_free_result($result1);
mysqli_free_result($result2);
mysqli_close($con);
?>

</body>
</html>