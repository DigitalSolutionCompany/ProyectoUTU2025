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

// Evento al hacer clic en el botón LOGIN
document.getElementById("btn-loguear").addEventListener("click", async function () {
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
            // Guardar el id del usuario logueado
            sessionStorage.setItem("id_usuario", result.usuario.id_usuario);
            sessionStorage.setItem("nombre_usuario", result.usuario.nombre);

            // Obtener lista de jugadores logueados
            const jugadores = JSON.parse(sessionStorage.getItem("jugadores")) || [];

            // Verificar si ya está logueado
            const jugadorExiste = jugadores.some(j => j.nombre === result.usuario.nombre);
            if (jugadorExiste) {
                alert("El usuario ya ha sido logueado anteriormente.");
                return;
            }

            // Agregar jugador
            jugadores.push({
                nombre: result.usuario.nombre,
                partidas_ganadas: result.usuario.partidas_ganadas,
                recintos: {
                    semejanza: [],
                    trios: [],
                    rey: [],
                    diferencia: [],
                    bosqueParejas: [],
                    solitario: []
                }
            });
            sessionStorage.setItem("jugadores", JSON.stringify(jugadores));

            if (UserActual < total) {
                // Ir al siguiente login si hay más jugadores
                window.location.href = `login.html?total=${total}&UserActual=${UserActual + 1}`;
            }
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error en la petición:", error);
        alert("Hubo un problema con la conexión al servidor.");
    }
});

// Evento al hacer clic en el botón JUGAR
document.getElementById("btn-jugar").addEventListener("click", async function () {
    const id_usuario = sessionStorage.getItem("id_usuario");

    if (!id_usuario) {
        alert("Debes iniciar sesión antes de jugar.");
        return;
    }

    try {
        const response = await fetch("http://localhost/ProyectoUTU2025/API/crearPartidaYTablero.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_usuario })
        });

        const result = await response.json();

        if (result.success) {
            sessionStorage.setItem("id_partida", result.id_partida);
            sessionStorage.setItem("id_tablero", result.id_tablero);
            window.location.href = "partida.html";
        } else {
            alert("Error al registrar la partida: " + (result.message || "Desconocido"));
        }

    } catch (error) {
        console.error("Error al conectar con el servidor:", error);
        alert("No se pudo conectar con el servidor.");
    }
});
