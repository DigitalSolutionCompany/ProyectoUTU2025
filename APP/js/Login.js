// Se define una clase llamada ApiFauna para interactuar con la API de fauna
class ApiJuego {
    // El constructor recibe la URL base de la API y la guarda en la instancia
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    // Método para obtener todos los animales de la API
    getAll() {
        // Realiza una petición fetch a la URL base
        return fetch(this.baseUrl)
            // Cuando recibe la respuesta, la convierte a JSON
            .then(response => response.json());
    }

    // Método para obtener un animal por su ID
    getById(id) {
        // Realiza una petición fetch a la URL base agregando el parámetro id
        return fetch(`${this.baseUrl}?id=${id}`)
            // Convierte la respuesta a JSON
            .then(response => response.json());
    }
}

// Se crea una instancia de ApiFauna con la URL de la API local
const api = new ApiJuego("http://localhost/ejemplo-basico-api/api/");

// Se obtiene el elemento del DOM donde se mostrarán los resultados
const resultado = document.getElementById("resultado");

// Se agrega un listener al botón con id "btnTodos" para mostrar todos los animales
document.getElementById("btnTodos").addEventListener("click", () => {
    // Llama al método getAll de la API y espera la respuesta
    api.getAll().then(data => {
        // Muestra los datos recibidos en formato JSON dentro del elemento resultado
        resultado.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
    });
});

// Se agrega un listener al botón con id "btnPorId" para buscar un animal por su ID
document.getElementById("btnPorId").addEventListener("click", () => {
    // Solicita al usuario que ingrese el ID del animal mediante un prompt
    const id = prompt("Ingrese el ID del animal:");
    // Si el usuario ingresó un valor
    if (id) {
        // Llama al método getById de la API con el ID proporcionado
        api.getById(id).then(data => {
            // Si la respuesta es un array, toma el primer elemento
            const animal = Array.isArray(data) ? data[0] : data;
            // Si se encontró un animal válido (tiene id_animal)
            if (animal && animal.id_animal) {
                // Muestra la información del animal en formato HTML estilizado
                resultado.innerHTML = `
                    <div style="border:1px solid #ccc; border-radius:8px; padding:16px; max-width:350px; box-shadow:0 2px 8px #eee;">
                        <h2 style="margin-top:0;">${animal.nombre_comun}</h2>
                        <p><strong>Nombre científico:</strong> ${animal.nombre_cientifico}</p>
                        <p><strong>Tipo:</strong> ${animal.tipo}</p>
                        <p><strong>Hábitat:</strong> ${animal.habitat}</p>
                        <small>ID: ${animal.id_animal}</small>
                    </div>
                `;
            } else {
                // Si no se encontró el animal, muestra un mensaje de error
                resultado.innerHTML = `<p>No se encontró el animal con ID ${id}.</p>`;
            }
        });
    }
});