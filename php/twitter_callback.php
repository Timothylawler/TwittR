<?php

require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

session_start();
$config = require_once 'config.php';
$keys = require_once 'twitter_keys.php';

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