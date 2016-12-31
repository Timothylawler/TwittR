angular.module('app')

/*	FILTER FOR DATESTRING RETURNED BY TWITTER, BUILT IN ANGULAR FILTER DOESNT
			RECOGNISE THE DATE AS A DATE */
.filter('customDateFormatter', function(){
	return function(data){
		//	Since we might expect dynamic data here we dont want to do anything if undefined
		if(data == undefined)
			return;
		var dateArr = data.split(' ');
		//	Expected year to be last, month to be second and date to be third
		var returnData = dateArr[dateArr.length-1] + " " + dateArr[1] + " " + dateArr[2];
		return returnData;
	};
});