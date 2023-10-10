<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {

    // Recuperar los datos del formulario (contenido del comentario)
    $nombre = $_POST['nombre'];
    $contenido = $_POST['contenido'];
    // la variable $publicado se establece como true si $_POST['publicado'] es igual a "true", y se establece como false en caso contrario.
    $publicado = $_POST['publicado'] === 'true'; // Convertir a valor booleano directamente

    conectarBD();

    $cientifico = getCientifico($_GET['id']);

    if(!empty($nombre) && $nombre!=$cientifico['nombre']) {
      modificarNombreCientifico($_GET['id'], $nombre);
    }

    if(!empty($contenido) && $contenido!=$cientifico['contenido']) {
      modificarContenidoCientifico($_GET['id'], $contenido);
    }

    if(boolval($cientifico['publicado']) !== $publicado) {
      modificarPublicadoCientifico($_GET['id'], $publicado);
    }

    desconectarBD();

    // Volvemos a cargar la página con los cambios guardados
    header("Location: listado_cientificos.php");
  }
?>