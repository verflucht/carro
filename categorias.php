<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 


	if ($_POST['nombrelista'] != ""){
		$_SESSION['nombrelista'] = $_POST['nombrelista'];
		$_SESSION['lista'] = array();
	}

	if (isset($_SESSION['facebook'])){
    	$_SESSION['user'] = "";
	}
	if(!isset($_SESSION['user'])){
		header(	'Location: index.php');
	}
	if(isset($_SESSION['facebook'])){
		$sqlSyntax= 'SELECT * FROM usuario WHERE id_facebook = "'.$_SESSION['id_fb'].'"'; //Se crea la sintaxis para la base de datos 
	}
	else{
		$sqlSyntax= 'SELECT * FROM usuario WHERE mail = "'.$_SESSION['user'].'"'; //Se crea la sintaxis para la base de datos 
	}
	require_once 'mysqlConnection.php'; //Requiere el archivo 'SqlConnection.php
	mysql_query("SET NAMES 'utf8'");	
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
	$id_usuario = $row['id_usuario'];
	$now = time();
	$num = date("w");
	if ($num == 0)
	{ $sub = 6; }
	else { $sub = ($num-1); }
	$WeekMon  = mktime(0, 0, 0, date("m", $now)  , date("d", $now)-$sub, date("Y", $now));    //monday week begin calculation
	$todayh = getdate($WeekMon); //monday week begin reconvert

	$d = $todayh[mday];
	$m = $todayh[mon];
	$y = $todayh[year];
	$dia = $d."/".$m."/".$y;

	echo $_SESSION['lista'];
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html lang="es">
<head>
	<title>CheckMart>> La Revolución en Compras</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/cliente.css">
	<link rel="stylesheet" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<!-- Latest compiled and minified JavaScript -->
	<script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

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
					// AJAX Code To Submit Form.
					$.ajax({
					type: "POST",
					url: "getcategorias.php",
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
            <li><a href="cliente.php">Buscar</a></li>
            <li><a href="listas.php">Listas</a></li>
            <li><a href="#promociones">Promociones</a></li>
            <li class="active"><a href="categorias.php">Categorías</a></li>
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
		<div class="row" style="margin-top: 30px; margin-bottom: 60px;">
			<center><img src="img/barra_roja.png" alt="" style="height: 3px; width: 80%; "></center>
			<p class="text-center" style="font-size: 20px; margin-top: 15px;">SELECCIONA UNA CATEGORÍA</p>
			<?php 	
					if ($_POST['nombrelista'] != ""){
						$sqlSyntax = 'INSERT INTO lista (nombre_lista, id_usuario, fecha) VALUES("'.$_POST['nombrelista'].'",'.$id_usuario.',"'.$dia.'")';
					    $result= @mysql_query($sqlSyntax); //Se ejecuta el query de $sqlSyntax  
					    if ($result == FALSE) { die(@mysql_error()); }
					    $_SESSION['id_lista'] =  mysql_insert_id();
					}
			?>
		</div>
	</div>
	<form method="GET" action="carrito.php">
	<div class="container" style="margin-bottom:50px;" >
			<div class="col-xs-3">
				<a href="carrito.php?cat=frescos"><img class="img_producto" src="img/frescos.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">FRESCOS</p></button>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=carnespescados"><img class="img_producto" src="img/carnes_pescados.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">CARNES/PESCADOS</p></a>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=despensa"><img class="img_producto" src="img/despensa.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">DESPENSA</p></a>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=panaderia"><img class="img_producto" src="img/panaderia.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">PANADERIA</p></a>
			</div>
	</div>
	<div class="container" style="margin-bottom:50px;" >
			<div class="col-xs-3">
				<a href="carrito.php?cat=congelados"><img class="img_producto" src="img/congelados.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">CONGELADOS</p></a>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=dulces"><img class="img_producto" src="img/dulces.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">DULCES</p></a>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=licores"><img class="img_producto" src="img/licores.jpeg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">LICORES</p></a>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=perfumeria"><img class="img_producto" src="img/perfumeria.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">PERFUMERIA</p></a>
			</div>
	</div>
	<div class="container" style="margin-bottom:50px;" >
			<div class="col-xs-3 col-xs-offset-3">
				<a href="carrito.php?cat=limpieza"><img class="img_producto" src="img/limpieza.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">LIMPIEZA</p></a>
			</div>
			<div class="col-xs-3">
				<a href="carrito.php?cat=hogar"><img class="img_producto" src="img/hogar.jpg" style="width: 200px; HEIGHT: 120px; margin: 0 auto;">
				<p class="text-center" style="font-size: 20px; margin-top: 15px;">HOGAR</p></a>
			</div>
	</div>
	</form>
</body>
</html>