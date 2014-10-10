<?php 
	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'off');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start(); 
	require_once 'mysqlConnection.php';
	mysql_query("SET NAMES 'utf8'");


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
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/styles.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<!-- Latest compiled and minified JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-1.11.1.min.js"></script>

    <script type="text/javascript">
    	$(document).on('click', '.number-spinner button', function () {    
			var btn = $(this),
				oldValue = btn.closest('.number-spinner').find('input').val().trim(),
				newVal = 0;
			
			if (btn.attr('data-dir') == 'up') {
				newVal = parseInt(oldValue) + 1;
			} else {
				if (oldValue > 1) {
					newVal = parseInt(oldValue) - 1;
				} else {
					newVal = 1;
				}
			}
			btn.closest('.number-spinner').find('input').val(newVal);
		});
    </script>
 	<script type="text/javascript">
    	$(document).on('click', '.botonAgregar', function () {    
			
			//var idProducto = this.id;
			//var nombreProducto = ;
			id = this.id
        	var cantidad = $('#id_'+this.id).val()
        		$.ajax({
		          type: "POST",
		          url: "agregarlista.php",
		          data: {cantidad:cantidad,id:id},
		          cache: false,
		          success: function(result){
		            if (result != ''){
		              alert("error"+"  "+result);
		            }
		            else{
		              alert("Producto Agregado al carro");
		            }
		            //  $("#lista").html(result);
		            
		          }
		        });

		});
    </script>
    <script type="text/javascript">
    	$(document).on('click', '.paginationbutton', function () {    
			
			//var idProducto = this.id;
			//var nombreProducto = ;
			var pagina = this.id
			//alert(pagina);
			//alert(subsubcategoria);
        		$.ajax({
		          type: "POST",
		          url: "subsubcategorias.php",
		          data: {pagina:pagina},
		          cache: false,
		          success: function(result){
		            if (result == ''){
		              alert(result);
		            }
		            else{
		              $("#resultadosBusqueda").html(result);
		            }
		            //  $("#lista").html(result);
		          }
	        });

		});
    </script>
    <script type="text/javascript">
    	$(document).on('click', '.botonActualizar', function () {    
			
			//var idProducto = this.id;
			//var nombreProducto = ;
			id = this.id
        	var cantidad = $('#id_'+this.id).val()
        	if (cantidad != ""){
        		$.ajax({
		          type: "POST",
		          url: "agregarlista.php",
		          data: {cantidad:cantidad,id:id},
		          cache: false,
		          success: function(result){
		            if (result != ''){
		              alert(result);
		            }
		            else{
		            	//alert(result);
		              	alert("Producto Actualizado");
		            }
		            //  $("#lista").html(result);
		            
		          }
		        });
        	}
        	else alert("Debe seleccionar una cantidad");
		});
    </script>
    <script type="text/javascript">
    	$(document).ready(function(){
			
    		// cada vez que se cambia el valor del combo
		    $("#comboSubcategorias").change(function() {

		        // obtenemos el valor seleccionado
		        var subCategoria = $(this).val();
		        //alert(subCategoria);
		        $("#resultadosBusqueda").html("");
		        // si es 0, no es subcategoria
		        if(subCategoria != 0)
		        {
		            //creamos un objeto JSON
		            $.ajax({
					type: "POST",
					url: "subsubcategorias.php",
					data: {subCategoria:subCategoria},
					cache: false,
					success: function(result){
						//alert(result);
						$("#comboSubsubcategorias").html(result);
					}
				});
		        }
		    });

		    $("#comboSubsubcategorias").change(function() {
		    	var subSubcategorias = $("#comboSubsubcategorias option:selected").text();
		    	$("#resultadosBusqueda").html(" ");
		    	//alert("asdad "+subSubcategorias);
		        // si es 0, no es subcategoria
		        if(subSubcategorias != 0)
		        {
		            //creamos un objeto JSON
		            $.ajax({
						type: "POST",
						url: "subsubcategorias.php",
						data: {subSubcategorias:subSubcategorias},
						cache: false,
						success: function(result){
							//alert(result);
							$("#resultadosBusqueda").html(result);
						}
					});
		        }
		    });
		}); 
    </script>
    <script type="text/javascript">
    	$(document).on('click', '.verlista', function () {    
			
			//var idProducto = this.id;
			//var nombreProducto = ;
        		$.ajax({
		          type: "POST",
		          url: "verlista.php",
		          data: {},
		          cache: false,
		          success: function(result){
		            if (result == ''){
		              alert("error");
		            }
		            else{
		              alert(result);
		              $('#modallista').html(" ");
		              $('#modallista').html(result);

		              $('#modalVerlista').modal('show');

		            }
		            //  $("#lista").html(result);
		            
		          }
		        });

		});
    </script>
</head>
<body>
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
			<div class="col-xs-4">
				<form action="categorias.php" method="post" accept-charset="utf-8">
					<button type="submit" href="categorias.php" class="btn btn-lg btn-info">VOLVER A CATEGORIAS</button>
				</form>
				<?php 
					if( isset($_SESSION['lista']) AND isset($_SESSION['update'])){
						echo '	<form action="actualizarlistafinal.php" method="post" accept-charset="utf-8">
		    						<button class="btn btn-lg btn-success">ACTUALIZAR LISTA</button>
    							</form>';
					}
					else if(isset($_SESSION['lista'])){
						echo '	<form action="finalizarlista.php" method="post" accept-charset="utf-8">
		    						<button class="btn btn-lg btn-success">FINALIZAR LISTA</button>
								</form>';
					}
				
				 ?>
			</div>
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

		<div class="col-xs-6 col-xs-offset-3" style="margin-bottom: 30px;">
			<h3 class="text-center">Selecciona una Subcategoria</h3>
	<?php 

		$sqlSyntax = 'SELECT subcategoria FROM 
				(
					SELECT * FROM producto
					ORDER BY categoria
				) t1 WHERE categoria = "'.$_GET['cat'].'" GROUP BY subcategoria';

		$result= @mysql_query($sqlSyntax);
        if ($result == FALSE) { die(@mysql_error()); }
        echo '<select id="comboSubcategorias" class="form-control" style="margin-top: 30px; margin-bottom: 30px;">';
	  	echo '<option value="0">Elige una Sub Categoría</option>';
    	while($row = mysql_fetch_array($result)){
	     echo '<option value="'.$row['subcategoria'].'">'.$row['subcategoria'].'</option>';
	  	}
        echo '</select>';

	?>
		<div id="working" style="display: none;"><img src="img/ajax-loader.gif" alt="loading..."></div>
		<div id="comboSubsubcategorias"></div>        
    </div>
    <div id="resultadosBusqueda"></div>



</body>
</html>