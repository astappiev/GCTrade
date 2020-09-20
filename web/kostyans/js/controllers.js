var app = angular.module('inventarApp', ['ngSanitize']);

app.controller('ListItem', function ($scope, $http) {
	$http.get('data.json').success(function(data) {
		$scope.Matr = data;
	});
});

app.directive('flexibleWidth', function($timeout) {
	return function(scope, element, attrs) {
        angular.element(document).ready(function() {
			if(!element.hasClass( "ng-hide" ))
			{
				var $desc = element.children().next();

				var h = element.find('h3');
				var len_h = h.text().length;
				var len_p = 0;
				angular.forEach(element.find('p'), function(value, key){
					var p = angular.element(value);
					if(!p.hasClass( "ng-hide" ) && !p.hasClass( "desc" ))
					{
						var len = p.text().length;
						if(len > len_p) len_p = len;
					}
				});

				len_h *= 20;
				len_p = (len_p > 20) ? (len_p * 6) : (len_p * 7);
				$desc.css('width', ((len_h > len_p) ? (len_h) : (len_p)) + 'px');
			}
        });
    };
});