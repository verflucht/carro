<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	require_once 'fb/app/start.php';

	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");
	$output = '
				<div class="container" style="margin-top: 30px;">
                    <div class="row">
                    <input type="hidden" name="facebook" value="1">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:80px;">Imagen</th>
                                <th class="text-center" style="width:100px;">Nombre</th>
                                <th class="text-center" style="width:80px;">Permiso para Editar</th>
                                <th class="text-center" style="width:80px;">Compartir</th>
                                
                            </tr>
                        </thead>
                        <tbody>';

	if (isset($_SESSION['amigos'])){
		$count=0;
		foreach ($_SESSION['amigos'] as $friends) {
			//echo $_SESSION['amigos'][$count]->name;
			$url_foto = 'https://graph.facebook.com/'.$_SESSION['amigos'][$count]->id.'/picture?width=50';
			$output .= '
				<tr>
					<td class="text-center" ><img class="img-circle" src="'.$url_foto.'" width="50" alt="User Pic" /></td>
					<td><span style="margin-top:25%;"><strong>'.$_SESSION['amigos'][$count]->name.'</strong></span></td>
					<td class="text-center"><input type="checkbox" class="form-group" value="'.$_SESSION['amigos'][$count]->id.'" style="margin-top:20%;" name="editar[]"></td>
					<td class="text-center"><input type="checkbox" class="form-group" value="'.$_SESSION['amigos'][$count]->id.'" style="margin-top:20%;" name="compartir[]"></td>
				</tr>
			';


			$output2 = '
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
					            	<span class="">Permiso para Editar</span><br><input type="checkbox" class="form-control" value="'.$_SESSION['amigos'][$count]->id.'" style="margin-left: 55px;" name="editar[]" >
					            </div>
					        </div>
				 		</div>
				 	</div>
				 </div>
			';
			$count++;
		}

		echo $output;

	}
	else{
		$loginFacebook =  $helper->getLoginUrl($config['scopes']);
		$output .= '
		    <div class="container">
		        <div class="row">
		        <div class="col-xs-12">
		                <div class="alert alert-danger">
		                    <p>Debes <a href="'.$loginFacebook.'">iniciar sesión</a> con Facebook Primero!</p>
		                </div>
		            </div>
		        </div>
		    </div>
    ';

    echo $output;
	}
?>