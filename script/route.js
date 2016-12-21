app
.config(function($stateProvider, $urlRouterProvider){
	
	$urlRouterProvider.otherwise('/main/front');
	
	$stateProvider
	.state('main',{
		url: '/main',
		abstract: true,
		templateUrl:'menu.html'
	})
		
		.state('main.frontpage',{
			url: '/front',
			templateUrl:'includes/templates/frontPage.html'
		})

		.state('main.about', {
			url: '/about',
			templateUrl:'includes/templates/about.html'
		})
	
	
});