
<?php

$contents = file_get_contents("php://input");
$state = json_decode($contents);
$door = $state->door;

//this opens the connection to the database you need to change this for your database
$con = mysql_connect("lochnagar.abertay.ac.uk","sql1304494","9gHwFQIX");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("sql1304494", $con);


mysql_query("INSERT INTO doorstate (state)
VALUES ('$door')");

mysql_close($con);

?>
