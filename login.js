

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
//aca lo que se hizo fue crear una clase que encapsula las llamadas http a la API para validar usuarios
// Luego se instancia un objeto Api que apunte a puertaLogin.php
//y el evento validarUser se encarga de hacer una solicitud post con credenciales en formato JSON


// Evento al hacer clic en el botón LOGIN
document.getElementById("btn-loguear").addEventListener("click", async function () {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("contraseña").value.trim();

    if (!username || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    try {
        const response = await Api.validarUser(username, password);//se llama al metodo validarUser del objeto Api q se creo antes
        const result = await response.json();

        if (result.success) {
            // Guardar el id del usuario logueado
            sessionStorage.setItem("id_usuario", result.usuario.id_usuario);
            sessionStorage.setItem("nombre_usuario", result.usuario.nombre);

            //aca lo q se hizo fue llamar a la API para validar el usuario en primer lugar
            //luego se espero una respuesta json del servidor
            //si la respuesta indica exito se guarda el id y nombre del usuario en sessionStorage






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
                id_usuario: result.usuario.id_usuario,
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
            alert("¡Inicio de sesión exitoso! preciona JUGAR para comenzar.");

            //aca nos fijamos si el usuario ya estaba logueado en la sesion actual
            //si no estaba logueado se agrega a la lista de jugadores en sessionStorage
            //y se muestra un mensaje de exito

           


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
    const id_usuario = sessionStorage.getItem("id_usuario");//obtenemos el id del usuario logueado

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
        //aca se hizo una solicitud post a la API crearPartidaYTablero.php
        //enviando el id del usuario logueado en formato json

        //daoto enviado:
        /*{
            "id_usuario": 5
        }*/

        
    const result = await response.json(); //aca se espera la respuesta json del servidor
        if (result.success) {
            sessionStorage.setItem("id_partida", result.id_partida);
            sessionStorage.setItem("id_tablero", result.id_tablero);
             //si la respuesta indica exito se guardan el id de la partida y del tablero en sessionStorage
            window.location.href = "partida.html";
        } else {
            alert("Error al registrar la partida: " + (result.message || "Desconocido"));
        }

    } catch (error) {
        console.error("Error al conectar con el servidor:", error);
        alert("No se pudo conectar con el servidor.");
    }
});
