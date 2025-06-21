<?php
//$rest="";
//$rest=Conexion::admin();
class Conexion {

    public static function conectar() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $server = "localhost";
        $user = "root";
        $ps = "";
        $db = "web_concesionario_proy";

        $conexion = new mysqli($server, $user, $ps, $db);

        if ($conexion->connect_errno) {
            die("Conexión fallida a la base de datos: " . $conexion->connect_error);
        } else {
            //echo "Conexión con la base de datos exitosa..."; // opcional
            return $conexion;
        }
    }


    public static function admin(){
        $conexion = Conexion::conectar();
        $clave ='75328';
        $id=10043;
        $telefono='3144260283';
        $correo='administrador@gmail.com';
        $apellido='Bautista Duran';
        $nombre='Jose Diego';
        $rol=1;
        $rol2=2;
        $rol3=3;
        
        $contraseñaHash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuario (idusuario,nombre,apellido,correo,telefono,contraseña) 
                Values (?,?,?,?,?,?)");
        $stmt2 = $conexion->prepare("INSERT INTO Usuario_rol (rol_idrol,usuario_idusuario) 
                Values (?,?)"); 
        $stmt3 = $conexion->prepare("INSERT INTO Usuario_rol (rol_idrol,usuario_idusuario) 
                Values (?,?)"); 
        $stmt4 = $conexion->prepare("INSERT INTO Usuario_rol (rol_idrol,usuario_idusuario) 
                Values (?,?)");       
        $stmt2->bind_param("ii",$rol,$id);
        $stmt3->bind_param("ii",$rol2,$id);  
        $stmt4->bind_param("ii",$rol3,$id);          
        $stmt->bind_param("isssss",$id,$nombre,$apellido,$correo,$telefono,$contraseñaHash);
        $stmt->execute();
        $stmt2->execute();
        $stmt3->execute();
        $stmt4->execute();

        echo('administrador ingresado');
         
    }
   
}
?>