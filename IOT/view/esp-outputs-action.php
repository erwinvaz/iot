<?php
include ($_SERVER["DOCUMENT_ROOT"]."/debug/Debug.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/PDOConnectionFactory/PDOConnectionFactory.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/IOT/model/board.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/IOT/DAO/BoardDAO.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/lib/Traducao.class.php"); 
include ($_SERVER["DOCUMENT_ROOT"]."/log/DAO/LogDAO.class.php");
include ($_SERVER["DOCUMENT_ROOT"]."/lib/guestip.php");

$captureIp = new GuestIp();
$ip_user = $captureIp->getIp();

$action = $id = $name = $gpio = $state = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = test_input($_POST["action"]);
        if ($action == "output_create") {
            $name = test_input($_POST["name"]);
            $board = test_input($_POST["board"]);
            $gpio = test_input($_POST["gpio"]);
            $state = test_input($_POST["state"]);
            $result = createOutput($name, $board, $gpio, $state);

            $result2 = getBoard($board);
            if(!$result2->fetch_assoc()) {
                createBoard($board);
            }
            echo $result;
        }
        else {
            echo "No data posted with HTTP POST.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $action = test_input($_GET["action"]);
        if ($action == "outputs_state") {
            $codigo_board = test_input($_GET["board"]);
			$id_pessoa = test_input($_GET["id_pessoa"]);

			$listarBoard = new BoardDAO();
			$listarBoard  -> getAllOutputStates($id_pessoa,$codigo_board) ;
        }
        else if ($action == "output_update") {
            $id = test_input($_GET["id"]);
            $state = test_input($_GET["state"]);
            $result = updateOutput($id, $state);
            echo $result;
        }
        else if ($action == "output_delete") {
            $id = test_input($_GET["id"]);
            $board = getOutputBoardById($id);
            if ($row = $board->fetch_assoc()) {
                $board_id = $row["board"];
            }
            $result = deleteOutput($id);
            $result2 = getAllOutputStates($board_id);
            if(!$result2->fetch_assoc()) {
                deleteBoard($board_id);
            }
            echo $result;
        }
        else {
            echo "Invalid HTTP request.";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>