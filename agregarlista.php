<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  

	session_start();
	mysql_query("SET NAMES 'utf8'");

	if ($_SESSION['lista'] == "") {
		echo "no se ha seleccionado una lista para agregar";
	}
	else{
		$id = $_POST['id'];
		$cantidad = $_POST['cantidad'];

		if (array_key_exists($id, $_SESSION['lista'])) {
			if ($_SESSION['lista'][$id] != $cantidad) {
				$_SESSION['lista'][$id] = $cantidad;
			}
		}
		else{
			$_SESSION['lista'] = array($id => $cantidad) + $_SESSION['lista'];
		}
		print_r($_SESSION['lista']);
	}

?>