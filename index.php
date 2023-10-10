<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once "bd.php";

  session_start();

  $variablesParaTwig = [];

  conectarBD();
  $variablesParaTwig['cientificos'] = getPortadaCientificos();
  if (isset($_SESSION['nickUsuario'])) {
    $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
  }
  desconectarBD();

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  echo $twig->render('portada.html', $variablesParaTwig);
?>

