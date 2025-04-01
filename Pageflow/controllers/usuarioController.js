(function () {
    const loginForm = document.getElementById('login-form');
    const registrationForm = document.getElementById('registration-form');
    const passwordRecoveryForm = document.getElementById('recovery-form');
    const configurationForm = document.getElementById('configuration-form');


    // Función para mejorar el estado del préstamo con emojis y texto descriptivo
    const transformarEstadoPrestamo = estado => {
        switch (estado) {
            case 'en_prestamo':
                return 'En préstamo 📚';
            case 'devuelto':
                return 'Devuelto ✅';
            case 'retrasado':
                return 'Retrasado ⏰';
            default:
                return estado;
        }
    }

    // Función para obtener el título del libro por su ID
    const obtenerTituloLibroPorId = (id_libro) => {
        return fetch(`../../controllers/libroController.php?action=obtenerTituloLibroPorId&id_libro=${id_libro}`)
            .then(response => response.json())
            .then(data => data.titulo_libro);
    };

    // Función para manejar el envío del formulario de inicio de sesión
    if (loginForm) {
        loginForm.addEventListener('submit', event => {
            event.preventDefault();
            const formData = new FormData(event.target);

            fetch('controllers/usuarioController.php?action=login', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'views/dashboard/dashboard.php';
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

            fetch('controllers/usuarioController.php?action=registrar', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = 'views/dashboard/dashboard.php';
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

            fetch('controllers/usuarioController.php?action=recuperarContrasena', {
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

    // Maneja el evento de clic para eliminar un usuario
    document.addEventListener("click", event => {
        if (event.target.closest(".btn-eliminar-usuario")) {
            const btnEliminar = event.target.closest(".btn-eliminar-usuario");
            const row = $(btnEliminar).closest("tr");
            const rowData = $('#table-usuarios').DataTable().row(row).data();

            const idUsuario = rowData[0];

            if (confirm("¿Está seguro de que desea eliminar este usuario?")) {
                const formData = new FormData();
                formData.append("id_usuario", idUsuario);

                fetch('../../controllers/usuarioController.php?action=eliminarUsuario', {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Eliminar la fila usando la API de DataTables y redibujar la tabla
                            $('#table-usuarios').DataTable().row(row).remove().draw();
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error al procesar la solicitud:", error);
                    });
            }
        }
    });

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
                                    <td>
                                        <button class="btn btn-ver-historial" data-id="${usuario.id}" data-nombre_usuario="${usuario.nombre_usuario}">
                                            <i class="fas fa-history"></i> Ver
                                        </button>
                                    </td>
                                    <td>
                                        ${usuario.id !== usuarioActual.id ? `
                                            <button class="btn-eliminar btn-eliminar-usuario" data-id="${usuario.id}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        ` : ''}
                                    </td>
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

    // Eventos para el botón "Ver historial"
    document.addEventListener('click', event => {
        if (event.target.classList.contains('btn-ver-historial')) {
            const idUsuario = event.target.getAttribute('data-id');
            const nombreUsuario = event.target.getAttribute('data-nombre_usuario');

            modalHistorialContentTitle.textContent = `Historial de préstamos - ${nombreUsuario}`;
            messageNoHistorial.classList.add('hide');
            tableHistorial.classList.add('hide');

            fetch(`../../controllers/prestamoController.php?action=obtenerPrestamosPorIdUsuario&id_usuario=${idUsuario}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        tableHistorial.classList.remove('hide');
                        tbodyHistorial.innerHTML = '';

                        // Agrega las nuevas filas a la tabla
                        if (data.prestamos.length > 0) {
                            const promesas = data.prestamos.map(prestamo => {
                                return obtenerTituloLibroPorId(prestamo.id_libro)
                                    .then(tituloLibro => {
                                        return {
                                            ...prestamo,
                                            tituloLibro
                                        };
                                    })
                                    .catch(error => {
                                        console.error('Error al obtener el título del libro:', error);
                                        return prestamo;
                                    });
                            });

                            Promise.all(promesas)
                                .then(prestamosCompletos => {
                                    prestamosCompletos.forEach(prestamo => {
                                        const estadoTransformado = transformarEstadoPrestamo(prestamo.estado);

                                        const row = document.createElement('tr');
                                        row.innerHTML = `
                                <td>${prestamo.id_prestamo}</td>
                                <td>${prestamo.tituloLibro}</td>
                                <td>${prestamo.fecha_prestamo}</td>
                                <td>${prestamo.fecha_devolucion_estimada}</td>
                                <td>${prestamo.fecha_devolucion ? prestamo.fecha_devolucion : 'No registrada'}</td>
                                <td>${estadoTransformado}</td>
                            `;
                                        tbodyHistorial.appendChild(row);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error al procesar los préstamos completos:', error);
                                });
                        }
                    } else {
                        messageNoHistorial.classList.remove('hide');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar el historial:', error);
                });

            // Muestra el modal
            showModal(modalHistorial);
        }
    });

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

    if (restauracionBtn && restauracionRespaldoSelect) {
        restauracionBtn.addEventListener("click", () => {
            const respaldoSeleccionado = restauracionRespaldoSelect.value;

            if (!respaldoSeleccionado) {
                alert("Seleccione un respaldo antes de restaurar.");
                return;
            }

            if (confirm("¿Está seguro de que desea restaurar la base de datos a la fecha seleccionada?")) {
                const formData = new FormData();
                formData.append("respaldo", respaldoSeleccionado);

                fetch("../../controllers/usuarioController.php?action=restaurarBaseDatos", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Base de datos restaurada correctamente. La página se recargará para reflejar los cambios.");
                            setTimeout(() => {
                                location.reload();
                            }, 500);

                        } else {
                            alert("Error al restaurar la base de datos: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error al procesar la restauración:", error);
                    });
            }
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
                        window.location.href = '../../index.php';
                    } else {
                        alert('Error al cerrar sesión');
                    }
                })
                .catch(error => {
                    console.error('Error al procesar la solicitud de cierre de sesión:', error);
                });
        });
    }

    // Cierra el modal cuando se hace clic fuera del área del modal
    window.addEventListener('click', event => {
        if (event.target === modalHistorial) hideModal(modalHistorial);
    });

    // Cierra el modal cuando se hace clic en el botón de cerrar del modal
    cerrarModalHistorialBtn.addEventListener('click', () => hideModal(modalHistorial));
})();