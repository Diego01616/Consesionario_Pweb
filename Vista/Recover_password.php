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
                        <h2>Recuperar contraseña</h2>
                    </div>
                    <div class="contentR">
                        <div class="form-group">
                            <span>Correo</span>
                            <input type="text" id="correo" name="correo" required>
                            <small class="small-red correo-error">*</small>
                        </div>
                        <div class="buttonlogin" id="button-password-disable">
                            <button class="inicio" type="submit">Enviar código</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        var notyf = new Notyf({
            duration: 3500,
            position: {
                x: 'center',
                y: 'top'
            },
        });
        const addform = document.getElementById("form-validation");
        const buttonpsw = document.getElementById("button-password-disable");
        const correoInput = document.getElementById('correo');
        const correoError = document.querySelector('.correo-error');


        addform.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;
            const correoRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com|outlook\.com)$/;
            const correoTrim = correoInput.value.trim();

            if (!correoRegex.test(correoTrim)) {
                correoError.style.display = 'inline';
                correoError.textContent = 'Correo no válido: usa Gmail, Hotmail u Outlook.';
                isValid = false;
            }
            if (!isValid) return;
            const datos = new FormData(addform);
            datos.append('accion', 'recuperar');
            fetch('../Controlador/Controlador_cliente.php', {
                    method: 'POST',
                    body: datos
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al encontrar el correo');
                    return response.text(); // ← importante: json, no text
                })
                .then(data => {
                    if (data.includes("encontrado")) {
                        console.log("ID encontrado:");
                        notyf.success('correo encontrado');
                            const dato = new FormData(addform);
                            dato.append('accion', 'enviar_codigo');
                            fetch('../Controlador/Controlador_cliente.php', {
                                    method: 'POST',
                                    body: dato
                                })
                                .then(resp => {

                                    if (!resp.ok) 
                                    throw new Error('Error al enviar los datos');
                                    return resp.text();
                                }).then (dat =>{
                                    console.log("Respuesta del servidor: " + dat);
                                    if(dat.includes('envio exitoso')){
                                        //notyf.success("Se envió un código a tu correo. ID: "+dat);
                                       // setTimeout(() => {
                                            window.location.href = "Codigo_recuperacion.php";
                                        //}, 2000);
                                    }

                                })

                                .catch(er =>{
                                     notyf.error('Error inesperado: ' + er.message);
                                })

                    } else {
                        notyf.error("No se encontró el correo");
                    }
                })
                .catch(error => {
                    notyf.error('Error inesperado: ' + error.message);
                    addform.reset();
                });


        });
    </script>


    <script>
        fetch("Nav.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
            })
            .catch(err => console.error("No se pudo cargar el navbar:", err));
    </script>




</body>

</html>