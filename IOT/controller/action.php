<?php
session_name(md5("seg".$_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"]));
session_start();

/* inicia a sessão */

if(isset($_SESSION['ultima_atividade']) && (time()-$_SESSION['ultima_atividade'] > 900)){
	//ultima atividade foi mais de (3600 = 60 minutos), (1800 = 30 minutos), (900 = 15 minutos) atras
		$mensagem = "Session expired!";
		echo $mensagem;
		die();	
}

include ($_SERVER["DOCUMENT_ROOT"]."/debug/Debug.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/PDOConnectionFactory/PDOConnectionFactory.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/IOT/model/board.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/IOT/DAO/BoardDAO.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/lib/Traducao.class.php"); 
include ($_SERVER["DOCUMENT_ROOT"]."/log/DAO/LogDAO.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/lib/guestip.php");

$captureIp = new GuestIp();
$ip_user = $captureIp->getIp();

$language = $_SESSION['language']; 
Traducao::setlanguageDefault($language);
Traducao::setPathFileTranslate($_SERVER["DOCUMENT_ROOT"]."/translate/");

$id_pessoa = $_SESSION['id_pessoa'];

$perfil=$_SESSION['perfil'];


$action = $_GET['action'];

if($action=="update"){
	
	$state = $_GET['state'];
	$componente = $_GET['id'];
	$board = new Board();
	$board -> setid_pessoa($id_pessoa);
	$board -> setid_comp_board($componente);
	$board -> setstate($state);


	$updateComponente = new BoardDAO();
	$respostaBoard = $updateComponente  -> updateComponente($board);
		
	$mensagem = Traducao::t($respostaBoard[0]['mensagem']);
	$email = $_SESSION['email'];

	$mensagem_log = "Atualiza Componente: ".$componente ." Resposta servidor: ".$mensagem;
	$insereLog = new LOG();
	$insereLog->insereLog($ip_user,$mensagem_log,$email);


	echo $mensagem;
	die();
	
}

if($action=="delete"){
	
	$componente = $_GET['id'];
	$board = new Board();
	$board -> setid_pessoa($id_pessoa);
	$board -> setid_comp_board($componente);


	$deletaComponente = new BoardDAO();
	$respostaBoard = $deletaComponente  -> deletaComponente($board);
		
	$mensagem = Traducao::t($respostaBoard[0]['mensagem']);
	$email = $_SESSION['email'];

	$mensagem_log = "Deleta Componente: ".$componente ." Resposta servidor: ".$mensagem;
	$insereLog = new LOG();
	$insereLog->insereLog($ip_user,$mensagem_log,$email);


	echo $mensagem;
	die();
	
}

?>