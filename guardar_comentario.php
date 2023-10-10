<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  $variablesParaTwig = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario']) && isset($_GET['cf'])) {
    conectarBD();

    // Datos del comentario
    $usuario = $_SESSION['nickUsuario'];
    $cientifico = $_GET['cf'];
    $contenido = $_POST['contenido'];

    // Añadir el comentario (siempre y cuando el contenido no esté vacío)
    if (strlen($contenido) > 0) {
        addComentario($cientifico, $usuario, $contenido);
    }

    // Funcionalidades, operaciones, consultas
    $variablesParaTwig['cientifico'] = getCientifico($cientifico);
    $variablesParaTwig['fotos'] = getGaleriaCientifico($cientifico);
    $variablesParaTwig['comentarios'] = getComentariosCientifico($cientifico);
    $variablesParaTwig['user'] = getUser($usuario);

    desconectarBD();
  }

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  echo $twig->render('cientifico.html', $variablesParaTwig);
?>