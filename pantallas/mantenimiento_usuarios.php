<?php
// Incluir el archivo de funciones para cargar los métodos necesarios
require '../utils/functions.php';

// Cargar los usuarios desde la base de datos
$usuarios = obtenerUsuarios(); // Ejecuta la función que devuelve un array de usuarios

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Usuarios - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/imagenes/app_icon.png" type="image/x-icon">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Añade el header a la vista -->
    <div id="header-container"></div>
    <script>
        fetch('/pantallas/shared/header.html')
            .then(response => response.text())
            .then(data => document.getElementById('header-container').innerHTML = data);
    </script>

    <main class="flex-grow-1 container my-5">
        <h1 class="text-center text-success mb-4">Mantenimiento de Usuarios</h1>
        <div class="d-flex justify-content-end mb-3">
            <!-- Botón para abrir el modal de agregar usuario -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Agregar Usuario</button>
        </div>

        <!-- Tabla de usuarios -->
        <table class="table table-bordered text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th>ID</th>
                    <th>Tipo de Usuario</th>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Agrega los usuarios -->
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['ID_USUARIOS']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['TIPO']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['USUARIO']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['NOMBRE_COMPLETO']); ?></td>
                            <td>
                                <!-- Botón de editar -->
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateUserModal"
                                    onclick="cargarValoresUsuario('<?php echo $usuario['ID_USUARIOS']; ?>', '<?php echo htmlspecialchars($usuario['TIPO']); ?>', '<?php echo htmlspecialchars($usuario['USUARIO']); ?>', '<?php echo htmlspecialchars($usuario['NOMBRE_COMPLETO']); ?>')">Editar</button>
                                
                                <!-- Botón de eliminar -->
                                <form action="../actions/CRUD_usuarios.php" method="POST" class="d-inline" id="deleteForm<?php echo $usuario['ID_USUARIOS']; ?>">
                                    <input type="hidden" name="accion" value="borrar">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['ID_USUARIOS']; ?>">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminacionUsuario('<?php echo $usuario['ID_USUARIOS']; ?>', '<?php echo htmlspecialchars($usuario['USUARIO']); ?>')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal para agregar usuario -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addUserModalLabel">Agregar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../actions/CRUD_usuarios.php" method="POST">
                        <input type="hidden" name="accion" value="crear">
                        <div class="mb-3">
                            <label for="tipoUsuario" class="form-label">Tipo de Usuario</label>
                            <select class="form-select" id="tipoUsuario" name="tipo_usuario" required>
                                <!-- Opciones para tipos de usuarios -->
                                <option value="1">Administrador</option>
                                <option value="2">Editor</option>
                                <option value="3">Usuario</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nombreUsuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="nombreUsuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombreCompleto" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombreCompleto" name="nombre_completo" required>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para actualizar usuario -->
    <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="updateUserModalLabel">Actualizar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../actions/CRUD_usuarios.php" method="POST">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" id="currentIdUsuario" name="id_usuario">
                        <div class="mb-3">
                            <label for="updateTipoUsuario" class="form-label">Tipo de Usuario</label>
                            <select class="form-select" id="updateTipoUsuario" name="tipo_usuario" required>
                                <option value="1">Administrador</option>
                                <option value="2">Editor</option>
                                <option value="3">Usuario</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateNombreUsuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="updateNombreUsuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateNombreCompleto" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="updateNombreCompleto" name="nombre_completo" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Añade el footer a la vista -->
    <div id="footer-container"></div>
    <script>
        fetch('/pantallas/shared/footer.html')
            .then(response => response.text())
            .then(data => document.getElementById('footer-container').innerHTML = data);
    </script>

    <script src="/utils/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
