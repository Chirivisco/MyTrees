<?php
// Incluir el archivo de funciones para cargar los métodos necesarios
require '../utils/functions.php';

session_start();

// Validar si la sesión está activa y que sea un admin.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {
    // Redirigir al login si no hay sesión activa.
    header("Location: ../index.php");
    exit();
}

$arboles = obtenerArboles();
$especies = cargarEspecies();
$estados = cargarEstados();

// foreach ( $arboles as $key => $value )
// {
//   foreach($value as $key2 => $value2) {
//     echo $key2. " = ".$value2."<br>";
//   }
//   echo "<br>";
// }
// die();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Árboles - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_arboles.css">
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
        <h1 class="text-center text-success mb-4">Mantenimiento de Árboles</h1>
        <div class="d-flex justify-content-end mb-3">
            <!-- Botón para abrir el modal de agregar árbol -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTreeModal">Agregar Árbol</button>
        </div>

        <!-- Tabla de árboles -->
        <table class="table table-bordered text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th>ID</th>
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Ubicación</th>
                    <th>Precio (₡)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Agrega los árboles -->
                <?php if (!empty($arboles)): ?>
                    <?php foreach ($arboles as $arbol): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($arbol['ID_ARBOL']); ?></td>
                            <td><?php echo htmlspecialchars($arbol['NOMBRE_COMERCIAL']); ?></td>
                            <td><?php echo htmlspecialchars($arbol['NOMBRE_CIENTIFICO']); ?></td>
                            <td><?php echo htmlspecialchars($arbol['UBICACION']); ?></td>
                            <td><?php echo '₡' . number_format($arbol['PRECIO'], 2); ?></td>
                            <td><?php echo htmlspecialchars($arbol['ESTADO']); ?></td>
                            <td>
                                <!-- Botón de editar -->
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateTreeModal"
                                    onclick="cargarArbol('<?php echo $arbol['ID_ARBOL']; ?>', 
                                                        '<?php echo htmlspecialchars($arbol['ESPECIE']); ?>', 
                                                        '<?php echo htmlspecialchars($arbol['UBICACION']); ?>', 
                                                        '<?php echo $arbol['PRECIO']; ?>', 
                                                        '<?php echo htmlspecialchars($arbol['RUTA_FOTO_ARBOL']); ?>', 
                                                        '<?php echo htmlspecialchars($arbol['ESTADO']); ?>')">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay árboles registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal para agregar árbol -->
    <div class="modal fade" id="addTreeModal" tabindex="-1" aria-labelledby="addTreeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addTreeModalLabel">Agregar Nuevo Árbol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- El form para agregar arboles -->
                    <form action="../actions/CRUD_arboles.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="crear">

                        <div class="mb-3">
                            <label for="especie" class="form-label">Especie</label>
                            <select class="form-select" id="especie" name="especie" required>

                                <!-- Carga las especies al select -->
                                <?php if (!empty($especies)): ?>
                                    <?php foreach ($especies as $especie): ?>
                                        <option value="<?php echo htmlspecialchars($especie['ID_ESPECIE']); ?>">
                                            <?php echo htmlspecialchars($especie['NOMBRE_COMERCIAL']) . ' - ' . htmlspecialchars($especie['NOMBRE_CIENTIFICO']); ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <option disabled>No hay especies disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (₡)</label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Cargar Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" required>
                        </div>

                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar árbol -->
    <div class="modal fade" id="updateTreeModal" tabindex="-1" aria-labelledby="updateTreeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="updateTreeModalLabel">Editar Árbol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Imagen del árbol -->
                    <div class="text-center mb-3" style="display: grid;">
                        <label for="imagen">Foto actual del árbol:</label>
                        <img id="imagenPreview" src="" alt="Foto del árbol" style="width:100px; height:auto; justify-self: center; margin-top: 10px;">
                    </div>

                    <!-- Formulario para editar árboles -->
                    <form action="../actions/CRUD_arboles.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" id="updateArbolId" name="arbol_id">

                        <div class="mb-3">
                            <label for="updateEspecie" class="form-label">Especie</label>
                            <select class="form-select" id="updateEspecie" name="especie" required>
                                <?php if (!empty($especies)): ?>
                                    <?php foreach ($especies as $especie): ?>
                                        <option value="<?php echo htmlspecialchars($especie['ID_ESPECIE']); ?>">
                                            <?php echo htmlspecialchars($especie['NOMBRE_COMERCIAL']) . ' - ' . htmlspecialchars($especie['NOMBRE_CIENTIFICO']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled>No hay especies disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="updateEstado" class="form-label">Estado</label>
                            <select class="form-select" id="updateEstado" name="estado" required>
                                <?php if (!empty($estados)): ?>
                                    <?php foreach ($estados as $estado): ?>
                                        <option value="<?php echo htmlspecialchars($estado['ID_ESTADO']); ?>">
                                            <?php echo htmlspecialchars($estado['ESTADO']);?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled>No hay estados disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="updateUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="updateUbicacion" name="ubicacion" required>
                        </div>

                        <div class="mb-3">
                            <label for="updatePrecio" class="form-label">Precio (₡)</label>
                            <input type="number" class="form-control" id="updatePrecio" name="precio" step="0.01" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="updateImagen" class="form-label">Cargar Nueva Imagen</label>
                            <input type="file" class="form-control" id="updateImagen" name="imagen">
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