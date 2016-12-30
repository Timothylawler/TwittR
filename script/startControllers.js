//	AppController

/*	CONTROLLER FOR MAIN CONTAINER HOLDING BOTH MENU AND CONTENT */
angular.module('app')
.controller('startController'
, function($scope, $http){
	$scope.username = "asd";
	
	$http.get('php/twitter_calls.php', {params:{func: 'screenName'}}).then(
	function(data){
		//	Success
		$scope.username = data['data'];
	}, function(data){
		//	Failure
		$scope.username = "N.A";
		console.log("failure getting user name: ", data);
	});
})

/*	CONTROLLER FOR MENU */
.controller('menuController'
, function($scope, $http){
	
	$scope.username = "asd";
	 
	$(document).ready(function(){
		$('.tooltipped').tooltip({delay: 50});
		$('ul.tabs').tabs();
	});
	
	
	
	$http.get('php/twitter_calls.php', {params:{func: 'screenName'}}).then(
	function(data){
		//	Success
		$scope.username = data['data'];
	}, function(data){
		//	Failure
		$scope.username = "N.A";
		console.log("failure getting user name: ", data);
	});
	
	//	For the side menu if implemented
	/*$('.button-collapse').sideNav({
			menuWidth: 150, // Default is 240
			edge: 'left', // Choose the horizontal origin
			closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
			draggable: true // Choose whether you can drag to open on touch screens
		});*/
})

/*	CONTROLLER FOR FRONTPAGE */
.controller('frontpageController'
, function($scope, $http){
	$scope.timeline; 
    //  Call to get 10 timeline tweets
	$http.get('php/twitter_calls.php', {params:{func: 'timeline', count: '10'}}).then(
		function(data){
			//console.log("success: ", data);
			console.log(data['data']);
			$scope.timeline = data['data'];
		}), function(data){
			// failure
			console.log("error: ", data);
	};
	$scope.getTimeline = function(){
		//$scope.timeline = "Fetch timeline hehe"; 
		console.log("button pressed");
		$http.get('php/twitter_calls.php', {params:{func: 'timeline', count: '10'}}).then(
		function(data){
			//console.log("success: ", data);
			console.log(data['data']);
			$scope.timeline = data['data'];
		}), function(data){
			// failure
			console.log("error: ", data);
		};
		//$scope.timeline = "Hello data!";
	};
});