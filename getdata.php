<?php

ob_start();
header('Content-Type: text/html; charset=UTF-8'); 
error_reporting(E_ALL); 
ini_set('display_errors', 'Off');

require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php 

$busqueda = $_POST['busqueda'];
#echo $_POST['busqueda'];
//Query of listado database

mysql_query("SET NAMES 'utf8'");
$sqlSyntax= "SELECT * FROM producto WHERE descripcion LIKE '%".$busqueda."%' ORDER BY marca ASC"; 
$listado = mysql_query($sqlSyntax) or die(mysql_error());

//Output results
if(!$listado)
{
    mysql_close();
    echo json_encode('There was an error running the query: ' . mysql_error());
}
elseif(!mysql_num_rows($listado))
{
    mysql_close();
    $output_string = '
    <div class="container">
        <div class="row">
        <div class="col-xs-6">
                <div class="alert alert-danger">
                    <p>
                    No se encontraron resultados para tu busqueda, intenta con otro producto!.</p>
                </div>
            </div>
        </div>
    </div>
    ';
}
else
{
    $output_string = '<div class="container" style="margin-top: 30px;"><div class="row">';
    while($row = mysql_fetch_assoc($listado))
    {
        $logo_lider = "img/lider-color.png";
        $logo_jumbo = "img/jumbo-color.png";
        
        if (strlen($row['descripcion']) > 60){
            $row['descripcion'] = substr($row['descripcion'], 0, 59)."...";
        }
        if (strlen($row['precio_lider']) == 0){
            $row['precio_lider'] = "-";
            $logo_lider = "img/lider-gris.png";
        }
        else{
            $row['precio_lider'] = "$".$row['precio_lider'];
        }

        if (strlen($row['precio_jumbo']) == 0){
            $row['precio_jumbo'] = "-";
            $logo_jumbo = "img/jumbo-gris.png";
        }
        else{
            $row['precio_jumbo'] = "$".$row['precio_jumbo'];
        }

        $output_string .= '
            <div class="item col-xs-6" style="height: auto; border: 1px; border-style: solid; margin-bottom: 15px;">
                <div class="col-xs-3">
                    <img class="producto" src="'.$row['url_imagen'].'" alt="">
                    <button type="button" class="btn btn-danger" style="width: 100px; margin-top: 30px;">Agregar</button>
                </div>
                <div class="col-xs-9" style="background-color: none; height: 120px;">
                    <p>Marca: <strong>'.$row['marca'].'</strong></p>
                    <p>Categoria: <strong>'.$row['categoria'].'</strong></p>
                    <p>Descripción: <strong>'.$row['descripcion'].'</strong></p>
                </div>
                <div class="col-xs-3"></div>
                <div class="col-xs-9">
                    <div class="col-xs-1"></div>
                    <div class="col-xs-4" style="">
                        <img class="logo" src="'.$logo_lider.'" alt="">
                        <center><p class="precio"><strong>'.$row['precio_lider'].'</strong></p></center>
                    </div>
                    <div class="col-xs-1"></div>
                    <div class="col-xs-4">
                        <img class="logo" src="'.$logo_jumbo.'" alt="">
                        <center><div class="precio"><strong>'.$row['precio_jumbo'].'</strong></div></center>
                    </div>
                </div>
            </div>
        ';
    }
    $output_string .= '</div></div>';
}
mysql_close();
// This echo for jquery 
echo $output_string;
#json_encode($output_string);

?>