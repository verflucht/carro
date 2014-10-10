<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 

	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");		

	if (isset($_POST['id'])){

		if(($_POST['id'] == "carga") || ($_POST['id'] == "propia")) {

			$listas = 'SELECT * FROM lista WHERE id_usuario='.$_SESSION['id_usuario'];
	        $result= @mysql_query($listas);
	        if ($result == FALSE) { die(@mysql_error()); }
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
        		echo '<tr><td class="text-center">'.++$count.'</td>';                  
                echo '<td class="text-center nombre_lista">'.$row['nombre_lista'].'</td>';
                echo '<td class="text-center">'.$_SESSION['nombre_usuario'].'</td>';
                echo '<td class="text-center">'.$row['fecha'].'</td>';
                echo '
               		<td class="text-center" style="padding:6px;">
    					<a href="#" id="'.$row['id_lista'].'" class="prices">
                			<span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-usd"></span>
                		</a>
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
    					<a id="'.$row['id_lista'].'" class="eraseList">
                	<span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-trash"></span>
                		</a>
    				</td>
    				</tr>';
	    	}
	    	echo '</table>';
	    }
	    else if ($_POST['id'] == "compartida"){

			$SqlSyntax = 'SELECT id_lista, permiso FROM compartir_lista 
							WHERE compartir_lista.id_usuario_compartido = '.$_SESSION['id_usuario'];

			$result= @mysql_query($SqlSyntax);
	        if ($result == FALSE) { die(@mysql_error()); }
	        $numTotal = mysql_num_rows($result);
	        $count = 0;
	        $listado = "SELECT * FROM lista WHERE id_lista in (";
	        while($row = mysql_fetch_array($result)){
	        	++$count;
	        	if ($numTotal == $count)
	        		$listado .= $row['id_lista'];
	        	else
	        		$listado .= $row['id_lista'];
	        }
	        $listado .= ')';

			$result= @mysql_query($listado);
	        if ($result == FALSE) { die(@mysql_error()); }
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
        		echo '<tr><td class="text-center">'.++$count.'</td>';                  
                echo '<td class="text-center nombre_lista">'.$row['nombre_lista'].'</td>';
                echo '<td class="text-center">'.$_SESSION['nombre_usuario'].'</td>';
                echo '<td class="text-center">'.$row['fecha'].'</td>';
                echo '
               		<td class="text-center" style="padding:6px;">
    					<a href="#" id="'.$row['id_lista'].'" class="prices">
                			<span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-usd"></span>
                		</a>
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
    					<a id="'.$row['id_lista'].'" class="eraseList">
                	<span style="padding: 14px; border: 1px solid grey; border-radius: 5px;"class="glyphicon glyphicon-trash"></span>
                		</a>
    				</td>
    				</tr>';
	    	}
	    	echo '</table>';	
	    }
		
	}
	else echo "error";

?>