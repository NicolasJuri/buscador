<?php 
  $servidor= "localhost";
  $usuario= "root";
  $password = "";
  $nombreBD= "buscador";
  $conexion = new mysqli($servidor, $usuario, $password, $nombreBD);
  if ($conexion->connect_error) {
      die("la conexión ha fallado: " . $conexion->connect_error);
  }
//   if (!$conexion->set_charset("utf8")) {
//       printf("Error al cargar el conjunto de caracteres utf8: %s\n", $conexion->error);
//       exit();
//   } else {
//       printf("Conjunto de caracteres actual: %s\n", $conexion->character_set_name());
//   }

  if (!isset($_POST['buscar'])){$_POST['buscar'] = '';}
  if (!isset($_REQUEST["mostrar_todo"])){$_REQUEST["mostrar_todo"] = '';}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

<title>Buscador avanzado</title>
</head>
<body>

<div class="container mt-5">
<div class="col-12">

<form method="POST" action="index.php">
<div class="mb-3">
<h1 class="display-3 text-decoration-underline text-center">Buscador de palabra</h1><br/>
<input type="text" class="form-control" id="buscar" name="buscar" value="<?php echo $_POST['buscar']; ?>">
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
  <button type="text" class="btn btn-success">Buscar <i class="bi bi-search"></i></button>
  <a class="btn btn-primary mt-2" href="index.php?mostrar_todo=ok">Mostrar todo <i class="bi bi-eye"></i></a>

</div>

</form>

<div class="card col-12 mt-0 border-0">
<div class="card-body border-0">

<?php 
if(!empty($_POST))
{

        // resaltar el resultado
        function resaltar_frase($string, $frase, $taga = '<b>', $tagb = '</b>'){
            return ($string !== '' && $frase !== '')
            ? preg_replace('/('.preg_quote($frase, '/').')/i'.('true' ? 'u' : ''), $taga.'\\1'.$tagb, $string)
            : $string;
             }
    
  
      $aKeyword = explode(" ", $_POST['buscar']);
      $filtro = "WHERE nombre LIKE LOWER('%".$aKeyword[0]."%') OR apellidos LIKE LOWER('%".$aKeyword[0]."%')";
      $query ="SELECT * FROM datos_usuario WHERE nombre LIKE LOWER('%".$aKeyword[0]."%') OR apellidos LIKE LOWER('%".$aKeyword[0]."%')";
  

     for($i = 1; $i < count($aKeyword); $i++) {
        if(!empty($aKeyword[$i])) {
            $query .= " OR nombre LIKE LOWER('%" . $aKeyword[$i] . "%') OR apellidos LIKE LOWER('%" . $aKeyword[$i] . "%')";
        }
      }
     
     $result = $conexion->query($query);
     $numero = mysqli_num_rows($result);
     if (!isset($_POST['buscar'])){
     echo "Has buscado la palabra:<b> ". $_POST['buscar']."</b>";
     }

     if( mysqli_num_rows($result) > 0 AND $_POST['buscar'] != '') {
        $row_count=0;
        echo "<br>Resultados encontrados:<b> ".$numero."</b>";
        echo "<br><br><table class='table table-striped'>";
        While($row = $result->fetch_assoc()) {   
            $row_count++;   
            echo "<tr><td>".$row_count." </td><td>". resaltar_frase($row['nombre'] ,$_POST['buscar']) . "</td><td>". resaltar_frase($row['apellidos'] ,$_POST['buscar']) . "</td></tr>";
        }
        echo "</table>";
	
    }
    else {
      //mostrar todos los resultados
      if( $_REQUEST["mostrar_todo"] == 'ok') {
        $row_count=0;
        echo "<br>Resultados encontrados:<b> ".$numero."</b>";
        echo "<br><br><table class='table table-striped'>";
        While($row = $result->fetch_assoc()) {   
            $row_count++;   
            echo "<tr><td>".$row_count." </td><td>". resaltar_frase($row['nombre'] ,$_POST['buscar']) . "</td><td>". resaltar_frase($row['apellidos'] ,$_POST['buscar']) . "</td></tr>";
        }
        echo "</table>";
	
    }
    }
}
?>


</div>
</div>

</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>
</body>
</html>