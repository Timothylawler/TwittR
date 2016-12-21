<?php

require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
 
session_start();
 
$config = require_once 'config.php';
$keys = require_once 'twitter_keys.php';


//	Make sure we have all data needed.
$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

if(empty($oauth_verifier) || 
	empty($_SESSION['oauth_token']) || 
	empty($_SESSION['oauth_token_secret'])){
	
	//	Missing something, route back to login
	echo("Status: 400");
	//header('Location: ' . $config['url_login']);
} else{
	//	Verify user
	$twitter = new TwitterOAuth(
		$keys['consumer_key'],
		$keys['consumer_secret'],
		$_SESSION['oauth_token'],
		$_SESSION['oauth_token_secret']
	);
	
	//	Reqeust user token
	$token = $twitter->oauth(
		'oauth/access_token', [
			'oauth_verifier' => $oauth_verifier
		]
	);
	
	/*$status = $twitter->post(
		"statuses/update", [
			"status" => "vaska den, twitterAPIstyle"
		]
	);*/
	
	//echo($status);
	
	$_SESSION['session_twitter'] = $twitter;
	echo json_encode(array());
	
	//header('Location: ' . $config['url_login_succesful']);
}

?>