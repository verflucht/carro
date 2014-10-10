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
    	$(function()
			{
			    $(document).on('click', '.btn-add', function(e)
			    {
			        e.preventDefault();

			        var controlForm = $('.controls form:first'),
			            currentEntry = $(this).parents('.entry:first'),
			            newEntry = $(currentEntry.clone()).appendTo(controlForm);

			        newEntry.find('input').val('');
			        controlForm.find('.entry:not(:last) .btn-add')
			            .removeClass('btn-add').addClass('btn-remove')
			            .removeClass('btn-success').addClass('btn-danger')
			            .html('<span class="glyphicon glyphicon-minus"></span>');
			    }).on('click', '.btn-remove', function(e)
			    {
					$(this).parents('.entry:first').remove();

					e.preventDefault();
					return false;
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

		<div class="row" style="margin-top: 20px;">
			<h3 class="text-center" style="margin-bottom:20px;">SELECCIONE UN MODO PARA COMPARTIR SU LISTA</h3>
			<div class="col-xs-3 col-xs-offset-4">
			<form action="" method="post" accept-charset="utf-8">
				<input type="hidden" name="inputText" value="mail">
				<button type="submit" name="SubmitButton"><img src="img/botones/mail.png" alt=""></button>
			</form>
			</div>

			<div class="col-xs-3">
				<form action="" method="post" accept-charset="utf-8">
				<input type="hidden" name="inputText" value="facebook">
				<button type="submit" name="SubmitButton"><img src="img/botones/facebook2.png" alt=""></button>
			</form>
			</div>
		</div>
<?php 
	if(isset($_POST['id_lista']))
		$_SESSION['id_lista'] = $_POST['id_lista'];
	if(isset($_POST['SubmitButton'])){ //check if form was submitted
		$input = $_POST['inputText']; //get input text
		//echo "Success! You entered: ".$input." ".$_SESSION['id_lista'];
		echo '<div class="container">
				<div class="row">
			        <div class="control-group" style="width: 300px; margin: 0 auto; margin-top: 40px;" id="fields">
			            <label class="control-label" for="field1">Agrega todos los amigos que quieras!</label>
			            <div class="controls" style="width: 260px; margin: 0 auto;" > 
			                <form role="form" autocomplete="off">
			                    <div class="entry input-group">
			                        <input class="form-control" name="fields[]" type="text" placeholder="Type something" />
			                    	<span class="input-group-btn">
			                            <button class="btn btn-success btn-add" type="button">
			                                <span class="glyphicon glyphicon-plus"></span>
			                            </button>
			                        </span>
			                    </div>
			                </form>
			            <br>
			            </div>
			        </div>
				</div>
			</div>';
	}    
 ?>
</body>
</html>