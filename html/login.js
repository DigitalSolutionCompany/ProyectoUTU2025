// Leer parámetros de la URL
const params = new URLSearchParams(window.location.search);
const total = parseInt(params.get("total"));
const UserActual = parseInt(params.get("UserActual"));

// Mostrar en qué jugador estamos
document.getElementById("titulo").textContent = `Login jugador ${UserActual} de ${total}`;

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
document.getElementById("btn-loguear").addEventListener("click", async function() {
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
            // Obtener la lista de jugadores logueados desde sessionStorage
            const jugadores = JSON.parse(sessionStorage.getItem("jugadores")) || [];

            // Verificar si el jugador ya está en la lista
            const jugadorExiste = jugadores.some(jugador => jugador.nombre === result.usuario.nombre);
            if(jugadorExiste){
                alert("El usuario ya ha sido logueado anteriormente.");
                return;
            }


            if (!jugadorExiste) {
                // Agregar el jugador al array si no existe
                jugadores.push({
                    nombre: result.usuario.nombre,
                    partidas_ganadas: result.usuario.partidas_ganadas
                });
                sessionStorage.setItem("jugadores", JSON.stringify(jugadores));
            }

            if (UserActual < total) {
                // Pasar al siguiente jugador
                window.location.href = `login.html?total=${total}&UserActual=${UserActual + 1}`;
            }
        } else {
            alert(result.message); // Usuario o contraseña incorrectos
        }
    } catch (error) {
        console.error("Error en la petición:", error);
        alert("Hubo un problema con la conexión al servidor.");
    }
});

// Evento al hacer clic en el botón "Jugar"
document.getElementById("btn-jugar").addEventListener("click", async function() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("contraseña").value.trim();

    if (!username || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    
    // Obtener la lista de jugadores logueados desde sessionStorage
    const jugadores = JSON.parse(sessionStorage.getItem("jugadores")) || [];
     
    // Verificar si el número de jugadores logueados coincide con el total esperado
    if (jugadores.length === total) {
        // Redirigir a partida.html si todos los jugadores están logueados
        window.location.href = "partida.html";
    } else {
        // Mostrar un mensaje indicando que faltan jugadores por loguear
        alert(`Faltan jugadores por loguear. Jugadores logueados: ${jugadores.length}/${total}`);
    }
});
