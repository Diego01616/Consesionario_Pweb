<?php
require_once '../Modelo/Dao_sucursal.php';
$sucursales = Dao_sucursal::obtener_sucursal();

$id_user = $_POST['id'] ?? null;
$name_user = $_POST['nombre'] ?? '';
$lastname_user = $_POST['apellido'] ?? '';

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

<body>
    <div id="navbar-placeholder"></div>


    <div class="center">
        <form class="formRegisterUsers" id="form-validation" action="" novalidate>
            <div class="data-users">
                <div class="title-form">
                    <a>Agregar empleado</a>
                </div>
                <div class="contentR">
                    <div class="form-group">
                        <span><strong>identificación :</strong><?= $id_user ?></span>
                    </div>
                    <div class="form-group">
                        <span><strong>nombre:</strong><?= $name_user ?> <?= $lastname_user ?></span>
                    </div>
                    <div class="form-group">
                        <span>Sueldo</span><input id="salary" type="text" name="salary" pattern="^[1-9]\d*$" title="Solo números"
                            placeholder="" required><small class="small-red">*</small>
                    </div>
                    <div class="form-group">
                        <span>Sucursal</span>
                        <div class="form-group-select">
                            <select id="tipo_id" name="id_sucursal" class="select_normal" required>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?= $sucursal->id ?>"><?= $sucursal->nombre ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="small-red">*</small>
                        </div>
                    </div>
                    <div class="buttonlogin">
                        <button class="inicio" type="submit" value="enviar">Enviar</button>
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

                // Este código se ejecuta solo cuando el navbar ya fue cargado
                const verticalmenu = document.getElementById('icon-verticalmenu');
                const ulverticalmenu = document.getElementById('ul-verticalmenu');

                if (verticalmenu && ulverticalmenu) {
                    verticalmenu.addEventListener('click', () => {
                        ulverticalmenu.style.display = (ulverticalmenu.style.display === 'block') ? 'none' : 'block';
                    });

                    document.addEventListener('click', (e) => {
                        if (!verticalmenu.contains(e.target) && !ulverticalmenu.contains(e.target)) {
                            ulverticalmenu.style.display = 'none';
                        }
                    });
                }
            })
            .catch(err => console.error("No se pudo cargar el navbar:", err));
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
                    aplicarFormatoPesosColombianos('salary');
                    const addform = document.getElementById('form-validation');
                    const salary = document.getElementById('salary');

                    addform.addEventListener('submit', (e) => {

                        var notyf = new Notyf({
                            duration: 3500,
                            position: {
                                x: 'center',
                                y: 'top'
                            },
                        });
                        //alert("le dio a enviar");
                        e.preventDefault();
                        const datos = new FormData(addform);
                        datos.append('accion', 'insertar');
                        datos.append('id', '<?= $id_user ?>');
                        fetch('../Controlador/Controlador_empleado.php', {
                                method: 'POST',
                                body: datos
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('error al enviar los datos');
                                }
                                return response.text();
                            })
                            .then(data => {
                                console.log("Respuesta del servidor:", data);
                                if (data.includes("Usuario registrado")) {
                                    notyf.success("empleado registrado");
                                    setTimeout(() => {
                                        window.location.href = "Gestion_cliente.php";
                                    }, 2000);
                                }else if(data.includes("ya existe")){
                                    notyf.error("el empleado ya está registrado en una sucursal, no se puede volver a contratar....");
                                }
                                else{
                                    notyf.error("No se pudo registrar empleados");
                                    alert(data);
                                }
                            }).catch(error => {
                                    console.error(error);
                                    notyf.error('Fallo en la conexión con el servidor');
                            });
                            });
                    });

                    function aplicarFormatoPesosColombianos(idInput) {
                        const input = document.getElementById(idInput);

                        const formatearPesos = (valor) => {
                            const numero = parseFloat(valor.replace(/\D/g, ''));
                            if (isNaN(numero)) return '';
                            return new Intl.NumberFormat('es-CO', {
                                style: 'currency',
                                currency: 'COP',
                                minimumFractionDigits: 0
                            }).format(numero);
                        };
                        // Formatea mientras se escribe
                        input.addEventListener('input', () => {
                            const valorSinFormato = input.value.replace(/\D/g, '');
                            input.value = formatearPesos(valorSinFormato);
                        });

                        // Limpia antes de enviar el formulario
                        const form = input.form;
                        if (form) {
                            form.addEventListener('submit', () => {
                                input.value = input.value.replace(/\D/g, '');
                            });
                        }
                    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

</body>

</html>