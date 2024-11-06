<?php
require '../utils/functions.php';

session_start();

// Validar si la sesión está activa y que sea un administrador.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$id_arbol = $_POST['id_arbol'];
$detalles_arbol = cargarInfoArbol($id_arbol);
// $detalles_actualizacion_arbol = cargarActualizacionesArbol();

// Manejo de errores (lado servidor)
$error = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'no_trees_to_view') {
        $error = "No hay árboles para mostrar.";
    } elseif ($_GET['error'] === 'tree_updated') {
        $error = "El árbol ha sido actualizado.";
    } elseif ($_GET['error'] === 'no_data') {
        $error = "No se pudo obtener los datos del árbol.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árboles del Usuario - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_UR_arbol.css">
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
        <h1 class="text-center text-success mb-4">Árboles del Usuario</h1>

        <!-- Muestra mensaje de error -->
        <?php if (!empty($error)): ?>
            <div class="alert <?php echo ($_GET['error'] === 'tree_updated') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Valida si hay un mensaje de error y el usuario posee árboles -->
        <?php if (!isset($_GET['error']) || !$error === "no_trees_to_view"): ?>
            
            <!-- Info del árbol -->
            <div class="row mb-4">
                <div class="col-md-4">

                    <label for="selectEspecie" class="form-label">Especie</label>
                    <select id="selectEspecie" class="form-select">
                        <!-- Acá hay que cargar la especie del árbol -->
                    </select>

                </div>
                <div class="col-md-4">
                    <label for="inputUbicacion" class="form-label">Ubicación</label>
                    <input type="text" id="inputUbicacion" class="form-control" placeholder="Ingresar ubicación">
                </div>
                <div class="col-md-4">
                    <label for="inputTamaño" class="form-label">Tamaño (metros)</label>
                    <input type="number" id="inputTamaño" class="form-control" placeholder="Ingresar tamaño">
                </div>
            </div>

            <div class="row">
                <!-- Card del árbol -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="" class="card-img-top" alt="Foto del árbol">
                        <div class="card-body">
                            <p class="card-text"><strong>Especie:</strong> <?php echo $detalles_arbol['ESPECIE'];?></p>
                            <p class="card-text"><strong>Ubicación:</strong> <?php echo $detalles_arbol['UBICACION'];?></p>
                            <p class="card-text"><strong>Tamaño:</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

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