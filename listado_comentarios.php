<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  require_once 'bd.php';
  
  $variablesParaTwig = [];
    
  if (isset($_SESSION['nickUsuario'])) {

    conectarBD();
    $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
    $variablesParaTwig['comentarios'] = getComentarios();
    desconectarBD();
  }
  
  echo $twig->render('listado_comentarios.html', $variablesParaTwig);

?>