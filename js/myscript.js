// Lista de palabras censuradas, recibidas por AJAX
var censored = [];

$(document).ready(function() {

  $.ajax({
    url: "../palabras.php",
    method: "GET",
    dataType: "json",
    success: function(palabras) {
      console.log(palabras);
      censored = palabras; // asignamos las palabras recibidas a la variable global censored
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log("Error: " + textStatus + " " + errorThrown);
    }
  });

});

// Abrir la barra de comentarios
function openComments() {
    var mediaqueryList = window.matchMedia("(min-width: 900px)");

    if(mediaqueryList.matches) {
        document.getElementById('sidebar-comments-id').style.width = "45%";
    } else {
        document.getElementById('sidebar-comments-id').style.width = "100%";
    }
}

// Cerrar la barra de comentarios
function closeComments() {
    document.getElementById('sidebar-comments-id').style.width = "0";
}

// Comprueba si el valor c es letra
function isChar(c) {
    var expr = /[a-zA-Z]/;
    if(expr.test(c)) return true;
    else return false;
}

// Devuelve un String de tamaño i de asteriscos
function asterisk(i) {
    var word = "";
    for (var j=0; j<i; j++) word += "*";
    return word;
}

// Censura las palabras restringidas
function censor(event) {
    var textArea = document.getElementById("form-comment-cnt");
    var cadena = textArea.value;    // tipo string, mas sencillo de trabajar
    console.log(cadena);            // para debug
    for(var i=0; i<censored.length; i++) {
        // Obtiene el índice de la subcadena
        var idx = cadena.indexOf(censored[i]);
        // Si la cadena existe
        if(idx != -1) {
            // Comprueba si no está contenida en otra palabra
            if( idx==0 && !isChar(cadena[censored[i].length]) ) { // Para el caso de que sea la palabra inicial
                console.log("Es independiente ¡y es la primera! " + censored[i]);
                cadena = asterisk(censored[i].length) + cadena.substring(idx+censored[i].length);
            } else if (!isChar(cadena[idx-1]) && !isChar(cadena[idx+censored[i].length])) {
                console.log("Es independiente " + censored[i]);
                cadena = cadena.substring(0, idx) + asterisk(censored[i].length) + cadena.substring(idx+censored[i].length);
            }
        }
    }
    console.log(cadena);
    textArea.value = cadena;    // Cambia el valor del elemento
}

/*
// [P2]
// Añadir comentario (comprobar campos)
// No funciona almacenarlos en la BD
function addComment(event) {
    event.preventDefault();
    var nm = document.getElementById("form-name");
    var ml = document.getElementById("form-mail");
    var cm = document.getElementById("form-comment-cnt");

    if (nm.value.length==0 || ml.value.length==0 || cm.value.length==0) {
        alert("Error: Campos Vacíos");
        return false;
    }

    // Define our regular expression (for mail).
    var validEmail =  /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    if( !validEmail.test(ml.value) ) {
        alert("Error: Email Inválido");
        return false;
    }
    
    // Añadir comentario
    var lista = document.getElementsByClassName("post-comment");
    lista[0].insertAdjacentHTML("beforeend", "\n" +
    "<div class=\"list\">\n" +
    "    <div class=\"user-meta\">\n" +
    "        <div class=\"name\">" + nm.value + "</div>\n" +
    "        <div class=\"mail\">" + ml.value + "</div>\n" +
    "        <div class=\"day\">Hace 1 minuto</div>\n" +
    "    </div>\n" +
    "    <div class=\"comment-cnt\">" + cm.value + "</div>\n" + 
    "</div>\n"
    );
}
*/

console.log("Cargadas correctamente las funciones de myscript.js");
