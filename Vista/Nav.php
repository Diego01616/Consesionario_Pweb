<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/estilo.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <title>Document</title>
</head>

<body>
    <header>
        <div class="content-menu">
            <div class="menu-container">
                <a href="Nav.php">logo</a>
                <nav class="nav-principalmenu">
                    <ul>
                        <li><a>chevrolet</a></li>
                        <li><a>Audi</a></li>
                        <li><a>Renault</a></li>
                    </ul>
                </nav>

                <?php
                if (isset($_SESSION['usuario']) && isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador'): ?>

                    <div class="user-menu">
                        <span class="nav-sesion-log">Administrador, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
                        <!-- <a href="logout.php" class="nav-sesion">Cerrar sesión</a> -->
                        <div class="icon-vertical-menu" id="icon-verticalmenu"></div>
                        <ul class="ul-vertical-menu" id="ul-verticalmenu">
                             <li>
                                <a href="Gestion_sucursal.php" onclick="enviarAccion(); return false;">Gestionar Sucursales</a>
                            </li>
                            <li>
                                <a href="Gestion_empleado.php" onclick="enviarAccion(); return false;">Gestionar empleados</a>
                            </li>
                            <li>
                                <a href="Gestion_cliente.php">Gestionar clientes</a>
                            </li>
                            <li>
                                <a href="Log-out.php">Cerrar sesión</a>
                            </li>
                            
                        </ul>
                    </div>


                <?php elseif (isset($_SESSION['usuario']) && isset($_SESSION['rol']) && $_SESSION['rol'] == 'Cliente'): ?>
                    <div class="user-menu">
                        <span class="nav-sesion-log"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
                        <!-- <a href="logout.php" class="nav-sesion">Cerrar sesión</a> -->
                        <div class="icon-vertical-menu" id="icon-verticalmenu"></div>
                        <ul class="ul-vertical-menu" id="ul-verticalmenu">
                            <li>
                                <a href="Log-out.php">Cerrar sesión</a>
                            </li>

                        </ul>

                    </div>
                <?php elseif (isset($_SESSION['usuario']) && isset($_SESSION['rol']) && $_SESSION['rol'] == 'Empleado'): ?>
                    <div class="user-menu">
                        <span class="nav-sesion-log">Empleado,<?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
                        <!-- <a href="logout.php" class="nav-sesion">Cerrar sesión</a> -->
                        <div class="icon-vertical-menu" id="icon-verticalmenu"></div>
                        <ul class="ul-vertical-menu" id="ul-verticalmenu">
                            <li>
                                <a href="Log-out.php">Cerrar sesión</a>
                            </li>

                        </ul>

                    </div>

                <?php else: ?>
                    <a class="nav-sesion" href="Start_sesion.php">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        const verticalmenu = document.getElementById('icon-verticalmenu');
        const ulverticalmenu = document.getElementById('ul-verticalmenu');            

        verticalmenu.addEventListener('click', () => {
            // alert('Click');
            if (ulverticalmenu.style.display === 'block') {
                ulverticalmenu.style.display = 'none';
            } else {
                ulverticalmenu.style.display = 'block';
            }
        });

        document.addEventListener('click', (e) => {

            if (!verticalmenu.contains(e.target) && !ulverticalmenu.contains(e.target)) {
                ulverticalmenu.style.display = 'none';
            }
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</body>



</html>