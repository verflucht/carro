<?php 

	require_once 'mysqlConnection.php';

	$sqlSyntax = 'SELECT * FROM producto WHERE descripcion LIKE "atun"';

	$result= @mysql_query($sqlSyntax);
    if ($result == FALSE) { die(@mysql_error()); }
    $row = mysql_fetch_array($result);

    echo "asdasd: ".$row[0];

?>	