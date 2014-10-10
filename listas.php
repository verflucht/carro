<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 

	if (isset($_SESSION['facebook']))
    	$_SESSION['user'] = "";
	if(!isset($_SESSION['user']))
		header(	'Location: index.php');
	if(isset($_SESSION['facebook']))
		$sqlSyntax= 'SELECT * FROM usuario WHERE id_facebook = "'.$_SESSION['id_fb'].'"'; //Se crea la sintaxis para la base de datos 
	else
		$sqlSyntax= 'SELECT * FROM usuario WHERE mail = "'.$_SESSION['user'].'"'; //Se crea la sintaxis para la base de datos 
	
	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");		
    
    $result= @mysql_query($sqlSyntax); //Se ejecuta el query de $sqlSyntax  
    if ($result == FALSE) { die(@mysql_error()); }

   $row = mysql_fetch_array($result);
    if (strlen($row['url_foto']) == ""){
    	if($row['sexo'] == "1")
    		$row['url_foto'] = "img/default-user-female.png";
    	else
    		$row['url_foto'] = "img/default-user-men.png";
	}
	$id_usuario = $row['id_usuario'];
	$_SESSION['id_usuario'] = $id_usuario;
	$nombre_usuario = $row['nombre'];
	$_SESSION['nombre_usuario'] = $row['nombre'];

	print_r($_SESSION['lista']);
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html lang="es">
<head>
	<title>CheckMart>> La Revolución en Compras</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/cliente.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/prueba.css">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<!-- Latest compiled and minified JavaScript -->
	<script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


	<script type="text/javascript">
	    $(function() {
			$('.prices').on('click', function() {
	    		var id = this.id
	    		var nombre_lista = $('.nombre_lista').text();
				$.ajax({
					type: "POST",
					url: "mostrarprecios.php",
					data: {id:id,nombre_lista:nombre_lista},
					cache: false,
					success: function(result){
						$("#modal_precios").html(" ");
						$("#modal_precios").html(result);
						$('#modalPrecios').modal('show');
					}
				});
	        });
	  	});
  	</script>
	<script type="text/javascript">
	    $(function() {
			$('.eraseList').on('click', function() {
	    		var id = this.id
	    		if (window.confirm("¿Esta seguro que desea eliminar esta lista?")){
					$.ajax({
						type: "POST",
						url: "eliminarlista.php",
						data: {id:id},
						cache: false,
						success: function(result){
							if (result == '')
							  window.location.reload();
							else
							  alert(result);
						}
					});
		        }
	        });
	  	});
  </script>
  	
  <script type="text/javascript">
  	$(function() {
		$('.tipoLista').on('click', function() {
    		var id = this.id
    		alert(id);
			$.ajax({
				type: "POST",
				url: "tipolista.php",
				data: {id:id},
				cache: false,
				success: function(result){
					if (result == '')
					  window.location.reload();
					else{
						$(".listas").html(" ");
						$(".listas").html(result);
					}

				}
			});
        });
  	});
  </script>
  <script type="text/javascript">
  	$(document).ready(function(){
  		var id = "carga";
  		$.ajax({
			type: "POST",
			url: "tipolista.php",
			data: {id:id},
			cache: false,
			success: function(result){
				$(".listas").html(" ");
				$(".listas").html(result);
			}
		});
  	});
  </script>
</head>
<body style="background-image: url('img/barra_superior_gris.png'); background-repeat: repeat-y;">
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img class="navbar-brand" src="img/CheckMart_logo.png" alt="" style="padding: 6px; max-width: 80px; height:auto;">
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="cliente.php">Buscar</a></li>
            <li class="active"><a>Listas</a></li>
            <li><a href="#promociones">Promociones</a></li>
            <li><a href="categorias.php">Categorías</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Perfil</a></li>
            <li><a target="_self" href="index.php?end=1">Salir</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	<div class="container" style="margin-top: 60px;">
		<div class="row" style="margin-left:20px; margin-top: 10px;">
			<div class="col-xs-1">
				<img class="img-circle" id="profile_pic" src="<?php echo $row['url_foto']; ?>" alt="">
			</div>
			<div class="col-xs-1" style="font-family: 'Open Sans', sans-serif; margin-top: 10px;">
				<p>Bienvenido</p>
			</div>	
			<div class="col-xs-1" style="font-family: 'Open Sans', sans-serif; margin-left: 10px; margin-right: 180px;">
				<h3><?php echo $row['nombre'] ?></h3>
			</div>	
			<div class="col-xs-4"></div>
			<div class="col-xs-1" style="padding: 10px;">
				<!-- <button class="btn btn-info btn-medium">Editar</button><br> -->
			</div>
			<div class="col-xs-1" style="padding: 10px; margin-left: 10px;">
				<!-- <button class="btn btn-success btn-medium" style="">Salir</button> -->
			</div>
		</div>
		<div class="row" style="margin-top: 30px;">
			<center><img src="img/barra_roja.png" alt="" style="height: 3px; width: 80%; "></center>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-4">
				<a class="btn btn-success btn-lg btn-block" style="margin-top:40px; border-radius: 5px;" data-toggle="modal" data-target="#crearlista">Crear Lista</a>
			</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<a class="tipoLista" id="propia"><div class="col-xs-5 	" style="background-color: #ED3237;	color: white!important; font-size: 20px;  padding-top: 10px;"><p class="text-center">Propias</p></div></a>
			<a class="tipoLista" id="compartida"><div class="col-xs-5 col-xs-offset-2	" style="background-color: #ED3237;	color: white!important; font-size: 20px;  padding-top: 10px;"><p class="text-center">Compartidas</p></div></a>
		</div>
		<div class="row col-xs-12 custyle">
                
               
               <div class="listas">
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
            	</table>
               </div>
         		
        </div>
	</div>

	<!-- Small modal -->
	<div class="modal fade" tabindex="-1" id="crearlista" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 100px;">
  		<div class="modal-dialog modal-sm">
	  		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        			<h4 class="modal-title" id="myModalLabel">Elige un nombre para tu lista</h4>
      			</div>
      			<div class="modal-body" style="padding-bottom: 0">
        			<form method="POST" action="categorias.php">
	        			<div class="form-group">
	   			 			<input type="text" class="form-control" id="nombrelista" name="nombrelista" placeholder="Nombre Lista">
	  					</div>
		      			<div class="modal-footer">
		        			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		    				<button type="submit" id="crearlista" class="btn btn-primary">Continuar</button>
		      			</div>
  					</form>
			 	</div>
	  		</div>
		</div>
	</div>

	<!-- Small modal -->
	<div class="modal fade" tabindex="-1" id="modalCompartir" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 100px;">
  		<div class="modal-dialog modal-sm">
	  		<div class="modal-content">

      			<div class="modal-body" style="padding-bottom: 0">

        			<form  method="GET" action="compartirlista.php?method=facebook">
        				<button id="inForm" type="submit"><img src="img/botones/facebook2.png" width="50" alt=""></button>
        				Compartir con Facebook
  					</form>
  					<form method="GET" action="compartirlista.php?method=mail">
        				<button id="inForm" type="submit"><img src="img/botones/mail.png" width="50" alt=""></button>
        				Compartir por mail.
  					</form>
	        			<div class="form-group">
	  					</div>
		      			<div class="modal-footer">
		        			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		      			</div>
			 	</div>
	  		</div>
		</div>
	</div>

	<div id="modal_precios"></div>

</body>
</html>