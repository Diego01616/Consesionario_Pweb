<?php
// log-out.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}        // Asegúrate de iniciar la sesión

// 1. Vaciar todas las variables de sesión
$_SESSION = [];

// 2. Eliminar la cookie de sesión (opcional, pero recomendable)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,      // Expira en el pasado
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destruir la sesión en el servidor
session_destroy();

// 4. Redirigir al usuario
header("Location: Start_sesion.php");   // o a Nav.php, index.php, etc.
exit;
