// Obtener referencia a los elementos del DOM
const searchInput = document.getElementById('searchInput');
const resultsContainer = document.getElementById('resultsContainer');
const resultsList = document.getElementById('resultsList');

// Agregar event listeners al campo de entrada
searchInput.addEventListener('input', handleInput);
searchInput.addEventListener('blur', handleBlur);

// Función para manejar el evento de entrada en el campo de búsqueda
function handleInput() {
  const query = searchInput.value.trim();

  if (query !== '') {
    search(query)
      .then(results => displayResults(results))
      .catch(error => console.error('Error en la búsqueda:', error));
  } else {
    clearResults();
  }
}

// Función para manejar el evento de pérdida de enfoque del campo de búsqueda
function handleBlur() {
  // Retrasar la ocultación de los resultados para permitir hacer clic en ellos
  setTimeout(() => {
    clearResults();
  }, 200);
}

// Resto del código...


// Función de búsqueda utilizando AJAX
function search(query) {
  return fetch('busqueda.php?query=' + query)
    .then(response => response.json())
    .then(data => data);
}

// Función para mostrar los resultados de búsqueda en la página
function displayResults(results) {
    clearResults();
  
    if (results.length === 0) {
      // No se encontraron sugerencias
      return;
    }
  
    const list = document.createElement('ul');
    results.forEach(result => {
      const listItem = document.createElement('li');
      const link = document.createElement('a');
      link.href = `cientifico.php?cf=${result.id}`;
      link.textContent = result.nombre;
      listItem.appendChild(link);
      list.appendChild(listItem);
    });
  
    resultsList.appendChild(list);
  }
  

// Función para limpiar los resultados de búsqueda en la página
function clearResults() {
  resultsList.innerHTML = '';
}

// Llamar a la función de búsqueda cuando se cambie el valor del campo de entrada
searchInput.oninput = function() {
  const query = searchInput.value.trim();
  if (query !== '') {
    search(query)
      .then(results => displayResults(results))
      .catch(error => console.error('Error en la búsqueda:', error));
  } else {
    clearResults();
  }
};

