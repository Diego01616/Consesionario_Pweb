
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['pre_emp'])) {
    header('Location: Start_sesion.php');
    exit;
}
unset($_SESSION['pre_emp']);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/estilo.css">
    
    <title>Login Admin</title>
</head>
<body>
    
    <div class="divMajoradmin">
        <div class="divlogin">
        <form class="form-login" action="">    
            <div class="contform">
                <h2>Bienvenido Administrador</h2>
                <div class="form-group2">
                    <div class="form2-group2">
                         <a><img src="./img/User_admin.svg" class="icon-admin"></a>
                         <span class="hlink">Nombre de usuario</span>
                         <input type="text" class="inputAdmin" name="nickusuario" placeholder="Digite su usuario" required>
                    </div>
                </div>
                <div class="form-group2">
                    <span class="hlink">Contraseña</span><input type="password" name="password" class="inputAdmin" placeholder="Digite su contraseña" required>
                </div>
                <div class="divlinks">
                    <a href="https://www.youtube.com" class="link">Olvidé mi contraseña</a>
                </div>
                <div class="buttonlogin2">
                            <button class="inicio">Iniciar Sesión</button>
                </div>
                <div class="divlinks-nouser">
                           <a class="linktxt">Nuevo Administrador</a>
                           <a href="https://www.youtube.com" class="link"><strong>Aquí</strong></a>
                    </div>  
        </form>
            <a href="Start_sesion.php"><img src="./img/arrow-left-solid.svg" class="icon-hover"></a>
        </div>
    </div>  
   


</body>
</html>