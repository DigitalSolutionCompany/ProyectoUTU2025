class api {
    constructor(url) {
        this.baseUrl = url;
    }

    insertarUser(nombre_usuario, partidas_ganadas, contrasena) {
        return fetch(this.baseUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nombre_usuario: nombre_usuario,
                partidas_ganadas: partidas_ganadas,
                contrasena: contrasena
            }),
        })
        .then((res) => {
            if (!res.ok) {
                throw new Error(`Error del servidor: ${res.status}`);
            }
            return res.json();
        })
        .then((data) => {
            if (data.success) {
                return { success: true, message: "Usuario registrado con éxito." };
            } else {
                return { success: false, message: data.message || "Error desconocido." };
            }
        })
        .catch((err) => {
            return { success: false, message: `Error en la solicitud: ${err.message}` };
        });
    }
}

const Api = new api("http://localhost/ProyectoUTU2025/API/");

document.getElementById("botonRegistrar").addEventListener("click", function() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!username || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    Api.insertarUser(username, 5, password)
        .then((result) => {
            if (result.success) {
                document.getElementById("resultado").innerHTML = result.message;
            } else {
                document.getElementById("resultado").innerHTML = "Error: " + result.message;
            }
        });
});