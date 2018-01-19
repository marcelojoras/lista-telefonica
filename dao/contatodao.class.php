<?php
include '../persistencia/conexaobanco.class.php';
class ContatoDAO{
	
	private $conexao=null;
	
	public function __construct(){
		/* Buscando uma instancia de banco na classe
		   ConexaoBanco.class.php */
	 $this->conexao = ConexaoBanco::getInstancia();   
	}//fecha construtor
	
	//Método cadastrar
	public function adicionarContato($c){
		try{
	$stat=$this->conexao->prepare("insert into contato(nome, telefone, data, operadora, foto)values(?, ?, ?, ?, ?)");			

			$stat->bindValue(1, $c['nome']);
			$stat->bindValue(2, $c['telefone']);
			$stat->bindValue(3, $c['data']);
			$stat->bindValue(4, $c['operadora']['nome']);
			$stat->bindValue(5, $c['foto']);		
			
			//encerramento da conexão
			$this->conexao = null;

			return $stat->execute();
	
		}catch(PDOException $e){
			echo 'Erro ao cadastrar Contato'.$e;
		}//fecha catch
	}//fecha cadastrar
	
	//Método Buscar
	public function buscarContatos(){
		try{
			return json_encode(
				$this->conexao->query("select * from contato")->fetchAll(PDO::FETCH_ASSOC)
			);
		}catch(PDOException $e){
			echo 'Erro ao buscar contatos '.$e;
		}//fecha catch
	}//fecha buscarUsuarios

	//Método Deletar
	public function deletarContatos($ids){
		try{
			$stat = $this->conexao->query("delete from contato where id in(".implode(',', $ids).")");

			return $stat->execute();
		}catch(PDOException $e){
			echo 'Erro ao deletar contatos '.$e;
		}//fecha catch
	}//fecha deletar

	public function alterarContato($contato){
		try{
			$stat=$this->conexao->prepare("update contato set nome = ?, telefone = ?, data = ?, operadora = ? foto = ? where id = ?");

			$stat->bindValue(1,$contato['nome']);
			$stat->bindValue(2,$contato['telefone']);
			$stat->bindValue(3,$contato['data']);
			$stat->bindValue(4,$contato['operadora']);
			$stat->bindValue(5,$contato['foto']);
			$stat->bindValue(6,$contato['id']);


			$this->conexao = null;

			return $stat->execute();
		}catch(PDOException $e){
			echo "Erro ao alterar Contato ".$e;
		}//fecha catch
	}//fecha alterar

}//fecha classe
?>