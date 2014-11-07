<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 
	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");

    if ($_POST['facebook'] == '1') {
        //print_r($_POST['editar']);
        //print_r($_POST['compartir']);
        $len = count($_POST['compartir']);
        $count = 0;
        $i = 0;

        $compartir = "INSERT INTO compartir_lista (id_lista, id_usuario_compartido, permiso, visto) VALUES ";
        
        foreach ($_POST['compartir'] as $comp) {
            $i++;
            if (isset($_POST['compartir'])) {
                $usuario = "SELECT id_usuario FROM usuario WHERE id_facebook = ".$_POST['compartir'][$count];
                $result= @mysql_query($usuario); //Se ejecuta el query de $sqlSyntax  
                if ($result == FALSE) { die(header('Location: listas.php?error=1')); }

                $row = mysql_fetch_array($result);

                if (in_array($_POST['compartir'][$count], $_POST['editar'])) {
                    
                    //item fue seleccionado insertamos en la tabla con permiso = 0
                    if ($i == $len) 
                        $compartir .= " (".$_SESSION['id_lista'].",".$row['id_usuario'].",1, 0)";
                    else
                        $compartir .= " (".$_SESSION['id_lista'].",".$row['id_usuario'].",1, 0),";
                }
                else{
                    //item no fue seleccionado insertamos en la tabla con permiso = 1
                    if ($i == $len) 
                        $compartir .= " (".$_SESSION['id_lista'].",".$row['id_usuario'].",0, 0)";
                    else
                        $compartir .= " (".$_SESSION['id_lista'].",".$row['id_usuario'].",0, 0),";
                }
                $count++;
            }  

        }
        $result= @mysql_query($compartir); //Se ejecuta el query de $sqlSyntax  
        if ($result == FALSE) { die(header('Location: listas.php?error=1')); }

        unset($_SESSION['first']);

        header('Location: listas.php');
    }

    else{

	$count = 0;
	$i = 0;
	$len = count($_POST['count']);
	echo $len;
    $compartir = "INSERT INTO compartir_lista (id_lista, id_usuario_compartido, permiso, visto) VALUES ";

    foreach ($_POST['count'] as $editar) {
    	$i++;
    	if (isset($_POST['editar'][$count])) {
    		//item fue seleccionado insertamos en la tabla con permiso = 0
    		if ($i == $len) 
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",1, 0)";
    		else
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",1, 0),";
    	}
    	else{
    		//item no fue seleccionado insertamos en la tabla con permiso = 1
    		if ($i == $len) 
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",0, 0)";
    		else
    			$compartir .= " (".$_SESSION['id_lista'].",".$_POST['id_usuario'][$count].",0, 0),";
    	}
    	$count++;
    }

    $result= @mysql_query($compartir); //Se ejecuta el query de $sqlSyntax  
    if ($result == FALSE) { die(header('Location: listas.php?error=1')); }

    unset($_SESSION['first']);

    header('Location: listas.php');
}
 ?>