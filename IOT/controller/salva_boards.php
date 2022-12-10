<?php
session_name(md5("seg".$_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"]));
session_start();

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

if(isset($_SESSION['ultima_atividade']) && (time()-$_SESSION['ultima_atividade'] > 900)){
	
	$resposta = array();
	
	array_push($resposta,
		array('status' => '1',
			  'mensagem' => Traducao::t('A sua sessão expirou, efetue o login novamente!')
		)
	);
	echo json_encode($resposta);
	die();
}

$id_pessoa = $_SESSION['id_pessoa'];

$perfil=$_SESSION['perfil'];

$nome_board = $_POST['nome_board'];


$board = new Board();
$board -> setnome_board($nome_board);
$board -> setid_pessoa($id_pessoa);

$resposta = array();

$incluirBoard = new BoardDAO();
$respostaBoard = $incluirBoard  -> cadastrarBoard($board);
	
array_push($resposta,
		array('status' => $respostaBoard[0]['status'],
			  'mensagem' => Traducao::t($respostaBoard[0]['mensagem'])
		)
	);
echo json_encode($resposta);
?>