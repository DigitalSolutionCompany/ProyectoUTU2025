// Clase API agregada
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
    }
}


const Api = new api("http://localhost/ProyectoUTU2025/API/");


document.getElementById('botonRegistrar').addEventListener('click', function() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const resultado = document.getElementById('resultado');

    
    resultado.className = '';

    if (username.trim() === '' || password.trim() === '') {
        resultado.textContent = 'completa todos los campos';
        resultado.className = 'error mostrar';
    } else if (password.length < 6) {
        resultado.textContent = 'La contraseña debe tener al menos 6 caracteres';
        resultado.className = 'error mostrar';
    } else {
    resultado.textContent = '¡Registro exitoso! Bienvenido, ' + username;
    resultado.className = 'exito mostrar';
    
    setTimeout(() => {
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        window.location.href = 'principal.html';
    }, 1500);
}
    
});


document.getElementById('username').addEventListener('input', limpiarResultado);
document.getElementById('password').addEventListener('input', limpiarResultado);

function limpiarResultado() {
    const resultado = document.getElementById('resultado');
    resultado.style.display = 'none';
    resultado.textContent = '';
}