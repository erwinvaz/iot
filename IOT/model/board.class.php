<?php
/******************************************************************************
* Class for custos
*******************************************************************************/

class Board
{
	/**
	* @var int
	* Class Unique ID
	*/
	private $id_board;
	
	/**
	* @var int
	* Class Unique ID
	*/
	private $id_comp_board;

	/**
	* @var int
	*/
	private $id_pessoa;
	
	/**
	* @var int
	*/
	private $codigo_board;

	/**
	* @var string
	*/
	private $nome_board;

	/**
	* @var string
	*/
	private $last_request;
	/**
	* @var 
	*/
	private $componente;
	/**
	* @var string
	*/
	private $gpio;
	/**
	* @var string
	*/
	private $state;
	
	

	public function setid_board($id_board)
	{
		$this->id_board = $id_board;
		return true;
	}

	public function getid_board()
	{
		return $this->id_board;
	}

	public function setid_comp_board($id_comp_board)
	{
		$this->id_comp_board = $id_comp_board;
		return true;
	}

	public function getid_comp_board()
	{
		return $this->id_comp_board;
	}
	
	
	public function setid_pessoa($id_pessoa)
	{
		$this->id_pessoa = $id_pessoa;
		return true;
	}

	public function getid_pessoa()
	{
		return $this->id_pessoa;
	}
	
	public function setcodigo_board($codigo_board)
	{
		$this->codigo_board = $codigo_board;
		return true;
	}

	public function getcodigo_board()
	{
		return $this->codigo_board;
	}
	

	public function setnome_board($nome_board)
	{
		$this->nome_board = $nome_board;
		return true;
	}

	public function getnome_board()
	{
		return $this->nome_board;
	}
	


	public function setlast_request($last_request)
	{
		$this->last_request = $last_request;
		return true;
	}
	public function getlast_request()
	{
		return $this->last_request;
	}
	public function setcomponente($componente)
	{
		$this->componente = $componente;
		return true;
	}

	public function getcomponente()
	{
		return $this->componente;
	}
	public function setgpio($gpio)
	{
		$this->gpio = $gpio;
		return true;
	}

	public function getgpio()
	{
		return $this->gpio;
	}
	public function setstate($state)
	{
		$this->state = $state;
		return true;
	}

	public function getstate()
	{
		return $this->state;
	}
} // END class custos
?>