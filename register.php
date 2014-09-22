<?php
ob_start();
header('Content-Type: text/html; charset=UTF-8'); 
error_reporting(E_ALL); 
ini_set('display_errors', 'Off');
session_start();

require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php 

$mail = $_POST['mail'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$pass = $_POST['pass'];
$sexo = $_POST['sexo'];




#echo $_POST['busqueda'];
//Query of listado database

if (isset($_SESSION['facebook'])){
    //$sqlSyntax= 'SELECT id_ FROM usuario WHERE mail = "'.$mail.'"';
    //$sqlResult = mysql_query($sqlSyntax) or die(mysql_error());
    $sqlSyntax = 'SELECT id_facebook FROM usuario WHERE id_facebook = "'.$_SESSION['id_fb'].'"';
    $sqlResult = mysql_query($sqlSyntax) or die(mysql_error());
    if(!mysql_num_rows($sqlResult)){
        //id facebook no existe, asi que lo registramos
        if(strcmp($_SESSION['sexo'], "male")){
            $sexo = "0";
        }
        if(strcmp($_SESSION['sexo'], "female")){
            $sexo = "1";
        }
        $sqlSyntax2= 'INSERT INTO usuario (id_facebook, sexo, url_foto, nombre) VALUES ("'.$_SESSION['id_fb'].'", "'.$sexo.'", "'.$_SESSION['imagen_fb'].'", "'.$_SESSION['nombre'].'")';
        $sqlResult2 = mysql_query($sqlSyntax2) or die(mysql_error());
        if(!$sqlResult2)
        {
            //error procesando la consulta
            mysql_close();
            echo json_encode('There was an error running the query: ' . mysql_error());
        }
        else{
            $_SESSION['user'] = $mail;
            echo "0"; 
            header('Location: cliente.php');
        } 
    }
    else{
        //id ya existe y inicio sesion
        header('Location: cliente.php');
    }
}

$sqlSyntax= 'SELECT * FROM usuario WHERE mail = "'.$mail.'"';
$sqlResult = mysql_query($sqlSyntax) or die(mysql_error());

//Output results
if(!$sqlResult)
{
    //error procesando la consulta
    mysql_close();
    echo json_encode('There was an error running the query: ' . mysql_error());
}
elseif(!mysql_num_rows($sqlResult))
{   
    //si no existe el usuario
    

    $sqlSyntax2= 'INSERT INTO usuario (mail, nombre, pass, sexo) VALUES ("'.$mail.'", "'.$nombre.' '.$apellido.'", "'.$pass.'", "'.$sexo.'")';
    $sqlResult2 = mysql_query($sqlSyntax2) or die(mysql_error());
    if(!$sqlResult2)
    {
        //error procesando la consulta
        mysql_close();
        echo json_encode('There was an error running the query: ' . mysql_error());
    }
    else{
        $_SESSION['user'] = $mail;
        echo "0"; 
    }
    
}
else
{
    mysql_close();
    echo "1";
}
mysql_close();
// This echo for jquery 
#json_encode($output_string);

?>