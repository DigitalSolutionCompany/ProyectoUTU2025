class api {
    constructor(url) {
        this.baseUrl = url;
    }

    insertarUser(nombre_usuario, partidas_ganadas, contrasena) {
       
        if (typeof fetch === 'function') {
            return fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_usuario: nombre_usuario,
                    partidas_ganadas: partidas_ganadas,
                    contrasena: contrasena
                })
            });
        }

        
        return new Promise((resolve, reject) => {
            try {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', this.baseUrl, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState !== 4) return;
                    const status = xhr.status;
                    const ok = status >= 200 && status < 300;
                    let parsed = null;
                    try {
                        parsed = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                    } catch (e) {
                        
                        return reject(e);
                    }
                    resolve({
                        ok: ok,
                        status: status,
                        json: () => Promise.resolve(parsed)
                    });
                };
                xhr.onerror = function() {
                    reject(new Error('Network error'));
                };
                xhr.send(JSON.stringify({
                    nombre_usuario: nombre_usuario,
                    partidas_ganadas: partidas_ganadas,
                    contrasena: contrasena
                }));
            } catch (err) {
                reject(err);
            }
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
    }, 1500)

    Api.insertarUser(username, 5, password)
        .then((res) => {
            if (!res.ok) {
                throw new Error(`Error del server: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (data && data.success) {
                document.getElementById("resultado").innerHTML = "Usuario registrado con éxito.";
            } else {
                document.getElementById("resultado").innerHTML = "Error: " + (data && data.message ? data.message : 'Respuesta inválida');
            }
        })
        .catch(err => {
            console.error("Error en la solicitud:", err);
            document.getElementById("resultado").innerHTML = "Error en la solicitud: " + err.message;
        });
});
