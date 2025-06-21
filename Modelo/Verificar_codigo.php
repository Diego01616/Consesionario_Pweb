<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Verificar_codigo
{
    public static function verificar()
    {
        $codigo = $_SESSION['codigo_rec'] ?? null;
        $codigo_usuario = $_POST['codigo'] ?? null;
        //echo "codigo enviado:".$codigo."codigo ingresado: ".$codigo_usuario;

        if ($codigo && $codigo == $codigo_usuario) {
            echo 'Código confirmado';
            unset($_SESSION['codigo_rec']);
        } else {
            echo 'Código no confirmado';
        }
    }
}
