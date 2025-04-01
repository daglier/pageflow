<?php
require_once '../models/libro.php';
require_once '../models/dao/libroDAO.php';

class LibroController
{
    private $libroDAO;

    public function __construct()
    {
        $this->libroDAO = new LibroDAO();
    }

    // Función para listar todos los libros
    public function listarLibros()
    {
        $libros = $this->libroDAO->getAllLibros();
        echo json_encode(['success' => true, 'libros' => $libros]);
    }

    // Función para crear un nuevo libro
    public function crearLibro()
    {
        // Recibir datos del formulario
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $genero = $_POST['genero'];
        $ano_publicacion = $_POST['ano-publicacion'];
        $estado = $_POST['estado'];
        $sinopsis = $_POST['sinopsis'];
        $portada = null;
        $fecha_registro = date('Y-m-d');

        // Verificar si ya existe el libro (título)
        if ($this->libroDAO->existeTitulo($titulo)) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un libro con este título.']);
            return;
        }

        // Crear el libro si no existe
        $nuevoLibro = new Libro($titulo, $autor, $genero, $ano_publicacion, $estado, $sinopsis, $portada, $fecha_registro);

        if ($this->libroDAO->crearLibro($nuevoLibro)) {
            $id = $this->libroDAO->getLastInsertId();
            $nuevoLibro->setId_libro($id);

            echo json_encode(['success' => true, 'message' => 'Libro creado exitosamente.', 'libro' => [
                'id' => $nuevoLibro->getId_libro(),
                'titulo' => $nuevoLibro->getTitulo(),
                'autor' => $nuevoLibro->getAutor(),
                'genero' => $nuevoLibro->getGenero(),
                'ano_publicacion' => $nuevoLibro->getAno_publicacion(),
                'estado' => $nuevoLibro->getEstado(),
                'sinopsis' => $nuevoLibro->getSinopsis(),
                'portada' => $nuevoLibro->getPortada(),
                'fecha_registro' => $nuevoLibro->getFecha_registro()
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el libro.']);
        }
    }

    // Función para obtener todos los libros
    public function obtenerLibros()
    {
        $libros = $this->libroDAO->getAllLibros();
        $librosArray = [];

        foreach ($libros as $libro) {
            $librosArray[] = [
                'id' => $libro->getId_libro(),
                'titulo' => $libro->getTitulo(),
                'autor' => $libro->getAutor(),
                'genero' => $libro->getGenero(),
                'ano_publicacion' => $libro->getAno_publicacion(),
                'estado' => $libro->getEstado(),
                'sinopsis' => $libro->getSinopsis(),
                'portada' => $libro->getPortada(),
                'fecha_registro' => $libro->getFecha_registro()
            ];
        }

        echo json_encode(['success' => true, 'libros' => $librosArray]);
    }

    // Función para obtener un libro específico por su ID
    public function obtenerLibro()
    {
        $id_libro = $_GET['id_libro'];

        $libro = $this->libroDAO->getLibroById($id_libro);

        if ($libro) {
            echo json_encode(['success' => true, 'libro' => $libro]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Libro no encontrado.']);
        }
    }

    // Función para actualizar la información de un libro existente
    public function actualizarLibro()
    {
        $id_libro = $_POST['id'];
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $genero = $_POST['genero'];
        $ano_publicacion = $_POST['ano-publicacion'];
        $estado = $_POST['estado'];
        $sinopsis = $_POST['sinopsis'];
        $portada = null;

        if ($this->libroDAO->actualizarLibro($id_libro, $titulo, $autor, $genero, $ano_publicacion, $estado, $sinopsis, $portada)) {
            echo json_encode(['success' => true, 'message' => 'Libro actualizado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el libro.']);
        }
    }

    // Función para eliminar un libro por su ID
    public function eliminarLibro()
    {
        $id_libro = $_POST['id_libro'];

        if ($this->libroDAO->eliminarLibro($id_libro)) {
            echo json_encode(['success' => true, 'message' => 'Libro eliminado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el libro.']);
        }
    }

    // Función para obtener el título de un libro por su ID
    public function obtenerTituloLibroPorId()
    {
        $id_libro = $_GET['id_libro'];
        $libro = $this->libroDAO->getLibroById($id_libro);

        if ($libro) {
            echo json_encode(['success' => true, 'titulo_libro' => $libro->getTitulo()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Libro no encontrado.']);
        }
    }
}

// Instanciar el controlador
$controller = new LibroController();

// Llama al método del controlador basado en la acción solicitada en la URL
$action = $_GET['action'];
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
