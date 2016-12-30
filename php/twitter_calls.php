<?php
//	Get requirements
require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
session_start();
//header('Content-Type: application/json');

/**
	I should really re design this singleton and use database instead... seems unneccessary at this point...
*/
class callCheck{
	private $rate = 60;
	static $calls;
	static $lastCallTime;
	private static $instance;
	
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new callCheck();
		}
		return self::$instance;
	}
	
	/*
		checks whether a call has been made on this insance
		returns true if no call has been made or if 60 seconds has passed since 
			the last call
		@return Boolean
	*/
	public function canCall(){
		if(!isset($lastCallTime))
			return true;
		return (time() - $lastCallTime > $rate);
		
	}
	
	/*
		adds a call to the total calls and puts the current time to last call time
	*/
	public function makeCall(){
		$calls++;
		$lastCallTime = time();
	}
}

	
if(isset($_SESSION['twitter_user'])){
	$rateChecker = callCheck::getInstance();
	/*	---------	POST --------- */
	if(isset($_POST['func'])){
		switch($_POST['func']){
			
	 
		 	default:
				//	No matching call available
			 	echo 'Invalid call';
				break;	
		}
	}
	
	/*	---------	GET --------- */
	if(isset($_GET['func'])){
		switch($_GET['func']){
			case 'screenName':
				//	Requesting screen name
				if(isset($_SESSION['twitter_screen_name'])){
					echo $_SESSION['twitter_screen_name'];
				}
				else{
					//	Return default value
					return 'NA';
				}
				break;
				
			case 'timeline':
				//	Requesting timeline
				if(isset($_GET['count'])){
					//	Requesting with passed count
					$count = $_GET['count'];
					$data = getTimeLine($count);
					echo json_encode($data);
				}
				else{
					//	pass default value
					echo getTimeLine(5);
				}
				break;
				
			case 'selfProfile':
				//	Requesting the current users profile
				break;
				
			default:
				echo 'Invalid call';
				break;
				
		}
	}
	
}

function getTimeLine($count){
	$twitter = $_SESSION['twitter_user'];
	//$twitterOAuth
	
	//var_dump($twitter);
	$statuses = $twitter->get("statuses/home_timeline", ["count" => $count, "exclude_replies" => true]);
	//var_dump($statuses);
	$json = json_encode($statuses);
	$data = json_decode($json, true);
	$returnData = array();
	foreach($data as $tweet){
		$returnData[] = $tweet['text'];
	}
	//var_dump($returnData);
	return $returnData;
}

function TESTgetEntireTimeLine($count){
	$twitter = $_SESSION['twitter_user'];
	//$twitterOAuth
	
	//var_dump($twitter);
	$statuses = $twitter->get("statuses/home_timeline", ["count" => $count, "exclude_replies" => true]);
	//var_dump($statuses);
	$json = json_encode($statuses);
	var_dump($json);
	$data = json_decode($json, true);
	echo ('<br><br>');
	$returnData = array();
	foreach($data as $tweet){
		//	Get text and tweet id
		$data = array();
		$data['text'] = $tweet['text'];
		$data['id'] = $tweet['id'];
		
		//	Get entities
		$ent = array();
		$ent = $tweet['entities'];
		$entities = array();
		
		//	Get url from entities
		if(isset($ent['urls'])){
			$urls = array();
			if(isset($ent['urls'])){
				$urls = $ent['urls'];
				$url = array();
				if(isset($urls['url'])){
					$url['url'] = $urls['url'];
				}
				if(isset($urls['display_url'])){
					$url['display_url'] = $urls['display_url'];
				}
				$entities['urls'] = $url;
			}	
		}
		
		//	Get media_url from media in entities
		echo('<br><br>');
		var_dump($ent['media_url']);
		if(isset($ent['media'])){
			$med = array();
			$med = $ent['media'];
			echo("MEDIA");
			//var_dump($med['media_url']);
			$media = array();
			if(isset($med['media_url'])){
				$media['media_url'] = $med['media_url'];
				$entities['media'] = $media;
			}
		}
		
		
		
		
		$data['entities'] = $entities;
		
		//	Get data of the posting user
		$user = array();
		$usr = array();
		$usr = $tweet['user'];
		$user['name'] = $usr['name'];
		$user['screen_name'] = $usr['screen_name'];
		$user['location'] = $usr['location'];
		$user['geo_enabled'] = $usr['geo_enabled'];
		$user['profile_image_url'] = $usr['profile_image_url'];
		$data['user'] = $user;
		
		//	Get location data if there is any
		$data['geo'] = $tweet['geo'];
		$data['coordinates'] = $tweet['coordinates'];
		$data['place'] = $tweet['place'];
		//	social status of the tweet
		$data['retweet_count'] = $tweet['retweet_count'];
		$data['favorite_count'] = $tweet['favorite_count'];
		//	Viewing user data
		$data['favorited'] = $tweet['favorited'];
		$data['retweeted'] = $tweet['retweeted'];
		
		
		
		$returnData[] = $data;
	}
	echo ('<br><br>');
	var_dump(json_encode($returnData));
	//return $returnData;*/
}

function getUser($userName){
	if(!isset($userName)){
		$userName = $_SESSION['twitter_screen_name'];
	}
	$twitter = $_SESSION['twitter_user'];
	$user = json_encode($twitter->get("users/show", ["screen_name" => $userName]));
	//$data = json_decode($user, true);
	var_dump($user);
	echo ("<br>");
	echo $data['id'];
	
}


?>