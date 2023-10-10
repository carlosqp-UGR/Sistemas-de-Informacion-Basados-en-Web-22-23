<?php

session_start();

  
  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once "bd.php";

    // Parametros:
    // - id del cientifico 
    // - im {0:portada, 1:galeria}
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {
    $cientifico = $_GET['id'];
    
    // Si la imagen no esta vacia
    if(!empty($_FILES['imagen']['name'])) {
      
      // Almacenar las imagenes subidas (imagen de galeria)
      $errors= array();
      $file_name = $_FILES['imagen']['name'];
      $file_size = $_FILES['imagen']['size'];
      $file_tmp = $_FILES['imagen']['tmp_name'];
      $file_type = $_FILES['imagen']['type'];
      $file_name_parts = explode('.', $_FILES['imagen']['name']);
      $file_ext = strtolower(end($file_name_parts));

      $srcImagen = "imgs/subidas/galeria/" . $file_name;
      
      $extensions= array("jpeg","jpg","png");
      
      if (in_array($file_ext,$extensions) === false){
        $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
      }
      
      if ($file_size > 2097152){
        $errors[] = 'Tamaño del fichero demasiado grande';
      }
      
      if (empty($errors)==true) {
          // Mover la imagen al directorio correspondiente
          move_uploaded_file($file_tmp, $srcImagen);    
      }

      conectarBD();
      addImagen($cientifico, $srcImagen);
      desconectarBD();

    }

    // Volver a cargar la página
    header('Location: editar_cientifico.php?id=' . $cientifico);
  }

?>