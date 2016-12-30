app
.config(function($stateProvider, $urlRouterProvider){
	
	$urlRouterProvider.otherwise('/main/front');
	
	$stateProvider
	.state('main',{
		url: '/main',
		abstract: true,
		templateUrl:'templates/menu.html'
	})
		
		.state('main.frontpage',{
			url: '/front',
			templateUrl:'templates/frontPage.html'
		})

		.state('main.about', {
			url: '/about',
			templateUrl:'templates/second.html'
		})
	
	
});