<?php
require_once '../config/conexion.php';
require_once '../models/usuario.php';

class UsuarioDAO
{
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos utilizando MySQLi
    public function __construct()
    {
        $this->conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    // Función para obtener todos los usuarios desde la base de datos
    public function getAllUsuarios()
    {
        $sql = "SELECT * FROM " . TABLE_USUARIOS;
        $result = $this->conexion->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuario = new Usuario(
                $row['nombre_usuario'],
                $row['contrasena'],
                $row['rol'],
                $row['fecha_registro'],
                $row['pregunta_seguridad_1'],
                $row['pregunta_seguridad_2'],
                $row['pregunta_seguridad_3'],
                $row['ultimo_inicio_sesion']
            );
            $usuario->setId_usuario($row['id_usuario']);
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }

    // Función para crear un nuevo usuario en la base de datos
    public function crearUsuario($usuario)
    {
        $stmt = $this->conexion->prepare("INSERT INTO " . TABLE_USUARIOS . " (nombre_usuario, contrasena, rol, fecha_registro, pregunta_seguridad_1, pregunta_seguridad_2, pregunta_seguridad_3) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Asignar los valores a variables locales
        $nombre_usuario = $usuario->getNombre_usuario();
        $contrasena = $usuario->getContrasena();
        $rol = $usuario->getRol();
        $fecha_registro = $usuario->getFecha_registro();
        $pregunta_seguridad_1 = $usuario->getPregunta_seguridad_1();
        $pregunta_seguridad_2 = $usuario->getPregunta_seguridad_2();
        $pregunta_seguridad_3 = $usuario->getPregunta_seguridad_3();

        // Pasar las variables locales a bind_param
        $stmt->bind_param("sssssss", $nombre_usuario, $contrasena, $rol, $fecha_registro, $pregunta_seguridad_1, $pregunta_seguridad_2, $pregunta_seguridad_3);

        return $stmt->execute();
    }

    // Función para obtener un usuario específico por ID
    public function getUsuarioById($id_usuario)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM " . TABLE_USUARIOS . " WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if ($data) {
            $usuario = new Usuario(
                $data['nombre_usuario'],
                $data['contrasena'],
                $data['rol'],
                $data['fecha_registro'],
                $data['pregunta_seguridad_1'],
                $data['pregunta_seguridad_2'],
                $data['pregunta_seguridad_3'],
                $data['ultimo_inicio_sesion']
            );
            $usuario->setId_usuario($data['id_usuario']);
            return $usuario;
        }
        return null;
    }

    // Función para obtener un usuario específico por nombre de usuario
    public function getUsuarioByNombreUsuario($nombre_usuario)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM " . TABLE_USUARIOS . " WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if ($data) {
            $usuario = new Usuario(
                $data['nombre_usuario'],
                $data['contrasena'],
                $data['rol'],
                $data['fecha_registro'],
                $data['pregunta_seguridad_1'],
                $data['pregunta_seguridad_2'],
                $data['pregunta_seguridad_3'],
                $data['ultimo_inicio_sesion'],
            );
            $usuario->setId_usuario($data['id_usuario']);
            return $usuario;
        }
        return null;
    }

    // Función para actualizar el nombre de usuario de un usuario específico
    public function actualizarNombreUsuario($id_usuario, $nuevo_nombre_usuario)
    {
        $stmt = $this->conexion->prepare("UPDATE " . TABLE_USUARIOS . " SET nombre_usuario=? WHERE id_usuario=?");
        $stmt->bind_param("si", $nuevo_nombre_usuario, $id_usuario);
        return $stmt->execute();
    }

    // Función para actualizar la contraseña de un usuario específico
    public function actualizarContrasena($id_usuario, $hashed_password)
    {
        $stmt = $this->conexion->prepare("UPDATE " . TABLE_USUARIOS . " SET contrasena=? WHERE id_usuario=?");
        $stmt->bind_param("si", $hashed_password, $id_usuario);
        return $stmt->execute();
    }

    public function actualizarPreguntasSeguridad($id_usuario, $pregunta_1, $pregunta_2, $pregunta_3)
    {
        $stmt = $this->conexion->prepare("UPDATE " . TABLE_USUARIOS . " SET pregunta_seguridad_1 = ?, pregunta_seguridad_2 = ?, pregunta_seguridad_3 = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $pregunta_1, $pregunta_2, $pregunta_3, $id_usuario);
        return $stmt->execute();
    }

    public function actualizarUltimoInicioSesion($id_usuario, $fecha)
    {
        $stmt = $this->conexion->prepare("UPDATE " . TABLE_USUARIOS . " SET ultimo_inicio_sesion = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $fecha, $id_usuario);
        return $stmt->execute();
    }

    // Función para obtener el ID del último registro insertado en la base de datos
    public function getLastInsertId()
    {
        return $this->conexion->insert_id;
    }
}
