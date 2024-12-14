<?php
require_once '../models/prestamo.php';
require_once '../models/dao/prestamoDAO.php';

class PrestamoController
{
    private $prestamoDAO;

    public function __construct()
    {
        $this->prestamoDAO = new PrestamoDAO();
    }

    // Función para listar todos los préstamos
    public function listarPrestamos()
    {
        $prestamos = $this->prestamoDAO->getAllPrestamos();
        echo json_encode(['success' => true, 'prestamos' => $prestamos]);
    }

    // Función para crear un nuevo préstamo
    public function crearPrestamo()
    {
        // Recibir datos del formulario
        $id_usuario = $_POST['id_usuario'];
        $id_libro = $_POST['id_libro'];
        $fecha_prestamo = date('Y-m-d');
        $fecha_devolucion_estimada = $_POST['fecha_devolucion_estimada'];

        $nuevoPrestamo = new Prestamo($id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion_estimada);

        if ($this->prestamoDAO->crearPrestamo($nuevoPrestamo)) {
            $id = $this->prestamoDAO->getLastInsertId();
            $nuevoPrestamo->setId_prestamo($id);

            echo json_encode(['success' => true, 'message' => 'Préstamo creado exitosamente.', 'prestamo' => [
                'id' => $nuevoPrestamo->getId_prestamo(),
                'id_usuario' => $nuevoPrestamo->getId_usuario(),
                'id_libro' => $nuevoPrestamo->getId_libro(),
                'fecha_prestamo' => $nuevoPrestamo->getFecha_prestamo(),
                'fecha_devolucion_estimada' => $nuevoPrestamo->getFecha_devolucion_estimada(),
                'estado' => $nuevoPrestamo->getEstado()
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el préstamo.']);
        }
    }

    // Función para obtener todos los préstamos
    public function obtenerPrestamos()
    {
        $prestamos = $this->prestamoDAO->getAllPrestamos();
        $prestamosArray = [];

        foreach ($prestamos as $prestamo) {
            $prestamosArray[] = [
                'id' => $prestamo->getId_prestamo(),
                'id_libro' => $prestamo->getId_libro(),
                'id_usuario' => $prestamo->getId_usuario(),
                'fecha_prestamo' => $prestamo->getFecha_prestamo(),
                'fecha_devolucion_estimada' => $prestamo->getFecha_devolucion_estimada(),
                'fecha_devolucion' => $prestamo->getFecha_devolucion(),
                'estado' => $prestamo->getEstado()
            ];
        }

        echo json_encode(['success' => true, 'prestamos' => $prestamosArray]);
    }

    // Nueva para obtener préstamos por usuario
    public function obtenerPrestamosPorIdUsuario()
    {
        $id_usuario = $_GET['id_usuario'];

        $prestamos = $this->prestamoDAO->getPrestamosPorIdUsuario($id_usuario);

        // Verificar si se encontraron préstamos
        if ($prestamos) {
            echo json_encode(['success' => true, 'prestamos' => $prestamos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron préstamos para este usuario.']);
        }
    }

    // Función para obtener un préstamo específico por su ID
    public function obtenerPrestamo()
    {
        $id_prestamo = $_GET['id_prestamo'];

        $prestamo = $this->prestamoDAO->getPrestamoById($id_prestamo);

        if ($prestamo) {
            echo json_encode(['success' => true, 'prestamo' => $prestamo]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Préstamo no encontrado.']);
        }
    }

    // Función para devolver un libro
    public function devolverLibro()
    {
        $id_usuario = $_POST['id_usuario'];
        $id_libro = $_POST['id_libro'];
        $fecha_devolucion = date('Y-m-d');

        // Verificar si existe el préstamo activo del libro para el usuario
        $prestamo = $this->prestamoDAO->obtenerPrestamoActivo($id_usuario, $id_libro);

        if ($prestamo) {
            $id_prestamo = $prestamo['id_prestamo'];

            if ($this->prestamoDAO->devolverLibro($id_prestamo, $fecha_devolucion)) {
                echo json_encode(['success' => true, 'message' => 'Libro devuelto exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al devolver el libro.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró un préstamo activo para este libro y usuario.']);
        }
    }

    // Función para eliminar un préstamo por su ID
    public function eliminarPrestamo()
    {
        $id_prestamo = $_POST['id_prestamo'];

        if ($this->prestamoDAO->eliminarPrestamo($id_prestamo)) {
            echo json_encode(['success' => true, 'message' => 'Préstamo eliminado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el préstamo.']);
        }
    }

    // Función para verificar si el usuario ya tiene el libro
    public function verificarPrestamoExistente()
    {
        $id_usuario = $_GET['id_usuario'];
        $id_libro = $_GET['id_libro'];

        // Verificar si el usuario tiene un préstamo activo del libro
        $prestamoExistente = $this->prestamoDAO->verificarPrestamoExistente($id_usuario, $id_libro);

        if ($prestamoExistente) {
            echo json_encode(['success' => true, 'existe' => true]);
        } else {
            echo json_encode(['success' => true, 'existe' => false]);
        }
    }
}


// Instanciar el controlador
$controller = new PrestamoController();

// Llama al método del controlador basado en la acción solicitada en la URL
$action = $_GET['action'];
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
