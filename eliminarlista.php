<?php
    ob_start();
    header('Content-Type: text/html; charset=UTF-8'); 
    error_reporting(E_ALL); 
    ini_set('display_errors', 'Off');

    require_once 'mysqlConnection.php';
    mysql_query("SET NAMES 'utf8'");


    $id_lista = $_POST['id_lista'];
    #echo $_POST['busqueda'];

    $sqlSyntax = "DELETE FROM descripcion_producto_lista WHERE id_lista = $id_lista";
    mysql_query($sqlSyntax) or die(mysql_error());

    $sqlSyntax = "DELETE FROM lista WHERE id_lista = $id_lista";
    mysql_query($sqlSyntax) or die(mysql_error());

    header('Location: listas.php');

?>