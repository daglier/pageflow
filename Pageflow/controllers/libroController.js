(function () {
    // Función para formatear la fecha en formato 'DD/MM/YYYY'
    const formatearFecha = fecha => {
        const newFecha = new Date(fecha + "T00:00:00");

        // Verifica si la fecha es válida
        const dia = String(newFecha.getDate()).padStart(2, '0');
        const mes = String(newFecha.getMonth() + 1).padStart(2, '0');
        const ano = newFecha.getFullYear();
        return `${dia}/${mes}/${ano}`;
    }

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
                        <td>${formatearFecha(formData.get("fecha-registro"))}</td>
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

    // Maneja el evento de clic para modificar un libro
    document.addEventListener("click", event => {
        if (event.target.closest(".btn-modificar-libro")) {
            const btnModificar = event.target.closest(".btn-modificar-libro");
            const row = btnModificar.closest("tr");

            // Cargar datos en el formulario
            libroId.value = row.cells[0].textContent;
            libroTitulo.value = row.cells[1].textContent;
            libroAutor.value = row.cells[2].textContent;
            libroGenero.value = row.cells[3].textContent;
            libroAnoPublicacion.value = row.cells[4].textContent;
            libroEstado.value = row.cells[5].textContent;
            libroSinopsis.value = row.cells[6].textContent;

            // Convertir la fecha del formato 'd/m/yyyy' a 'yyyy-mm-dd'
            const fecha = row.cells[7].textContent;
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
            const row = btnEliminar.closest("tr");
            const idAno = row.cells[0].textContent;

            if (confirm("¿Está seguro de que desea eliminar este libro?")) {
                const formData = new FormData();
                formData.append("id_libro", idAno);

                fetch('../../controllers/libroController.php?action=eliminarLibro', {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Eliminar fila de la tabla
                            datatableLibros.row(row).remove().draw();
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

    //Funcion que cargar los libros en la interfaz
    fetch('../../controllers/libroController.php?action=obtenerLibros')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.libros.length > 0) {
                    data.libros.forEach(libro => {
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
                            `;
                        tbodyLibros.appendChild(row);
                        datatableLibros.row.add(row).draw();
                    });
                } else {
                    // Si no hay libros, mostrar mensaje
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td colspan="4">No hay libros registrados.</td>
                        `;
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
