<?php
//	Get requirements
require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
session_start();
//header('Content-Type: application/json');
$databaseInfo = require_once 'databaseConfig.php';

function canCall (&$userId, &$db) {
	if(isset($db)){
		$rate = 60;
		$query = "select lastCall from usertable where ID = " . $userId . ";";
		$result = (int)$db->query($query);
		//return $result;
		if(!isset($result))
				return true;
		$returnValue = ((time() - $result) > $rate);
		
		//echo "<br>" . var_dump($result) . "<br>";
		//$result->close();
		//return (time() - $lastCall > $rate);
		return $returnValue;
	}
	//	Cant connect
	return false;
}

/*This does not work, does not update any values, it is the correct database though*/
function makeCall(&$userId, &$db){
	if(isset($db)){
		//	Increment number of calls
		$query = "update usertable set noCalls = noCalls + 1 where ID = " . (int)$userId . ";";
		//$query = "insert into usertable (ID) values(321);";
		$db->query($query); 
		//	Set last call to now
		$now = (int)time();
		$timeQuery = "update usertable set lastCall = " . (int)$now . " where ID = " . $userId . ";";
		$db->query($timeQuery);
	
	}
	return ("Error connecting to database");
}

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
		//$calls++;
		$lastCallTime = time();
	}
}

	
if(isset($_SESSION['twitter_user'])){
	$rateChecker = callCheck::getInstance();
	// Create connection
	$db = new mysqli($databaseInfo['servername'], $databaseInfo['username'], $databaseInfo['password'], $databaseInfo['dbname']);
	$twitter = $_SESSION['twitter_user'];
	/*	---------	POST --------- */
	if(isset($_POST['func'])){
		switch($_POST['func']){
			
			case 'tweet':
				//	Make sure we really got data
				
				
	 
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
					echo json_encode(getTimeLine(5));
				}
				break;
				
			case 'userProfile':
				//	Requesting the current users profile
				$userName = null;
				if(isset($_GET['userName'])){
					$userName = $_GET['userName'];
				}
				$data = getUser($userName);
				echo json_encode($data);
				break;
				
			default:
				echo 'Invalid call';
				break;
				
			case 'postTweet':
				//	Make sure we really got data
				if(isset($_GET['text'])){
					//$twitter = $_SESSION['twitter_user'];
					//	Check if an image was passed along
					if(isset($_GET['media'])){
						echo($_GET['media']);
						$mediaId = uploadMedia($_GET['media']);
						$response = postTweetWithMedia($_GET['text'], $mediaId);
						//echo ("mediaID " . $mediaId);
					}
					else{
						$status = $twitter->post(
							"statuses/update", [
								"status" => $_GET['text']
							]
						);
					}
				}
				break;
				
			case 'favorite':
				//	Cant do anything without id
				if(isset($_GET['tweetId'])){
					$tweetId = $_GET['tweetId'];
					$response = $twitter->post(
						"favorites/create", [
							"id" => $tweetId
						]
					);
					if($twitter->getLastHttpCode() == 200){
						echo http_response_code(200);
					}
					else{
						echo http_response_code(500);
					}
				}
				else{
					echo http_response_code(400);
					echo "failed to get id";
				}
				break;
				
			case 'comment':
				break;
			case 'retweet':
				if(isset($_GET['tweetId'])){
					$tweetId = $_GET['tweetId'];
					$response = $twitter->post(
						"statuses/retweet", [
							"id" => $tweetId
						]
					);
					if($twitter->getLastHttpCode() == 200){
						echo http_response_code(200);
					}
					else{
						echo http_response_code(500);
					}
				}
				else{
					echo http_response_code(400);
					echo "failed to get id";
				}
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
	//	FIX THIS TO TAKE OUT MORE DATA THAN JUST TEXT
	$returnData = array();
	foreach($data as $tweet){
		$item = array();
		$item['text'] = $tweet['text'];
		$item['created_at'] = $tweet['created_at'];
		$item['id'] = $tweet['id'];
		
		//	Get entities, where urls and media is 
		if(isset($tweet['entities'])){
			$media = $tweet['entities'];
			$item['entities'] = $media;
		}
		$user = $tweet['user'];
		$item['user'] = $user;
		if(isset($tweet['geo'])){
			$item['geo'] = $tweet['geo'];
		}
		if(isset($tweet['coordinates'])){
			$item['coordinates'] = $tweet['coordinates'];
		}
		if(isset($tweet['place'])){
			$item['place'] = $tweet['place'];
		}
		$item['retweet_count'] = $tweet['retweet_count'];
		$item['favorite_count'] = $tweet['favorite_count'];
		$item['favorited'] = $tweet['favorited'];
		$item['retweeted'] = $tweet['retweeted'];
		
		$returnData[] = $item;
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
	//	Check if user profile is cached
	if(isset($_SESSION['userProfile' . $userName])){
		return $_SESSION['userProfile' . $userName];
	}
	
	$twitter = $_SESSION['twitter_user'];
	$user = json_encode($twitter->get("users/show", ["screen_name" => $userName]));
	$data = json_decode($user, true);
	//var_dump($user);
	
	$userData = array();
	$userData['id'] = $data['id'];
	$userData['name'] = $data['name'];
	$userData['screen_name'] = $data['screen_name'];
	if(isset($data['location'])){
		$userData['location'] = $data['location'];
	}
	if(isset($data['profile_location'])){
		$userData['profile_location'] = $data['id'];
	}
	if(isset($data['description'])){
		$userData['description'] = $data['description'];
	}
	if(isset($data['url'])){
		$userData['url'] = $data['url'];
	}
	$userData['followers_count'] = $data['followers_count'];
	$userData['friends_count'] = $data['friends_count'];
	$userData['listed_count'] = $data['listed_count'];
	$userData['created_at'] = $data['created_at'];
	$userData['favourites_count'] = $data['favourites_count'];
	$userData['statuses_count'] = $data['statuses_count'];
	if(isset($data['status'])){
		$status = $data['status'];
		$userData['status'] = $status;
	}
	$userData['profile_background_color'] = $data['profile_background_color'];
	$userData['profile_image_url'] = $data['profile_image_url'];
	
	if(isset($data['profile_background_image_url'])){
		$userData['profile_background_image_url'] = $data['profile_background_image_url'];
	}
	
	$_SESSION['userProfile' . $userName] = $userData;
	return($userData);
	
	
}

function uploadMedia(&$path){
	//	Twitter requires Base64 encoding or raw binary. 
	//$file = file_get_contents($path);
	//$encoded = base64_encode($file);
	//if(isset($encoded)){
		$twitter = $_SESSION['twitter_user'];
		$response = $twitter->upload(
			"media/upload", [
				"media" => $path
			]
		);
		$data = json_decode(json_encode($response), true);
		//echo $data['code'];
		return($data['media_id']);
		
	//}
}

function postTweetWithMedia(&$text, &$mediaId){
	$twitter = $_SESSION['twitter_user'];
	$parameters = [
    'status' => $text,
    'media_ids' => $mediaId
	];
	$result = $twitter->post('statuses/update', $parameters);
	return $result;
}



















?>