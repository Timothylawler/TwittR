//	loginController

angular.module('app')
.controller('loginController'
, function($scope, $http){
	
	var twit;
	$scope.signIn = function(){
		//	Call php twitter_login.
		//alert("hello");
		var twit = window.open("http://localhost/www/php/twitter_login.php", "Twitter", "width=500, height=500");
		
		console.log(twit);
		/*$.ajax({
			url: 'php/twitter_login.php',
			success: function(response){
				alert(reponse);
			}
		});*/
		
	}
	function asd(path){
		console.log("in asd");
		$http.post('php/twitter_callback.php', path).then(function(data){
			//	Success
			alert("Success");
			twit.close();
		}, function(data){
			//	Failure
			alert("fail");
		});
	}
	
	window.logIn = function(data){
		console.log("calling parent");
		asd(data);
		
	}
	
})
.controller('callbackController'
, function($scope, $location, $window){
	$(document).ready(function(){
		var path = $location.absUrl();
		opener.logIn(path);
		$window.close();
	});
	
});