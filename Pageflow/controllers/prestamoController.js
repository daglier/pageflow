(function () {
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

    // Función para obtener el nombre del usuario por su ID
    const obtenerNombreUsuarioPorId = (id_usuario) => {
        return fetch(`../../controllers/usuarioController.php?action=obtenerNombreUsuarioPorId&id_usuario=${id_usuario}`)
            .then(response => response.json())
            .then(data => data.nombre_usuario);
    };

    // Función para obtener el título del libro por su ID
    const obtenerTituloLibroPorId = (id_libro) => {
        return fetch(`../../controllers/libroController.php?action=obtenerTituloLibroPorId&id_libro=${id_libro}`)
            .then(response => response.json())
            .then(data => data.titulo_libro);
    };

    // Función para cargar los préstamos en la interfaz
    fetch('../../controllers/prestamoController.php?action=obtenerPrestamos')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tbodyPrestamos.innerHTML = '';
                datatablePrestamos.clear().draw();

                if (data.prestamos.length > 0) {
                    const promesas = data.prestamos.map(prestamo => Promise.all([
                        obtenerNombreUsuarioPorId(prestamo.id_usuario),
                        obtenerTituloLibroPorId(prestamo.id_libro)
                    ]).then(([nombreUsuario, tituloLibro]) => ({
                        ...prestamo, nombreUsuario, tituloLibro
                    })));

                    Promise.all(promesas).then(prestamosCompletos => {
                        prestamosCompletos.forEach(prestamo => {
                            const fechaDevolucionEstimada = new Date(prestamo.fecha_devolucion_estimada);
                            const fechaDevolucion = prestamo.fecha_devolucion ? new Date(prestamo.fecha_devolucion) : null;
                            const hoy = new Date();
                            let estadoFinal = transformarEstadoPrestamo(prestamo.estado);

                            // Si el préstamo está "en_prestamo", se calcula el número de días transcurridos
                            if (prestamo.estado === 'en_prestamo') {
                                const diasPrestamo = Math.floor((hoy - new Date(prestamo.fecha_prestamo)) / (1000 * 60 * 60 * 24));
                                estadoFinal += ` (${diasPrestamo} días)`;
                            }
                            // Mantenimiento de la lógica para "retrasado"
                            else if (prestamo.estado === 'retrasado' && !fechaDevolucion) {
                                const diasRetraso = Math.floor((hoy - fechaDevolucionEstimada) / (1000 * 60 * 60 * 24));
                                estadoFinal += ` (${diasRetraso} días)`;
                            }

                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td>${prestamo.id}</td>
                            <td>${prestamo.nombreUsuario}</td>
                            <td>${prestamo.tituloLibro}</td>
                            <td>${new Date(prestamo.fecha_prestamo).toLocaleDateString('es-ES')}</td>
                            <td>${fechaDevolucionEstimada.toLocaleDateString('es-ES')}</td>
                            <td>${fechaDevolucion ? fechaDevolucion.toLocaleDateString('es-ES') : 'No registrada'}</td>
                            <td>${estadoFinal}</td>
                        `;
                            tbodyPrestamos.appendChild(row);
                            datatablePrestamos.row.add(row).draw();
                        });
                    });
                } else {
                    tbodyPrestamos.innerHTML = '<tr><td colspan="7">No hay préstamos registrados.</td></tr>';
                }
            } else {
                console.error('Error al cargar los préstamos:', data.message);
            }
        })
        .catch(error => console.error('Error al realizar la solicitud para cargar los préstamos:', error));
})();