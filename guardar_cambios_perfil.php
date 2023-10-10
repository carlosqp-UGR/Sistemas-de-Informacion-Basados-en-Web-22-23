<?php
  session_start();

  require_once "/usr/local/lib/php/vendor/autoload.php";
  require_once 'bd.php';

  $variablesParaTwig = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickUsuario'])) {
    conectarBD();

    // Recuperar los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Obtener los datos de usuario de forma local para comprobaciones
    $usuario_local = getUser($_SESSION['nickUsuario']);

    // Realizar comprobaciones y llamar a funciones para modificar los datos del usuario
    if(!empty($email) && $email != $usuario_local['email']) {
        modificarEmailUsuario($_SESSION['nickUsuario'], $email);
    }

    if(!empty($name) && $name != $usuario_local['name']) {
        modificarNombreUsuario($_SESSION['nickUsuario'], $name);
    }

    if(!empty($password)) {
        // Encriptamos la contraseña para comprobaciones
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Si ha cambiado la contraseña, entonces actualizamos la BD
        if(!password_verify($hashed_password, $usuario_local['password'])) {
            modificarPasswordUsuario($_SESSION['nickUsuario'], $hashed_password);
        }
    }

    // Obtenemos los datos del usuario tras ser modificados
    $variablesParaTwig['user'] = getUser($_SESSION['nickUsuario']);
    // Volvemos a cargar la página con los cambios guardados
    header("Location: editar_perfil.php");

    desconectarBD();
    exit();
  }

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  echo $twig->render('editar_perfil.html', $variablesParaTwig);
?>