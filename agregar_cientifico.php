<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  $variablesParaTwig = [];
    
  if (isset($_SESSION['nickUsuario'])) {
    conectarBD();
    $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
    desconectarBD();
  }
  

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  echo $twig->render('agregar_cientifico.html', $variablesParaTwig);
?>