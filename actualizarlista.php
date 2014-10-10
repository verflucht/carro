<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  

	session_start();
	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");

	if(isset($_SESSION['lista'])){
		unset($_SESSION['lista']);
		unset($_SESSION['update']);
	}
	echo $_GET['id_lista'];

	$_SESSION['id_lista'] = $_GET['id_lista'];
	$_SESSION['lista'] = array();
	$_SESSION['update'] = "1";

	$sqlSyntax = 'SELECT id_producto, cantidad FROM descripcion_producto_lista WHERE id_lista = '.$_GET['id_lista'];

	echo $sqlSyntax;

	$listado = mysql_query($sqlSyntax) or die(mysql_error());

    while($row = mysql_fetch_assoc($listado)){
    	//Llenar la lista
    	$_SESSION['lista'][$row['id_producto']] = $row['cantidad'];
    }

    header("Location: categorias.php")
?>