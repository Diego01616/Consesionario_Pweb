<?php
    include_once 'Conexion.php';
    include_once 'Sucursal.php';
    class Dao_sucursal{

        public static function actualizar(){
            $conexion = Conexion::conectar();
            $id = ($_POST['id']);
            $nombre = ($_POST['nombre']);
            $direccion = ($_POST['direccion']);
            $stmt = $conexion->prepare("UPDATE sucursal SET nombre= ?, direccion=? where idsucursal=?");
            $stmt->bind_param("ssi",$nombre,$direccion,$id);
            if($stmt->execute()){
                echo('actualizado');
            }else{
                echo('no');
            }
        }

        public static function obtener_sucursal(){
            $conexion = Conexion::conectar();
            $result = $conexion->query("SELECT idSucursal,nombre,direccion from sucursal");

            $sucursal= [];
            while($fila = $result->fetch_assoc()){
                $sucursal []= Sucursal::list_sucurs(
                    $fila['idSucursal'],
                    $fila['nombre'],
                    $fila['direccion']
                );
            }
             return $sucursal;
        }

        public static function eliminar(){
            $conexion = Conexion::conectar();
            $id = ($_POST['id']);
            $stmt = $conexion->prepare("DELETE from sucursal where idsucursal=?");
            $stmt->bind_param("i",$id);
            if($stmt->execute()){
                echo('eliminado');
            }else{
                echo('no');
            }

        }

        public static function insertar_sucursal(){
            $conexion = Conexion::conectar();
            $nombre = ($_POST['nombre']);
            $direccion = ($_POST['direccion']);
            $stmt = $conexion->prepare("INSERT INTO sucursal (nombre,direccion) Values (?,?)");
            $stmt->bind_param("ss", $nombre, $direccion);
            if ($stmt->execute()) {
             echo "registrado";
            } else {
            echo "Error al registrar" . $stmt->error;
        }
        }

        

    }


?>