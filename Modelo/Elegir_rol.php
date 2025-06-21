<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['roles'])) {
    echo "<div class='title-form'><a>Selecciona tu rol</a></div>";
    echo "<form class='form-n'>";
    foreach ($_SESSION['roles'] as $rol_id) {
        $nom = '';
        if ($rol_id == 1) $nom = "Administrador";
        if ($rol_id == 2) $nom = "Empleado";
        if ($rol_id == 3) $nom = "Cliente";
        echo "<input class='button-n' type='submit' value='$nom'>";
    }
    echo "</form>";
}
?>
