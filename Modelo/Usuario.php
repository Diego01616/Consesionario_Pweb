<?php

class Usuario
{
    public $idUsuario;
    public $nombre;
    public $apellido;
    public $correo;
    public $telefono;
    public $contraseña;


    public function __construct($idUsuario, $nombre, $apellido, $correo, $telefono, $contraseña = null)
    {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->contraseña = $contraseña;
    }
    public static function list_user($idUsuario, $nombre, $apellido, $correo, $telefono)
    {
        //$empleado = new self($idEmpleado, $nombreCompleto, '', $sueldo, null, $centroCosto,$cargo,null,null,null,null,null,null,null,null,null,$riesgo);//se ponen nulos a los datos que no necesitamos para la consulta en caso de ser menos del total de tabla 
        $usuario = new self($idUsuario, $nombre, $apellido, $correo, $telefono);
        return $usuario;
    }
}
