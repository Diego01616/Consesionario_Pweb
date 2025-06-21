<?php
require_once '../Modelo/Dao_usuario.php';
require_once '../Modelo/Envio_correo.php';
require_once '../Modelo/Verificar_codigo.php';

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
    case 'recuperar':
        Dao_usuario::buscar_correo();
        break;
    case 'enviar_codigo':
        Envio_correo::enviar_correo();
        break; 
    case 'verificar_codigo':
        Verificar_codigo::verificar();
        break;
    case 'reestablecer':
        Dao_usuario::reestablecer_clave();
        break;
    case 'actualizar':
        Dao_usuario::actualizar();
        break;  
    case 'eliminar':
        Dao_usuario::eliminar();
        break;
    case 'contratar':
       // Dao_usuario::contratar();
        break;                        
    default:
        echo 'acción no especificada';    
}

?>