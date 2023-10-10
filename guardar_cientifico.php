<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario'])) {

    // Comprobar que NINGUN CAMPO este vacio
    if (!empty($_POST['nombre']) && !empty($_POST['contenido']) && !empty($_FILES['portada']['name']) && !empty($_FILES['imagen']['name'])) {
      // Recuperar los datos del formulario
      $nombre = $_POST['nombre'];
      $contenido = $_POST['contenido'];

      // Almacenar las imagenes subidas (imagen de portada)
      $errors= array();
      $file_name = $_FILES['portada']['name'];
      $file_size = $_FILES['portada']['size'];
      $file_tmp = $_FILES['portada']['tmp_name'];
      $file_type = $_FILES['portada']['type'];
      $file_name_parts = explode('.', $_FILES['portada']['name']);
      $file_ext = strtolower(end($file_name_parts));

      $srcPortada = "imgs/subidas/portada/" . $file_name;
      
      $extensions= array("jpeg","jpg","png");
      
      if (in_array($file_ext,$extensions) === false){
        $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
      }
      
      if ($file_size > 2097152){
        $errors[] = 'Tamaño del fichero demasiado grande';
      }
      
      if (empty($errors)==true) {
          // Mover la imagen al directorio correspondiente
          move_uploaded_file($file_tmp, $srcPortada);    
      }

      // Almacenar las imagenes subidas (imagen de galeria)
      $errors2= array();
      $file_name = $_FILES['imagen']['name'];
      $file_size = $_FILES['imagen']['size'];
      $file_tmp = $_FILES['imagen']['tmp_name'];
      $file_type = $_FILES['imagen']['type'];
      $file_name_parts = explode('.', $_FILES['imagen']['name']);
      $file_ext = strtolower(end($file_name_parts));

      $srcImagen = "imgs/subidas/galeria/" . $file_name;
      
      $extensions= array("jpeg","jpg","png");
      
      if (in_array($file_ext,$extensions) === false){
        $errors2[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
      }
      
      if ($file_size > 2097152){
        $errors2[] = 'Tamaño del fichero demasiado grande';
      }
      
      if (empty($errors2)==true) {
          // Mover la imagen al directorio correspondiente
          move_uploaded_file($file_tmp, $srcImagen);    
      }

      // Conectar a la BD e insertar al cientifico
      conectarBD();
      if(addCientifico($nombre, $contenido)) {
          // La funcion devuelve el id del cientifico por nombre $nombre
          $cientifico = buscarCientificoNombre($nombre);
          addImagen($cientifico, $srcPortada);
          addImagen($cientifico, $srcImagen);
      }
      desconectarBD();
    }

  // Volvemos a cargar la página
  header('Location: listado_cientificos.php');
}

?>