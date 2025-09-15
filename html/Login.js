class api {
    constructor(url) {
        this.baseUrl = url;
    }
    insertarUser(nombre_usuario, partidas_ganadas, contrasena){
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
    
    setTimeout(() => {
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        window.location.href = 'principal.html';
    }, 1500);


    Api.insertarUser(username, 5, password)

    .then ((res) => {
        if (!res.ok ) {
            throw new Error(`Error del server: ${res.status}`);
        }
        return res.json();
    })

    
    .then(data => {
        if (data.success) {
            document.getElementById("resultado").innerHTML = "Usuario registrado con éxito.";
        } else {
            document.getElementById("resultado").innerHTML = "Error: " + data.message;
        }
    })
    .catch(err => {
        console.error("Error en la solicitud:", err);
    });
});
