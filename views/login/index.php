<?php
session_start();
session_destroy();
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
    <title>Pageflow | Gestión de Libros</title>
    <link rel="icon" href="../../public/images/icons/logo.jpg" type="image/jpg">
    <link rel="stylesheet" href="../../public/fontawesome/css/all.css">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <script src="../../controllers/usuarioController.js" defer></script>

</head>

<body>
    <!-- FORMULARIO DE INICIO DE SESIÓN Y REGISTRO -->
    <div class="background"></div>
    <div class="container">
        <div class="item">
            <div class="header">
                <img src="../../public/images/icons/logo.jpg" alt="Logo del Liceo" class="logo-img">
                <h2 class="logo">Pageflow</h2>
            </div>
            <div class="text-item">
                <h2>¡Bienvenido! <br><span>
                        Al Sistema de Gestión de Libros de Pageflow
                    </span></h2>
                <p>Gestiona tus libros y accede a la información de la biblioteca de manera fácil y rápida.</p>
            </div>
        </div>
        <div class="login-section">
            <div class="form-box login">
                <form id="login-form" method="post" autocomplete="off">
                    <h2>Iniciar sesión</h2>
                    <div class="input-box">
                        <span class="icon"><i class='fas fa-user'></i></span>
                        <input type="text" name="nombre_usuario" required>
                        <label>Nombre de usuario</label>
                    </div>
                    <div class="input-box">
                        <input id="password-login" type="password" name="contrasena" required>
                        <label>Contraseña</label>
                        <i class="fa-solid fa-eye toggle-password icon" id="toggle-login-password"></i>
                    </div>
                    <div class="recover-password">
                        <p><a class="recover-link" href="#">Recuperar contraseña</a></p>
                    </div>
                    <input class="btn" id="iniciar-sesion" type="submit" value="Iniciar sesión">
                    <div class="create-account">
                        <p>¿No tienes una cuenta? <a href="#" class="register-link">Regístrate</a></p>
                    </div>
                </form>
            </div>
            <div class="form-box register">
                <form id="registration-form" method="post" autocomplete="off">
                    <h2>Registrarse</h2>
                    <div class="input-box">
                        <span class="icon"><i class='fas fa-user'></i></span>
                        <input type="text" name="nombre_usuario" pattern="[\p{L}\d][\p{L}\d ]+" title="Solo se permiten letras y números." required>
                        <label>Nombre de usuario</label>
                    </div>
                    <div class="input-box">
                        <input id="password-register" type="password" name="contrasena" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,25}" title="La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y tener entre 8 y 25 caracteres" maxlength="25" required>
                        <label>Contraseña</label>
                        <i class="fa-solid fa-eye toggle-password icon" id="toggle-register-password"></i>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-question"></i></span>
                        <input type="text" name="pregunta_seguridad_1" title="Solo se permiten letras y números." minlength="4" maxlength="30" required>
                        <label>Comidad favorita</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-question"></i></span>
                        <input type="text" name="pregunta_seguridad_2" title="Solo se permiten letras y números." minlength="4" maxlength="30" required>
                        <label>Ciudad de nacimiento</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-question"></i></span>
                        <input type="text" name="pregunta_seguridad_3" title="Solo se permiten letras y números." minlength="4" maxlength="30" required>
                        <label>Nombre de mascota</label>
                    </div>
                    <div class="checkbox-box">
                        <input type="checkbox" id="admin-checkbox" name="rol">
                        <label for="admin-checkbox">Registrarse como administrador</label>
                    </div>
                    <input class="btn" type="submit" value="Registrarse">

                    <div class="create-account">
                        <p>¿Ya tienes una cuenta? <a href="#" class="login-link">Inicia Sesión</a></p>
                    </div>
                </form>
            </div>
            <div class="form-box recover">
                <form id="recovery-form" method="post" autocomplete="off">
                    <h2>Recuperar contraseña</h2>
                    <div class="input-box">
                        <span class="icon"><i class='fas fa-user'></i></span>
                        <input type="text" name="nombre_usuario" pattern="[\p{L}\d][\p{L}\d ]+" title="Solo se permiten letras y números." required>
                        <label>Nombre de usuario</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-question"></i></span>
                        <input type="text" name="pregunta_seguridad_1" maxlength="30" required>
                        <label>Comidad favorita</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-question"></i></span>
                        <input type="text" name="pregunta_seguridad_2" maxlength="30" required>
                        <label>Ciudad de nacimiento</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-question"></i></span>
                        <input type="text" name="pregunta_seguridad_3" maxlength="30" required>
                        <label>Nombre de mascota</label>
                    </div>
                    <div class="input-box">
                        <input id="password-recovery" type="password" name="nueva_contrasena" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,25}" title="La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y tener entre 8 y 25 caracteres" maxlength="25" required>
                        <label>Nueva contraseña</label>
                        <i class="fa-solid fa-eye toggle-password icon" id="toggle-recovery-password"></i>
                    </div>

                    <input class="btn" type="submit" value="Recuperar">

                    <div class="create-account">
                        <p>Volver al <a href="#" class="back-to-login-link">login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>