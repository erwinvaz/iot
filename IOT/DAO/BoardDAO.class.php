<?php
/*
 * Classname             BoardDAO
 * 
 * Version information   (1.2)
 *
 * Date                  (11/10/2018)
 * 
 * author                (Erwin Rommel Oliveira Vaz)
 * Copyright notice      (Classe de persistencia de dados na Tabela Board
 */
class BoardDAO {

	public $conexao;
    public $con;
    
	/* Construtor da Classe, inicializa a conexão*/
	public function __construct() {
		$this->con = new PDOConnectionFactory();
		$this->conexao = $this->con->getConnection();
	}

	public function cadastrarBoard(Board $board) {
		
		$operacao = array();
		
		$queryDespesa = "INSERT INTO boards (`id_board`,`id_pessoa`,`nome_board`) VALUES (null,?,?)";
			
				
		$stmt = $this->conexao->prepare($queryDespesa);
				
		$stmt->bindValue(1, $board -> getid_pessoa(), PDO::PARAM_INT);
		$stmt->bindValue(2, $board -> getnome_board(), PDO::PARAM_STR);
				
		$inserted = $stmt -> execute();
				
			
			
		if($inserted){
						
			array_push($operacao,
				array('status' => '0',
					  'mensagem' => "Operação realizada com sucesso!"
				)
			);
			$this->con->Close();
			return $operacao;
						
		}else{
					
			array_push($operacao,
				array('status' => '1',
					  'mensagem' =>$stmt->errorInfo()
				)
			);
			$this->con->Close();
			return $operacao;
							
		}
			
	}
	public function cadastrarComponente(Board $board) {
		
		$operacao = array();
		
		$queryDespesa = "INSERT INTO components_board (`id_comp_board`,`id_board`,`componente`,`gpio`,`state`) VALUES (null,?,?,?,?)";
			
				
		$stmt = $this->conexao->prepare($queryDespesa);
				
		$stmt->bindValue(1, $board -> getid_board(), PDO::PARAM_INT);
		$stmt->bindValue(2, $board -> getcomponente(), PDO::PARAM_STR);
		$stmt->bindValue(3, $board -> getgpio(), PDO::PARAM_INT);
		$stmt->bindValue(4, $board -> getstate(), PDO::PARAM_INT);
				
		$inserted = $stmt -> execute();
				
			
			
		if($inserted){
						
			array_push($operacao,
				array('erro' => '0',
					'mensagem' => "Operação realizada com sucesso!"
				)
			);
			$this->con->Close();
			return $operacao;
						
		}else{
					
			array_push($operacao,
				array('erro' => '1',
					'mensagem' =>$stmt->errorInfo()
				)
			);
			$this->con->Close();
			return $operacao;
							
		}
			
	}
	public function montaBoards($id_pessoa) {
	
		$boards = array();
		$query = "SELECT * FROM boards where id_pessoa=?";
		
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(1, $id_pessoa, PDO::PARAM_INT);
		$stmt -> execute();
		while ($obj = $stmt -> fetch(PDO::FETCH_OBJ)) {
			
			array_push($boards,
				array('id_elemento' => $obj->id_board,
					'descricao' => Traducao::t($obj->nome_board),
					'alterar' => 0
				)
			);
		
		}		
		return $boards;
		$this->con->Close();
		
	}
	public function  listarBoards($id_pessoa,$board) {
		
		if($board==NULL){$board=-1;}
		$boards = array();
		$queryTotalRegistros = "SELECT count(*) as COUNT_OF_RECS_IN_MY_TABLE FROM boards b 
								INNER JOIN components_board cb 
								ON b.id_board = cb.id_board 
								where b.id_pessoa = ? and b.id_board = $board";
								
		$queryTotalRegistros = $this->conexao->prepare($queryTotalRegistros);
		$queryTotalRegistros -> bindValue(1, $id_pessoa, PDO::PARAM_INT);
		$queryTotalRegistros -> execute();
		$totalRegistros = $queryTotalRegistros->fetch(PDO::FETCH_OBJ);
		
		if($totalRegistros->COUNT_OF_RECS_IN_MY_TABLE>0){
			
			
			$query = "SELECT * FROM boards b 
								INNER JOIN components_board cb 
								ON b.id_board = cb.id_board 
								where b.id_pessoa = ? and b.id_board = $board";
			
			
			$stmt = $this->conexao->prepare($query);
			$stmt -> bindValue(1, $id_pessoa, PDO::PARAM_INT);
			$stmt -> execute();
				
			while ($obj = $stmt -> fetch(PDO::FETCH_OBJ)) {
				
				array_push($boards,
					array('id_board' => $obj->id_board,
						'id_pessoa' => $obj->id_pessoa,
						'codigo_board' => $obj->codigo_board,
						'last_request' => $obj->last_request,
						'id_comp_board' => $obj->id_comp_board,
						'componente' => $obj->componente,
						'gpio' => $obj->gpio,
						'state' => $obj->state
					)
				);
				
			}		
			return $boards;
			$this->con->Close();
		
		}else{
			
			return  $boards;
			$this->con->Close();
		}
			
	}
	public function  listarAllBoards($id_pessoa) {
		
		//if($board==NULL){$board=-1;}
		$boards = array();
		$queryTotalRegistros = "SELECT count(*) as COUNT_OF_RECS_IN_MY_TABLE FROM boards b 
								INNER JOIN components_board cb 
								ON b.id_board = cb.id_board 
								where b.id_pessoa = ? ";
								
		$queryTotalRegistros = $this->conexao->prepare($queryTotalRegistros);
		$queryTotalRegistros -> bindValue(1, $id_pessoa, PDO::PARAM_INT);
		$queryTotalRegistros -> execute();
		$totalRegistros = $queryTotalRegistros->fetch(PDO::FETCH_OBJ);
		
		if($totalRegistros->COUNT_OF_RECS_IN_MY_TABLE>0){
			
			
			$query = "SELECT * FROM boards b 
								INNER JOIN components_board cb 
								ON b.id_board = cb.id_board 
								where b.id_pessoa = ? ORDER BY b.nome_board ASC";
			
			
			$stmt = $this->conexao->prepare($query);
			$stmt -> bindValue(1, $id_pessoa, PDO::PARAM_INT);
			$stmt -> execute();
				
			while ($obj = $stmt -> fetch(PDO::FETCH_OBJ)) {
				
				array_push($boards,
					array('id_board' => $obj->id_board,
						'id_pessoa' => $obj->id_pessoa,
						'nome_board' => $obj->nome_board,
						'last_request' => $obj->last_request,
						'id_comp_board' => $obj->id_comp_board,
						'componente' => $obj->componente,
						'gpio' => $obj->gpio,
						'state' => $obj->state
					)
				);
				
			}		
			return $boards;
			$this->con->Close();
		
		}else{
			
			return  $boards;
			$this->con->Close();
		}
			
	}
	public function updateComponente($board){
		
		$queryUpdateBoard = "UPDATE components_board SET state=? WHERE id_comp_board=? ";
		$stmt = $this->conexao->prepare($queryUpdateBoard);
					
		$stmt->bindValue(1, $board -> getstate(), PDO::PARAM_INT);
		$stmt->bindValue(2, $board -> getid_comp_board(), PDO::PARAM_INT);
		$updated = $stmt -> execute();
		echo $updated;
		
	}
	
	public function deletaComponente($board){
		
		$queryUpdateBoard = "delete from components_board  WHERE id_comp_board=? ";
		$stmt = $this->conexao->prepare($queryUpdateBoard);
					
		$stmt->bindValue(1, $board -> getid_comp_board(), PDO::PARAM_INT);
		$updated = $stmt -> execute();
		
		
	}
	public function  getAllOutputStates($id_pessoa,$board) {
		
		if($board==NULL){$board=-1;}
		
		$queryTotalRegistros = "SELECT count(*) as COUNT_OF_RECS_IN_MY_TABLE FROM boards b 
								INNER JOIN components_board cb 
								ON b.id_board = cb.id_board 
								where b.id_pessoa = ? and b.id_board = $board";
								
		$queryTotalRegistros = $this->conexao->prepare($queryTotalRegistros);
		$queryTotalRegistros -> bindValue(1, $id_pessoa, PDO::PARAM_INT);
		$queryTotalRegistros -> execute();
		$totalRegistros = $queryTotalRegistros->fetch(PDO::FETCH_OBJ);
		
		if($totalRegistros->COUNT_OF_RECS_IN_MY_TABLE>0){
			
			
			$query = "SELECT * FROM boards b 
								INNER JOIN components_board cb 
								ON b.id_board = cb.id_board 
								where b.id_pessoa = ? and b.id_board = $board";
			
			
			$stmt = $this->conexao->prepare($query);
			$stmt -> bindValue(1, $id_pessoa, PDO::PARAM_INT);
			$stmt -> execute();
				
			while ($obj = $stmt -> fetch(PDO::FETCH_OBJ)) {
				
				$rows[$obj->gpio] = $obj->state;
				
			}		
			echo json_encode($rows);
			$this->con->Close();
		
		}else{
			
			echo json_encode("");
			$this->con->Close();
		}
			
	}
	
	
}
?>