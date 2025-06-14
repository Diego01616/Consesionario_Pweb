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
                        <h2>Reestablecer contraseña</h2>
                    </div>
                    <div class="contentR">
                        <div class="form-group">
                            <span>Nueva contraseña</span>
                            <input type="password" id="password" name="password" required>
                            <small id="password-msg">Mínimo 8 carácteres</small>
                        </div>
                        <div class="form-group">
                            <span>Confirmar contraseña</span>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                            <small id="password-msg-confirm"></small>
                        </div>
                        <div class="buttonlogin" id="button-password-disable">
                            <button class="inicio" type="submit">Reestablecer</button>
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
        document.addEventListener('DOMContentLoaded', () => {
            var notyf = new Notyf({
                duration: 3500,
                position: {
                    x: 'center',
                    y: 'top'
                },
            });
            const addform = document.getElementById('form-validation');
            const passwordInput = document.getElementById('password');
            const password_confirm_Input = document.getElementById('confirm-password');
            const pwMsg = document.getElementById('password-msg');
            const pwMsgcon = document.getElementById('password-msg-confirm');
            const cent = false;

            passwordInput.addEventListener('input', () => {


                if (passwordInput.value.length >= 8) {
                    pwMsg.classList.add('valid');
                    pwMsg.textContent = 'Contraseña válida';
                    cent = true;
                } else {
                    pwMsg.classList.remove('valid');
                    pwMsg.textContent = 'Mínimo 8 caracteres';
                }

            });

            addform.addEventListener('submit', (e) => {
                e.preventDefault();

                if(comparar(passwordInput.value, password_confirm_Input.value)==true){
                    notyf.success("válidado");
                    const datos = new FormData(addform);
                    datos.append('accion','reestablecer');      
                    fetch('../Controlador/Controlador_empleado.php',{
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




                        
                    })



                }else{
                    notyf.error("los campos no concuerdan");

                }
            });

        });

        function comparar(a, b) {
            if (a === b) {
                return true;
            } else {
                return false;
            }
        }
    </script>

</body>

</html>