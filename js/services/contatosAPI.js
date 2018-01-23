angular.module('listaTelefonica').factory('contatosAPI', function($http){

	var _getContatos = function(){
		return $http.get("../lista-telefonica/controle/contatocontrole.php");
	};

	var _setContatos = function(data){
		return $http.post("../lista-telefonica/controle/contatocontrole.php", data, {
				withCredentials: true,
               	headers: {
                 	'Content-Type': undefined,
                 	'Access-Control-Allow-Origin': '*'
               },
               transformRequest: angular.identity
           });
	};

	var _updateContatos = function(data){
		return $http.put("../lista-telefonica/controle/contatocontrole.php", data);
	};

	var _deletarContatos = function(data){
		return $http.delete("../lista-telefonica/controle/contatocontrole.php", {data: data});
	};

	return {
		getContatos: _getContatos,
		setContatos: _setContatos,
		updateContatos: _updateContatos,
		deletarContatos: _deletarContatos
	};

});