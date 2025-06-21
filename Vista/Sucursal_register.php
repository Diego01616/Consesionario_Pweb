<?php 
require_once 'verificar_admin.php';
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
<div id="navbar-placeholder"></div>

<body>

    <div class="center">

        <form class="formRegisterUsers" id="form-validation" action="" novalidate>
            <div class="data-users">
                <div class="title-form">
                    <a>Nueva sucursal</a>
                </div>
                <div class="contentR">
                    <div class="form-group">
                        <span>Nombre</span><input type="text" name="nombre" pattern="[A-Za-z\s]+" title="Solo letras"
                            placeholder="" required><small class="small-red">*</small>

                    </div>
                    <div class="form-group">
                        <span>Direccion</span><input type="text" name="direccion" 
                             placeholder="" required>
                    </div>
                    <div class="buttonlogin">
                        <button class="inicio" type="submit">Enviar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        fetch("Nav.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
            })
            .catch(err => console.error("No se pudo cargar el navbar:", err));
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const addform = document.getElementById('form-validation');      
            addform.addEventListener('submit', (e) => {
                
                var notyf = new Notyf({
                    duration: 3500,
                    position:{
                        x:'center',
                        y:'top'
                    },
                });
                e.preventDefault();
                
                let isValid = true;

                if (!addform.checkValidity()) {
                    addform.classList.add('was-validated');
                    isValid = false;
                }

                if(isValid==false){
                    notyf.error('Datos faltantes o formato incorrecto');
                }
                 
                if (!isValid) return;  

                const datos = new FormData(addform);
                datos.append('accion','insertar');

                fetch('../Controlador/Controlador_sucursal.php', {
                    method: 'POST',
                    body: datos 
                })
                    .then(response=>{
                        if(!response.ok){
                            throw new Error ('error al enviar los datos');
                        }
                        return response.text();
                    })
                    .then(data=>{
                        console.log("Respuesta del servidor:", data);

                        if(data.includes("registrado")){
                            notyf.success('datos enviados');
                            setTimeout(() => {
                                window.location.href="Gestion_sucursal.php";
                            }, 2000);
                           // addform.reset();
                           // addform.classList.remove('was-validated');
                           
                        }else{
                            notyf.error("No se pudo insertar la sucursal");
                        }
                    })
                    .catch(error=>{
                        //alert('paila todo '+ error.message);
                        notyf.error('error inesperado'+error.message);
                        addform.reset();
                    });
            });
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

</body>

</html>