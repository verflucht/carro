<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL);
 	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8'); 
 	session_start(); 

	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");

	if (isset($_POST['id'])) {
		if($_POST['mail'] == ''){
			$count=0;
			foreach ($_SESSION['amigos'] as $friends) {
				//echo $_SESSION['amigos'][$count]->name;
				$url_foto = 'https://graph.facebook.com/'.$_SESSION['amigos'][$count]->id.'/picture?width=30';

				echo '
					<br><br>
					<div class="container">
					    <div class="row">
					 		<div class="amigos">
					 			<div class="row user-row">
						            <div class="col-xs-1 col-xs-offset-3">
						                <img class="img-circle" src="'.$url_foto.'" width="30" alt="User Pic">
						            </div>
						            <div class="col-xs-3 ">
						                <strong>'.$_SESSION['amigos'][$count]->name.'</strong><br>
						                <span class="text-muted">'.$_SESSION['amigos'][$count]->id.'</span>
						            </div>
						            <div class="col-xs-3">
						           		<input type="hidden" name="facebook" value="1">
						            	<input type="hidden" name="count[]" value="123">
						            	<input type="hidden" name="id_usuario[]" value="'.$_SESSION['amigos'][$count]->id.'">
						            	<input type="hidden" name="mail[]" value="'.$_SESSION['amigos'][$count]->id.'">	
						            	<span class="">Permiso para Editar</span><br><input type="checkbox" value="'.$_SESSION['amigos'][$count]->id.'" style="margin-left: 55px;" name="editar[]" >
						            </div>
						        </div>
					 		</div>
					 	</div>
					 </div>
				';
				$count++;
			}
		}  
		}
		$_SESSION['id_lista'] = $_POST['id'];
		$_SESSION['first'] = 1;
		# code...
		//echo $_POST['id']; //id_lista
		//echo $_POST['mail']; //mail a compartir

		$sqlSyntax = 'SELECT * FROM usuario WHERE mail = "'.$_POST['mail'].'"';
		$result = mysql_query($sqlSyntax) or die();

		if (mysql_num_rows($result) == 0) {
			 die();
		}

		$row = mysql_fetch_assoc($result);
		if ($row['url_foto'] == null) {
			if ($row['sexo'] == 0) {
				$url_foto = 'img/default-user-men.png';
			}
			else{
				$url_foto = 'img/default-user-female.png';	
			}
		}
		else
			$url_foto = $row['url_foto'];

		echo '
			<br><br>
			<div class="container">
			    <div class="row">
			 		<div class="amigos">
			 			<div class="row user-row">
				            <div class="col-xs-1 col-xs-offset-3">
				                <img class="img-circle" src="'.$url_foto.'" width="50" alt="User Pic">
				            </div>
				            <div class="col-xs-3 ">
				                <strong>'.$row['nombre'].'</strong><br>
				                <span class="text-muted">'.$row['mail'].'</span>
				            </div>
				            <div class="col-xs-3">
				            	<input type="hidden" name="count[]" value="123">
				            	<input type="hidden" name="id_usuario[]" value="'.$row['id_usuario'].'">
				            	<input type="hidden" name="mail[]" value="'.$row['mail'].'">	
				            	<span class="">Permiso para Editar</span><br><input type="checkbox" value="'.$row['mail'].'" style="margin-left: 55px;" name="editar[]" >
				            </div>
				        </div>
			 		</div>
			 	</div>
			 </div>

		';
	
?>