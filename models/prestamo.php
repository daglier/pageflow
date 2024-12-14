<?php

class Prestamo
{
    private $id_prestamo;
    private $id_usuario;
    private $id_libro;
    private $fecha_prestamo;
    private $fecha_devolucion_estimada;
    private $fecha_devolucion;
    private $estado;

    // Constructor para inicializar un préstamo con los datos proporcionados
    public function __construct($id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion_estimada, $fecha_devolucion = null, $estado = 'en_prestamo')
    {
        $this->id_usuario = $id_usuario;
        $this->id_libro = $id_libro;
        $this->fecha_prestamo = $fecha_prestamo;
        $this->fecha_devolucion_estimada = $fecha_devolucion_estimada;
        $this->fecha_devolucion = $fecha_devolucion;
        $this->estado = $estado;
    }

    // Métodos getter y setter para id_prestamo
    public function getId_prestamo()
    {
        return $this->id_prestamo;
    }

    public function setId_prestamo($id_prestamo)
    {
        $this->id_prestamo = $id_prestamo;
    }

    // Métodos getter y setter para id_usuario
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
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

    // Métodos getter y setter para fecha_prestamo
    public function getFecha_prestamo()
    {
        return $this->fecha_prestamo;
    }

    public function setFecha_prestamo($fecha_prestamo)
    {
        $this->fecha_prestamo = $fecha_prestamo;
    }

    // Métodos getter y setter para fecha_devolucion_estimada
    public function getFecha_devolucion_estimada()
    {
        return $this->fecha_devolucion_estimada;
    }

    public function setFecha_devolucion_estimada($fecha_devolucion_estimada)
    {
        $this->fecha_devolucion_estimada = $fecha_devolucion_estimada;
    }

    // Métodos getter y setter para fecha_devolucion
    public function getFecha_devolucion()
    {
        return $this->fecha_devolucion;
    }

    public function setFecha_devolucion($fecha_devolucion)
    {
        $this->fecha_devolucion = $fecha_devolucion;
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

}
