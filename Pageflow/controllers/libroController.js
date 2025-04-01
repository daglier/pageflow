(function () {
    // Función para formatear la fecha en formato 'DD/MM/YYYY'
    const formatearFecha = fecha => {
        const newFecha = new Date(fecha + "T00:00:00");

        const dia = String(newFecha.getDate()).padStart(2, '0');
        const mes = String(newFecha.getMonth() + 1).padStart(2, '0');
        const ano = newFecha.getFullYear();
        return `${dia}/${mes}/${ano}`;
    }

    // Función para convertir la fecha de 'DD/MM/YYYY' a 'YYYY-MM-DD'
    const formatearFechaAFormatoDB = fecha => {
        const [dia, mes, ano] = fecha.split('/');
        return `${ano}-${mes}-${dia}`;
    }

    // Función para calcular la fecha estimada de devolución (1 semana a partir de hoy)
    const calcularFechaDevolucion = () => {
        const hoy = new Date();
        hoy.setDate(hoy.getDate() + 7); // Añade 7 días
        return formatearFecha(hoy.toISOString().split('T')[0]); // Usar el formato ISO de 'YYYY-MM-DD'
    };

    // Función para mostrar una confirmación antes de pedir el libro
    const confirmarPrestamo = (libroNombre, libroFechaPublicacion, fechaDevolucionEstimada) => {
        const mensaje = `
        ¿Deseas pedir el libro?
        - ${libroNombre} (${libroFechaPublicacion})
        - Fecha estimada de devolución: ${fechaDevolucionEstimada} (1 semana)
        `;
        return confirm(mensaje);
    };

    // Función para mostrar una confirmación antes de devolver el libro
    const confirmarDevolucion = (libroNombre, libroFechaPublicacion) => {
        const mensaje = `
    ¿Estás seguro de que deseas devolver el libro?
    ${libroNombre} (${libroFechaPublicacion})
    `;
        return confirm(mensaje);
    };

    // Función para actualizar el botón entre "Pedir" y "Devolver"
    const actualizarBotonPrestamoDevolucion = (libroId, estaPrestado) => {
        // Selecciona todas las instancias del botón con el data-id dado
        const botones = document.querySelectorAll(`button[data-id="${libroId}"]`);

        // Itera sobre cada botón encontrado y actualízalo
        botones.forEach(btn => {
            if (estaPrestado) {
                btn.classList.replace("btn-prestamo", "btn-devolver");
                btn.classList.replace("btn-prestamo-libro", "btn-devolver-libro");
                btn.innerHTML = `<i class="fa-solid fa-undo"></i>`;
            } else {
                btn.classList.replace("btn-devolver", "btn-prestamo");
                btn.classList.replace("btn-devolver-libro", "btn-prestamo-libro");
                btn.innerHTML = `<i class="fa-solid fa-book"></i>`;
            }
        });

        $('#table-libros').DataTable().responsive.recalc();
    };

    // Función para pedir un libro (préstamo)
    const pedirLibro = (libroId, libroNombre, libroFechaPublicacion, fechaDevolucionEstimada) => {
        if (!confirmarPrestamo(libroNombre, libroFechaPublicacion, fechaDevolucionEstimada)) {
            alert("Solicitud de préstamo cancelada.");
            return;
        }

        const formData = new FormData();
        formData.append("id_libro", libroId);
        formData.append("fecha_devolucion_estimada", formatearFechaAFormatoDB(fechaDevolucionEstimada));

        fetch('../../controllers/prestamoController.php?action=crearPrestamo', {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Libro solicitado con éxito.");
                    actualizarBotonPrestamoDevolucion(libroId, true);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error al procesar la solicitud de préstamo:", error);
            });
    };

    // Función para devolver el libro
    const devolverLibro = (libroId, libroNombre, libroFechaPublicacion) => {
        if (!confirmarDevolucion(libroNombre, libroFechaPublicacion)) {
            alert("Devolución cancelada.");
            return;
        }

        const formData = new FormData();
        formData.append("id_libro", libroId);

        fetch('../../controllers/prestamoController.php?action=devolverLibro', {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Libro devuelto con éxito.");
                    actualizarBotonPrestamoDevolucion(libroId, false);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error al procesar la solicitud de devolución:", error);
            });
    };

    // Función para verificar si el usuario ya tiene el libro
    const verificarPrestamoExistente = (id_libro) => {
        return fetch(`../../controllers/prestamoController.php?action=verificarPrestamoExistente&id_libro=${id_libro}`)
            .then(response => response.json())
            .then(data => data.existe);
    };

    // Maneja el evento de envío del formulario para añadir o modificar un libro
    formAnadirModificarLibro.addEventListener('submit', event => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const id = formData.get("id");
        const url = id ? '../../controllers/libroController.php?action=actualizarLibro' : '../../controllers/libroController.php?action=crearLibro';

        fetch(url, {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (id) {
                        const row = document.querySelector(`#table-libros button[data-id='${id}']`).closest("tr");

                        datatableLibros.row(row).data([
                            id,
                            formData.get("titulo"),
                            formData.get("autor"),
                            formData.get("genero"),
                            formData.get("ano-publicacion"),
                            formData.get("estado"),
                            formData.get("sinopsis"),
                            formatearFecha(formData.get("fecha-registro")),
                            `<button class="btn-modificar btn-modificar-libro" data-id="${id}"><i class="fa-solid fa-edit"></i></button>`,
                            `<button class="btn-eliminar btn-eliminar-libro" data-id="${id}"><i class="fa-solid fa-trash"></i></button>`
                        ]).draw();
                    } else {
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${data.libro.id}</td>
                            <td>${data.libro.titulo}</td>
                            <td>${data.libro.autor}</td>
                            <td>${data.libro.genero}</td>
                            <td>${data.libro.ano_publicacion}</td>
                            <td>${data.libro.estado}</td>
                            <td>${data.libro.sinopsis}</td>
                            <td>${formatearFecha(data.libro.fecha_registro)}</td>
                            <td><button class="btn-modificar btn-modificar-libro" data-id="${data.libro.id}"><i class="fa-solid fa-edit"></i></button></td>
                            <td><button class="btn-eliminar btn-eliminar-libro" data-id="${data.libro.id}"><i class="fa-solid fa-trash"></i></button></td>
                        `;
                        tbodyLibros.appendChild(newRow);
                        datatableLibros.row.add(newRow).draw();
                    }
                    formAnadirModificarLibro.reset();
                    hideModal(modalLibros);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error al procesar la solicitud:", error);
            });
    });

    // Maneja el evento de clic para pedir un libro (préstamo)
    document.addEventListener("click", event => {
        if (event.target.closest(".btn-prestamo-libro")) {
            const btnPrestamo = event.target.closest(".btn-prestamo-libro");
            const libroId = btnPrestamo.getAttribute("data-id");
            const libroNombre = btnPrestamo.getAttribute("data-nombre");
            const libroFechaPublicacion = btnPrestamo.getAttribute("data-ano-publicacion");
            const fechaDevolucionEstimada = calcularFechaDevolucion();

            pedirLibro(libroId, libroNombre, libroFechaPublicacion, fechaDevolucionEstimada);
        }
    });

    // Maneja el evento de clic para devolver un libro
    document.addEventListener("click", event => {
        if (event.target.closest(".btn-devolver-libro")) {
            const btnDevolver = event.target.closest(".btn-devolver-libro");
            const libroId = btnDevolver.getAttribute("data-id");
            const libroNombre = btnDevolver.getAttribute("data-nombre");
            const libroFechaPublicacion = btnDevolver.getAttribute("data-ano-publicacion");

            devolverLibro(libroId, libroNombre, libroFechaPublicacion);
        }
    });

    // Maneja el evento de clic para modificar un libro
    document.addEventListener("click", event => {
        if (event.target.closest(".btn-modificar-libro")) {
            const btnModificar = event.target.closest(".btn-modificar-libro");
            const row = $(btnModificar).closest("tr");
            const rowData = $('#table-libros').DataTable().row(row).data();

            // Cargar datos en el formulario desde DataTables
            libroId.value = rowData[0];
            libroTitulo.value = rowData[1];
            libroAutor.value = rowData[2];
            libroGenero.value = rowData[3];
            libroAnoPublicacion.value = rowData[4];
            libroEstado.value = rowData[5];
            libroSinopsis.value = rowData[6];

            // Convertir la fecha del formato 'd/m/yyyy' a 'yyyy-mm-dd'
            const fecha = rowData[7];
            const [dia, mes, ano] = fecha.split('/');
            libroFechaRegistro.value = `${ano}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`;

            // Actualizar título y botón del modal
            modalLibrosContentTitle.innerHTML = '<i class="fa-solid fa-edit"></i> Modificar libro';
            modalLibrosBtn.innerHTML = '<i class="fa-solid fa-save"></i> Guardar';

            showModal(modalLibros);
        }
    });

    // Maneja el evento de clic para eliminar un libro
    document.addEventListener("click", event => {
        if (event.target.closest(".btn-eliminar-libro")) {
            const btnEliminar = event.target.closest(".btn-eliminar-libro");
            const row = $(btnEliminar).closest("tr");
            const rowData = $('#table-libros').DataTable().row(row).data();

            const idLibro = rowData[0];

            if (confirm("¿Está seguro de que desea eliminar este libro?")) {
                const formData = new FormData();
                formData.append("id_libro", idLibro);

                fetch('../../controllers/libroController.php?action=eliminarLibro', {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Eliminar la fila usando la API de DataTables y redibujar la tabla
                            $('#table-libros').DataTable().row(row).remove().draw();
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

    // Función para cargar los libros en la interfaz
    fetch('../../controllers/libroController.php?action=obtenerLibros')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.libros.length > 0) {
                    data.libros.forEach(libro => {
                        verificarPrestamoExistente(libro.id).then(existe => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${libro.id}</td>
                        <td>${libro.titulo}</td>
                        <td>${libro.autor}</td>
                        <td>${libro.genero}</td>
                        <td>${libro.ano_publicacion}</td>
                        <td>${libro.estado}</td>
                        <td>${libro.sinopsis}</td>
                        <td>${new Date(libro.fecha_registro).toLocaleDateString()}</td>
                        ${usuarioActual.rol === 'lector' ? `
                            <td>
                                <!-- Verificar si el usuario ya tiene el libro prestado -->
                                ${existe ? `
                                    <button class="btn-devolver btn-devolver-libro" 
                                        data-id="${libro.id}" 
                                        data-nombre="${libro.titulo}" 
                                        data-ano-publicacion="${libro.ano_publicacion}">
                                        <i class="fa-solid fa-undo"></i>
                                    </button>
                                ` : `
                                    <button class="btn-prestamo btn-prestamo-libro" 
                                        data-id="${libro.id}" 
                                        data-nombre="${libro.titulo}" 
                                        data-ano-publicacion="${libro.ano_publicacion}">
                                        <i class="fa-solid fa-book"></i>
                                    </button>
                                `}
                            </td>
                        ` : ''}
                        ${usuarioActual.rol === 'administrador' ? `
                            <td>
                                <button class="btn-modificar btn-modificar-libro" data-id="${libro.id}">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                            </td>
                            <td>
                                <button class="btn-eliminar btn-eliminar-libro" data-id="${libro.id}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        ` : ''}
                    `;
                            tbodyLibros.appendChild(row);
                            datatableLibros.row.add(row).draw();
                        });
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="9">No hay libros registrados.</td>`;
                    tbodyLibros.appendChild(row);
                }
            } else {
                console.error('Error al cargar los libros:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al realizar la solicitud para cargar los libros:', error);
        });

    // Muestra el modal para añadir un nuevo año cuando se hace clic en el botón correspondiente
    abrirModalLibrosBtn.addEventListener('click', () => {
        formAnadirModificarLibro.reset();
        modalLibrosContentTitle.innerHTML = '<i class="fa-solid fa-plus"></i> Añadir libro';
        modalLibrosBtn.innerHTML = '<i class="fa-solid fa-plus"></i> Añadir';
        libroId.value = '';
        showModal(modalLibros);
    });

    // Cierra el modal cuando se hace clic fuera del área del modal
    window.addEventListener('click', event => {
        if (event.target === modalLibros) hideModal(modalLibros);
    });

    // Cierra el modal cuando se hace clic en el botón de cerrar del modal
    cerrarModalLibrosBtn.addEventListener('click', () => hideModal(modalLibros));
})();
