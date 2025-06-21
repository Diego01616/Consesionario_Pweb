<?php
require_once '../Modelo/Dao_usuario.php';
require_once 'verificar_admin.php';
$clientes = Dao_usuario::lista_usuario();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <title>Lista de Usuarios</title>
</head>

<body>
    <!-- Aquí se cargará el navbar -->
    <div id="navbar-placeholder"></div>

    <div class="Contenedor_completo">
        <div class="lista">
            <div class="title-form">
                <a>Lista de usuarios</a>
            </div>
            <form name="lista_usuario" id="form-validation" class="form_lista" novalidate>
                <table id="table-validation" class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th colspan="3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= $cliente->idUsuario ?></td>
                                <td><?= $cliente->nombre ?></td>
                                <td><?= $cliente->apellido ?></td>
                                <td><?= $cliente->correo ?></td>
                                <td><?= $cliente->telefono ?></td>
                                <td>
                                    <div class="buttonlist">
                                        <button class="press_edit" type="button" name="editar" value="Actualizar_usuario">Editar</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="buttonlist">
                                        <button class="press_del" type="button" name="eliminar" value="Eliminar_usuario">Eliminar</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="buttonlist">
                                        <button class="press_cont" type="button" name="contratar" value="Contratar_empleado">Contratar</button>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="edit_users">
            <div class="title-form">
                <a>Usuario a editar</a>
            </div>
            <form id="form-validation-edit">
                <div class="edit_user_content">
                    <div class="edit_input_user">
                        <div class="item_user">
                            <span>Nombre</span><input id="nombre_edit" name="nombre" pattern="[A-Za-z\s]+" required>
                        </div>
                        <div class="item_user">
                            <span>Apellido</span><input id="apellido_edit" name="apellido" pattern="[A-Za-z\s]+" required>
                        </div>
                        <div class="item_user">
                            <span>Correo</span><input id="correo_edit" name="correo" required>
                        </div>
                        <div class="item_user">
                            <span>Telefono</span><input id="telefono_edit" name="telefono" pattern="^[1-9]\d*$" required>
                        </div>
                    </div>
                    <div class="accion_edit">
                        <div class="buttonlist">
                            <button class="button_normal" type="submit" name="actualizar" value="actualizar">Actualizar</button>
                        </div>
                        <div class="buttonlist">
                            <button class="button_normal" id="btn-vaciar" type="button" name="vaciar" value="vaciar">Vaciar</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>                        
        <!-- <div id="contenedor-empleado"></div>-->

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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const notyf = new Notyf({
                    duration: 3500,
                    position: {
                        x: 'center',
                        y: 'top'
                    },
                });

                const addform = document.getElementById("form-validation-edit");

                // Validar y enviar el formulario de edición
                addform.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const correoInput = document.getElementById('correo_edit');
                    const correoRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com|outlook\.com)$/;
                    const correoTrim = correoInput.value.trim();
                    let isValid = true;

                    if (!correoRegex.test(correoTrim)) {
                        notyf.error("Correo inválido: solo gmail, hotmail u outlook.");
                        isValid = false;
                    }

                    if (!addform.checkValidity()) {
                        addform.classList.add('was-validated');
                        notyf.error("Por favor, completa todos los campos correctamente.");
                        isValid = false;
                    }

                    if (!isValid) return;

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "No podrás revertir esto",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, actualizar',
                        cancelButtonText: 'No, cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            notyf.success("Enviando...");
                            const datos = new FormData(addform);
                            datos.append('accion', 'actualizar');

                            fetch('../Controlador/Controlador_cliente.php', {
                                    method: 'POST',
                                    body: datos
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error("Error en el servidor");
                                    return response.text();
                                })
                                .then(data => {
                                    console.log("Respuesta:", data);
                                    if (data.includes("actualizado")) {
                                        notyf.success("Usuario actualizado");
                                        setTimeout(() => {
                                            addform.reset();
                                            addform.classList.remove('was-validated');
                                            window.location.href = "Gestion_cliente.php";
                                        }, 2000);

                                    } else {
                                        notyf.error("No se pudo actualizar");
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    notyf.error("Error al enviar los datos");
                                });
                        }
                    });
                });

                // Botón vaciar
                const btnVaciar = document.getElementById('btn-vaciar');
                btnVaciar.addEventListener('click', () => {
                    addform.reset();
                    const hiddenId = document.getElementById('id_edit');
                    if (hiddenId) hiddenId.value = '';
                });

                // Llenar campos al hacer clic en editar
                const botonesEditar = document.querySelectorAll('.press_edit');
                botonesEditar.forEach(boton => {
                    boton.addEventListener('click', (e) => {
                        const fila = e.target.closest('tr');

                        const id = fila.cells[0].textContent.trim();
                        const nombre = fila.cells[1].textContent.trim();
                        const apellido = fila.cells[2].textContent.trim();
                        const correo = fila.cells[3].textContent.trim();
                        const telefono = fila.cells[4].textContent.trim();

                        document.getElementById('nombre_edit').value = nombre;
                        document.getElementById('apellido_edit').value = apellido;
                        document.getElementById('correo_edit').value = correo;
                        document.getElementById('telefono_edit').value = telefono;

                        let hiddenId = document.getElementById('id_edit');
                        if (!hiddenId) {
                            hiddenId = document.createElement('input');
                            hiddenId.type = 'hidden';
                            hiddenId.id = 'id_edit';
                            hiddenId.name = 'id';
                            addform.appendChild(hiddenId);
                        }
                        hiddenId.value = id;
                    });
                });
            });


            
            const botonEliminar = document.querySelectorAll('.press_del');
            const botonContratar = document.querySelectorAll('.press_cont');
            botonEliminar.forEach(boton => {
                boton.addEventListener('click', (e) => {
                    const notyf = new Notyf({
                        duration: 3500,
                        position: {
                            x: 'center',
                            y: 'top'
                        },
                    });
                    const fila = e.target.closest('tr');
                    const idUsuario = fila.cells[0].textContent.trim();
                    Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Este usuario será eliminado permanentemente",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                const addCont = new FormData();
                                addCont.append('accion', 'eliminar');
                                addCont.append('id', idUsuario);
                                //alert('diste que si');
                                fetch('../Controlador/Controlador_cliente.php', {
                                        method: 'POST',
                                        body: addCont
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Error al enviar los datos');
                                        }
                                        return response.text(); // Obtiene el HTML como texto
                                    })
                                    .then(data => {
                                        console.log("Respuesta del servidor:", data);
                                        if(data.includes("eliminado")){
                                            notyf.success("Usuario eliminado");
                                            setTimeout(() => {
                                            window.location.href = "Gestion_cliente.php";
                                            }, 2000);
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error en el fetch:", error);
                                    });
                            }
                        });
                });
            });

            botonContratar.forEach(boton => {
                boton.addEventListener('click', (e) => {
                    const notyf = new Notyf({
                        duration: 3500,
                        position: {
                            x: 'center',
                            y: 'top'
                        },
                    });
                    const fila = e.target.closest('tr');
                    const idUsuario = fila.cells[0].textContent.trim();
                    const nombre_usuario = fila.cells[1].textContent.trim();
                    const apellido_usuario = fila.cells[2].textContent.trim();
                    Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Pasará al formulario de contratación para este usuario",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, contratar',
                            cancelButtonText: 'Cancelar'
                        })
                        .then((result) => {
                            const addCont = new FormData();
                            addCont.append('accion', 'contratar');
                            addCont.append('id', idUsuario);
                            addCont.append('nombre', nombre_usuario);
                            addCont.append('apellido', apellido_usuario);
                            if (result.isConfirmed) {
                                if (result.isConfirmed) {
                                    // Crear formulario dinámico
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = 'Employee_register.php';

                                    // Agregar los campos al formulario
                                    const inputId = document.createElement('input');
                                    inputId.type = 'hidden';
                                    inputId.name = 'id';
                                    inputId.value = idUsuario;

                                    const inputNombre = document.createElement('input');
                                    inputNombre.type = 'hidden';
                                    inputNombre.name = 'nombre';
                                    inputNombre.value = nombre_usuario;

                                    form.appendChild(inputId);
                                    form.appendChild(inputNombre);

                                    document.body.appendChild(form);
                                    form.submit(); // Redirige a Employe.php con POST
                                }

                            }


                        });


                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const btnVaciar = document.getElementById('btn-vaciar');

                btnVaciar.addEventListener('click', () => {
                    // Vaciar inputs del formulario de edición
                    document.getElementById('nombre_edit').value = '';
                    document.getElementById('apellido_edit').value = '';
                    document.getElementById('correo_edit').value = '';
                    document.getElementById('telefono_edit').value = '';

                    // Si creaste un input oculto para el ID
                    const hiddenId = document.getElementById('id_edit');
                    if (hiddenId) hiddenId.value = '';
                });
            });
        </script>


        <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</body>

</html>