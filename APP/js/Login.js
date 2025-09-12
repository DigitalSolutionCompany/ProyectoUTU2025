document.getElementById("registroForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!username || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'insertUser',
            nombre_usuario: username,
            contraseña: password
        })
    })
    .then(res => res.json())
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
