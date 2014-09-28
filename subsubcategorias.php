<?php
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 

	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");

	if (isset($_POST['subSubcategorias'])){
		$subsubcategoria = $_POST['subSubcategorias'];
		#echo $_POST['busqueda'];
		//Query of listado database

		mysql_query("SET NAMES 'utf8'");
		$sqlSyntax= "SELECT * FROM producto WHERE subsubcategoria = '".$subsubcategoria."' ORDER BY marca ASC"; 
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
		                    <div style="margin-top: 20px;">';

            	if (isset($_SESSION['lista'])) {
            		if (array_key_exists($row['id_producto'], $_SESSION['lista'])) {
            			$output_string .= '<button type="button" id="'.$row['id_producto'].'" class="btn btn-info btn-md botonActualizar" style="">Actualizar</button><br>';
            		}
            		else{
            		$output_string .= '<button type="button" id="'.$row['id_producto'].'" class="btn btn-success btn-md botonAgregar" style="">Agregar</button><br>';
            		}
        		}
            	else{
            		$output_string .= '<button type="button" id="'.$row['id_producto'].'" class="btn btn-success btn-md botonAgregar" style="">Agregar</button><br>';
            	}

            		$output_string .='
		                    	<select id="id_'.$row['id_producto'].'" name="selectbasic" class="form-control">
							      <option value="0">0</option>
							      <option value="1">1</option>
							      <option value="2">2</option>
							      <option value="3">3</option>
							      <option value="4">4</option>
							      <option value="5">5</option>
							      <option value="6">6</option>
							      <option value="7">7</option>
							      <option value="8">8</option>
							      <option value="9">9</option>
							      <option value="10">10</option>
							      <option value="11">11</option>
							      <option value="12">12</option>
							      <option value="13">13</option>
							      <option value="14">14</option>
							      <option value="15">15</option>
							      <option value="16">16</option>
							      <option value="17">17</option>
							      <option value="18">18</option>
							      <option value="19">19</option>
							      <option value="20">20</option>
							    </select>
		                    </div>	                    		
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
	}
	else {

		// esta función se va a llamar al cargar el primer combo
	    $subsubcategoria = array();
	    $subsubcategoria = "<h3 class='text-center'>Selecciona un tipo de producto</h3>";
	    $sqlSyntax = 'SELECT subsubcategoria FROM 
				(
					SELECT * FROM producto
					ORDER BY subsubcategoria
				) t1
				WHERE subcategoria = "'.$_POST['subCategoria'].'" GROUP BY subcategoria';

		$result= @mysql_query($sqlSyntax);
	    if ($result == FALSE) { die(@mysql_error()); }
	  	$subsubcategoria .= '<select id="comboSubsubcategoria" class="form-control"><option value="0">Selecciona una opción</option>';
		while($row = mysql_fetch_array($result)){
	     $subsubcategoria .= '<option value="'.$row['subsubcategoria'].'">'.$row['subsubcategoria'].'</option>';
	  	}
	  	$subsubcategoria .= '</select>';
	    // devolvemos el arreglo
	    echo $subsubcategoria;
	}
?>