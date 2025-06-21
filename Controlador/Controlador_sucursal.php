<?php 
require_once '../Modelo/Dao_sucursal.php';

$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? null;

switch($accion){
    case 'insertar':
        Dao_sucursal::insertar_sucursal();
        break;
    case 'actualizar':
       Dao_sucursal::actualizar();
        break;    
    case 'eliminar':
        Dao_sucursal::eliminar();
        break;    
    default:
        echo 'acción no especificada';
}

?>