<?php

require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

session_start();
$config = require_once 'config.php';
$keys = require_once 'twitter_keys.php';
$databaseInfo = require_once 'databaseConfig.php';


$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');
 
//	Check so that oauth_verifier parameter was receive
//		Also check so that the user has approved this application by checking 
//			oauth token and secret. 
//		TODO: Should probably check what's currently in session and if it's matching the received tokens
if (empty($oauth_verifier) ||
    empty($_SESSION['oauth_token']) ||
    empty($_SESSION['oauth_token_secret'])
) {
    // something's missing, go and login again
    header('Location: ' . $config['url_login']);
}

// connect with application token
$connection = new TwitterOAuth(
    $keys['consumer_key'],
    $keys['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);
 
// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);

//	Save screen name(user name) in session vaiable, good to have
$_SESSION['twitter_screen_name'] = $token['screen_name'];

//	Create a new twitter oauth object with the "permanent" credentials
$twitter = new TwitterOAuth(
    $keys['consumer_key'],
    $keys['consumer_secret'],
    $token['oauth_token'],
    $token['oauth_token_secret']
);

//	Save the new twitter object to session, this is used to fetch from twitter's rest API
$_SESSION['twitter_user'] = $twitter;

//	Insert user profile into database
$userName = $token['screen_name'];
$user = json_encode($twitter->get("users/show", ["screen_name" => $userName]));
$data = json_decode($user, true);
$userId = $data['id'];
$_SESSION['twitter_user_id'] = $userId;

// Create connection
$db = new mysqli($databaseInfo['servername'], $databaseInfo['username'], $databaseInfo['password'], $databaseInfo['dbname']);
$query = "SELECT ID FROM usertable WHERE ID = " . $userId . ";";
$result = $db->query($query);

if($result->num_rows > 0){
  // found
	//	Increment visited
	$query = "update usertable set noVisited = noVisited + 1 where ID = " . $userId . ";";
	$db->query($query);
}else{
  // not found
	// add user to database with 1 in number of times visited
	$query = "insert into usertable (ID, noVisited) values(" . $userId . ", 1);";
	$db->query($query);
}

//	Redirect user to the startpage of the application
header("location: http://localhost/TwittR/start.html");

//	Get timeline
/*
$statuses = $twitter->get("statuses/home_timeline", ["count" => 5, "exclude_replies" => true]);


$json = json_encode($statuses);
$data = json_decode($json, true);

//var_dump($data);

foreach($data as $tweet){
	echo($tweet['text'] . "<br>");
}*/

//echo($data[0]['text']);


/*$status = $twitter->post(
    "statuses/update", [
        "status" => "Tweeting, GlittR style"
    ]
);*/
 
/*echo ('Created new status with #' . $status->id . PHP_EOL);*/

?>