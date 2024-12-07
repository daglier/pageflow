<?php

class Libro
{
    private $id_libro;
    private $titulo;
    private $autor;
    private $genero;
    private $ano_publicacion;
    private $estado;
    private $sinopsis;
    private $portada;
    private $fecha_registro;

    // Constructor para inicializar un libro con los datos proporcionados
    public function __construct($titulo, $autor, $genero, $ano_publicacion, $estado, $sinopsis, $portada, $fecha_registro)
    {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->genero = $genero;
        $this->ano_publicacion = $ano_publicacion;
        $this->estado = $estado;
        $this->sinopsis = $sinopsis;
        $this->portada = $portada;
        $this->fecha_registro = $fecha_registro;
    }

    // Métodos getter y setter para id_libro
    public function getId_libro()
    {
        return $this->id_libro;
    }

    public function setId_libro($id_libro)
    {
        $this->id_libro = $id_libro;
    }

    // Métodos getter y setter para titulo
    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    // Métodos getter y setter para autor
    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor($autor)
    {
        $this->autor = $autor;
    }

    // Métodos getter y setter para genero
    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    // Métodos getter y setter para ano_publicacion
    public function getAno_publicacion()
    {
        return $this->ano_publicacion;
    }

    public function setAno_publicacion($ano_publicacion)
    {
        $this->ano_publicacion = $ano_publicacion;
    }

    // Métodos getter y setter para estado
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    // Métodos getter y setter para sinopsis
    public function getSinopsis()
    {
        return $this->sinopsis;
    }

    public function setSinopsis($sinopsis)
    {
        $this->sinopsis = $sinopsis;
    }

    // Métodos getter y setter para portada
    public function getPortada()
    {
        return $this->portada;
    }

    public function setPortada($portada)
    {
        $this->portada = $portada;
    }

    // Métodos getter y setter para fecha_registro
    public function getFecha_registro()
    {
        return $this->fecha_registro;
    }

    public function setFecha_registro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }
}
