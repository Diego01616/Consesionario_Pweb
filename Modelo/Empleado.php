<?php

class Empleado
{
    public $idempleado;
    public $fecha_ingreso;
    public $sueldo;
    public $estado;
    public $id_sucursal;
    public $id_usuario;
 


    public function __construct($idempleado = null,$fecha_ingreso = null,$sueldo = null,$estado = null,$id_sucursal = null,$id_usuario = null) {
        $this->idempleado = $idempleado;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->sueldo = $sueldo;
        $this->estado = $estado;
        $this->id_sucursal = $id_sucursal;
        $this->id_usuario = $id_usuario;
    }
    public static function list_empleado($idempleado, $fecha_ingreso,$sueldo, $estado, $id_sucursal)
    {
        $usuario = new self($idempleado,$fecha_ingreso, $sueldo, $estado,$id_sucursal );
        return $usuario;
    }
}
