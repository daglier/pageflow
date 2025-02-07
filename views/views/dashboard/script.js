//Enlaces y secciones para la navegacion
const systemSections = document.querySelectorAll('.system-section');
const navLinksAnchors = document.querySelectorAll('.menu-item');

const navLinks = document.querySelector('.menu');

const menuToggle = document.querySelector('.menu-toggle');
const sidebar = document.querySelector('.sidebar');

//Tablas principales de los modulos
const tableLibros = document.getElementById("table-libros");
const tablePrestamos = document.getElementById("table-prestamos");
const tableUsuarios = document.getElementById("table-usuarios");
const tableHistorial= document.getElementById("table-historial");

//tbody de las tablas principales de los modulos
const tbodyLibros = document.getElementById("tbody-libros");
const tbodyPrestamos = document.getElementById("tbody-prestamos");
const tbodyUsuarios = document.getElementById("tbody-usuarios");
const tbodyHistorial = document.getElementById("tbody-historial");

// Elementos del modal del módulo de libros
const modalLibros = document.getElementById('modal-anadir-modificar-libro');
const abrirModalLibrosBtn = document.getElementById('anadir-libro-btn');
const cerrarModalLibrosBtn = document.getElementById('close-modal-libro-btn');
const formAnadirModificarLibro = document.getElementById('form-anadir-modificar-libro');
const modalLibrosContentTitle = document.getElementById("title-modal-libro");
const modalLibrosBtn = document.getElementById('modal-libros-btn');

// Campos del formulario de libros
const libroId = document.getElementById("libro-id");
const libroTitulo = document.getElementById("libro-titulo");
const libroAutor = document.getElementById("libro-autor");
const libroGenero = document.getElementById("libro-genero");
const libroAnoPublicacion = document.getElementById("libro-ano-publicacion");
const libroEstado = document.getElementById("libro-estado");
const libroSinopsis = document.getElementById("libro-sinopsis");
const libroFechaRegistro = document.getElementById("libro-fecha-registro");

// Elementos del modulo de usuarios
const modalHistorial = document.getElementById('modal-historial-prestamos');
const contenidoHistorial = document.getElementById('historial-prestamos-contenido');
const cerrarModalHistorialBtn = document.getElementById('close-modal-historial-btn');
const modalHistorialContentTitle = document.getElementById("title-modal-historial");
const messageNoHistorial = document.getElementById("message-no-historial");

// Elementos del modulo de configuracion
const selectOpcion = document.getElementById('configuracion-opcion');
const nombreUsuarioText = document.getElementById('nombre-usuario');
const contrasenaActualDiv = document.getElementById('configuracion-contrasena-actual-div');
const nombreUsuarioDiv = document.getElementById('configuracion-nombre-usuario-div');
const contrasenaDiv = document.getElementById('configuracion-contrasena-div');
const preguntasSeguridadDiv = document.getElementById('configuracion-preguntas-seguridad-div');
const nombreUsuarioNuevoInput = document.getElementById('configuracion-nombre-usuario');
const contrasenaNuevaInput = document.getElementById('configuracion-nueva-contrasena');

// Botones de hacer reporte de cada modulo
const btnDescargarExcelLibros = document.getElementById('btn-descargar-excel-libros');
const btnDescargarPdfLibros = document.getElementById('btn-descargar-pdf-libros');
const btnDescargarExcelUsuarios = document.getElementById('btn-descargar-excel-usuarios');
const btnDescargarPdfUsuarios = document.getElementById('btn-descargar-pdf-usuarios');
const btnDescargarExcelPrestamos = document.getElementById('btn-descargar-excel-prestamos');
const btnDescargarPdfPrestamos = document.getElementById('btn-descargar-pdf-prestamos');

// Boton para cerrar sesion
const logoutButton = document.getElementById('logout-button');


// Funciones para mostrar y ocultar modales
const showModal = modal => modal.classList.remove('hide');
const hideModal = modal => modal.classList.add('hide')

// Función para manejar el clic en un enlace de navegación rápida
const handleNavigationClick = event => {
    event.preventDefault();

    const targetClass = event.currentTarget.classList[1];
    const targetSection = document.querySelector(`#${targetClass}`);

    if (!targetClass) return;

    systemSections.forEach(section => {
        section.classList.remove('active');
    });

    targetSection.classList.add('active');

    navLinksAnchors.forEach(link => {
        link.classList.remove('is-active');
    });

    event.currentTarget.classList.add('is-active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Asignar el event listener a cada enlace de navegación
navLinksAnchors.forEach(link => {
    link.addEventListener('click', handleNavigationClick);
});

menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('is-active');
    sidebar.classList.toggle('is-active');
});

// Función para obtener el nombre del usuario actual
const obtenerNombreUsuarioActual = () => {
    const elementoNombreUsuario = document.getElementById('nombre-usuario');
    return elementoNombreUsuario.textContent;
};

// Función para obtener la fecha actual en formato YYYY-MM-DD
const obtenerFechaActual = () => {
    const fecha = new Date();
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, '0'); // Meses desde 0 a 11
    const day = String(fecha.getDate()).padStart(2, '0'); // Días desde 1 a 31
    return `${year}-${month}-${day}`;
};

// Función para obtener la fecha y la hora actual en formato YYYY-MM-DD HH:MM:SS
const obtenerFechaActualYHora = () => {
    const fecha = new Date();

    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, '0'); // Meses desde 0 a 11
    const day = String(fecha.getDate()).padStart(2, '0'); // Días desde 1 a 31

    const hours = String(fecha.getHours()).padStart(2, '0'); // Horas desde 0 a 23
    const minutes = String(fecha.getMinutes()).padStart(2, '0'); // Minutos desde 0 a 59
    const seconds = String(fecha.getSeconds()).padStart(2, '0'); // Segundos desde 0 a 59

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

// Función para inicializar DataTable en una tabla específica con columnDefs personalizados y encabezado en PDF
const inicializarDataTable = (table, targets, nombreArchivo = '', columnsToRemove, isWideTable = false) => {
    let exportOptions = {};

    if (columnsToRemove && columnsToRemove.length > 0) {
        exportOptions = {
            columns: ':not(' + columnsToRemove.map(column => `:nth-child(${column + 1})`).join(',') + ')' // Excluir las columnas especificadas
        };
    }

    const nombreUsuario = obtenerNombreUsuarioActual();
    const fechaActual = obtenerFechaActual();
    const fechaHoraActual = obtenerFechaActualYHora();

    return $(table).DataTable({
        destroy: true,
        paging: true,  // Activar la paginación
        searching: true,  // Activa el campo de búsqueda
        pageLength: 10,  // Número de filas por página
        ordering: true,  // Activar la ordenación
        lengthMenu: [5, 10, 25, 50, 100],  // Opciones de filas por página
        info: true,  // Ocultar la información de entradas mostradas
        responsive: true,  // Activar diseño responsivo
        language: {
            emptyTable: "No hay información disponible en la tabla",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros por página",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            zeroRecords: "No se encontraron resultados coincidentes",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
            aria: {
                sortAscending: ": activar para ordenar la columna de manera ascendente",
                sortDescending: ": activar para ordenar la columna de manera descendente"
            }
        },
        columnDefs: [
            { targets: targets, orderable: false },
            { targets: 3, type: 'date' }
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                title: nombreArchivo ? `${nombreArchivo} ${fechaActual}` : fechaActual,
                filename: nombreArchivo ? `${nombreArchivo.replace(/\s/g, '_')}_${fechaActual}` : fechaActual,
                exportOptions: exportOptions
            },
            {
                extend: 'pdfHtml5',
                title: nombreArchivo ? `${nombreArchivo} ${fechaActual}` : fechaActual,
                filename: nombreArchivo ? `${nombreArchivo.replace(/\s/g, '_')}_${fechaActual}` : fechaActual,
                exportOptions: exportOptions,
                customize: function (doc) {
                    // Configurar márgenes del documento
                    doc.pageMargins = [40, 80, 40, 40]; // Márgenes: [izquierda, arriba, derecha, abajo]

                    // Encabezado con estilos modernos
                    doc['header'] = function () {
                        return {
                            columns: [
                                {
                                    text: [
                                        { text: 'Pageflow\n', fontSize: 16, bold: true, color: '#333' }
                                    ],
                                    width: '*',
                                    margin: [10, 0, 0, 0]
                                },
                                {
                                    text: [
                                        { text: `Generado por: ${nombreUsuario}\n`, fontSize: 10, color: '#666' },
                                        { text: `Fecha: ${fechaHoraActual}`, fontSize: 10, color: '#666' }
                                    ],
                                    alignment: 'right',
                                    margin: [0, 0, 10, 0]
                                }
                            ],
                            margin: [20, 10]
                        };
                    };

                    // Pie de página
                    doc['footer'] = function (currentPage, pageCount) {
                        return {
                            columns: [
                                { text: `Página ${currentPage} de ${pageCount}`, alignment: 'center', fontSize: 8, color: '#666' }
                            ],
                            margin: [20, 10]
                        };
                    };

                    // Estilos de tabla
                    doc.styles.tableHeader = {
                        fillColor: '#8d6e63',
                        color: 'white',
                        bold: true,
                        fontSize: 12
                    };

                    // Alternar colores en las filas de la tabla
                    doc.content[1].layout = {
                        hLineColor: function () { return '#cccccc'; },
                        vLineColor: function () { return '#cccccc'; },
                        paddingLeft: function () { return 5; },
                        paddingRight: function () { return 5; },
                        paddingTop: function () { return 5; },
                        paddingBottom: function () { return 5; },
                        fillColor: function (rowIndex) {
                            return (rowIndex % 2 === 0) ? '#f2f2f2' : null; // Alternar colores
                        }
                    };

                    // Ajustes generales de contenido
                    doc['content'].forEach(function (contentItem) {
                        if (typeof contentItem === 'object' && contentItem.table) {
                            // Condición para aplicar mayor padding solo a la tabla de órdenes
                            if (isWideTable) {
                                // Aplicar mayor padding a la tablas anchas
                                contentItem.layout = {
                                    paddingTop: function () { return 10; },
                                    paddingBottom: function () { return 10; },
                                    paddingLeft: function () { return 25; },
                                    paddingRight: function () { return 25; }
                                };
                            } else {
                                // Mantener el padding estándar para otras tablas
                                contentItem.layout = {
                                    paddingTop: function () { return 5; },
                                    paddingBottom: function () { return 5; },
                                    paddingLeft: function () { return 5; },
                                    paddingRight: function () { return 5; }
                                };
                            }
                        }
                    });
                }
            },
            'print' // Agregar el botón de impresión
        ]
    });
};

// Inicializa las tablas con nombres personalizados
const datatableLibros = inicializarDataTable(
    '#table-libros',
    usuarioActual.rol === 'administrador' ? [8, 9] : [],
    'Libros',
    usuarioActual.rol === 'administrador' ? [8, 9] : []
);

const datatablePrestamos = inicializarDataTable('#table-prestamos', [], 'Prestamos', []);
const datatableUsuarios = inicializarDataTable('#table-usuarios', [4], 'Usuarios', [4]);

// Función para agregar un evento de descarga a un botón específico
const agregarEventoDescargar = (boton, datatable, tipo) => {
    if (boton && datatable) {
        boton.addEventListener('click', () => {
            datatable.buttons(`.buttons-${tipo}`).trigger();
        });
    }
};

// Función para agregar todos los eventos de descarga
const agregarEventosDescargar = () => {

    agregarEventoDescargar(btnDescargarExcelLibros, datatableLibros, 'excel');
    agregarEventoDescargar(btnDescargarPdfLibros, datatableLibros, 'pdf');

    agregarEventoDescargar(btnDescargarExcelPrestamos, datatablePrestamos, 'excel');
    agregarEventoDescargar(btnDescargarPdfPrestamos, datatablePrestamos, 'pdf');

    agregarEventoDescargar(btnDescargarExcelUsuarios, datatableUsuarios, 'excel');
    agregarEventoDescargar(btnDescargarPdfUsuarios, datatableUsuarios, 'pdf');
}

agregarEventosDescargar();