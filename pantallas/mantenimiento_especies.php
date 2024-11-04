<?php
require '../utils/functions.php';

session_start();

// Validar si la sesión está activa y que sea un admin.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {
    // Redirigir al login si no hay sesión activa.
    header("Location: ../index.php");
    exit();
}


// Cargar las especies desde la base de datos
$especies = cargarEspecies(); // Ejecuta la función que devuelve un array de especies

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Especies - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_especies.css">
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
        <h1 class="text-center text-success mb-4">Mantenimiento de Especies de Árboles</h1>
        <div class="d-flex justify-content-end mb-3">
            <!-- Botón para abrir el modal de agregar especie -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSpeciesModal">Agregar Especie</button>
        </div>

        <!-- Tabla de especies -->
        <table class="table table-bordered text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th>ID</th>
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Agrega las especies -->
                <?php if (!empty($especies)): ?>
                    <?php foreach ($especies as $especie): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($especie['ID_ESPECIE']); ?></td>
                            <td><?php echo htmlspecialchars($especie['NOMBRE_COMERCIAL']); ?></td>
                            <td><?php echo htmlspecialchars($especie['NOMBRE_CIENTIFICO']); ?></td>
                            <td>
                                <!-- Botón de editar -->
                                <form action="../actions/CRUD_especies.php" method="POST" class="d-inline">
                                    <input type="hidden" name="accion" value="editar">
                                    <input type="hidden" name="id_especie" value="<?php echo $especie['ID_ESPECIE']; ?>">
                                    <!-- Botón para abrir el modal de actualizar especie -->
                                    <button type="button" class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateSpeciesModal"
                                        onclick="valoresActualesEspecie('<?php echo $especie['ID_ESPECIE']; ?>', '<?php echo htmlspecialchars($especie['NOMBRE_COMERCIAL']); ?>', '<?php echo htmlspecialchars($especie['NOMBRE_CIENTIFICO']); ?>')">Editar</button>


                                </form>
                                <!-- Botón de eliminar -->
                                <form action="../actions/CRUD_especies.php" method="POST" class="d-inline" id="deleteForm<?php echo $especie['ID_ESPECIE']; ?>">
                                    <input type="hidden" name="accion" value="borrar">
                                    <input type="hidden" name="id_especie" value="<?php echo $especie['ID_ESPECIE']; ?>">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="Eliminar('<?php echo $especie['ID_ESPECIE']; ?>', '<?php echo htmlspecialchars($especie['NOMBRE_COMERCIAL']); ?>')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- Caso en el que no hay especies -->
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay especies registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal para agregar especie -->
    <div class="modal fade" id="addSpeciesModal" tabindex="-1" aria-labelledby="addSpeciesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addSpeciesModalLabel">Agregar Nueva Especie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Formulario desplegable para crear una especie -->
                    <form action="../actions/CRUD_especies.php" method="POST">
                        <input type="hidden" name="accion" value="crear">
                        <div class="mb-3">
                            <label for="nombreComercial" class="form-label">Nombre Comercial</label>
                            <input type="text" class="form-control" id="nombreComercial" name="nombre_comercial" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombreCientifico" class="form-label">Nombre Científico</label>
                            <input type="text" class="form-control" id="nombreCientifico" name="nombre_cientifico" required>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para actualizar especie -->
    <div class="modal fade" id="updateSpeciesModal" tabindex="-1" aria-labelledby="updateSpeciesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="updateSpeciesModalLabel">Actualizar Especie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Formulario desplegable para actualizar una especie -->
                    <form action="../actions/CRUD_especies.php" method="POST">
                        <!-- Inputs ocultos para lograr cargar los valores actuales de la especie -->
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" id="currentNombreComercial" name="current_nombre_comercial">
                        <input type="hidden" id="currentNombreCientifico" name="current_nombre_cientifico">

                        <!-- Inputs visibles para actualizar la especie -->
                        <div class="mb-3">
                            <label for="updateNombreComercial" class="form-label">Nombre Comercial</label>
                            <input type="text" class="form-control" id="updateNombreComercial" name="nombre_comercial" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateNombreCientifico" class="form-label">Nombre Científico</label>
                            <input type="text" class="form-control" id="updateNombreCientifico" name="nombre_cientifico" required>
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