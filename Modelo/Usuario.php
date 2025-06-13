<?php

    class Usuario{
        public $idUsuario;
        public $nombre;
        public $apellido;
        public $correo;
        public $telefono;
        public $contraseña;


        public function usuario ($idUsuario,$nombre,$apellido,$correo,$telefono,$contraseña){
           $this->idUsuario= $idUsuario;
           $this->nombre= $nombre;
           $this->apellido= $apellido;
           $this->correo= $correo;
           $this->telefono= $telefono;
           $this->contraseña= $contraseña;
        }
        




    }
?>