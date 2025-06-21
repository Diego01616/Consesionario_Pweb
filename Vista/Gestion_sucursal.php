<?php
require_once '../Modelo/Dao_sucursal.php';
require_once 'verificar_admin.php';
$sucursales = Dao_sucursal::obtener_sucursal();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <title>Document</title>
</head>

<body>

    <div id="navbar-placeholder"></div>
    <div id="navbar-placeholder"></div>

    <div class="Contenedor_completo">
        <div class="lista">
            <div class="title-form">
                <a>Lista de Sucursales</a>
            </div>
            <form name="lista_usuario" id="form-validation" class="form_lista" novalidate>
                <table id="table-validation" class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sucursales as $sucursal): ?>
                            <tr>
                                <td><?= $sucursal->id ?></td>
                                <td><?= $sucursal->nombre ?></td>
                                <td><?= $sucursal->direccion ?></td>
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

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="buttonlist">
                    <button class="press_add" type="button" name="eliminar" value="Eliminar_usuario">Agregar nueva sucursal</button>
                </div>
            </form>
        </div>
        <div class="edit_users">
            <div class="title-form">
                <a>Sucursal a editar</a>
            </div>
            <form id="form-validation-edit">
                <div class="edit_user_content">
                    <div class="edit_input_user">
                        <div class="item_user">
                            <span>Nombre</span><input id="nombre_edit" name="nombre" pattern="[A-Za-z\s]+" required>
                        </div>
                        <div class="item_user">
                            <span>Direccion</span><input id="direccion_edit" name="direccion" required>
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
                let isValid = true;
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

                        fetch('../Controlador/Controlador_sucursal.php', {
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
                                    notyf.success("Sucursal actualizada");
                                    setTimeout(() => {
                                        addform.reset();
                                        addform.classList.remove('was-validated');
                                        window.location.href = "Gestion_sucursal.php";
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
                    const direccion = fila.cells[2].textContent.trim();
                    const estado = fila.cells[3].textContent.trim();
                    const id_sucursal = fila.cells[4].textContent.trim();

                    document.getElementById('nombre_edit').value = nombre;
                    document.getElementById('direccion_edit').value = direccion;

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
                        text: "Esta sucursal será eliminada permanentemente",
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
                           // alert('diste que si');
                            fetch('../Controlador/Controlador_sucursal.php', {
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
                                    if (data.includes("eliminado")) {
                                        notyf.success("Sucursal eliminado");
                                        setTimeout(() => {
                                            window.location.href = "Gestion_sucursal.php";
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
        const botonAgregar = document.querySelectorAll('.press_add');
        botonAgregar.forEach(boton => {
            boton.addEventListener('click', (e) => {
                const notyf = new Notyf({
                    duration: 3500,
                    position: {
                        x: 'center',
                        y: 'top'
                    },
                });
                Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Quieres adicionar una nueva sucursal?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                           window.location.href ="Sucursal_register.php";
                        }
                    });
            });
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</body>

</html>