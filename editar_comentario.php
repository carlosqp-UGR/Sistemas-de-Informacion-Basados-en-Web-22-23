<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  require_once 'bd.php';
  
  $variablesParaTwig = [];
    
  if (isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {
    $idComentario = $_GET['id'];

    conectarBD();
    $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
    $variablesParaTwig['comentario'] = getComentario($idComentario);
    desconectarBD();
  }
  
  echo $twig->render('editar_comentario.html', $variablesParaTwig);

?>