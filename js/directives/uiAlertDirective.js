angular.module("listaTelefonica").directive("uiAlert", function(){
	return{
		templateUrl: "../lista-telefonica/view/ui-alert.html",
		replace: true,
		restrict: "AE",
		scope: {
			title: "@"
			//messege: "="
		},
		transclude: true
	};
});