<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 

	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");		

	if (isset($_POST['id'])){

		if(($_POST['id'] == "carga") || ($_POST['id'] == "propia")) {

			$listas = 'SELECT * FROM lista WHERE id_usuario='.$_SESSION['id_usuario'];

	        $result= @mysql_query($listas);
	        if ($result == FALSE) {  die(print_r('<div class="alert alert-warning" style="margin-top:20px;" role="alert">No hay listas para mostrar</div>')); }
	        $count = 0;

	        echo '
	        	<table class="table table-striped custab" style="margin-top: 40px;">
                <thead>
                        <tr>
                            <th style="display:;" class="text-center">#</th>
                            <th class="text-center">Nombre Lista</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Fecha Ingreso</th>
                            <th class="text-center">Comparar Precios</th>
                            <th class="text-center">Compartir</th>
                            <th class="text-center">Editar</th>
                            <th class="text-center">Eliminar</th>
                        </tr>
                </thead>

	        ';
	        while($row = mysql_fetch_array($result)){
                    $sqlSyntax = 'SELECT id_producto, cantidad 
                                    FROM descripcion_producto_lista 
                                WHERE id_lista = "'.$row['id_lista'].'"';
                    $listado = mysql_query($sqlSyntax) or  die(print_r('<div class="alert alert-warning" style="margin-top:20px;" role="alert">No hay listas para mostrar</div>'));
                    
                    if (mysql_num_rows($listado) == 0) {
                        $precio_lider = 0;
                        $precio_jumbo = 0;
                    }
                    else {
                        $items = array();
                        $numResults = mysql_num_rows($listado);

                        $precios = "SELECT precio_lider, precio_jumbo FROM producto WHERE id_producto in (";
                        $counter = 0;
                        while($rowPrecio = mysql_fetch_assoc($listado)){
                            //Armar la consulta para buscar todo.
                            $items[] = $rowPrecio['cantidad'];
                            if (++$counter == $numResults) {
                            // last rowPrecio
                                $precios .= $rowPrecio['id_producto'].')';
                            } else {
                                // not last rowPrecio
                                $precios .= $rowPrecio['id_producto'].',';
                            }
                        }

                        $listado_precios = mysql_query($precios) or die(mysql_error());
                        $precio_lider = 0;
                        $precio_jumbo = 0;
                        $count = 0;

                        while($rowPrecio = mysql_fetch_array($listado_precios)){
                            $precio_lider += intval($rowPrecio['precio_lider'])*$items[$count];
                            $precio_jumbo += intval($rowPrecio['precio_jumbo'])*$items[$count];
                            $count++;
                        }
                    }
        		echo '<tr><td class="text-center">'.++$count.'</td>';                  
                echo '<td class="text-center nombre_lista">'.$row['nombre_lista'].'</td>';
                echo '<td class="text-center">'.$_SESSION['nombre_usuario'].'</td>';
                echo '<td class="text-center">'.$row['fecha'].'</td>';
                echo '
               		<td class="text-center" style="padding:6px;">
                        <div class="col-xs-6">
                            <img width="50" src="img/lider-color.png" style="margin-bottom: 10px;"><br>
                            <span class="text-center">$'.$precio_lider.'</span>
                        </div>
                        <div class="col-xs-6">
                            <img width="50" src="img/jumbo-color.png" style="margin-bottom: 10px;"><br>
                            <span class="text-center">$'.$precio_jumbo.'</span>
                        </div>
                    </td>
                ';
                echo '<td class="text-center" style="padding:6px;">
                        <form action="compartirLista.php" method="post" accept-charset="utf-8">
                            <input type="hidden" name="id_lista" value="'.$row['id_lista'].'">
                            <button type="submit" style="border: 0; background: none; padding:0;">
                                <span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-share"></span>
                            </button>
                        </form>
                    </td>
					<td class="text-center" style="padding:6px;">
    					<form action="actualizarlista.php" method="get" accept-charset="utf-8">
							<input type="hidden" name="id_lista" value="'.$row['id_lista'].'">
							<button type="submit" style="border: 0; background: none; padding:0;">
                				<span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-pencil"></span>
           					</button>
						</form>
					</td>
					<td class="text-center" style="padding:6px;">
                        <form action="eliminarlista.php" method="post" accept-charset="utf-8">
                            <input type="hidden" name="id_lista" value="'.$row['id_lista'].'">
                            <button type="submit" style="border: 0; background: none; padding:0;">
                                <span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-trash"></span>
                            </button>
                        </form>
                    </td>
    				</tr>';
	    	}
	    	echo '</table>';
	    }
	    else if ($_POST['id'] == "compartida"){

			$SqlSyntax = 'SELECT id_lista, permiso FROM compartir_lista 
							WHERE compartir_lista.id_usuario_compartido = '.$_SESSION['id_usuario'];

			$result= @mysql_query($SqlSyntax);

	        if ($result == FALSE) {  die(print_r('<div class="alert alert-warning" style="margin-top:20px;" role="alert">No hay listas para mostrar</div>')); }

	        $numTotal = mysql_num_rows($result);
	        $count = 0;
            $permiso = array();

	        $listado = "SELECT * FROM lista WHERE id_lista in (";
	        while($row = mysql_fetch_array($result)){
                $permiso[] = $row['permiso'];
	        	++$count;
	        	if ($numTotal == $count)
	        		$listado .= $row['id_lista'];
	        	else
	        		$listado .= $row['id_lista'];
	        }
	        $listado .= ')';


			$result= @mysql_query($listado);
	        if ($result == FALSE) { die(print_r('<div class="alert alert-warning" style="margin-top:20px;" role="alert">No hay listas para mostrar</div>')); }

	        $count = 0;
	        echo '
	        	<table class="table table-striped custab" style="margin-top: 40px;">
                <thead>
                        <tr>
                            <th style="display:;" class="text-center">#</th>
                            <th class="text-center">Nombre Lista</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Fecha Ingreso</th>
                            <th class="text-center">Comparar Precios</th>
                            <th class="text-center">Compartir</th>
                            <th class="text-center">Editar</th>
                            <th class="text-center">Eliminar</th>
                        </tr>
                </thead>

	        ';

            $count2 = 1;
	        while($row = mysql_fetch_array($result)){
                $sqlSyntax = 'SELECT id_producto, cantidad 
                                FROM descripcion_producto_lista 
                                WHERE id_lista = "'.$row['id_lista'].'"';
                $listado = mysql_query($sqlSyntax) or die(mysql_error());
                if (mysql_num_rows($listado) == 0) {
                    $precio_lider = 0;
                    $precio_jumbo = 0;
                }
                else {
                $items = array();
                $precios_lider = array();
                $precios_jumbo = array();
                $numResults = mysql_num_rows($listado);

                $precios = "SELECT precio_lider, precio_jumbo FROM producto WHERE id_producto in (";
                $counter = 0;
                while($rowPrecio = mysql_fetch_assoc($listado)){
                    //Armar la consulta para buscar todo.
                    $items[] = $rowPrecio['cantidad'];

                    if (++$counter == $numResults) {
                    // last rowPrecio
                        $precios .= $rowPrecio['id_producto'].')';
                    } else {
                        // not last rowPrecio
                        $precios .= $rowPrecio['id_producto'].',';
                    }
                   // echo $rowPrecio['id_producto']."-->".$rowPrecio['cantidad'];
                }

                $listado_precios = mysql_query($precios) or die(mysql_error());
                $precio_lider = 0;
                $precio_jumbo = 0;
                $count = 0;
                $number = 0;
                while($rowPrecio = mysql_fetch_array($listado_precios)){
                    //echo $rowPrecio['precio_lider'];
                    //echo $rowPrecio['precio_jumbo'];
                    $precio_lider += intval($rowPrecio['precio_lider'])*$items[$count];
                    $precio_jumbo += intval($rowPrecio['precio_jumbo'])*$items[$count];
                    $count++;
                    }
                }

                $getNombre = "SELECT nombre FROM usuario WHERE id_usuario =".$row['id_usuario'];
                $result= @mysql_query($getNombre);
                if ($result == FALSE) { die(@mysql_error()); }
                $nombre = mysql_fetch_array($result);


        		echo '<tr><td class="text-center">'.++$number.'</td>';                  
                echo '<td class="text-center nombre_lista">'.$row['nombre_lista'].'</td>';
                echo '<td class="text-center">'.$nombre['nombre'].'</td>';
                echo '<td class="text-center">'.$row['fecha'].'</td>';
                echo '
               		<td class="text-center" style="padding:6px;">
    					<div class="col-xs-6">
                            <img width="50" src="img/lider-color.png" style="margin-bottom: 10px;"><br>
                            <span class="text-center">$'.$precio_lider.'</span>
                        </div>
                        <div class="col-xs-6">
                            <img width="50" src="img/jumbo-color.png" style="margin-bottom: 10px;"><br>
                            <span class="text-center">$'.$precio_jumbo.'</span>
                        </div>
    				</td>
                ';
                echo '<td class="text-center" style="padding:6px;">
                        <form action="compartirLista.php" method="post" accept-charset="utf-8">
                            <input type="hidden" name="id_lista" value="'.$row['id_lista'].'">
                            <button type="submit" style="border: 0; background: none; padding:0;">
                                <span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-share"></span>
                            </button>
                        </form>
                    </td>
                    ';

               // echo $permiso[$count2]."aaaaa";

                if ($permiso[$count2] == 0) {
                    echo '
                    <td class="text-center" style="padding:6px;">
                        <span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-remove-sign"></span>
                    </td>';
                echo '<td class="text-center" style="padding:6px;">
                         <span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-remove-sign"></span>
                        </td>
                    </tr>';
                }
                else{
                echo '
					<td class="text-center" style="padding:6px;">
    					<form action="actualizarlista.php" method="get" accept-charset="utf-8">
							<input type="hidden" name="id_lista" value="'.$row['id_lista'].'">
							<button type="submit" style="border: 0; background: none; padding:0;">
                				<span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-pencil"></span>
           					</button>
						</form>
					</td>';
                echo '<td class="text-center" style="padding:6px;">
                    <form action="eliminarlista.php" method="post" accept-charset="utf-8">
                        <input type="hidden" name="id_lista" value="'.$row['id_lista'].'">
                        <button type="submit" style="border: 0; background: none; padding:0;">
                            <span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-trash"></span>
                        </button>
                    </form>
                </td
                </tr>';
                }
					
	    	}
	    	echo '</table>';	
	    }
		
	}
	else echo "error";

?>