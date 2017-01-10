angular.module('app')
.directive('repeatDirective', function(){
	return function(scope){
		scope.stateClassFav = '';
		scope.stateClassRt = '';
		var tw = scope.timeline[scope.$index];
		//console.log(tw);
		if(tw != undefined){
			if(tw.favorited === true)
				scope.stateClassFav = 'disabled';
			if(tw.retweeted === true)
				scope.stateClassRt = 'disabled';
		}
		
		//	Could put some nice colors here if i want to
		//		This will then be unique for each tweet
		//	http://stackoverflow.com/questions/13471129/ng-repeat-finish-event
		//	PLUNKER: http://plnkr.co/edit/or5mys?p=preview
		/*if(scope.$last){
			console.log("last tweet");
			scope.$emit('lastElement');
		}*/
	};
})
.directive('timelineDirective', function(){
	return function(scope){
		
		
		scope.$on('LastElement', function(){
			//$('.tooltipped').tooltip({delay: 50});
		});
	};
});