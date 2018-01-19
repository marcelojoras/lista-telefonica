<?php
include '../dao/contatodao.class.php';
include '../util/wideimage/WideImage.php';

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		echo (new ContatoDAO())->buscarContatos();

		break;

	case 'POST':
		var_dump($_POST);
		die();
		//fazendo o upload da imagem de perfil
		try{
    		if($_FILES['file']['name']!=''){
        		$wide = WideImage::load($_FILES['file']['tmp_name']);
	            $resized = $wide->resize(400);
	            $novoNome = strtolower('['.rand().']'.'.png');
	            $resize = $resized->saveToFile('../imagens/contato/'.$novoNome);
        	}else{
        		$novoNome = 'perfilPadrao.jpg';
        	}//fecha if
	    }catch(Exception $exc){
	        echo 'erro ao cadastrar foto de perfil '.$exc;
	    }//fecha catch

	    if(isset($_POST['contato']))
	    	$contato = json_decode($_POST['contato'], true);
	   	else{
			$data = json_decode(file_get_contents("php://input"), true);
			$contato = $data['contato'];
	   	}


		$contato['foto'] = $novoNome;

		$erros = [];

		if(!isset($contato['nome']) || is_null($contato['nome'])){
			$erros[] = "Preencha o nome";
		}

		if(!isset($contato['data'])){
			$contato['data'] = '';
		}

		if(count($erros)>0){
			die(json_encode($erros));
		}

		if((new ContatoDAO())->adicionarContato($contato)){
			header("HTTP/1.0 201 Criado", 201, true);
		}else{
			die("Não foi possível cadastrar no banco");
		}

		break;

	case 'DELETE':

		$contatos = json_decode(file_get_contents("php://input"), true);

		echo json_encode($contatos);

		$ids = [];

		foreach ($contatos as $contato) {
			$ids[] = $contato["id"];
		}

		if((new ContatoDAO())->deletarContatos($ids)){
			header("HTTP/1.0 202 Criado", 202, true);
		}else{
			die("Não foi possível deletar no banco");
		}

		break;

	case 'PATCH':
		var_dump($_FILES);
		var_dump($_POST);
		$data = json_decode(file_get_contents("php://input"), true);
		var_dump($data);
		die();
		//fazendo o upload da imagem de perfil
		try{
    		if($_FILES['file']['name']!=''){
        		$wide = WideImage::load($_FILES['file']['tmp_name']);
	            $resized = $wide->resize(400);
	            $novoNome = strtolower('['.rand().']'.'.png');
	            $resize = $resized->saveToFile('../imagens/contato/'.$novoNome);
        	}else{
        		$novoNome = 'perfilPadrao.jpg';
        	}//fecha if
	    }catch(Exception $exc){
	        echo 'erro ao cadastrar foto de perfil '.$exc;
	    }//fecha catch

	    if(isset($_POST['contato']))
	    	$contatoAlterado = json_decode($_POST['contato'], true);
	   	else{
			$data = json_decode(file_get_contents("php://input"), true);
			$contatoAlterado = $data['contato'];
	   	}

	   	if($novoNome!='perfilPadrao.jpg' || $contatoAlterado['foto']=='perfilPadrao.jpg')
	   		$contatoAlterado['foto'] = $novoNome;

		$erros = [];

		if(!isset($contatoAlterado['nome']) || is_null($contatoAlterado['nome'])){
			$erros[] = "Preencha o nome";
		} 

		if(!isset($contatoAlterado['data'])){
			$contatoAlterado['data'] = '';
		}

		if(count($erros)>0){
			die("Não foi possível alterar no banco!");
		}

		if((new ContatoDAO())->alterarContato($contatoAlterado)){
			header("HTTP/1.0 202 Aceito", 202, true);
		}else{
			die("Não foi possível alterar no banco");
		}

		break;
	
	default:
		//code
		break;
}

?>