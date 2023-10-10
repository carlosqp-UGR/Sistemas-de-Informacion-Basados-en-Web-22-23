<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";  
  require_once 'bd.php';
  
  $variablesParaTwig = [];
    
  if (isset($_SESSION['nickUsuario']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    conectarBD();
    // Obtenemos el cientifico antes de ser modificado para comparaciones
    $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
    $variablesParaTwig['cientifico'] = getCientifico($id);
    desconectarBD();
  }

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  echo $twig->render('editar_cientifico.html', $variablesParaTwig);

?>
