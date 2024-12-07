<?php

class Usuario
{
    private $id_usuario;
    private $nombre_usuario;
    private $contrasena;
    private $rol;
    private $fecha_registro;
    private $pregunta_seguridad_1;
    private $pregunta_seguridad_2;
    private $pregunta_seguridad_3;
    private $ultimo_inicio_sesion;

    // Constructor para inicializar un usuario con los datos proporcionados
    public function __construct($nombre_usuario, $contrasena, $rol, $fecha_registro, $pregunta_seguridad_1, $pregunta_seguridad_2, $pregunta_seguridad_3, $ultimo_inicio_sesion)
    {
        $this->nombre_usuario = $nombre_usuario;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
        $this->fecha_registro = $fecha_registro;
        $this->pregunta_seguridad_1 = $pregunta_seguridad_1;
        $this->pregunta_seguridad_2 = $pregunta_seguridad_2;
        $this->pregunta_seguridad_3 = $pregunta_seguridad_3;
        $this->ultimo_inicio_sesion = $ultimo_inicio_sesion;
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

    // Métodos getter y setter para nombre_usuario
    public function getNombre_usuario()
    {
        return $this->nombre_usuario;
    }

    public function setNombre_usuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    // Métodos getter y setter para contrasena
    public function getContrasena()
    {
        return $this->contrasena;
    }

    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    }

    // Métodos getter y setter para rol
    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
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

    // Métodos getter y setter para pregunta_seguridad_1
    public function getPregunta_seguridad_1()
    {
        return $this->pregunta_seguridad_1;
    }

    public function setPregunta_seguridad_1($pregunta_seguridad_1)
    {
        $this->pregunta_seguridad_1 = $pregunta_seguridad_1;
    }

    // Métodos getter y setter para pregunta_seguridad_2
    public function getPregunta_seguridad_2()
    {
        return $this->pregunta_seguridad_2;
    }

    public function setPregunta_seguridad_2($pregunta_seguridad_2)
    {
        $this->pregunta_seguridad_2 = $pregunta_seguridad_2;
    }

    // Métodos getter y setter para pregunta_seguridad_3
    public function getPregunta_seguridad_3()
    {
        return $this->pregunta_seguridad_3;
    }

    public function setPregunta_seguridad_3($pregunta_seguridad_3)
    {
        $this->pregunta_seguridad_3 = $pregunta_seguridad_3;
    }

    // Métodos getter y setter para fecha_registro
    public function getUltimo_inicio_sesion()
    {
        return $this->ultimo_inicio_sesion;
    }

    public function setUltimo_inicio_sesion($ultimo_inicio_sesion)
    {
        $this->ultimo_inicio_sesion = $ultimo_inicio_sesion;
    }
}
