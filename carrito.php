<?php 
	require_once 'mysqlConnection.php';
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
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-1.11.1.min.js"></script>

    <script type="text/javascript">
	    $( document ).ready(function() {
	    	var categoria = "<?php echo $_GET['cat'];?>";

	    	if(categoria == "frescos") {
	    		alert("asdasd");
	    		$("#lista").html('<select name=dropdown size=1><option value=1>option 1</option><option value=2>option 2</option></select>');	
	    	}

		});	
    </script>

</head>
<body>
	<?php 
		/*$sqlSyntax = "SELECT subcategoria, subsubcategoria from producto WHERE categoria='".$_GET['cat']."' LIMIT 1";

		$result= @mysql_query($sqlSyntax);
        if ($result == FALSE) { die(@mysql_error()); }
        $row = mysql_fetch_array($result);
*/
	 ?>

	 <div id="lista">

	 </div>
 <script type="text/javascript">
	$('select[name="dropdown"]').change(function(){
		if ($(this).val() == "2")
			alert("call the do something function on option 2");
	});​
</script>
</body>
</html>