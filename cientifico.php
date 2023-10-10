<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once "bd.php";

  session_start();

  $variablesParaTwig = [];

  if (isset($_GET['cf'])) {
    $id = $_GET['cf'];

    conectarBD();

    // Funcionalidades, operaciones, consultas
    $variablesParaTwig['cientifico'] = getCientifico($id);
    $variablesParaTwig['fotos'] = getGaleriaCientifico($id);
    $variablesParaTwig['comentarios'] = getComentariosCientifico($id);

    // Obtener el usuario (si lo hay)
    if (isset($_SESSION['nickUsuario'])) {
      $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
    }
    desconectarBD();

  }

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  echo $twig->render('cientifico.html', $variablesParaTwig);
?>
