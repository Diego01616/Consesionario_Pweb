<?php


class Sucursal
{
    public $id;
    public $nombre;
    public $direccion;
    public function __construct($id, $nombre, $direccion)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }

    public static function list_sucurs($id,$nombre,$direccion){
        $sucursal=new self($id,$nombre,$direccion);
        return $sucursal;
    }   


    
}
