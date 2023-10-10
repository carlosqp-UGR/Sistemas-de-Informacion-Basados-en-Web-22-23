<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  // Parámetros de entrada cf && sty

  $estilo = "css/estilos.css";

  $cientifico = array();
  $fotos = array();

  $idCf = -1;
  if(isset($_GET['cf'])) {
    $idCf = $_GET['cf'];
    // Inicio de conexion
    conectarBD();

    // Funcionalidades, operaciones, consultas
    $cientifico = getCientifico($idCf);
    $fotos = getGaleriaCientifico($idCf);

    // Cierre de conexión
    desconectarBD();
  }

  echo $twig->render('imprimir.html', ['cientifico' => $cientifico, 'fotos' => $fotos]);
  
?>