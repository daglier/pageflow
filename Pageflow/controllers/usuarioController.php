<?php
require_once '../models/usuario.php';
require_once '../models/dao/usuarioDAO.php';

class UsuarioController
{
    private $usuarioDAO;

    public function __construct()
    {
        $this->usuarioDAO = new UsuarioDAO();
    }

    // Función para manejar el inicio de sesión de un usuario
    public function login()
    {
        session_start();

        // Recibir datos del formulario
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];

        // Consultar la base de datos
        $usuario = $this->usuarioDAO->getUsuarioByNombreUsuario($nombre_usuario);

        // Verificar si se encontró al usuario
        if ($usuario) {
            // Verificar la contraseña
            if (password_verify($contrasena, $usuario->getContrasena())) {

                // Actualizar último inicio de sesión
                $fechaActual = date('Y-m-d H:i:s');
                $this->usuarioDAO->actualizarUltimoInicioSesion($usuario->getId_usuario(), $fechaActual);

                // Iniciar sesión, establecer las variables de sesión y redirigir al panel
                $_SESSION["id_usuario"] = $usuario->getId_usuario();
                $_SESSION["nombre_usuario"] = $usuario->getNombre_usuario();
                $_SESSION["rol"] = $usuario->getRol();
                $_SESSION["fecha_registro"] = $usuario->getFecha_registro();

                echo json_encode(['success' => true]);
            } else {
                // Contraseña incorrecta
                echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
            }
        } else {
            // Nombre de usuario no encontrado
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario no está asociado a ningún usuario registrado.']);
        }
    }

    // Función para manejar el registro de un nuevo usuario
    public function registrar()
    {
        session_start();

        // Recibir datos del formulario
        $nombre_usuario = $_POST["nombre_usuario"];
        $contrasena = $_POST["contrasena"];
        $rol = isset($_POST["rol"]) && $_POST["rol"] === "on" ? "administrador" : "lector";
        $fecha_registro = date("Y-m-d H:i:s");
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
        $pregunta_seguridad_1 = $_POST["pregunta_seguridad_1"];
        $pregunta_seguridad_2 = $_POST["pregunta_seguridad_2"];
        $pregunta_seguridad_3 = $_POST["pregunta_seguridad_3"];

        // Verificar si el nombre de usuario ya está registrado
        if ($this->usuarioDAO->getUsuarioByNombreUsuario($nombre_usuario)) {
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está en uso.']);
            exit;
        }

        // Crear y registrar el nuevo usuario
        $nuevoUsuario = new Usuario($nombre_usuario, $hashed_password, $rol, $fecha_registro, $pregunta_seguridad_1, $pregunta_seguridad_2, $pregunta_seguridad_3, $fecha_registro);

        if ($this->usuarioDAO->crearUsuario($nuevoUsuario)) {

            $id_usuario = $this->usuarioDAO->getLastInsertId();

            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre_usuario'] = $nombre_usuario;
            $_SESSION["rol"] = $rol;
            $_SESSION["fecha_registro"] = $fecha_registro;

            // Actualizar último inicio de sesión

            echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        }
    }

    public function recuperarContrasena()
    {
        // Recibir datos del formulario
        $nombre_usuario = $_POST["nombre_usuario"];
        $nueva_contrasena = $_POST["nueva_contrasena"];
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $pregunta_seguridad_1 = $_POST['pregunta_seguridad_1'];
        $pregunta_seguridad_2 = $_POST['pregunta_seguridad_2'];
        $pregunta_seguridad_3 = $_POST['pregunta_seguridad_3'];

        // Verificar si el nombre de usuario existe
        $usuario = $this->usuarioDAO->getUsuarioByNombreUsuario($nombre_usuario);

        if (!$usuario) {
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario ingresado no está asociado a ningún usuario registrado.']);
            return;
        }

        // Verificar las preguntas de seguridad
        if ($usuario->getPregunta_seguridad_1() === $pregunta_seguridad_1 && $usuario->getPregunta_seguridad_2() === $pregunta_seguridad_2 && $usuario->getPregunta_seguridad_3() === $pregunta_seguridad_3) {
            // Verificar si la nueva contraseña es igual a la actual
            if (password_verify($nueva_contrasena, $usuario->getContrasena())) {
                echo json_encode(['success' => false, 'message' => 'La nueva contraseña no puede ser igual a la contraseña actual. Por favor, elija una nueva contraseña diferente.']);
                return;
            }

            // Actualizar la contraseña
            if ($this->usuarioDAO->actualizarContrasena($usuario->getId_usuario(), $hashed_password)) {
                echo json_encode(['success' => true, 'message' => 'Contraseña actualizada exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al recuperar la contraseña.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Al menos una pregunta de seguridad es incorrecta.']);
        }
    }

    // Función para obtener la lista de todos los usuarios registrados
    public function obtenerUsuarios()
    {
        $usuarios = $this->usuarioDAO->getAllUsuarios();
        $usuariosArray = [];

        foreach ($usuarios as $usuario) {
            $usuariosArray[] = [
                'id' => $usuario->getId_usuario(),
                'nombre_usuario' => $usuario->getNombre_usuario(),
                'rol' => $usuario->getRol(),
                'pregunta_seguridad_1' => $usuario->getPregunta_seguridad_1(),
                'pregunta_seguridad_2' => $usuario->getPregunta_seguridad_2(),
                'pregunta_seguridad_3' => $usuario->getPregunta_seguridad_3(),
                'ultimo_inicio_sesion' => $usuario->getUltimo_inicio_sesion()
            ];
        }

        echo json_encode(['success' => true, 'usuarios' => $usuariosArray]);
    }

    // Función para obtener un usuario específico por su ID
    public function obtenerUsuario()
    {
        $id_usuario = $_GET['id_usuario'];

        $usuario = $this->usuarioDAO->getUsuarioById($id_usuario);

        if ($usuario) {
            echo json_encode(['success' => true, 'usuario' => $usuario]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        }
    }

    // Función para obtener el nombre de usuario específico por su ID
    public function obtenerNombreUsuarioPorId()
    {
        $id_usuario = $_GET['id_usuario'];
        $usuario = $this->usuarioDAO->getUsuarioById($id_usuario);

        if ($usuario) {
            echo json_encode(['success' => true, 'nombre_usuario' => $usuario->getNombre_usuario()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        }
    }

    // Función para cambiar la contraseña del usuario actual
    public function cambiarContrasena()
    {
        session_start();

        // Recibir datos del formulario
        $contrasena_actual = $_POST['contrasena-actual'];
        $contrasena_nueva = $_POST['nueva-contrasena'];
        $usuario_id = $_SESSION['id_usuario'];

        // Obtener el usuario actual desde la base de datos
        $usuario = $this->usuarioDAO->getUsuarioById($usuario_id);

        // Verificar que la contraseña actual es correcta
        if (!password_verify($contrasena_actual, $usuario->getContrasena())) {
            echo json_encode(['success' => false, 'message' => 'La contraseña actual no es correcta.']);
            return;
        }

        // Verificar que la nueva contraseña no sea igual a la actual
        if (password_verify($contrasena_nueva, $usuario->getContrasena())) {
            echo json_encode(['success' => false, 'message' => 'La nueva contraseña no puede ser igual a la contraseña actual.']);
            return;
        }

        // Hashear la nueva contraseña
        $hashed_password = password_hash($contrasena_nueva, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        if ($this->usuarioDAO->actualizarContrasena($usuario_id, $hashed_password)) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
        }
    }

    // Función para cambiar el nombre de usuario del usuario actual
    public function cambiarNombreUsuario()
    {
        session_start();

        // Recibir datos del formulario
        $nombre_usuario_nuevo = $_POST['nuevo-nombre-usuario'];
        $contrasena_actual = $_POST['contrasena-actual'];
        $usuario_id = $_SESSION['id_usuario'];

        // Obtener el usuario actual desde la base de datos
        $usuario = $this->usuarioDAO->getUsuarioById($usuario_id);

        // Verificar que la contraseña actual es correcta
        if (!password_verify($contrasena_actual, $usuario->getContrasena())) {
            echo json_encode(['success' => false, 'message' => 'La contraseña actual no es correcta.']);
            return;
        }

        // Verificar si el nuevo nombre de usuario ya está en uso
        if ($this->usuarioDAO->getUsuarioByNombreUsuario($nombre_usuario_nuevo)) {
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está en uso.']);
            return;
        }

        // Actualizar el nombre de usuario en la base de datos
        if ($this->usuarioDAO->actualizarNombreUsuario($usuario_id, $nombre_usuario_nuevo)) {
            $_SESSION['nombre_usuario'] = $nombre_usuario_nuevo;
            echo json_encode(['success' => true, 'message' => 'Nombre de usuario actualizado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el nombre de usuario.']);
        }
    }

    public function cambiarPreguntasSeguridad()
    {
        session_start();

        // Recibir los datos del formulario
        $pregunta_1 = $_POST['pregunta-seguridad-1'];
        $pregunta_2 = $_POST['pregunta-seguridad-2'];
        $pregunta_3 = $_POST['pregunta-seguridad-3'];
        $usuario_id = $_SESSION['id_usuario'];

        // Actualizar las preguntas de seguridad en la base de datos
        if ($this->usuarioDAO->actualizarPreguntasSeguridad($usuario_id, $pregunta_1, $pregunta_2, $pregunta_3)) {
            echo json_encode(['success' => true, 'message' => 'Preguntas de seguridad actualizadas exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar las preguntas de seguridad.']);
        }
    }

    // Función para restaurar base de datos
    public function restaurarBaseDatos()
    {
        if (isset($_POST['respaldo'])) {
            $nombreArchivo = $_POST['respaldo'];
            $rutaArchivo = "../backups/" . $nombreArchivo;

            // Verificar si el archivo realmente existe
            if (!file_exists($rutaArchivo)) {
                echo json_encode(['success' => false, 'message' => 'El archivo de respaldo no existe.']);
                return;
            }

            if ($this->usuarioDAO->restaurarBaseDatos($rutaArchivo)) {
                echo json_encode(['success' => true, 'message' => 'Base de datos restaurada exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al restaurar la base de datos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se proporcionó un archivo de respaldo válido.']);
        }
    }

    // Función para eliminar un usuario por su ID
    public function eliminarUsuario()
    {
        $id_usuario = $_POST['id_usuario'];

        if ($this->usuarioDAO->eliminarUsuario($id_usuario)) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
        }
    }

    // Función para que el usuario cierre sesión
    public function cerrarSesion()
    {
        session_start();
        session_unset();
        session_destroy(); //Se destruyen los datos de sesion, por lo que la sesion cierra

        echo json_encode(['success' => true]);
        exit;
    }
}

// Instanciar el controlador
$controller = new UsuarioController();

// Llama al método del controlador basado en la acción solicitada en la URL
$action = $_GET['action'];
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
