app
.config(function($stateProvider, $urlRouterProvider, $locationProvider){
	
	$urlRouterProvider.otherwise('/front');
	
	$stateProvider
	.state('main',{
		url: '/',
		abstract: true,
		templateUrl:'templates/menu.html'
	})
		
		.state('main.frontpage',{
			url: 'front',
			templateUrl:'templates/frontPage.html'
		})

		.state('main.about', {
			url: 'about',
			templateUrl:'templates/second.html'
		})
		
		.state('main.profile', {
			url: 'profile',
			templateUrl:'templates/profile.html'
		})
	
	$locationProvider.hashPrefix('');
});