<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  if (isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {
    conectarBD();

    // Sanitizamos el valor del ID recibido para evitar inyecciones de código SQL
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Eliminamos el comentario
    if (borrarComentario($id)) {
      // Si se elimina correctamente, redireccionamos a la lista de comentarios
      header("Location: listado_comentarios.php");
      exit();
    } else {
      // Si hay algún error al eliminar, mostramos un mensaje de error
      echo "Ha ocurrido un error al eliminar el comentario.";
    }

    desconectarBD();
  }
?>
