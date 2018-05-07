angular.module('listaTelefonica').factory('contatosAPI', function($http, config){

	var _getContatos = function(){
		return $http.get(config.controllerPhpUrl);
	};

	var _setContatos = function(data){
		console.log(data);
		return $http.post(config.controllerPhpUrl, data, {
				withCredentials: true,
               	headers: {
                 	'Content-Type': undefined,
                 	'Access-Control-Allow-Origin': '*'
               },
               transformRequest: angular.identity
           });
	};

	var _updateContatos = function(data){
		return $http.put(config.controllerPhpUrl, data);
	};

	var _deletarContatos = function(data){
		return $http.delete(config.controllerPhpUrl, {data: data});
	};

	return {
		getContatos: _getContatos,
		setContatos: _setContatos,
		updateContatos: _updateContatos,
		deletarContatos: _deletarContatos
	};

});