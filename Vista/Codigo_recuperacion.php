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
    <div id="navbar-placeholder"></div>
    <div class="center-absolute">
        <div class="center">
            <form class="formRegisterUsers" id="form-validation" novalidate>
                <div class="data-users">
                    <div class="title-form">
                        <h2>Ingrese el código enviado a su correo</h2>
                    </div>
                    <div class="contentR">
                        <div class="form-group">
                            <input type="text" id="codigo" name="codigo" required>
                        </div>
                        <div class="buttonlogin" id="button-password-disable">
                            <button class="inicio" type="submit">Confirmar</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        fetch("Nav.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
            })
            .catch(err => console.error("No se pudo cargar el navbar:", err));
    </script>

    <script>
        const addform= document.getElementById('form-validation');
        const codigo=document.getElementById('codigo');
        addform.addEventListener('submit',(e)=>{
            e.preventDefault();
            const notyf = new Notyf({
                duration: 3500,
                position: {
                    x: 'center',
                    y: 'top'
                    }
            });
            const datos =new FormData(addform);
            datos.append('accion','verificar_codigo');
            fetch('../Controlador/Controlador_cliente.php', {
                method: 'POST',
                body: datos
            })
            .then(response =>{
                if(!response.ok)
                throw new Error('Error al comprobar codigo');
                return response.text();
            })
            .then (data=>{
                console.log("Respuesta del servidor",data);
                if(data.includes("Código confirmado")){
                    setTimeout(() => {
                    notyf.success("Código confirmado");
                    
                    }, 2000);
                    window.location.href="Nueva_contraseña.php";
                }else {
                    notyf.error("Código no válido");
                    notyf.error(" s "+data);
                }
            })
            .catch(error=>{
                console.error(error);
                notyf.error("Fallo al comprobar la conexión, error inesperado del programa");
            });

        }) ;   
    </script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</body>
</html>