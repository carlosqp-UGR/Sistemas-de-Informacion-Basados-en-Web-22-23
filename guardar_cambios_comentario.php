<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {
    conectarBD();

    // Recuperar los datos del formulario (contenido del comentario)
    $contenido = $_POST['contenido'];

    // Verificar que el contenido no esté vacío
    if (strlen($contenido) > 0) {
        // Modificar el comentario (y activar la bandera MODIFICADO)
        modificarComentario($_GET['id'], $contenido);
    }

    desconectarBD();

    // Volvemos a cargar la página con los cambios guardados
    header("Location: listado_comentarios.php");

  }
?>