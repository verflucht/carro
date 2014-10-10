<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  

	session_start();
	mysql_query("SET NAMES 'utf8'");

	$id = $_POST['id'];
	$cantidad = $_POST['cantidad'];

	if (!isset($_SESSION['lista'])) {
		echo "Seleccione una Lista primero";
	}
	else if($cantidad == "0"){
		unset($_SESSION['lista'][$id]);
	}
	else{

		foreach ($_SESSION['lista'] as $key => $value) {
			if($_SESSION['lista'][$key] == $id){
				$_SESSION['lista'][$key][$value] = $cantidad;
				break;
			}
			else{
				$_SESSION['lista'][$id] = $cantidad;
				break;
			}
		}


		#print_r("Producto Agregado al Carro");
	}
	//print_r($_SESSION['lista']);

?>