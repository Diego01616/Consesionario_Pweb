<?php
require_once '../Modelo/Dao_usuario.php';

$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? null;
$rol= $_POST['rol'] ?? '';

switch($accion){
    case 'insertar':
        Dao_usuario::insertar_usaurio();
        break;

    case 'ingresar_usuario':
        Dao_usuario::login();
        break;

    case 'seleccionar_rol':     
        header("Location: ../Vista/Elegir_rol.php");
        exit(); // <- siempre que uses header, añade exit
        break;

    case 'rol_seleccionado':
        session_start();
        if($rol != null){
            $_SESSION['rol'] = $rol;
            echo "rol_ok"; // para manejarlo con JS
        } else {
            echo "error_rol";
        }
        break;

    default:
        echo 'acción no especificada';    
}

?>