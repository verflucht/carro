<?php 
  ob_start();
  error_reporting(E_ALL); 
  ini_set('display_errors', 'On');
?> 
<!DOCTYPE html>
<html lang="es">
<head>
	<title>CheckMart>> La Revolución en Compras</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" href="css/prueba.css">

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript"> 
        function CheckForm() 
        { 
            var User= document.getElementById('inUser').value; //Se crea la variable User conteniendo el valor del input con id 'inUser' 
            var Pass= document.getElementById('inPass').value; 
            var errormsg= 'Debe completar: ' //Se crea un mensaje de error en la variable errormsg 
            if(User == '') //Si la variable 'User' no tiene contenido: 
                { 
                var error= true; //crea la variable 'error' con valor verdadero (existe) 
                var errormsg= errormsg + 'Nombre de Usuario'; 
                } 
            if(Pass == '') 
                { 
                var error= true; 
                var errormsg= errormsg + 'Contraseña'; 
                } 
            if(error) //Si existe la variable 'error' (si el valor es verdadero, true): 
            { 
                alert(errormsg) //Muestra un mensaje de alerta con el contenido de la variable 'errormsg' 
            } 
            else //sino 
            { 
                document.getElementById('loginForm').submit(); //Hace un submit en el form con id 'loginForm' 
            } 
        } 
    </script>

</head>
<body>
	<?php
    session_start(); //Esto inicia la sesión 

    if (isset($_GET["end"])) {
        if ($_GET["end"] == 1) {
            unset($_SESSION['user']);
            unset($_SESSION['time']);
            session_destroy();
        }   
    }
    


    if(isset($_SESSION['user'])) //Si existe la variable de sesión 'user': 
    { 
        $_SESSION['time']= time(); //Se crea la variable de sesión 'time' con el valor de time() (ejemplo: 1339168896) 
        if(isset($_SESSION['time'])) //Si existe la variable de sesión 'time': 
        { 
            $timeNow= time(); //Se asigna el valor de time() (ejemplo: 1339168963) a la variable timeNow 
            $timeCount= $timeNow - $_SESSION['time']; //Se le asigna a la variable timeCount el valor de la variable timeNow menos la variable de sesión 'time' (1339168963 - 1339168896 = 67 segundos) 

            if($timeCount>1200) //Si el valor de la variable timeCount es superior a 1200 (segundos, 20 minutos):  
            { 
                unset($_SESSION['user']); //Se destruye el valor de la variable de sesión 'user' 
                $_SESSION['error'] = 'Su sesion ha expirado. Ingrese nuevamente.'; //Se le asigna un mensaje de error a la variable de sesión 'error' 
            } 
        } 
    } 
    if(isset($_SESSION['error'])) //Si existe la variable de sesión 'error': 
    { 
        echo '<div style="margin-bottom: -52px;" class="alert alert-danger" id="error"><p>'.$_SESSION['error'].'</p></div>'; //Muestra un div con el mensaje de error contenido en la variable de sesión 'error' 
        unset($_SESSION['error']); //Destruye la variable de sesión 'error' 
    } 
    if(isset($_SESSION['user'])) //Si existe la variable de sesión 'user' 
    {  
        $_SESSION['time']= time(); //Se establece el valor time() de la variable de sesión 'time'.  
        header('Location: cliente.php'); //Redirecciona a la carpeta 'home' 
    } 
    else //si no existe la variable de sesión 'user' muestra el html siguiente: 
    { 
?>
	<div id="userExist"></div>
	<div style="width:100%; background-image: url('img/barra_superior_gris.png'); background-repeat:no-repeat; background-size:cover;">
		<div class="container" id="nav" >
			<div class="row" style="background-color: none;">
				<div class="col-xs-4 icono">
					<img src="img/CheckMart_logo.png">
				</div>
				<div class="col-xs-2"></div>
				<div class="col-xs-6">
					<ul class="lista_nav list-inline">
					  <li class="selected"><a href="#">Bienvenido</a></li>
					  <li><a href="#">Descargas</a></li>
					  <li><a href="#">Funciones</a></li>
					  <li><a href="#">Contacto</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div  style="width:100%; padding-bottom: 60px; background-image: url('img/fondo.png'); background-repeat:no-repeat; background-size: cover; background-position: 50% 50%;">
		<div class="container">
			<div class="row">
				<div class="col-xs-6"></div>
				<div class="col-xs-6">
					<form class="form-signin" style="background-image: url('img/marco_login.png');" id="loginForm" method="POST" action="login.php">
					    <h1 class="form-signin-heading text-muted" style="padding-bottom: 40px;"><img src=""></h1>
					    <input type="text" id="inUser" name="inUser" class="form-control" placeholder="Mail" autofocus="">
					    <input type="password" id="inPass" name="inPass" class="form-control" placeholder="Contrasena">
					    <input type="button" class="btn btn-lg btn-danger btn-block" id="inForm" onclick="CheckForm()" value="Iniciar Sesión">
					    <!--<input type="button" class="btn btn-lg btn-info btn-block" id="" onclick="CrearCuenta()" value="Crear Cuenta">-->
					    <a href="#crearCuenta" id="buttonModalCrearCuenta" role="button" class="btn btn-lg btn-info btn-block" data-toggle="modal">Crear Cuenta</a>
					    <input type="image" style="max-width: 250px; padding-top: 4px;" src="img/btn_facebook.png" onclick="CheckForm()">
				  	</form>  
			  	</div>
			</div>
		</div>
	</div>
	<div style="width:100%; height: 15px; background-image: url('img/barra_roja.png'); background-repeat:no-repeat; background-size: auto"></div>
	<div class="container" style="margin-top: 60px;" >
		<div class="row" style="padding: 30px;">
			<div class="col-xs-9">
				<p class="texto1" >CheckMart hará que tus listas de compras sean mas ordenas, 
					informadas, inteligentes y fáciles de recordar usando tu Smartphone</p>
			</div>
			<div class="col-xs-3">
				<img class="icono_texto" src="img/CheckMart_ico.png">
			</div>
		</div>
		<div class="row" style="margin-top: 60px; height: 7px; background-image: url('img/barra_gris_delgada.png'); background-repeat:no-repeat; background-size: auto"></div>
	</div>
	<div class="container" style="margin-top: 20px;" >
		<div class="row" style="padding-top: 15px;">
			<div class="col-xs-4">
				<img class="imagen1" src="img/mano_checkmart1.png">
			</div>
			<div class="col-xs-8">
				<h2>Proximamente disponible en Google Play</h2><br>
				<h4>CheckMart es una aplicación disenada para plataforma web y Android y
					blablablablablablabla</h4><br>
				<img src="img/google_play.png" style="width: 40%; height: auto; margin-left: 300px;">
			</div>
		</div>
	</div>
	<div style="width:auto; height: 7px; background-image: url('img/barra_gris_delgada.png'); background-repeat:no-repeat; background-size: 60%; background-position: 100% 50%;"></div>
	
	<div class="container" style="margin-top: 20px;" >
		<div class="row" style="padding-top: 15px;">
			<div class="col-xs-8" style="margin-left: 30px;">
				<h2>Como Funciona</h2><br>
				<h4>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.	</h4><br>
			</div>
			<div class="col-xs-3">
				<img class="imagen2" src="img/checkmart_celu.png">
			</div>
		</div>
	</div>

	<div style="width:auto; height: 7px; background-image: url('img/barra_gris_delgada.png'); background-repeat:no-repeat; background-size: 60%; background-position: 0% 50%;"></div>
	<br><br><br><br>

	<!-- Modal -->
<div class="modal fade bs-modal-sm" id="crearCuenta" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <br>
        <div class="bs-example bs-example-tabs">
            <ul id="myTab" class="nav nav-tabs">
              <center><li class=""><h2><span class="label label-info">Crear Cuenta</span></h2></li></center>
            </ul>
        </div>
      <div class="modal-body">
        
        <form class="form-horizontal">
            <fieldset>
            <!-- Sign Up Form -->
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="Email">Email:</label>
              <div class="controls">
                <input id="Email" name="Email" class="form-control" type="text" placeholder="JoeSixpack@sixpacksrus.com" class="input-large" required="">
              </div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="nombre">Nombre:</label>
              <div class="controls">
                <input id="nombre" name="nombre" class="form-control" type="text" placeholder="JoeSixpack" class="input-large" required="">
              </div>
            </div>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="apellido">Apellido:</label>
              <div class="controls">
                <input id="apellido" name="apellido" class="form-control" type="text" placeholder="Einstein" class="input-large" required="">
              </div>
            </div>
            
            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="password">Contrasena:</label>
              <div class="controls">
                <input id="password" name="password" class="form-control" type="password" placeholder="********" class="input-large" required="">
              </div>
            </div>
            
            
            <!-- Multiple Radios (inline) -->
            <br>
            <div class="control-group">
              <label class="control-label" for="humancheck">Detector de Humanos:</label>
              <div class="controls">
                <label class="radio inline" for="humancheck-0">
                  <input type="radio" name="humancheck" id="humancheck-0" value="robot" checked="checked">Soy un Robot</label>
                <label class="radio inline" for="humancheck-1">
                  <input type="radio" name="humancheck" id="humancheck-1" value="human">Soy un Humano</label>
              </div>
            </div>
            
            <!-- Button -->
            <div class="control-group">
              <label class="control-label" for="confirmsignup"></label>
              <div class="controls">
              </div>
            </div>
            </fieldset>
            </form>
            
      </div>
      <div class="modal-footer">
      <center>
        <a type="button" id="confirmsignup" name="confirmsignup" class="btn btn-success" value="">Registrar</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </center>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $("#confirmsignup").click(

      function confirmmail() {
      var mail = $("#Email").val();
      var nombre = $("#nombre").val();
      var apellido = $("#apellido").val();
      var pass = $("#password").val();
      
      if(mail == "" || nombre == "" || apellido == "" || pass == ""){
        alert("Dejaste algunos de los campos vacios, completalos para seguir!");
      }
      else{
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if(filter.test(mail)){
          alert("entro");
          $.ajax({  
            type: "POST",
            url: "register.php",
            data: {mail:mail, nombre:nombre, apellido:apellido, pass:pass},
            cache: false,
            success: function(result){
              if(result == "1"){
                $('#crearCuenta').modal('toggle');
                alert(result);
                //location.reload();
                $("#userExist").html('<div style="margin-bottom: -52px;" class="alert alert-danger" id="error"><p>Este mail ya esta registrado, intenta con otro!</p></div>');
              }
              else if(result == "0"){
                alert(result);
                window.location.replace("cliente.php");
              }
              //$("#lista").html(result);
            }
          });
        }
        else{
          alert("Mail incorrecto, intentalo denuevo!");
        }
      }
    });
  </script>

</body>
</html>

<?php } ?>