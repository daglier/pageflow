<?php
require_once '../config/conexion.php';
require_once '../models/prestamo.php';

class PrestamoDAO
{
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos utilizando MySQLi
    public function __construct()
    {
        $this->conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    // Función para obtener todos los préstamos de la base de datos
    public function getAllPrestamos()
    {
        $sql = "SELECT * FROM " . TABLE_PRESTAMOS;
        $result = $this->conexion->query($sql);
        $prestamos = [];
        while ($row = $result->fetch_assoc()) {
            $prestamo = new Prestamo(
                $row['id_usuario'],
                $row['id_libro'],
                $row['fecha_prestamo'],
                $row['fecha_devolucion_estimada'],
                $row['fecha_devolucion'],
                $row['estado']
            );
            $prestamo->setId_prestamo($row['id_prestamo']);
            $prestamos[] = $prestamo;
        }
        return $prestamos;
    }

    // Función para crear un nuevo préstamo en la base de datos
    public function crearPrestamo($prestamo)
    {
        $stmt = $this->conexion->prepare("INSERT INTO " . TABLE_PRESTAMOS . " (id_usuario, id_libro, fecha_prestamo, fecha_devolucion_estimada, estado) VALUES (?, ?, ?, ?, ?)");

        // Asignar los valores a variables locales
        $id_usuario = $prestamo->getId_usuario();
        $id_libro = $prestamo->getId_libro();
        $fecha_prestamo = $prestamo->getFecha_prestamo();
        $fecha_devolucion_estimada = $prestamo->getFecha_devolucion_estimada();
        $estado = $prestamo->getEstado();

        // Pasar las variables locales a bind_param
        $stmt->bind_param("iisss", $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion_estimada, $estado);

        return $stmt->execute();
    }

    // Función para obtener un préstamo de la base de datos por su ID
    public function getPrestamoById($id_prestamo)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM " . TABLE_PRESTAMOS . " WHERE id_prestamo = ?");
        $stmt->bind_param("i", $id_prestamo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if ($data) {
            $prestamo = new Prestamo(
                $data['id_usuario'],
                $data['id_libro'],
                $data['fecha_prestamo'],
                $data['fecha_devolucion_estimada'],
                $data['fecha_devolucion'],
                $data['estado']
            );
            $prestamo->setId_prestamo($data['id_prestamo']);
            return $prestamo;
        }
        return null;
    }

    // Función para actualizar los datos de un préstamo existente en la base de datos
    public function actualizarPrestamo($id_prestamo, $estado)
    {
        $stmt = $this->conexion->prepare("UPDATE " . TABLE_PRESTAMOS . " SET estado = ? WHERE id_prestamo = ?");
        $stmt->bind_param("ii", $estado, $id_prestamo);
        return $stmt->execute();
    }

    // Función para eliminar un préstamo de la base de datos por su ID
    public function eliminarPrestamo($id_prestamo)
    {
        $stmt = $this->conexion->prepare("DELETE FROM " . TABLE_PRESTAMOS . " WHERE id_prestamo = ?");
        $stmt->bind_param("i", $id_prestamo);
        return $stmt->execute();
    }

    // Función en el DAO para verificar si el usuario tiene un préstamo activo del libro
    public function verificarPrestamoExistente($id_usuario, $id_libro)
    {
        $sql = "SELECT COUNT(*) as total FROM " . TABLE_PRESTAMOS . " WHERE id_usuario = ? AND id_libro = ? AND estado = 'en_prestamo'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_libro);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['total'] > 0;
    }

    // Función para obtener un préstamo activo del usuario para un libro
    public function obtenerPrestamoActivo($id_usuario, $id_libro)
    {
        $sql = "SELECT * FROM " . TABLE_PRESTAMOS . " WHERE id_usuario = ? AND id_libro = ? AND estado = 'en_prestamo' LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_libro);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Devuelve el préstamo activo como array asociativo
    }

    // Función para devolver un libro y actualizar el estado y la fecha de devolución
    public function devolverLibro($id_prestamo, $fecha_devolucion)
    {
        $sql = "UPDATE " . TABLE_PRESTAMOS . " SET estado = 'devuelto', fecha_devolucion = ? WHERE id_prestamo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $fecha_devolucion, $id_prestamo);
        return $stmt->execute(); // Devuelve true si la actualización fue exitosa
    }

    // Método para obtener los préstamos asociados a un usuario específico
    public function getPrestamosPorIdUsuario($id_usuario)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM prestamos WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $prestamos = [];
        while ($row = $result->fetch_assoc()) {
            $prestamos[] = $row;
        }

        return $prestamos;
    }

    // Función para obtener el ID del último registro insertado en la base de datos
    public function getLastInsertId()
    {
        return $this->conexion->insert_id;
    }
}
