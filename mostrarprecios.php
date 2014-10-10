<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  

	session_start();
	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");

	$id_lista = $_POST['id'];
	$nombre_lista = $_POST['nombre_lista'];

	//Seleccionar todos los elementos de la lista

	$sqlSyntax = 'SELECT id_producto, cantidad 
					FROM descripcion_producto_lista 
					WHERE id_lista = "'.$id_lista.'"';


	$listado = mysql_query($sqlSyntax) or die(mysql_error());

	$items = array();
	$numResults = mysql_num_rows($listado);

	$precios = "SELECT precio_lider, precio_jumbo FROM producto WHERE id_producto in (";
	$counter = 0;
    while($row = mysql_fetch_assoc($listado)){
    	//Armar la consulta para buscar todo.
    	$items = $row['cantidad'];
    	if (++$counter == $numResults) {
        // last row
    		$precios .= $row['id_producto'].')';
	    } else {
	        // not last row
        	$precios .= $row['id_producto'].',';
	    }
    }

	$listado_precios = mysql_query($precios) or die(mysql_error());
	$precio_lider = 0;
	$precio_jumbo = 0;
	$count = 0;
	while($row = mysql_fetch_array($listado_precios)){
		$precio_lider += intval($row['precio_lider'])*$items[$count];
		$precio_jumbo += intval($row['precio_jumbo'])*$items[$count];
		$count++;
	}

	echo '
		<div class="modal fade" id="modalPrecios">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      
	      <div class="modal-body" style="margin-top: 20px; height:100px;">
	        	<div class="pull-left">
	        		<img src="img/lider-color.png" width="150" style="margin-left: 50px;" alt="">
	        		<h3 class="text-center">$'.$precio_lider.'</h3>
	        	</div>

	        	<div class="pull-right">
	        		<img src="img/jumbo-color.png" width="150" style="margin-right: 50px;" alt="">
	        		<h3 class="text-center">$'.$precio_jumbo.'</h3>
       		 	</div>
	      </div>
	      <div class="modal-footer">
	      	<div class="pull-left">
        		<h4 class="text-center">LISTA: '.$nombre_lista.'</h4>	
	      	</div>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	';


 ?>