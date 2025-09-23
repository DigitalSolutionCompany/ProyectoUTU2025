// Leer parámetros de la URL
const params = new URLSearchParams(window.location.search);
const total = parseInt(params.get("total"));
const current = parseInt(params.get("current"));

// Mostrar en qué jugador estamos
document.getElementById("titulo").textContent = `Login jugador ${current} de ${total}`;

// API
class API {
    constructor(url) {
        this.baseUrl = url;
    }

    validarUser(nombre_usuario, contrasena) {
        return fetch(this.baseUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre_usuario, contrasena }),
        });
    }
}
const Api = new API("http://localhost/ProyectoUTU2025/API/puertaLogin.php");

// Evento al hacer clic en el botón
document.getElementById("btn-iniciar").addEventListener("click", async function() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("contraseña").value.trim();

    if (!username || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    try {
        const response = await Api.validarUser(username, password);
        const result = await response.json();

        if (result.success) {
            // Guardar jugador logueado en sessionStorage
             // Guardar jugador en sessionStorage
    const jugadores = JSON.parse(sessionStorage.getItem("jugadores")) || [];
    jugadores.push({
        nombre: result.usuario.nombre,
        partidas_ganadas: result.usuario.partidas_ganadas
    });
    sessionStorage.setItem("jugadores", JSON.stringify(jugadores));

            if (current < total) {
                // pasa al siguiente jugador
                window.location.href = `login.html?total=${total}&current=${current + 1}`;
            } else {
                // último jugador, volver a principal
                window.location.href = "principal.html";
            }
        } else {
            alert(result.message); // Usuario o contraseña incorrectos
        }
    } catch (error) {
        console.error("Error en la petición:", error);
        alert("Hubo un problema con la conexión al servidor.");
    }
});
