<?php
require_once '../config/conexion.php';
require_once '../models/libro.php';

class LibroDAO
{
    private $conexion;

    // Función para inicializar la conexión a la base de datos utilizando MySQLi
    public function __construct()
    {
        $this->conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    // Función para obtener todos los libros de la base de datos
    public function getAllLibros()
    {
        $sql = "SELECT * FROM " . TABLE_LIBROS;
        $result = $this->conexion->query($sql);
        $libros = [];
        while ($row = $result->fetch_assoc()) {
            $libro = new Libro(
                $row['titulo'],
                $row['autor'],
                $row['genero'],
                $row['ano_publicacion'],
                $row['estado'],
                $row['sinopsis'],
                $row['portada'],
                $row['fecha_registro']
            );
            $libro->setId_libro($row['id_libro']);
            $libros[] = $libro;
        }
        return $libros;
    }

    // Función para crear un nuevo libro en la base de datos
    public function crearLibro($libro)
    {
        $stmt = $this->conexion->prepare("INSERT INTO " . TABLE_LIBROS . " (titulo, autor, genero, ano_publicacion, estado, sinopsis, portada, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Asignar los valores a variables locales
        $titulo = $libro->getTitulo();
        $autor = $libro->getAutor();
        $genero = $libro->getGenero();
        $ano_publicacion = $libro->getAno_publicacion();
        $estado = $libro->getEstado();
        $sinopsis = $libro->getSinopsis();
        $portada = $libro->getPortada();
        $fecha_registro = $libro->getFecha_registro();

        // Pasar las variables locales a bind_param
        $stmt->bind_param("sssissss", $titulo, $autor, $genero, $ano_publicacion, $estado, $sinopsis, $portada, $fecha_registro);

        return $stmt->execute();
    }

    // Función para obtener un libro de la base de datos por su ID
    public function getLibroById($id_libro)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM " . TABLE_LIBROS . " WHERE id_libro = ?");
        $stmt->bind_param("i", $id_libro);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if ($data) {
            $libro = new Libro(
                $data['titulo'],
                $data['autor'],
                $data['genero'],
                $data['ano_publicacion'],
                $data['estado'],
                $data['sinopsis'],
                $data['portada'],
                $data['fecha_registro']
            );
            $libro->setId_libro($data['id_libro']);
            return $libro;
        }
        return null;
    }

    // Función para actualizar los datos de un libro existente en la base de datos
    public function actualizarLibro($id_libro, $titulo, $autor, $genero, $ano_publicacion, $estado, $sinopsis, $portada)
    {
        $stmt = $this->conexion->prepare("UPDATE " . TABLE_LIBROS . " SET titulo=?, autor=?, genero=?, ano_publicacion=?, estado=?, sinopsis=?, portada=? WHERE id_libro=?");
        $stmt->bind_param("sssisssi", $titulo, $autor, $genero, $ano_publicacion, $estado, $sinopsis, $portada, $id_libro);
        return $stmt->execute();
    }

    // Función para eliminar un libro de la base de datos por su ID
    public function eliminarLibro($id_libro)
    {
        $stmt = $this->conexion->prepare("DELETE FROM " . TABLE_LIBROS . " WHERE id_libro=?");
        $stmt->bind_param("i", $id_libro);
        return $stmt->execute();
    }

    // Función para verificar si ya existe un libro con el mismo título
    public function existeTitulo($titulo, $id_libro = null)
    {
        $sql = "SELECT COUNT(*) as total FROM " . TABLE_LIBROS . " WHERE titulo = ?";

        // Si se está actualizando un libro, se excluye el libro actual del chequeo
        if ($id_libro !== null) {
            $sql .= " AND id_libro != ?";
        }

        $stmt = $this->conexion->prepare($sql);

        // Asignar parámetros basados en si es una creación o actualización
        if ($id_libro !== null) {
            $stmt->bind_param("si", $titulo, $id_libro);
        } else {
            $stmt->bind_param("s", $titulo);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Si el total es mayor que 0, significa que ya existe un libro con ese título
        return $data['total'] > 0;
    }

    // Función para obtener el ID del último registro insertado en la base de datos
    public function getLastInsertId()
    {
        return $this->conexion->insert_id;
    }
}
