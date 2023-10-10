<?php

session_start();

require_once 'bd.php';

// Realiza la conexión a la base de datos
conectarBD();

// Solo busca los publicados
$search = true;

// Si se ha iniciado sesion y el usuario es gestor de contenido o super usuario
if(isset($_SESSION['nickUsuario'])) {
  $usuario = getUser($_SESSION['nickUsuario']);
  if ($usuario['roll'] == 'super' || $usuario['roll'] == 'gestor_contenido') {
    // Busca tanto publicados como no publicados
    $search = false;
  }
}

// Obtén la cadena de búsqueda enviada por el cliente
$query = $_GET['query'];

// Busca las sugerencias
$sugerencias = busquedaCientifico($query, $search);

// Envía los resultados como respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($sugerencias);

// Cierra la conexión a la base de datos
desconectarBD();
?>
