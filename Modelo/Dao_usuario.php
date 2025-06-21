<?php
include_once 'Usuario.php';
include_once 'Conexion.php';
include_once 'Usuario.php';
//$resp=Dao_usuario::lista_usuario();
class Dao_usuario
{

    public static function buscarid($idusario, $correo)
    {
        $conexion = Conexion::conectar();
        $check = $conexion->prepare("SELECT idUsuario FROM usuario WHERE idUsuario = ? OR correo = ?");
        $check->bind_param("is", $idusario, $correo);
        $check->execute();
        $check->store_result(); //resultado completo de la consulta 

        if ($check->num_rows > 0) {
            echo "El ID o el correo ya están registrados en la base de datos.";
            return false;
        } else {
            return true;
        }
    }



    public static function logfinal($correo, $contraseña)
    {
        $conexion = Conexion::conectar();
        $check = $conexion->prepare("SELECT contraseña FROM usuario WHERE correo = ?");
        $check->bind_param("s", $correo);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {
            return false; // correo no encontrado
        }

        $check->bind_result($hashGuardado);
        $check->fetch();

        if (password_verify($contraseña, $hashGuardado)) {
            return true; // contraseña correcta
        } else {
            return false; // contraseña incorrecta
        }
    }

    public static function buscar_correo(){
        $conexion = Conexion::conectar();
        $correo =($_POST['correo']);
        $check = $conexion->prepare("SELECT idusuario FROM usuario WHERE correo = ?");
        $check->bind_param("s", $correo);
        $check->execute();
        $check->store_result();//almacena el resultado de la consulta para poder usar funciones como contar la cantidad de resultados

        if ($check->num_rows === 0) {
            echo ("no");
          
            return; 
        }else if($check->num_rows === 1){
            session_start();
            $check->bind_result($id);
            $check->fetch();
            $correo_rcover='';
            $_SESSION['cor_cover']=$correo_rcover;
            $_SESSION['id_rec']=$id;
            echo ("encontrado");
        }

    }
    public static function login(){
        session_start();
        $conexion = Conexion::conectar();
        $contraseña = ($_POST['contraseña']);
        $correo = trim($_POST['correo']);
        $dominiosPermitidos = ['@gmail.com', '@hotmail.com', '@outlook.com'];
        $correoValido = false;
        foreach ($dominiosPermitidos as $dominio) {
            if (str_ends_with($correo, $dominio)) {
                $correoValido = true;
                break;
            }
        }
        if (!$correoValido || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "Correo inválido o dominio no permitido (solo gmail, hotmail, outlook).";
            return false;
        }

        if (!self::logfinal($correo, $contraseña)) {
            echo "Correo o contraseña incorrectos";
            return false;
        } else {
            
            $sql = "SELECT u.nombre,u.idusuario,u.contraseña,u.correo,ur.rol_idrol FROM usuario as u JOIN usuario_rol as ur on (ur.usuario_idusuario=u.idUsuario) WHERE correo = ?";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            // Aquí inicializas $usuario con la fila del resultado
            $roles=[];
            $usuario=null;
            //$usuario = $resultado->fetch_assoc();
            while($fila=$resultado->fetch_assoc()){
                if($usuario===null){
                    $usuario=[
                        'id' => $fila['idusuario'],
                        'nombre' => $fila['nombre'],
                        'email' => $fila['correo']
                        
                    ];
                }
                $roles[]=$fila['rol_idrol'];
                
            }
            if($usuario!==null){
                $_SESSION['usuario']=$usuario;
                $_SESSION['roles']=$roles;
                if(count($roles)>1){
                    echo ">1";

                    return $roles;
                }
                if(count($roles)===1){
                    $_SESSION['rol']='Cliente';
                    echo "solo un rol";
                }

                
                //header("Location: Elegir_rol.php");
                /*$guardad='';
                foreach($_SESSION['roles'] as $rol){
                    $guardad.=$rol;
                }

                echo "Inicio de sesión exitoso el usuario ".$guardad;*/
            }
            
            return true;
           // header("Location: Nav.php");
            exit();
        }
    }    
    
    public static function reestablecer_clave(){
        session_start();
        $conexion = Conexion::conectar();
        $contraseña = ($_POST['password']);
        $id =$_SESSION['id_rec'];
        $contraseñahash = password_hash($contraseña,PASSWORD_DEFAULT);
        $sql ="UPDATE usuario set contraseña = ? where idUsuario = ?";
        $stmt = $conexion->prepare($sql);
        //echo("Contraseña actualizada".$id);
        $stmt ->bind_param("si",$contraseñahash,$id);
        if($stmt->execute()){
            echo("Contraseña actualizada");
            unset($_SESSION['id_rec']);
        }else{
            echo("No se pudo actualizar");
        }       
    }
    public static function insertar_usaurio()
    {   
        $rol=3;
        $conexion = Conexion::conectar();
        $id = ($_POST['id']);
        $nombre = ($_POST['nombre']);
        $apellido = ($_POST['apellido']);
        $correo = ($_POST['correo']);
        $telefono = ($_POST['telefono']);
        $contraseña = ($_POST['contraseña']);
        //Comprobaciones de los tipos de datos y requisitos de atributos
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            echo "Error inesperado...El nombre solo debe contener letras.";
            return false;
        }
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {
            echo "Error inesperado...El apellido solo debe contener letras.";
            return false;
        }
        if (!preg_match("/^[0-9]{7,15}$/", $telefono)) {
            echo "Error inesperado...El teléfono solo debe contener números (7 a 15 dígitos).";
            return false;
        }

        $dominiosPermitidos = ['@gmail.com', '@hotmail.com', '@outlook.com'];
        $correoValido = false;
        foreach ($dominiosPermitidos as $dominio) {
            if (str_ends_with($correo, $dominio)) {
                $correoValido = true;
                break;
            }
        }

        if (!$correoValido || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "Correo inválido o dominio no permitido (solo gmail, hotmail, outlook).";
            return false;
        }
        if (strlen($contraseña) < 8) {
            echo "La contraseña debe tener al menos 8 caracteres.";
            return false;
        }
        $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

        if (self::buscarid($id, $correo) == false) {
        } else {
            $stmt = $conexion->prepare("INSERT INTO usuario (idusuario,nombre,apellido,correo,telefono,contraseña) 
                Values (?,?,?,?,?,?)");   
            $stmt->bind_param("isssss", $id, $nombre, $apellido, $correo, $telefono, $contraseñaHash);
            $stmt2 = $conexion->prepare("INSERT INTO Usuario_rol (rol_idrol,usuario_idusuario) 
                Values (?,?)");
            $stmt2->bind_param("ii",$rol,$id);
            if ($stmt->execute()&&$stmt2->execute()) {
                echo "Usuario registrado";
            } else {
                echo "Error al registrar" . $stmt->error;
            }
        }
    }

    public static function actualizar(){
        $conexion = Conexion::conectar();
        $id = ($_POST['id']);
        $nombre = ($_POST['nombre']);
        $apellido = ($_POST['apellido']);
        $correo = ($_POST['correo']);
        $telefono = ($_POST['telefono']);

        $stmt = $conexion->prepare("UPDATE usuario SET nombre = ? ,apellido= ?, correo=?,telefono=? where idUsuario=?");
        $stmt->bind_param("ssssi",$nombre,$apellido,$correo,$telefono,$id);
        if($stmt->execute()){
            echo('actualizado');
        }else{
            echo('no');
        }
    }

    public static function eliminar(){
        $conexion = Conexion::conectar();
        $rol=3;
        $id = ($_POST['id']);
        $stmt = $conexion->prepare("DELETE from usuario_rol where usuario_idusuario=? && rol_idrol=?");
        $stmt2= $conexion->prepare("DELETE from usuario where idusuario=?");
        $stmt->bind_param("ii",$id,$rol);
        $stmt2->bind_param("i",$id);
        if($stmt->execute() && $stmt2->execute()){
            echo('eliminado');
        }else{
            echo('no');
        }
    }

   public static function lista_usuario(){
    $conexion = Conexion::conectar();
    $result =$conexion->query("SELECT u.idUsuario, u.nombre, u.apellido, u.correo, u.telefono FROM usuario AS u
                                    WHERE u.idUsuario NOT IN (SELECT usuario_idusuario FROM usuario_rol WHERE rol_idrol = 1)");
    $usuarios = [];

    while ($fila = $result->fetch_assoc()){
        $usuarios [] = Usuario::list_user(
            $fila['idUsuario'],
            $fila['nombre'],
            $fila['apellido'],
            $fila['correo'],
            $fila['telefono']   
        );

    }
    /*FORMA DE SABER QUE TRAE EL OBJETO CORRER LA VARIABLE DEL PRINCIPIO DE DOCUMENTO
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'listados',
        'usuarios' => $usuarios
    ]);
    exit;*/
    //echo("listados");
    return $usuarios;

   }
}
