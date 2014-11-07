<?php

ob_start();
header('Content-Type: text/html; charset=UTF-8'); 
error_reporting(E_ALL); 
ini_set('display_errors', 'Off');
session_start();

require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php 
mysql_query("SET NAMES 'utf8'");

if (isset($_POST['busqueda'])){
    $_SESSION['busqueda'] = $_POST['busqueda'];
}
#echo $_POST['busqueda'];
//Query of listado database

$sqlSyntax= "SELECT * FROM producto WHERE descripcion LIKE '%".$_SESSION['busqueda']."%' ORDER BY marca ASC"; 
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
        <div class="col-xs-12">
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
    $output_string = '<div class="container" style="margin-top: 30px;">
                        <p>    Los productos seran agregados a la lista <strong>Nombre LISTA<strong>...Cambiar Lista</p>
                        <div class="row">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Imagen</th>
                                    <th class="text-center">Marca</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center"><img width="50" src="img/lider-color.png" style="margin-top: 5px; margin-bottom: 5px;"></th>
                                    <th class="text-center"><img width="50" src="img/jumbo-color.png" style="margin-top: 5px; margin-bottom: 5px;"></th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Agregar</th>
                                </tr>
                            </thead>
                            <tbody>';

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
             $row['precio_lider'] = '$'.number_format( $row['precio_lider'], 0, '', '.');
        }

        if (strlen($row['precio_jumbo']) == 0){
            $row['precio_jumbo'] = "-";
            $logo_jumbo = "img/jumbo-gris.png";
        }
        else{
            $row['precio_jumbo'] = '$'.number_format($row['precio_jumbo'], 0, '', '.');
        }
        $output_string .= '
            <tr>
                <td><img class="producto" src="'.$row['url_imagen'].'" width="50" alt=""></td>
                <td><p><strong>'.$row['marca'].'</strong></p></td>
                <td><p><strong>'.$row['descripcion'].'</strong></p></td>
                <td><center><strong style="font-size: 20px;">'.$row['precio_lider'].'</strong></center></td>
                <td><center><strong style="font-size: 20px;">'.$row['precio_jumbo'].'</strong></center></td>
                <td><input type="number" name="cantidad" min="0" max="10"></td>
                <td class="text-center"><button type="button" class="btn btn-default btn-lg" style="border-style:none;">
                    <span style="color:green;"class="glyphicon glyphicon-plus"></span></button>
                </td>

            </tr>
            
    ';
        $output_string2 .= '
            <div class="item col-xs-6" style="height: auto; border: 1px; border-style: solid; margin-bottom: 15px;">
                <div class="col-xs-3">
                    <img class="producto" src="'.$row['url_imagen'].'" alt="">
                    <!--<button type="button" class="btn btn-danger" style="width: 100px; margin-top: 30px;">Agregar</button>-->
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
    $output_string .= '</tbody></div></div>';
}
mysql_close();
// This echo for jquery 
echo $output_string;
#json_encode($output_string);

?>