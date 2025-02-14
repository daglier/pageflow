<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre_usuario"])) {
    // Si no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header('location:../login/index.php'); //Envia al usuario al index.php
    exit();
}

//Variables de sesion:
$idUsuario = isset($_SESSION["id_usuario"]) ? $_SESSION["id_usuario"] : "";
$nombreUsuario = isset($_SESSION["nombre_usuario"]) ? $_SESSION["nombre_usuario"] : "";
$rolUsuario = isset($_SESSION["rol"]) ? $_SESSION["rol"] : "";
$fechaRegistro = isset($_SESSION["fecha_registro"]) ? $_SESSION["fecha_registro"] : "";

// Conectar a la base de datos
include '../../config/conexion.php';
include '../../config/backup.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gestión de Libros para Pageflow">
    <meta name="keywords" content="sistema de gestión de libros, biblioteca, gestión de libros, administración de libros, software de biblioteca, registro de libros, usuarios, lectura, educación, Pageflow">
    <meta name="author" content="Daglier Pérez">
    <title>Pageflow | Panel principal</title>
    <link rel="icon" href="../../public/images/icons/logo.jpg" type="image/jpg">
    <link rel="stylesheet" href="../../public/fontawesome/css/all.css">

    <style>
        /* poppins-900italic - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 900;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-900italic.woff2') format('woff2');
        }

        /* poppins-900 - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: black;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-900.woff2') format('woff2');
        }

        /* poppins-700italic - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 700;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-700italic.woff2') format('woff2');
        }

        /* poppins-700 - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: bold;
            font-weight: 700;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-700.woff2') format('woff2');
        }

        /* poppins-600italic - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 600;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-600italic.woff2') format('woff2');
        }

        /* poppins-600 - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: semibold;
            font-weight: 600;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-600.woff2') format('woff2');
        }

        /* poppins-500italic - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 500;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-500italic.woff2') format('woff2');
        }

        /* poppins-500 - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: medium;
            font-weight: 500;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-500.woff2') format('woff2');
        }

        /* poppins-italic - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 400;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-italic.woff2') format('woff2');
        }

        /* poppins-regular - latin */
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('../../public/fonts/poppins-v22-latin-regular.woff2') format('woff2');
        }

        :root {
            --font-primary: 'Poppins', serif;
        }

        body {
            font-family: var(--font-primary);
        }
    </style>

    <link rel="stylesheet" href="styles.css">
    <script type="text/javascript" src="../../public/datatables/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="../../public/datatables/datatables.js"></script>
    <script type="text/javascript" src="../../public/datatables/datatables.min.js"></script>
    <script src="script.js" type="text/javascript" defer></script>
    <script src="darkMode.js" type="text/javascript" defer></script>
    <script src="../../controllers/usuarioController.js" defer></script>
    <script src="../../controllers/libroController.js" defer></script>
    <script src="../../controllers/prestamoController.js" defer></script>
    <link rel="stylesheet" href="../../public/datatables/datatables.css">
    <link rel="stylesheet" href="../../public/datatables/datatables.min.css">
</head>

<body>
    <div class="container-sidebar">
        <aside class="sidebar is-active">
            <div class="sidebar-header">
                <div class="menu-toggle">
                    <div class="hamburger">
                        <span></span>
                    </div>
                </div>
            </div>

            <nav class="menu">
                <a class="menu-item libros is-active" href="#">
                    <i class="fas fa-book"></i> <span class="menu-text">Libros</span>
                </a>
                <?php if ($rolUsuario === 'administrador') : ?>
                    <a class="menu-item seguimiento-prestamos" href="#">
                        <i class="fas fa-book-reader"></i> <span class="menu-text">Seguimiento préstamos</span>
                    </a>
                    <a class="menu-item usuarios" href="#">
                        <i class="fas fa-users"></i> <span class="menu-text">Usuarios</span>
                    </a>
                <?php endif; ?>
                <a class="menu-item configuracion" href="#">
                    <i class="fas fa-gear"></i> <span class="menu-text">Configuración</span>
                </a>
                <li>
                    <label for="slider" class="switch">
                        <input type="checkbox" id="slider">
                        <span class="slider">
                            <i class="fa-solid fa-moon icon moon-icon"></i>
                            <i class="fa-solid fa-sun icon sun-icon"></i>
                        </span>
                    </label>
                </li>
            </nav>

            <button class="btn" id="logout-button">
                <i class="fas fa-sign-out-alt"></i> <span class="menu-text">Cerrar sesión</span>
            </button>
        </aside>
    </div>

    <main>
        <section id="libros" class="system-section libros-section active">
            <div class="header-flex">
                <h2 class="section-title">Libros</h2>
                <div class="div-buttons">
                    <button id="btn-descargar-pdf-libros" class="btn-pdf"><i class="fa-solid fa-file-pdf"></i> Descargar
                        PDF</button>
                    <button id="btn-descargar-excel-libros" class="btn-excel"><i class="fa-solid fa-file-excel"></i>
                        Descargar Excel</button>
                </div>
                <button id="anadir-libro-btn" class="btn-anadir"><i class="fa-solid fa-plus"></i> Añadir Libro</button>
            </div>

            <!-- Tabla de libros -->
            <table class="content-table" id="table-libros" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Género</th>
                        <th>Año de publicación</th>
                        <th>Estado</th>
                        <th>Sinopsis</th>
                        <th>Fecha de registro</th>
                        <?php if ($rolUsuario === 'administrador') : ?>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        <?php elseif ($rolUsuario === 'lector') : ?>
                            <th>Pedir préstamo</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tbody-libros">
                </tbody>
            </table>


            <!-- Modal para añadir/modificar libro -->
            <div class="modal hide" id="modal-anadir-modificar-libro">
                <div class="modal-content">
                    <span id="close-modal-libro-btn" class="close-btn">&times;</span>
                    <div class="title" id="title-modal-libro"><i class="fa-solid fa-book"></i> Añadir Libro</div>
                    <form id="form-anadir-modificar-libro" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="libro-id">

                        <div class="row">
                            <label for="libro-titulo"><i class="fa-solid fa-file-alt"></i> Título:</label>
                            <input type="text" name="titulo" id="libro-titulo" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-.,]+$" title="El título debe contener solo letras, números, espacios y signos permitidos." minlength="2" maxlength="255" required>
                        </div>
                        <div class="row">
                            <label for="libro-autor"><i class="fa-solid fa-user"></i> Autor:</label>
                            <input type="text" name="autor" id="libro-autor" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-.,]+$" title="El nombre del autor debe contener solo letras, espacios y signos permitidos." minlength="2" maxlength="255" required>
                        </div>
                        <div class="row">
                            <label for="libro-genero"><i class="fa-solid fa-tag"></i> Género:</label>
                            <select name="genero" id="libro-genero" required>
                                <option value="" disabled selected>Selecciona un género</option>
                                <option value="ficción">Ficción</option>
                                <option value="no ficción">No Ficción</option>
                                <option value="misterio">Misterio</option>
                                <option value="romance">Romance</option>
                                <option value="ciencia ficción">Ciencia Ficción</option>
                                <option value="fantasía">Fantasía</option>
                                <option value="terror">Terror</option>
                                <option value="biografía">Biografía</option>
                                <option value="historia">Historia</option>
                                <option value="aventura">Aventura</option>
                                <option value="autoayuda">Autoayuda</option>
                                <option value="cómics">Cómics</option>
                                <option value="poesía">Poesía</option>
                                <option value="infantil">Infantil</option>
                                <option value="drama">Drama</option>
                                <option value="ensayo">Ensayo</option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="libro-ano"><i class="fa-solid fa-calendar"></i> Año de publicación:</label>
                            <input type="number" name="ano-publicacion" id="libro-ano-publicacion" min="1000" max="<?php echo date('Y'); ?>" required>
                        </div>
                        <div class="row">
                            <label for="libro-estado"><i class="fa-solid fa-book-open"></i> Estado:</label>
                            <select name="estado" id="libro-estado" required>
                                <option value="disponible">Disponible</option>
                                <option value="prestado">Prestado</option>
                                <option value="vendido">Vendido</option>
                                <option value="reservado">Reservado</option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="libro-sinopsis"><i class="fa-solid fa-align-left"></i> Sinopsis:</label>
                            <textarea name="sinopsis" id="libro-sinopsis" rows="4" maxlength="1000"></textarea>
                        </div>
                        <div class="row" style="display: none;">
                            <label for="libro-fecha-registro"><i class="fa-solid fa-clock"></i> Fecha de registro:</label>
                            <input type="date" name="fecha-registro" id="libro-fecha-registro">
                        </div>
                        <div class="row button">
                            <button id="modal-libros-btn" type="submit"><i class="fa-solid fa-plus"></i> Añadir</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section id="seguimiento-prestamos" class="system-section seguimiento-prestamos-section">
            <div class="header-flex">
                <h2 class="section-title">Seguimiento de préstamos</h2>
                <div class="div-buttons">
                    <button id="btn-descargar-pdf-prestamos" class="btn-pdf"><i class="fa-solid fa-file-pdf"></i>
                        Descargar PDF</button>
                    <button id="btn-descargar-excel-prestamos" class="btn-excel"><i class="fa-solid fa-file-excel"></i>
                        Descargar Excel</button>
                </div>
            </div>

            <!-- Tabla de préstamos -->
            <table class="content-table" id="table-prestamos" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha de préstamo</th>
                        <th>Fecha de devolución estimada</th>
                        <th>Fecha de devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="tbody-prestamos">
                </tbody>
            </table>
        </section>

        <section id="usuarios" class="system-section usuarios-section">
            <div class="header-flex">
                <h2 class="section-title">Usuarios</h2>
                <div class="div-buttons">
                    <button id="btn-descargar-pdf-usuarios" class="btn-pdf"><i class="fa-solid fa-file-pdf"></i>
                        Descargar PDF</button>
                    <button id="btn-descargar-excel-usuarios" class="btn-excel"><i class="fa-solid fa-file-excel"></i>
                        Descargar Excel</button>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <table class="content-table" id="table-usuarios" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Último inicio de sesión</th>
                        <th>Historial de préstamos</th>
                    </tr>
                </thead>
                <tbody id="tbody-usuarios">
                </tbody>
            </table>

            <!-- Modal para gestionar historial de préstamos -->
            <div class="modal hide" id="modal-historial-prestamos" style="width: 100%;">
                <div class="modal-content">
                    <span id="close-modal-historial-btn" class="close-btn">&times;</span>
                    <div class="title" id="title-modal-historial"><i class="fa-solid fa-book-open"></i> Historial de préstamos</div>
                    <div id="historial-prestamos-contenido">
                        <table class="content-table" id="table-historial">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Libro</th>
                                    <th>Fecha de préstamo</th>
                                    <th>Fecha de devolución estimada</th>
                                    <th>Fecha de devolución</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-historial">
                                <!-- Las filas se agregarán aquí dinámicamente -->
                            </tbody>
                        </table>

                        <p id="message-no-historial">No hay historial de préstamos para este usuario.</p>
                    </div>
                </div>
            </div>

            <!-- Modal para aplicar penalización -->
            <div class="modal hide" id="modal-aplicar-penalizacion">
                <div class="modal-content">
                    <span id="close-modal-penalizacion-btn" class="close-btn">&times;</span>
                    <div class="title"><i class="fa-solid fa-exclamation-triangle"></i> Aplicar Penalización</div>
                    <form id="form-aplicar-penalizacion" autocomplete="off">
                        <input type="hidden" name="id_usuario" id="id_usuario_penalizacion">
                        <div class="row">
                            <label for="penalizacion-motivo"><i class="fa-solid fa-comment-dots"></i> Motivo de
                                penalización:</label>
                            <textarea name="motivo" id="penalizacion-motivo" required></textarea>
                        </div>
                        <div class="row button">
                            <button type="submit" id="btn-aplicar-penalizacion"><i class="fa-solid fa-check-circle"></i>
                                Aplicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section id="configuracion" class="system-section configuracion-section">
            <div class="header-flex">
                <h2 class="section-title">Configuración</h2>
            </div>

            <div class="configuracion-form">
                <div class="configuracion-info modern-box">
                    <div class="user-info">
                        <i class="fa-solid fa-user"></i>
                        <p>Usuario actual: <strong id="nombre-usuario"><?php echo $nombreUsuario; ?></strong></p>
                    </div>
                    <div class="user-info">
                        <i class="fa-solid fa-calendar"></i>
                        <p>Fecha registro: <strong><?php echo date('d-m-Y', strtotime($fechaRegistro)); ?></strong></p>
                    </div>
                    <div class="user-info">
                        <i class="fa-solid fa-id-badge"></i>
                        <p>Rol: <strong><?php echo $rolUsuario; ?></strong></p>
                    </div>
                </div>

                <form id="configuration-form" method="post" autocomplete="off">
                    <!-- Select para elegir qué cambiar -->
                    <div class="row">
                        <label for="configuracion-opcion"><i class="fa-solid fa-cogs"></i> ¿Qué desea
                            actualizar?</label>
                        <select id="configuracion-opcion" name="opcion" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option value="nombre-usuario">Cambiar nombre de usuario</option>
                            <option value="contrasena">Cambiar contraseña</option>
                            <option value="preguntas-seguridad">Cambiar preguntas de seguridad</option>
                        </select>
                    </div>

                    <!-- Input para la contraseña actual -->
                    <div class="row hide" id="configuracion-contrasena-actual-div">
                        <label for="configuracion-contrasena-actual"><i class="fa-solid fa-key"></i> Contraseña
                            actual:</label>
                        <input id="configuracion-contrasena-actual" type="password" name="contrasena-actual" maxlength="255" required>
                    </div>

                    <!-- Inputs condicionales dependiendo de la opción seleccionada -->
                    <div class="row hide" id="configuracion-nombre-usuario-div">
                        <label for="configuracion-nombre-usuario"><i class="fa-solid fa-user"></i> Nuevo nombre de
                            usuario:</label>
                        <input id="configuracion-nombre-usuario" type="text" name="nuevo-nombre-usuario" pattern="[\p{L}\d][\p{L}\d ]+" title="Solo se permiten letras y números." maxlength="255">
                    </div>

                    <div class="row hide" id="configuracion-contrasena-div">
                        <label for="configuracion-nueva-contrasena"><i class="fa-solid fa-lock"></i> Nueva
                            contraseña:</label>
                        <input id="configuracion-nueva-contrasena" type="password" name="nueva-contrasena" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,25}" title="La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y tener entre 8 y 25 caracteres" maxlength="25">
                    </div>

                    <div class="row hide" id="configuracion-preguntas-seguridad-div">
                        <label for="configuracion-pregunta-1"><i class="fa-solid fa-question"></i> Comidad
                            favorita:</label>
                        <input id="configuracion-pregunta-1" type="text" name="pregunta-seguridad-1" title="Solo se permiten letras y números." minlength="4" maxlength="30">

                        <label for="configuracion-pregunta-2"><i class="fa-solid fa-question"></i> Ciudad de
                            nacimiento:</label>
                        <input id="configuracion-pregunta-2" type="text" name="pregunta-seguridad-2" title="Solo se permiten letras y números." minlength="4" maxlength="30">

                        <label for="configuracion-pregunta-3"><i class="fa-solid fa-question"></i> Nombre de
                            mascota:</label>
                        <input id="configuracion-pregunta-3" type="text" name="pregunta-seguridad-3" title="Solo se permiten letras y números." minlength="4" maxlength="30">
                    </div>

                    <div class="row button">
                        <button id="configuracion-btn" type="submit"><i class="fa-solid fa-save"></i> Guardar
                            cambios</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="logo">
                <img src="../../public/images/icons/logo.jpg" alt="Logo">
            </div>
            <p>Pageflow | 2024</p>
        </div>
    </footer>

    <script>
        // Pasar el usuario al script.js
        const usuarioActual = {
            id: "<?php echo $idUsuario; ?>",
            nombre: "<?php echo $nombreUsuario; ?>",
            rol: "<?php echo $rolUsuario; ?>",
            fechaRegistro: "<?php echo $fechaRegistro; ?>"
        };
    </script>
</body>

</html>