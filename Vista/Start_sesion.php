<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="CSS/estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
</head>

<body>
    <div id="navbar-placeholder"></div>

    <div class="divMajor">
        <div class="divlogin">
            <form class="form-login" id="form-validation" action="" novalidate>
                <div class="contform">
                    <h2>Bienvenido Usuario</h2>
                    <div class="div-admin">
                        <a href="Admin_start.php"><img src="./img/admin.png" class="icon-hover"></a>
                    </div>

                    <div class="form-group">
                        <span><a class="title-short">Correo</a></span>
                        <input type="text" name="correo" id="correo" placeholder="Digite su correo" required>
                        <small class="small-red correo-error">*</small>
                    </div>

                    <div class="form-group">
                        <span><a class="title-short">Contraseña</a></span>
                        <input type="password" id="password" name="contraseña" placeholder="Digite su contraseña" required>
                        <small class="small-red">*</small>
                    </div>

                    <div class="divlinks">
                        <a href="Recover_password.php" class="link">Olvidé mi contraseña</a>
                    </div>

                    <div class="buttonlogin">
                        <button class="inicio" type="submit">Iniciar Sesión</button>
                    </div>

                    <div class="divlinks-nouser">
                        <a class="linktxt">¿No tienes usuario?</a>
                        <a href="User_Register.html" class="link"><strong>Regístrate</strong></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal rol -->
    <div id="rolModal" class="pantalla-fondo" style="display: none;">
        <div class="box-center-flotante" id="rolModalContent">
            <a></a>
        </div>
    </div>

    <!-- Navbar carga -->
    <script>
        fetch("Nav.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
            })
            .catch(err => console.error("No se pudo cargar el navbar:", err));
    </script>

    <!-- Lógica del formulario -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addform = document.getElementById('form-validation');
            const correoInput = document.getElementById('correo');
            const passwordInput = document.getElementById('password');
            const correoError = document.querySelector('.correo-error');

            addform.addEventListener('submit', (e) => {
                e.preventDefault();

                const notyf = new Notyf({
                    duration: 3500,
                    position: {
                        x: 'center',
                        y: 'top'
                    }
                });

                let isValid = true;

                if (!addform.checkValidity()) {
                    addform.classList.add('was-validated');
                    isValid = false;
                    notyf.error('Campos inválidos o incompletos');
                }

                const correo = correoInput.value.trim();
                const dominiosPermitidos = ['@gmail.com', '@hotmail.com', '@outlook.com'];
                const dominioValido = dominiosPermitidos.some(d => correo.endsWith(d));

                if (!dominioValido || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                    correoError.textContent = "Correo no válido o dominio no permitido";
                    correoError.style.display = "block";
                    notyf.error('Correo no válido');
                    isValid = false;
                } else {
                    correoError.style.display = "none";
                }

                if (!isValid) return;

                const datos = new FormData(addform);
                datos.append('accion', 'ingresar_usuario');

                fetch('../Controlador/Controlador_cliente.php',{
                        method: 'POST',
                        body: datos
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Error al enviar los datos');
                        return response.text();
                    })
                    .then(data => {
                        console.log("Respuesta del servidor:", data);
                        if (data.includes("solo un rol")) {
                            notyf.success('Inicio de sesión exitoso, Redirigiendo...');
                            setTimeout(() => window.location.href = "Nav.php", 2000);
                        } else if (data.includes(">1")) {
                            notyf.success('Más de un rol, elige uno');

                            const modal = document.getElementById("rolModal");
                            const contenido = document.getElementById("rolModalContent");

                            fetch("../Modelo/Elegir_rol.php")
                                .then(response => {
                                    if (!response.ok) throw new Error("No se pudo cargar el contenido.");
                                    return response.text();
                                })
                                .then(html => {
                                    contenido.innerHTML = html;
                                    modal.style.display = "block";

                                    // Ahora asignamos los eventos
                                    setTimeout(() => {
                                        const botones = document.querySelectorAll('.button-n');
                                        botones.forEach(boton => {
                                            boton.addEventListener('click', (e) => {
                                                e.preventDefault();

                                                const valor = e.target.value;
                                                const datos = new FormData();
                                                datos.append('accion', 'rol_seleccionado');
                                                datos.append('rol', valor);

                                                fetch('../Controlador/Controlador_cliente.php', {
                                                        method: 'POST',
                                                        body: datos
                                                    })
                                                    .then(resp => {
                                                        if (!resp.ok) throw new Error('Error al enviar los datos');
                                                        return resp.text();
                                                    })
                                                    .then(dat => {
                                                        console.log("Respuesta del servidor: " + dat);
                                                        if (dat.includes('rol_ok')) {
                                                            notyf.success('Rol seleccionado, redirigiendo...');
                                                           // setTimeout(() => {
                                                                window.location.href = "Nav.php";
                                                          //  }, 2000);
                                                        } else {
                                                            notyf.error('No se pudo seleccionar el rol');
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error(error);
                                                        notyf.error('Fallo en la conexión con el servidor');
                                                    });
                                            });
                                        });
                                    }, 100);
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        } else {
                            notyf.error("Usuario o contraseña incorrectos");
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        notyf.error('Fallo en la conexión con el servidor');
                    });
            });
        });
    </script>

    <!-- Notyf -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</body>

</html>