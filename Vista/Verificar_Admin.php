<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {


    echo "<h2 style=\"color: red; text-align: center; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; margin-top: 50px;\">
    Acceso denegado. No tienes permisos para ver esta página.</h2>";

    exit;
}

