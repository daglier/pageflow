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
    <link rel="stylesheet" href="styles.css">
    <script type="text/javascript" src="../../public/datatables/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="../../public/datatables/datatables.js"></script>
    <script type="text/javascript" src="../../public/datatables/datatables.min.js"></script>
    <script src="script.js" type="text/javascript" defer></script>
    <script src="../../controllers/usuarioController.js" defer></script>
    <script src="../../controllers/libroController.js" defer></script>
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
                <a class="menu-item seguimiento-prestamos" href="#">
                    <i class="fas fa-book-reader"></i> <span class="menu-text">Seguimiento préstamos</span>
                </a>
                <a class="menu-item usuarios" href="#">
                    <i class="fas fa-users"></i> <span class="menu-text">Usuarios</span>
                </a>
                <a class="menu-item configuracion" href="#">
                    <i class="fas fa-gear"></i> <span class="menu-text">Configuración</span>
                </a>
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
                        <th>Modificar</th>
                        <th>Eliminar</th>
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
                            <input type="number" name="ano-publicacion" id="libro-ano-publicacion" min="1000" max="9999" required>
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
                        <div class="row" style="display: none;" >
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
                        <th>Último Inicio de Sesión</th>
                        <th>Historial de Préstamos</th>
                        <th>Aplicar Penalización</th>
                    </tr>
                </thead>
                <tbody id="tbody-usuarios">
                </tbody>
            </table>


            <!-- Modal para gestionar historial de préstamos -->
            <div class="modal hide" id="modal-historial-prestamos">
                <div class="modal-content">
                    <span id="close-modal-historial-btn" class="close-btn">&times;</span>
                    <div class="title"><i class="fa-solid fa-book-open"></i> Historial de Préstamos</div>
                    <div id="historial-prestamos-contenido">
                        <!-- Aquí se mostrará el historial de préstamos del usuario seleccionado -->
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
                        <th>Fecha de Inicio</th>
                        <th>Devolución Estimada</th>
                        <th>Estado</th>
                        <th>Actualizar Estado</th>
                    </tr>
                </thead>
                <tbody id="tbody-prestamos">
                    <tr>
                        <td>1</td>
                        <td>Juan Pérez</td>
                        <td>El Gran Gatsby</td>
                        <td>2024-10-01</td>
                        <td>2024-10-15</td>
                        <td>Activo</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ana González</td>
                        <td>Cien años de soledad</td>
                        <td>2024-10-05</td>
                        <td>2024-10-19</td>
                        <td>Devuelto</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Pedro Rodríguez</td>
                        <td>1984</td>
                        <td>2024-10-07</td>
                        <td>2024-10-21</td>
                        <td>Activo</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Lucía Fernández</td>
                        <td>Orgullo y prejuicio</td>
                        <td>2024-10-10</td>
                        <td>2024-10-24</td>
                        <td>Activo</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Carlos Méndez</td>
                        <td>Don Quijote de la Mancha</td>
                        <td>2024-10-12</td>
                        <td>2024-10-26</td>
                        <td>Devuelto</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Maria López</td>
                        <td>La sombra del viento</td>
                        <td>2024-10-15</td>
                        <td>2024-10-29</td>
                        <td>Activo</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>José Martínez</td>
                        <td>La casa de los espíritus</td>
                        <td>2024-10-18</td>
                        <td>2024-11-01</td>
                        <td>Activo</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Laura Sánchez</td>
                        <td>El código Da Vinci</td>
                        <td>2024-10-20</td>
                        <td>2024-11-03</td>
                        <td>Devuelto</td>
                        <td><button class="btn btn-actualizar"><i class="fas fa-sync-alt"></i> Actualizar</button></td>
                    </tr>
                </tbody>
            </table>


            <!-- Modal para registrar préstamo -->
            <div class="modal hide" id="modal-registrar-prestamo">
                <div class="modal-content">
                    <span id="close-modal-prestamo-btn" class="close-btn">&times;</span>
                    <div class="title"><i class="fa-solid fa-book"></i> Registrar Préstamo</div>
                    <form id="form-registrar-prestamo" autocomplete="off">
                        <div class="row">
                            <label for="prestamo-usuario"><i class="fa-solid fa-user"></i> Usuario:</label>
                            <select name="usuario" id="prestamo-usuario" required>
                                <!-- Opciones de usuarios disponibles -->
                            </select>
                        </div>
                        <div class="row">
                            <label for="prestamo-libro"><i class="fa-solid fa-book-open"></i> Libro:</label>
                            <select name="libro" id="prestamo-libro" required>
                                <!-- Opciones de libros disponibles -->
                            </select>
                        </div>
                        <div class="row">
                            <label for="prestamo-fecha-inicio"><i class="fa-solid fa-calendar"></i> Fecha de
                                Inicio:</label>
                            <input type="date" name="fecha_inicio" id="prestamo-fecha-inicio" required>
                        </div>
                        <div class="row">
                            <label for="prestamo-fecha-devolucion"><i class="fa-solid fa-calendar"></i> Devolución
                                Estimada:</label>
                            <input type="date" name="fecha_devolucion" id="prestamo-fecha-devolucion" required>
                        </div>
                        <div class="row">
                            <label for="prestamo-estado"><i class="fa-solid fa-exclamation-circle"></i> Estado:</label>
                            <select name="estado" id="prestamo-estado" required>
                                <option value="prestado">En préstamo</option>
                                <option value="devuelto">Devuelto</option>
                                <option value="retrasado">Retrasado</option>
                            </select>
                        </div>
                        <div class="row button">
                            <button type="submit" id="btn-registrar-prestamo"><i class="fa-solid fa-plus"></i> Registrar
                                Préstamo</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal para actualizar estado de préstamo -->
            <div class="modal hide" id="modal-actualizar-estado-prestamo">
                <div class="modal-content">
                    <span id="close-modal-actualizar-btn" class="close-btn">&times;</span>
                    <div class="title"><i class="fa-solid fa-sync-alt"></i> Actualizar Estado del Préstamo</div>
                    <form id="form-actualizar-estado-prestamo" autocomplete="off">
                        <input type="hidden" name="prestamo_id" id="prestamo-id">
                        <div class="row">
                            <label for="nuevo-estado-prestamo"><i class="fa-solid fa-exclamation-triangle"></i> Nuevo
                                Estado:</label>
                            <select name="nuevo_estado" id="nuevo-estado-prestamo" required>
                                <option value="devuelto">Devuelto</option>
                                <option value="retrasado">Retrasado</option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="comentarios-estado"><i class="fa-solid fa-comment-dots"></i>
                                Comentarios:</label>
                            <textarea name="comentarios" id="comentarios-estado" rows="4"></textarea>
                        </div>
                        <div class="row button">
                            <button type="submit" id="btn-actualizar-estado"><i class="fa-solid fa-check-circle"></i>
                                Actualizar Estado</button>
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
</body>

</html>