<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 
	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");

	$count = 0;
	$i = 0;
	$len = count($_POST['count']);
	echo $len;
    $compartir = "INSERT INTO compartir_lista (id_lista, id_usuario_compartido, permiso) VALUES ";

    foreach ($_POST['count'] as $editar) {
    	$i++;
    	if (isset($_POST['editar'][$count])) {
    		//item fue seleccionado insertamos en la tabla con permiso = 0
    		if ($i == $len) 
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",1)";
    		else
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",1),";
    	}
    	else{
    		//item no fue seleccionado insertamos en la tabla con permiso = 1
    		if ($i == $len) 
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",0)";
    		else
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",0),";
    	}
    	$count++;
    }

    $result= @mysql_query($compartir); //Se ejecuta el query de $sqlSyntax  
    if ($result == FALSE) { die(header('Location: listas.php?error=1')); }

    unset($_SESSION['first']);

    header('Location: listas.php');

 ?>