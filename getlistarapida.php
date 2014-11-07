<?php
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 

	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");


		if (isset($_SESSION['facebook'])){
			$id_facebook = $_SESSION['id_fb'];
			$sqlSyntax = 'SELECT id_usuario FROM usuario WHERE id_facebook = '.$id_facebook;
			$result = mysql_query($sqlSyntax) or die(mysql_error());
			$row = mysql_fetch_array($result);
			$id_usuario = $row['id_usuario'];
		}

		else {
			$id_usuario = $_SESSION['user'];
		}
		$sqlSyntax= "SELECT id_lista FROM lista WHERE id_usuario = ".$id_usuario;
		$result = mysql_query($sqlSyntax) or die(mysql_error());
		$len = mysql_num_rows($result);
		$count = 1;

		$productos = 'SELECT id_producto FROM descripcion_producto_lista WHERE id_lista in ( ';
		while ($row = mysql_fetch_array($result)) {
			if ($len == $count){
				$productos .= $row['id_lista'];
			}
			else
				$productos .= $row['id_lista'].',';
			$count++;
		}

		$productos .= ') GROUP BY id_producto';


		$result = @mysql_query($productos);
		$num_total_registros = mysql_num_rows($result);

		//echo $num_total_registros;

		//Si hay registros
		//numero de registros por página
		$rowsPerPage = 10;

		//por defecto mostramos la página 1
	    $pageNum = 1;

	    // si $_POST['page'] esta definido, usamos este número de página
	    if(isset($_POST['pagina'])) {
	        sleep(1);
	        $pageNum = $_POST['pagina'];
	    }

	    //contando el desplazamiento
	    $offset = ($pageNum - 1) * $rowsPerPage;
	    $total_paginas = ceil($num_total_registros / $rowsPerPage);

		$sqlSyntax = 'SELECT * FROM producto WHERE id_producto in (';

		$len = mysql_num_rows($result);
		$count = 1;

		while ($row = mysql_fetch_array($result) ) {
			if ($len == $count) {
				$sqlSyntax .= $row['id_producto'];
				break;
			}
			else{
				$sqlSyntax .= $row['id_producto'].',';
			}
			$count++;	
		}

		$sqlSyntax .= ") ORDER BY marca ASC LIMIT ".$offset.", ".$rowsPerPage;
		
		@mysql_query($sqlSyntax);

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
		        <div class="col-xs-8 col-xs-offset-2">
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
			$output_string = "";
			
			 if ($total_paginas > 1) {
			    $output_string .= '<div class="row col-xs-4 col-xs-offset-2"><div class="pagination">';
			    $output_string .= '<ul>';
			    if ($pageNum != 1)
			        $output_string .= '<li><a class="paginationbutton" id="'.($pageNum-1).'">Anterior</a></li>';
		        for ($i=1;$i<=$total_paginas;$i++) {
		        	if ($total_paginas > 3){
			            if ($pageNum == 1){
		            		if($i == 4){
			                	$output_string .= '<li><a>...</a></li>';
			                	break;
		            		}
		            		else{
		            			if($pageNum == $i)
		            				$output_string .= '<li><a style="background-color: #EB2836; color: white;">'.$i.'</a></li>';
		            			else
			                		$output_string .= '<li><a class="paginationbutton" id="'.$i.'">'.$i.'</a></li>';
		            		}
		            	}
	            		else{
	            			if(($i == 1 && $total_paginas > 4) || ($i == $total_paginas && $total_paginas > 4)){
			                	$output_string .= '<li><a>...</a></li>';
	            			}
	            			else if(($pageNum - 1) == $i || ($pageNum + 1) == $i){
			                	$output_string .= '<li><a class="paginationbutton" id="'.$i.'">'.$i.'</a></li>';
				            }
				            else if ($pageNum == $i){
				                //si muestro el índice de la página actual, no coloco enlace
				                $output_string .= '<li><a style="background-color: #EB2836; color: white;">'.$i.'</a></li>';
			            	}
	            		}
	       		 	}
		            else{
			            if ($pageNum == $i)
			                //si muestro el índice de la página actual, no coloco enlace
			                $output_string .= '<li><a style="background-color: #EB2836; color: white;">'.$i.'</a></li>';
			            else
			                //si el índice no corresponde con la página mostrada actualmente,
			                //coloco el enlace para ir a esa página
			                $output_string .= '<li><a class="paginationbutton" id="'.$i.'">'.$i.'</a></li>';
	         		}
	         	}
	         	if ($pageNum != $total_paginas)
	             	$output_string .= '<li><a class="paginationbutton" id="'.($pageNum+1).'">Siguiente</a></li>';
	         	$output_string .= '</ul>';
	          	$output_string .= '</div></div>';
			}


		    $output_string .= '<div class="container" style="margin-top: 30px;">
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
                                    <th class="text-center">Opciones</th>
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
		                <td class="text-center" style="width:80px;"><input  type="number" id="id_'.$row['id_producto'].'" class="form-control" data-min="0" data-max="10" data-wrap="true"></td>
		                             
		            ';
		            

            	if (isset($_SESSION['lista'])) {
            		if (array_key_exists($row['id_producto'], $_SESSION['lista'])) {
            			$output_string .= '<td class="text-center"><button type="button" id="'.$row['id_producto'].'" class="btn btn-info btn-lg botonActualizar" style="border-style:none;">
							                  <span style="color:green;"class="glyphicon glyphicon-refresh"></span>
							                  <span class="glyphicon-class">Actualizar</span></button>
							            </td>';
            		}
            		else{
            		//$output_string .= '<button type="button" id="'.$row['id_producto'].'" class="btn btn-success btn-md botonAgregar" style="">Agregar</button><br>';
            		$output_string .= '<td class="text-center"><button type="button" id="'.$row['id_producto'].'" class="btn btn-success btn-lg botonAgregar" style="border-style:none;">
							                  <span style="color:green;"class="glyphicon glyphicon-plus"></span>
							                  <span class="glyphicon-class">Agregar</span></button>
							            </td>';
            		}
        		}
            	else{
            		$output_string .= '<td class="text-center"><button type="button" id="'.$row['id_producto'].'" class="btn btn-success btn-lg botonAgregar" style="border-style:none;">
							                  <span style="color:green;"class="glyphicon glyphicon-plus"></span>
							                  <span class="glyphicon-class">Agregar</span></button>
							            </td>';
            		//$output_string .= '<button type="button" id="'.$row['id_producto'].'" class="btn btn-success btn-md botonAgregar" style="">Agregar</button><br>';
            	}
		    }
		    
		    $output_string .= '</tr></div></tbody></div></div>';

		   
		}
		mysql_close();
		// This echo for jquery 
		echo $output_string;


?>