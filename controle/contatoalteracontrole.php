<?php
include '../dao/contatodao.class.php';
include '../util/wideimage/WideImage.php';

switch ($_SERVER['REQUEST_METHOD']) {

	case 'POST':
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
			$contatoAlterado['data'] = '';
		}

		if(count($erros)>0){
			die("Não foi possível alterar no banco!");
		}

		if((new ContatoDAO())->alterarContato($contato)){
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