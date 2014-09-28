<?php
	require_once 'mysqlConnection.php';

    // esta función se va a llamar al cargar el primer combo
    function obtenerTodasLasSubcategorias() {
        $subcateogiras = array();
        $sqlSyntax = "SELECT subsubcategoria FROM 
				(
					SELECT * FROM subcategoria
					ORDER BY subsubcategoria
				) t1
				WHERE subcategoria = ".POST['subcategoria']."
				GROUP BY subcategoria";

		$result= @mysql_query($sqlSyntax);
        if ($result == FALSE) { die(@mysql_error()); }
        $row = mysql_fetch_array($result);
        // obtenemos todos los países

        // creamos objetos de la clase país y los agregamos al arreglo
        while($row = $result->fetch_assoc()){
            $row['nombre'] = mb_convert_encoding($row['nombre'], 'UTF-8', mysqli_character_set_name($db));          
            $pais = new pais($row['idpais'], $row['nombre']);
            array_push($subcateogiras, $pais);
        }

        cerrarConexion($db, $result);

        // devolvemos el arreglo
        return $subcateogiras;
    }

    class pais {
        public $id;
        public $nombre;

        function __construct($id, $nombre) {
            $this->id = $id;
            $this->nombre = $nombre;
        }
    }
?>