//	AppController

/*	CONTROLLER FOR MAIN CONTAINER HOLDING BOTH MENU AND CONTENT */
angular.module('app')
	.controller('startController', function ($scope, $http) {
		$scope.username = "asd";

		$http.get('php/twitter_calls.php', {
			params: {
				func: 'screenName'
			}
		}).then(
			function (data) {
				//	Success
				$scope.username = data['data'];
			},
			function (data) {
				//	Failure
				$scope.username = "N.A";
				console.log("failure getting user name: ", data);
			});
	})

/*	CONTROLLER FOR MENU */
.controller('menuController', function ($scope, $http) {
	var self = this;

	this.username = "asd";

	angular.element(document).ready(function () {
		$('.tooltipped').tooltip({
			delay: 50
		});
	
		//	HANDLE TAB CLICKS
		//		Add class active to the pressed tab
		$(".tab").click(function(event){
			//	Remove class active from all tabs
			$(".tab").removeClass("active");
			//	Add class active to the calling element
			$(this).addClass("active");
		});
	});
	
	$http.get('php/twitter_calls.php', {
		params: {
			func: 'screenName'
		}
	}).then(
		function (data) {
			//	Success
			self.username = data['data'];
		},
		function (data) {
			//	Failure
			self.username = "N.A";
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

/*	CONTROLLER FOR CREATING TWEET */
.controller('tweetController', 
function($http, Upload, $scope){
	var self = this;
	this.thumb = "images/thumb.svg";
	
	
	activate();
	
	function activate(){
		console.log("activate");
		self.imgSrc = undefined;
		self.image = false;
		self.tweet = {};
		self.imageFile = undefined;
		angular.element("#showImageBtn").removeClass("disabled");
	}	
	
	this.clear = function(){
		self.imgSrc = undefined;
		self.image = false;
		self.tweet = {};
		self.imageFile = undefined;
		angular.element("#showImageBtn").removeClass("disabled");
	}
	
	this.uploadImage = function(file){
		console.log(file);
		
		Upload.upload({
			url: 'php/imageUploader.php',
			method: 'POST',
			data: {file: file, 'username': "asd"}
		}).then(function(data){
			//	Success
			/* Expected path to uploaded file in data.path */
			self.tweet.media = data.data.path; 
			//	TODO the user needs some kind of confirmation that the upload went through
		}, function(data){
			//	Error
			console.log('Error status: ' + data.status);
		}, function(evt){
			//	Progress
			var progress = parseInt(100.0 * evt.loaded / evt.total);
			console.log(progress);
		});
	}
	
	
	this.addImage = function(){
		self.imgSrc = "https://www.smashingmagazine.com/wp-content/uploads/2015/06/10-dithering-opt.jpg";
		
		//showImage();
	}
	
	this.fileChanged = function(){
		var fileUpload = $('#modalFileUpload');
		if('files' in fileUpload){
			if(fileUpload.files.length == 0){
				//	No files selected	
			}
			else{
				//	Only care about one file, the first
				var file = fileUpload.files[0];
				
			}
		}
	}
	
	this.removeImage = function(){
		self.image = false;
		self.imgSrc = undefined;
		
		angular.element("#showImageBtn").removeClass("disabled");
	}
	
	/*	Sets image to true displaying the imageArea */
	this.showImageArea = function (){
		self.image = true;
		angular.element("#showImageBtn").addClass("disabled");
	}
	
	this.post = function(){
		//	Check data
		$http.get('php/twitter_calls.php', {
			params: {
				func: 'postTweet',
				text: self.tweet.tweetText,
				media: self.tweet.media
			}
		}).then(
			function (data) {
				//console.log("success: ", data);
				console.log(data);
				self.clear();
				//$scope.timeline = data['data'];
			}),
		function (data) {
			// failure
			console.log("error: ", data);
		};
	}
	
})

/*	CONTROLLER FOR FIXED FLOATING ACTION BUTTON */
.controller('tweetFabController',
function(){
	var self = this;
	
	angular.element(document).ready(function(){
		$('#tweetModal').modal({
      dismissible: true, // Modal can be dismissed by clicking outside of the modal
      in_duration: 300, // Transition in duration
      out_duration: 200, // Transition out duration
      ready: function(modal, trigger) {
				// Callback for Modal open. Modal and trigger parameters available.
				//	Could set saved text here
      },
      complete: function() { 
				// 	Callback for Modal close
				//	Could save text here
			} 
    });
	});
	
	//	Open modal for tweet selection
	this.openTweetModal = function(){
		$('#tweetModal').modal('open');
	}
})

/*	CONTROLLER FOR FRONTPAGE */
.controller('frontpageController', 
function ($scope, $http) {
	var self = this;

	//  Call to get 10 timeline tweets
	$http.get('php/twitter_calls.php', {
		params: {
			func: 'timeline',
			count: '10'
		}
	}).then(
		function (data) {
			//console.log("success: ", data);
			console.log(data['data'].length);
			if(data['data'].length > 0){
				$scope.timeline = data['data'];
			}
		}),
	function (data) {
		// failure
		console.log("error: ", data);
	};
})

/*	CONTROLLER FOR PROFILE PAGE */
.controller('profileController',
function ($http) {
	var self = this;
	this.userData = "asd";
	//	Get user profile
	$http.get('php/twitter_calls.php', {
			params: {
				func: 'userProfile'
			}
		}).then(
			function (data) {
				//console.log("success: ", data);
				console.log(data['data']);
				self.userData = data['data'];
			}),
		function (data) {
			// failure
			console.log("error: ", data);
		};
	angular.element(document).ready(function(){
		
    $('.parallax').parallax();
   
	});

})

/*	CONTROLLER FOR TWEET CARDS WITH TOOLTIP */
.controller('cardController',
function ($scope, $http) {
	var self = this;
	//	Setup states
	$scope.moreInformation = true;
	
	/*	Called when the favoritebutton is pressed
		sends a request to twitter_calls.php to favorite the tweet
	*/
	this.favorite = function(index){
		//	Get id of the tweet
		var tweet = $scope.timeline[index];
		//	favorite a tweet
		$http.get('php/twitter_calls.php', {
				params: {
					func: 'favorite',
					tweetId: tweet.id
				}
			}).then(
				function (data) {
					console.log(data['data']);
					//	Increment the favorites by 1
					$scope.timeline[index].favorite_count +=1;
					$('a.' + $scope.timeline[index].id).addClass('disabled');
				}),
			function (data) {
				// failure
				console.log("error: ", data);
			};
	}
	
	/*	Called when the favoritebutton is pressed
	*/
	this.comment = function(index){
		
	}
	
	/*	Called when the favoritebutton is pressed
	*/
	this.retweet = function(index){
		//	Get id of the tweet
		var tweet = $scope.timeline[index];
		
		//	retweet a tweet
		$http.get('php/twitter_calls.php', {
				params: {
					func: 'retweet',
					tweetId: tweet.id
				}
			}).then(
				function (data) {
					console.log(data['data']);
					//	Increment the favorites by 1
					$scope.timeline[index].retweet_count +=1;
					$('span.' + $scope.timeline[index].id).addClass('disabled');
				}),
			function (data) {
				// failure
				console.log("error: ", data);
			};
	}
	
	angular.element(document).ready(function () {
		//	Instantiate tooltip for the card
		$('.tooltipped').tooltip({
			delay: 50
		});
	});

});