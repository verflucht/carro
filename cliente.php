<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 

	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");

	if (isset($_SESSION['facebook'])){
    	$_SESSION['user'] = "";
    	$_SESSION['id'] = $_SESSION['id_fb'];
	}
	if(!isset($_SESSION['user'])){
		header(	'Location: index.php');
	}
	if(isset($_SESSION['facebook'])){
		$sqlSyntax= 'SELECT * FROM usuario WHERE id_facebook = "'.$_SESSION['id_fb'].'"'; //Se crea la sintaxis para la base de datos 

	}
	else{
		$sqlSyntax= 'SELECT * FROM usuario WHERE mail = "'.$_SESSION['user'].'"'; //Se crea la sintaxis para la base de datos 
		$_SESSION['id'] = $_SESSION['user'];
	}

    $result= @mysql_query($sqlSyntax); //Se ejecuta el query de $sqlSyntax  
    if ($result == FALSE) { die(@mysql_error()); }

   $row = mysql_fetch_array($result);
    if (strlen($row['url_foto']) == ""){
    	if($row['sexo'] == "1"){
    		$row['url_foto'] = "img/default-user-female.png";
		}
    	else{
    		$row['url_foto'] = "img/default-user-men.png";
    	}	
	}

?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html lang="es">
<head>
	<title>CheckMart>> La Revolución en Compras</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/cliente.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<!-- Latest compiled and minified JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-1.11.1.min.js"></script>
</head>

		<script type="text/javascript">
		$(document).ready(function(){
			$("#getdata").click(function(){
				var busqueda = $("#producto").val();
				// Returns successful data submission message when the entered information is stored in database.
				var busqueda = busqueda;
				if(busqueda=='')
				{
					alert("Elija lo que desea buscar primero");
				}
				else
				{
					$("#lista").html('<div class="container" style="margin-top: 70px;"><div class="row" style="margin-left:300px;"><img src="img/loading/loading.gif"> </div></div>');
					// AJAX Code To Submit Form.
					$.ajax({
					type: "POST",
					url: "getdata.php",
					data: {busqueda:busqueda},
					cache: false,
					success: function(result){
						//alert(result);
						$("#lista").html(result);
					}
				});
			}
			return false;
			});
		});
	</script>
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
            <li class="active"><a href="#">Buscar</a></li>
            <li><a href="listas.php">Listas</a></li>
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
		<div class="row" style="margin-top: 10px; margin-bottom: 20px;">
			<div class="col-xs-3"><h3 >Buscar Producto</h3></div>
			<div class="col-xs-3" style="margin-top: 10px;">
				<input type="text" id="producto" class="form-control input-lg col-xs-3" style="border-radius: 0px;" name="" value="">
			</div>
			<div class="col-xs-4" style="padding-left: 0px; margin-top:10px;" >
				<button type="button" name="getdata" id="getdata" class="buscar btn btn-lg btn-warning">Buscar</button>
			</div>

		</div>
		
	</div>

	<div id="lista"></div>
</body>