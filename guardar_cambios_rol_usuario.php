<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  $variablesParaTwig = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {
    conectarBD();

    // Obtener el usuario que va a ser modificado
    $id = $_GET['id'];

    // Obtener el valor del rol seleccionado por el usuario
    $roll = $_POST['roll'];

    if (modificarRolUsuario($id, $roll)) {
      // Si se elimina correctamente, redireccionamos a la lista de comentarios
      desconectarBD();
      header("Location: listado_usuarios.php");
      exit();
    } else {
      // Si hay algún error al eliminar, mostramos un mensaje de error
      desconectarBD();
      echo "Ha ocurrido un error al modificar el rol";
    }
  }
?>
