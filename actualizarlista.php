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

		if($cantidad == "0"){
			//Se elimina de la lista el producto
			unset($_SESSION['lista'][$id]);
		}
		//SI NO, SE ACTUALIZA LA LISTA
		else{
			$_SESSION['lista'][$id] = $cantidad;
		}
		print_r($_SESSION['lista']);
	}

?>