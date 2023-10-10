<?php
  require_once 'bd.php';

  conectarBD();
  // Aquí iría la lógica para conectarse a la base de datos y obtener las palabras censuradas
  $palabras = getPalabrasCensuradas(); // Este es solo un ejemplo

  desconectarBD();
  
  header('Content-Type: application/json');
  echo json_encode($palabras);
?>
