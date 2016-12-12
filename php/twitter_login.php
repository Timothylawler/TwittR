<?php


require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

session_start();

$config = require_once 'config.php';
$keys = require_once 'twitter_keys.php';


// Do Twitter stuff
//	Create TwitterOAuth object
$twitteroauth = new TwitterOAuth(
	$keys['consumer_key'],
	$keys['consumer_secret']
);

//	Request token
$request_token = $twitteroauth->oauth(
    'oauth/request_token', [
        'oauth_callback' => $config['url_callback']
    ]
);

//	Throw exception if error
if($twitteroauth->getLastHttpCode() != 200){
	throw new \Exception("Problem reqeuesting token");
}

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

//	generate url to request authorization
$url = $twitteroauth->url(
	'oauth/authorize', [
		'oauth_token' => $request_token['oauth_token']
	]
);

//	Redirect user
header('Location: ' . $url);

?>