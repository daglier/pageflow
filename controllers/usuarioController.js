(function () {
    const loginForm = document.getElementById('login-form');
    const registrationForm = document.getElementById('registration-form');
    const passwordRecoveryForm = document.getElementById('recovery-form');
    const configurationForm = document.getElementById('configuration-form');


    // Función para manejar el envío del formulario de inicio de sesión
    if (loginForm) {
        loginForm.addEventListener('submit', event => {
            event.preventDefault();
            const formData = new FormData(event.target);

            fetch('../../controllers/usuarioController.php?action=login', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '../dashboard/dashboard.php';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al procesar la solicitud de inicio de sesión:', error);
                });
        });
    }

    // Función para manejar el envío del formulario de registro de usuario
    if (registrationForm) {
        // Lógica de realizar el registro de usuario
        registrationForm.addEventListener('submit', event => {
            event.preventDefault();
            const formData = new FormData(event.target);

            fetch('../../controllers/usuarioController.php?action=registrar', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '../dashboard/dashboard.php';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al procesar la solicitud de registro:', error);
                });
        });
    }

    // Función para manejar la recuperación de contraseña
    if (passwordRecoveryForm) {
        passwordRecoveryForm.addEventListener('submit', event => {
            event.preventDefault();
            const formData = new FormData(event.target);

            fetch('../../controllers/usuarioController.php?action=recuperarContrasena', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        passwordRecoveryForm.reset();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al procesar la solicitud de recuperación de contraseña:', error);
                });
        });
    }

    if (selectOpcion) {
        selectOpcion.addEventListener('change', function () {
            const opcionSeleccionada = this.value;
            contrasenaActualDiv.classList.remove('hide');

            if (opcionSeleccionada === 'nombre-usuario') {
                nombreUsuarioDiv.classList.remove('hide');
                contrasenaDiv.classList.add('hide');
                preguntasSeguridadDiv.classList.add('hide');
                nombreUsuarioNuevoInput.setAttribute('required', '');
                contrasenaNuevaInput.removeAttribute('required');

                urlAccion = "../../controllers/usuarioController.php?action=cambiarNombreUsuario";
            } else if (opcionSeleccionada === 'contrasena') {
                contrasenaDiv.classList.remove('hide');
                nombreUsuarioDiv.classList.add('hide');
                preguntasSeguridadDiv.classList.add('hide');
                contrasenaNuevaInput.setAttribute('required', '');
                nombreUsuarioNuevoInput.removeAttribute('required');

                urlAccion = "../../controllers/usuarioController.php?action=cambiarContrasena";
            } else if (opcionSeleccionada === 'preguntas-seguridad') {
                preguntasSeguridadDiv.classList.remove('hide');
                nombreUsuarioDiv.classList.add('hide');
                contrasenaDiv.classList.add('hide');
                contrasenaNuevaInput.removeAttribute('required');
                nombreUsuarioNuevoInput.removeAttribute('required');

                urlAccion = "../../controllers/usuarioController.php?action=cambiarPreguntasSeguridad";
            }
        });
    }

    // Función para cargar y mostrar la lista de usuarios en una tabla
    if (tbodyUsuarios) {
        fetch('../../controllers/usuarioController.php?action=obtenerUsuarios')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.usuarios.forEach(usuario => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                                    <td>${usuario.id}</td>
                                    <td>${usuario.nombre_usuario}</td>
                                    <td>${usuario.rol}</td>
                                    <td>${usuario.ultimo_inicio_sesion}</td>
                                    <td><button class="btn btn-ver-historial"><i class="fas fa-history"></i> Ver Historial</button>
                                    </td>
                                    <td><button class="btn btn-aplicar-penalizacion"><i class="fas fa-exclamation-triangle"></i>
                                            Aplicar</button></td>
                                `;
                        datatableUsuarios.row.add(row).draw();
                    });
                } else {
                    console.error('Error al cargar los usuarios:', data.message);
                }
            })
            .catch(error => {
                console.error('Error al realizar la solicitud para cargar los usuarios:', error);
            });
    };

    if (configurationForm) {
        // Lógica de realizar la actualización de datos del usuario
        configurationForm.addEventListener("submit", event => {
            event.preventDefault();
            const formData = new FormData(event.target);

            if (!urlAccion) {
                alert("Por favor, seleccione una opción para actualizar.");
                return;
            }

            fetch(urlAccion, {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);

                        if (selectOpcion.value === "nombre-usuario") {
                            const nuevoNombreUsuario = formData.get("nuevo-nombre-usuario");
                            nombreUsuarioText.textContent = nuevoNombreUsuario;
                        }

                        configurationForm.reset();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                });
        });
    }

    // Función para manejar el cierre de sesión
    if (logoutButton) {
        // Lógica para cerrar sesión
        logoutButton.addEventListener('click', event => {
            event.preventDefault();

            fetch('../../controllers/usuarioController.php?action=cerrarSesion', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '../login/index.php';
                    } else {
                        alert('Error al cerrar sesión');
                    }
                })
                .catch(error => {
                    console.error('Error al procesar la solicitud de cierre de sesión:', error);
                });
        });
    }
})();
