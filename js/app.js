angular.module("listaTelefonica", ["ngMask"]); //Criando meu módulo (aplicação)

angular.module("listaTelefonica").controller('listaTelefonicaController',function($scope, $filter, $http){ //criando meu controle, primeiro localizei o módulo e atribui o controle
	$scope.titulo = "Lista Telefônica"; //criando uma variável comum, está guardando apenas um texto.
	$scope.criterioDeOrdenacao = 'nome';
	$scope.contatos = [];//criando um array com os contatos, nome e telefone sao as chaves desse array
	$scope.operadoras = [
		{nome: "Oi", codigo: 14, categoria: "Celular", preco: 2},
		{nome: "Vivo", codigo: 15, categoria: "Celular", preco: 1},
		{nome: "Tim", codigo: 41, categoria: "Celular", preco: 3},
		{nome: "GVT", codigo: 25, categoria: "Fixo", preco: 2},
		{nome: "Embratel", codigo: 21, categoria: "Fixo", preco: 1}
	];
	// $scope.adicionarContato = function(contato){ //criando uma função para adicionar mais contatos dentro do array, usando o push do javascript e o copy do angular.
	// 	contato.nome = $filter('uppercase')(contato.nome);
	// 	contato.data = new Date();
	// 	$scope.contatos.push(angular.copy(contato));
	// 	delete $scope.contato; //Resetando o campo
	// 	$scope.contatoForm.$setPristine(); //resetando os campos para pristine, ja que estavam dirty
	// };
	$scope.class1 = "selecionado";
	$scope.class2 = "negrito";
	$scope.apagarContatos = function(contatos){
		var delContatos = contatos.filter(function(contato){
			if (contato.selecionado) return contato;
		});

		$http.delete("../lista-telefonica/controle/contatocontrole.php", {data: delContatos}).then(function(p){
			if(p.status==202){
				$scope.buscarContatos();
			}else{
				alert("deu erro");
				console.log(p);
			}
		}).catch(function(e){
			console.log('error ' + e);
		});
	};
	$scope.isContatoSelecionado = function(contatos){
		var isContatoSelecionado = contatos.some(function(contato){
			return contato.selecionado;
		});
		return !isContatoSelecionado;
	};
	$scope.ordenarPor = function(campo){
		$scope.criterioDeOrdenacao = campo;
		$scope.direcaoDaOrdenacao = !$scope.direcaoDaOrdenacao;
	};

	$scope.adicionarContato = function(c, file){
		if(!c) c = {};
		c.data = new Date();

		//criando um formdata (conjunto de variaveis de formulario) vazio
		var data = new FormData();

		//adicionando arquivo na variavel File
		data.append('file', file);

		//adicionando contato na variavel contato
		data.append('contato', JSON.stringify(c));

		$http.post("../lista-telefonica/controle/contatocontrole.php", data, {
				withCredentials: true,
               	headers: {
                 	'Content-Type': undefined,
                 	'Access-Control-Allow-Origin': '*'
               },
               transformRequest: angular.identity
           }).then(function(p){
			if(p.status==201){
				delete $scope.cadContato;
				$scope.contatoForm.$setPristine();
				$scope.buscarContatos();
			}else{
				alert("deu erro");
				console.log(p);
			}
		}).catch(function(e){
			console.log('error' + e);
		});
	};

	$scope.buscarContatos = function(){
		$http.get("../lista-telefonica/controle/contatocontrole.php").then(function(p){
			$scope.contatos = p.data;
			// setTimeout(function(){$scope.$apply();},50);
		}).catch(function(e){
			console.log(e);
		});
	};

	$scope.fileChanged = function(event){
		$scope.file = event.target.files[0];
		$scope.$apply();
    };

	$scope.buscarContatos();

	$scope.alterarContato = function(contato){
		$scope.alterar = true;
		console.log(contato);
		$scope.cadContato = angular.copy(contato);
	};

	$scope.alterarContatoBanco = function(contatoAlterado, file){

		console.log(file);
		//criando um formdata (conjunto de variaveis de formulario) vazio
		var data = new FormData();

		//adicionando arquivo na variavel File
		data.append('file', file);

		//adicionando contato na variavel contato
		data.append('contato', JSON.stringify(contatoAlterado));

		data.append('_method', 'PATCH');

		$http.post("../lista-telefonica/controle/contatocontrole.php", data, {
				data: data,
				withCredentials: true,
               	headers: {
                 	'Content-Type': undefined,
                 	'Access-Control-Allow-Origin': '*'
               },
               transformRequest: angular.identity
           }).then(function(p){
			if(p.status==202){
				delete $scope.cadContato;
				$scope.contatoForm.$setPristine();
				$scope.buscarContatos();
				$scope.alterar = false;
			}else{
				alert("deu erro");
				console.log(p);
			}
		}).catch(function(e){
			console.log('error' + e);
		});
	};
	
}).directive('customOnChange', function() {
   return {
     restrict: 'A',
     link: function (scope, element, attrs) {
       var onChangeHandler = scope.$eval(attrs.customOnChange);
       element.bind('change', onChangeHandler);
     }
   };
});