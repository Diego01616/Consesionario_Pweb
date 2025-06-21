<?php
include_once 'Conexion.php';
include_once 'Empleado.php';


class Dao_empleado
{

    public static function buscarid($idusario)
    {
        $conexion = Conexion::conectar();
        $check = $conexion->prepare("SELECT idempleado FROM empleado WHERE idempleado = ?");
        $check->bind_param("i", $idusario);
        $check->execute();
        $check->store_result(); //resultado completo de la consulta 

        if ($check->num_rows > 0) {
            echo "El ID o el correo ya están registrados en la base de datos.";
            return false;
        } else {
            return true;
        }
    }

    public static function insertar_empleado()
    {
        $rol = 2;
        $conexion = Conexion::conectar();
        $id = ($_POST['id']);
        $sueldo = ($_POST['salary']);
        $idsucursal = ($_POST['id_sucursal']);
        $fecha = date('Y-m-d');
        $estado = 1;
        $stmt = $conexion->prepare("INSERT INTO empleado (idEmpleado,fecha_ingreso,sueldo,estado,idSucursal,idUsuario) VALUES (?,?,?,?,?,?)");
        $stmt2 = $conexion->prepare("INSERT INTO Usuario_rol (rol_idrol,usuario_idusuario) 
                Values (?,?)");
        $stmt->bind_param("isdiii", $id, $fecha, $sueldo, $estado, $idsucursal, $id);
        $stmt2->bind_param("ii", $rol, $id);
        if(self::buscarid($id)==true){
            if ($stmt->execute() && $stmt2->execute()) {
                echo "Usuario registrado";
            } else {
                echo "Error al registrar" . $stmt->error;
            }
        }else{
            echo "ya existe";
        }
        
    }

    public static function actualizar(){
        $conexion = Conexion::conectar();
        $id = ($_POST['id']);
        $salario = ($_POST['salario']);
        $estado = ($_POST['estado']);
        $sucursal = ($_POST['sucursal']);
        $stmt = $conexion->prepare("UPDATE empleado SET sueldo = ? ,estado= ?, idsucursal=? where idempleado=?");
        $stmt->bind_param("diii",$salario,$estado,$sucursal,$id);
        if($stmt->execute()){
            echo('actualizado');
        }else{
            echo('no');
        }
    }
    
    public static function eliminar(){
        $conexion = Conexion::conectar();
        $id = ($_POST['id']);
        $stmt = $conexion->prepare("DELETE from empleado where idEmpleado=?");
        $stmt->bind_param("i",$id);
        if($stmt->execute()){
            echo('eliminado');
        }else{
            echo('no');
        }

    }

    public static function lista_empleado()
    {
        $conexion = Conexion::conectar();
        /* $result =$conexion->query("SELECT e.idEmpleado, u.nombre, u.apellido, e.sueldo,e.fecha_ingreso ,s.nombre as sucursal 
            FROM empleado as e join usuario as u on (e.idUsuario=u.idUsuario) 
            JOIN sucursal as s on(e.idSucursal=s.idSucursal); ");*/
        $result = $conexion->query("SELECT idempleado,fecha_ingreso,sueldo,estado,idsucursal from empleado");
        $empleados = [];
        while ($fila = $result->fetch_assoc()) {
            $empleados[] = Empleado::list_empleado(
                $fila['idempleado'],
                $fila['fecha_ingreso'],
                $fila['sueldo'],
                $fila['estado'],
                $fila['idsucursal'],
            );
        }

        return $empleados;
    }
}
