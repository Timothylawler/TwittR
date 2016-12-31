

<?php
//	TODO Fix the database ffs.
session_start();
include("twitter_calls.php");
$databaseInfo = require_once 'databaseConfig.php';

// Create connection
/*$db = new mysqli($databaseInfo['servername'], $databaseInfo['username'], $databaseInfo['password'], $databaseInfo['dbname']);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 
echo "Connected successfully <br>";
echo("UID: " . $_SESSION['twitter_user_id'] . "<br>");
//	var dump twitter call.
//TESTgetEntireTimeLine(1);
$canCall = canCall($_SESSION['twitter_user_id'], $db);

if($canCall){
	echo("making call");
	makeCall($_SESSION['twitter_user_id'], $db);
}
echo "canCall: " . canCall($_SESSION['twitter_user_id'], $db);

echo "<br>" . time();*/

var_dump(getTimeLine(2));

?>