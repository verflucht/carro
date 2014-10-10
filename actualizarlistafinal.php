<?php 
	
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  

	session_start();
	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");

	$id_lista = $_SESSION['id_lista'];
	$contenido_lista = $_SESSION['lista'];

	//UPDATE LOS QUE EXISTEN EN EL ARRAY Y EN DESC_PROD
	/*
		INSERT INTO table (id, name, age) 
		VALUES(1, "A", 19) 
		ON DUPLICATE KEY 
		UPDATE 
		name = VALUES(name), age = VALUES(age)
	*/
	$sqlSyntax = 'DELETE FROM descripcion_producto_lista WHERE id_lista = '.$id_lista;
	$result= @mysql_query($sqlSyntax); //Se ejecuta el query de $sqlSyntax  
    if ($result == FALSE) { die(@mysql_error()); }

    //SELECCIONAR LOS DATOS, si se igualan los del arreglo 
	$sqlSyntax = 'INSERT INTO descripcion_producto_lista (id_lista, id_producto, cantidad, isChecked) VALUES ';
	//Crear la sintaxis para llenar la lista
	$numItems = count($contenido_lista);
	$i = 0;
	foreach ($contenido_lista as $item => $value){
		$sqlSyntax .= '('.$id_lista.','.$item.','.$value.',0),';
		if(++$i === $numItems){
			//Ultimo elemento del array
			$sqlSyntax .= '('.$id_lista.','.$item.','.$value.',0)';
		}
	}
	print_r($sqlSyntax);

	$result= @mysql_query($sqlSyntax); //Se ejecuta el query de $sqlSyntax  
    if ($result == FALSE) { die(@mysql_error()); }

    //INSERT LOS QUE ESTEN EN EL ARRAY PERO NO ESTEN EN LA BD

    unset($_SESSION['lista']);
    unset($_SESSION['id_lista']);

    header('Location: listas.php');

 ?>