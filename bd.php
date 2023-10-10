<?php

require_once "config.php";

// Variable global utlizada para la conexion
$conexion;

function conectarBD() {
  // Crear una conexion persistente
  // $conexion = new mysqli("lamp-mysql8", "web", "web", "sibw");
  global $conexion, $db_host, $db_user, $db_password, $db_name;
  $conexion = new mysqli($db_host, $db_user, $db_password, $db_name);
  if (!$conexion) {
    return false;
    // die("Error de conexión: " . mysqli_connect_error());
  } else {
    return true;
    // echo "Conexion correcta con MYSQL";
  }
}

function desconectarBD() {
  global $conexion;

  if($conexion) {
    // Cerrar la conexión al final del script
    // echo "Cerrando la conexion MYSQL";
    mysqli_close($conexion);
  }
}

// A partir de aquí, todas las funciones de consulta
// utilizan la conexion global

function getPortadaCientificos() {
  global $conexion;

//  $resultado = $conexion->query("SELECT * FROM cientifico");
  $resultado = $conexion->query("SELECT c.id AS id, c.nombre AS nombre, c.publicado AS publicado, f.src AS imagen FROM cientifico c, foto f WHERE
  c.id = f.cientifico AND f.src LIKE '%/portada/%';");

  $cientificos = array();

  while($res = $resultado->fetch_assoc()) {
      $cientificos[] = $res;
  }
  return $cientificos;
}

function getGaleriaCientifico($idCf) {
  global $conexion;

  //Impedir inyeccion de codigo
  $stmt = $conexion->prepare("SELECT * FROM foto WHERE cientifico=? AND src LIKE '%/galeria/%'");
  $stmt->bind_param("i", $idCf);
  $stmt->execute();

  $resultado = $stmt->get_result();
  $fotos = array();

  while($res = $resultado->fetch_assoc()) {
    $fotos[] = $res;
  }

  return $fotos;
}

function getComentariosCientifico($idCf) {
  global $conexion;

  //Impedir inyeccion de codigo
  $stmt = $conexion->prepare("SELECT * FROM vista_comentarios WHERE cientifico=?");
  $stmt->bind_param("i", $idCf);
  $stmt->execute();

  $resultado = $stmt->get_result();
  $comentarios = array();

  while($res = $resultado->fetch_assoc()) {
    $comentarios[] = $res;
  }

  return $comentarios;
}

function getComentarios() {
  global $conexion;

  //Impedir inyeccion de codigo
  $stmt = $conexion->prepare("SELECT * FROM vista_comentarios");
  $stmt->execute();

  $resultado = $stmt->get_result();
  $comentarios = array();

  while($res = $resultado->fetch_assoc()) {
    $comentarios[] = $res;
  }

  return $comentarios;
}

function getUsuarios() {
  global $conexion;

  $stmt = $conexion->prepare("SELECT * FROM usuario");
  $stmt->execute();

  $resultado = $stmt->get_result();
  $usuarios = array();

  while($res = $resultado->fetch_assoc()) {
    $usuarios[] = $res;
  }

  return $usuarios;
}

function getComentario($id) {
  global $conexion;

  //Impedir inyeccion de codigo
  $stmt = $conexion->prepare("SELECT * FROM vista_comentarios WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $result = $stmt->get_result();

  // If the user is found, return their information
  if ($result->num_rows > 0) {
    $comentario = $result->fetch_assoc();
    return $comentario;
  } else {
    // If the user is not found, return an error message
    return 0;
  }
}

function modificarComentario($id, $contenido) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos


  $query = "UPDATE comentario SET contenido = '$contenido', modificado = true WHERE id = '$id'";
  // $query = "UPDATE comentario SET contenido = '$contenido' WHERE id = '$id'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
    return true; // si la actualización fue exitosa
  } else {
    return false; // si hubo un error al actualizar
  }
}

function borrarComentario($id) {
  global $conexion;

  // Preparar la sentencia SQL con una variable marcada como ? para evitar inyecciones de código SQL
  $sentencia = $conexion->prepare("DELETE FROM comentario WHERE id = ?");
  // Asociar el valor del ID a la variable marcada como ?
  $sentencia->bind_param("i", $id);
  // Ejecutar la sentencia
  $resultado = $sentencia->execute();
  // Cerrar la sentencia
  $sentencia->close();

  if ($resultado) {
    return true; // si la eliminación fue exitosa
  } else {
    return false; // si hubo un error al eliminar
  }
}

function getCientificos() {
  global $conexion;

  $stmt = $conexion->prepare("SELECT * FROM cientifico");
  $stmt->execute();

  $resultado = $stmt->get_result();
  $cientificos = array();

  while($res = $resultado->fetch_assoc()) {
    $cientificos[] = $res;
  }

  return $cientificos;
}

function getCientifico($idCf) {
  global $conexion;

  //Impedir inyeccion de codigo
  $stmt = $conexion->prepare("SELECT * FROM cientifico WHERE id=?");
  $stmt->bind_param("i", $idCf);
  $stmt->execute();

  $res = $stmt->get_result();
  $cientifico = array();
  
  if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $cientifico = array('id' => $row['id'], 'nombre' => $row['nombre'], 'contenido' => $row['contenido'],  'publicado' => $row['publicado']);
  }
  
  return $cientifico;
}


// [Practica 4]
// Devuelve true si existe un usuario con esa contraseña
// NO ES PERSISTENTE (es la única funcion que no lo es)
function checkLogin($nick, $pass) {
  global $conexion;

  conectarBD();
  
  // Comprobar si existe un usuario en la bd con ese nick
  $stmt = $conexion->prepare("SELECT password FROM usuario WHERE nick = ?");
  $stmt->bind_param("s", $nick);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // El usuario no existe
    desconectarBD();
    return false;
  }

  // Obtener el hash de la contraseña del usuario
  $hash = $result->fetch_assoc()["password"];

  // Comprobar si la contraseña es correcta
  if (password_verify($pass, $hash)) {
    // La contraseña es correcta
    desconectarBD();
    return true;
  } else {
    // La contraseña es incorrecta
    desconectarBD();
    return false;
  }
}

// Devuelve la información de un usuario a partir de su nick
function getUser($nick) {
  global $conexion;

  // Query the database to retrieve the user's information
  $stmt = $conexion->prepare("SELECT * FROM usuario WHERE nick = ?");
  $stmt->bind_param("s", $nick);
  $stmt->execute();
  $result = $stmt->get_result();

  // If the user is found, return their information
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // If the user is not found, return an error message
    return 0;
  }
}

function modificarEmailUsuario($nick, $nuevoEmail) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos

  $query = "UPDATE usuario SET email = '$nuevoEmail' WHERE nick = '$nick'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
      return true; // si la actualización fue exitosa
  } else {
      return false; // si hubo un error al actualizar
  }
}

function modificarNombreUsuario($nick, $nuevoNombre) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos

  $query = "UPDATE usuario SET name = '$nuevoNombre' WHERE nick = '$nick'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
      return true; // si la actualización fue exitosa
  } else {
      return false; // si hubo un error al actualizar
  }
}

function modificarPasswordUsuario($nick, $nuevoPassword) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos

  $query = "UPDATE usuario SET password = '$nuevoPassword' WHERE nick = '$nick'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
      return true; // si la actualización fue exitosa
  } else {
      return false; // si hubo un error al actualizar
  }
}
function modificarRolUsuario($nick, $roll) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos
  
  // Comprobar el rol anterior del usuario (antes de modificarlo)  
  $prev_roll;
  $sql_prev_roll = "SELECT roll FROM usuario WHERE nick = '$nick'";
  $result_prev_roll = mysqli_query($conexion, $sql_prev_roll);
  if ($result_prev_roll && mysqli_num_rows($result_prev_roll) > 0) {
    $row = mysqli_fetch_assoc($result_prev_roll);
    $prev_roll = $row['roll'];
  } else {
    return false; // si hubo un error al obtener el rol anterior del usuario
  }

  // if prev_roll == roll entonces no hacer nada
  if ($prev_roll == $roll) {
    return true; // si no se modificó el rol porque era igual al anterior
  } else if ($prev_roll == 'super') {
    // if count usuarios where roll==super <=1 entonces no permitir el cambio de roll (siempre debe haber al menos un super en el sistema)
    $sql_count_supers = "SELECT COUNT(*) as count_supers FROM usuario WHERE roll = 'super'";
    $result_count_supers = mysqli_query($conexion, $sql_count_supers);
    if ($result_count_supers && mysqli_num_rows($result_count_supers) > 0) {
      $row = mysqli_fetch_assoc($result_count_supers);
      $count_supers = $row['count_supers'];
      if ($count_supers <= 1) {
        return false; // si no se permitió el cambio de rol porque siempre debe haber al menos un super en el sistema
      }
    } else {
      return false; // si hubo un error al contar los usuarios con rol 'super'
    }
  }

  // else entonces modificar el roll
  $sql_update_roll = "UPDATE usuario SET roll = '$roll' WHERE nick = '$nick'";
  $resultado = mysqli_query($conexion, $sql_update_roll);
  if ($resultado) {
    return true; // si la actualización fue exitosa
  } else {
    return false; // si hubo un error al actualizar
  }
}

function getPalabrasCensuradas() {
  global $conexion;

  $stmt = $conexion->prepare("SELECT * FROM censura");
  $stmt->execute();

  $resultado = $stmt->get_result();
  $palabras = array();

  while($res = $resultado->fetch_assoc()) {
    $palabras[] = $res['palabra'];
  }

  return $palabras;
}

function addComentario($cientifico, $usuario, $contenido) {
  global $conexion;

  // Preparamos la consulta SQL para insertar el comentario
  $stmt = $conexion->prepare("INSERT INTO comentario (cientifico, usuario, fecha, contenido) VALUES (?, ?, NOW(), ?)");
  $stmt->bind_param("iss", $cientifico, $usuario, $contenido);
  
  // Ejecutamos la sentencia
  $stmt->execute();

  // Cerramos la conexión y liberamos recursos
  $stmt->close();

}

function addCientifico($nombre, $contenido) {
  global $conexion;

  // Preparamos la consulta SQL para insertar el comentario
  $stmt = $conexion->prepare("INSERT INTO cientifico (nombre, contenido) VALUES (?, ?)");
  $stmt->bind_param("ss", $nombre, $contenido);

  // Ejecutamos la sentencia
  $stmt->execute();

  // Comprobamos si la inserción fue exitosa
  if($stmt->affected_rows > 0) {
    // Cerramos la conexión y liberamos recursos
    $stmt->close();
    return true;
  } else {
    // Cerramos la conexión y liberamos recursos
    $stmt->close();
    return false;
  }
}

function buscarCientificoNombre($nombre) {
  global $conexion;

  // Preparamos la consulta SQL para buscar el científico
  $stmt = $conexion->prepare("SELECT id FROM cientifico WHERE nombre = ?");
  $stmt->bind_param("s", $nombre);

  // Ejecutamos la consulta
  $stmt->execute();
  $resultado = $stmt->get_result();

  // Comprobamos si se encontró algún resultado
  if ($resultado->num_rows == 0) {
    // No se encontró ningún científico con ese nombre
    return -1;
  } else {
    // Se encontró al menos un científico, devolvemos el id del primero
    $row = $resultado->fetch_assoc();
    return $row['id'];
  }
}


function addImagen($cientifico, $src) {
  global $conexion;

  // Preparamos la consulta SQL para insertar el comentario
  $stmt = $conexion->prepare("INSERT INTO foto (cientifico, src) VALUES (?, ?)");
  $stmt->bind_param("is", $cientifico, $src);

  // Ejecutamos la sentencia
  $stmt->execute();

  // Cerramos la conexión y liberamos recursos
  $stmt->close();
}


function borrarCientifico($id) {
  global $conexion;

  if (!is_numeric($id)) {
    return false;
  }  

  // Borrar las fotos de la base de datos
  $sentencia = $conexion->prepare("DELETE FROM foto WHERE cientifico = ?");
  $sentencia->bind_param("i", $id);
  $resultado = $sentencia->execute();
  $sentencia->close();

  // Borrar los comentarios
  $sentencia = $conexion->prepare("DELETE FROM comentario WHERE cientifico = ?");
  $sentencia->bind_param("i", $id);
  $resultado = $sentencia->execute();
  $sentencia->close();

  // Borrar el científico
  $sentencia = $conexion->prepare("DELETE FROM cientifico WHERE id = ?");
  $sentencia->bind_param("i", $id);
  $resultado = $sentencia->execute();
  $sentencia->close();

  if ($resultado) {
    return true;
  } else {
    return false;
  }
}

function modificarNombreCientifico($id, $nombre) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos

  $query = "UPDATE cientifico SET nombre = '$nombre' WHERE id = '$id'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
      return true; // si la actualización fue exitosa
  } else {
      return false; // si hubo un error al actualizar
  }
}

function modificarContenidoCientifico($id, $contenido) {
  global $conexion; // suponiendo que ya tenemos una conexión establecida a la base de datos

  $query = "UPDATE cientifico SET contenido = '$contenido' WHERE id = '$id'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
      return true; // si la actualización fue exitosa
  } else {
      return false; // si hubo un error al actualizar
  }
}

/// Practica 5, enviar resultados de búsqueda

// str es la cadena de busqueda, y publicado es si se desean los resultados
// publicados (true) o los no publicados también (false)
function busquedaCientifico($str, $publicado) {
  global $conexion;

  // Preparamos la consulta SQL para buscar el científico
  $sql = "SELECT id,nombre FROM cientifico WHERE nombre LIKE ?";
  
  // Si el parámetro $publicado es true, añadir la condición 'publicado = true' a la consulta
  if ($publicado) {
    $sql .= " AND publicado = true";
  }

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("s", $nombre);

  // Modificar la variable $nombre con el valor de la cadena de búsqueda
  $nombre = '%' . $str . '%';

  // Ejecutamos la consulta
  $stmt->execute();
  $resultado = $stmt->get_result();

  // Prepara los resultados para enviarlos al cliente en formato JSON
  $suggestions = [];
  if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
      $suggestions[] = [
        'id' => $row['id'],
        'nombre' => $row['nombre']
      ];
    }
  }

  return $suggestions;
}

function modificarPublicadoCientifico($id, $publicado) {
  global $conexion;

  $sql = "UPDATE cientifico SET publicado = ";
  if($publicado) {
    $sql .= "true ";
  } else {
    $sql .= "false ";
  }

  $sql .= " WHERE id = ?";

  // Preparamos la consulta SQL para actualizar el campo 'publicado' del científico
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("i", $id);

  // Ejecutamos la consulta
  $resultado = $stmt->execute();

  if ($resultado) {
    return true; // Si la actualización fue exitosa
  } else {
    return false; // Si hubo un error al actualizar
  }
}

?>
