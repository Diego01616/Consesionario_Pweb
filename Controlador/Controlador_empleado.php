<?php 
require_once '../Modelo/Dao_empleado.php';

$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? null;
$rol= $_POST['rol'] ?? '';

switch($accion){
    case 'insertar':
        Dao_empleado::insertar_empleado();
        break;
    case 'actualizar':
        Dao_empleado::actualizar();
        break;    
    case 'eliminar':
        Dao_empleado::eliminar();
        break;    
    default:
        echo 'acción no especificada';
}


?>