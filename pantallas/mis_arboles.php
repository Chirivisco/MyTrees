<?php
session_start();
require '../utils/functions.php';

// Validar que la sesión esté activa y que el usuario sea un amigo
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Amigo') {
    header("Location: ../index.php");
    exit();
}

// Obtener los árboles adquiridos por el usuario
$arboles_adquiridos = obtenerMisArboles($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Árboles - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_menu_amigo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/imagenes/app_icon.png" type="image/x-icon">
</head>

<body>
    <!-- Añade el header a la vista -->
    <div id="header-container"></div>
    <script>
        fetch('/pantallas/shared/header_amigo.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-container').innerHTML = data;
                bindEvents();
            });
    </script>

    <main>
        <div class="container mt-5">
            <h1 class="text-center text-success mb-4">Mis Árboles Adquiridos</h1>
            <div class="row">
                <!-- Mostrar mensaje si el usuario no ha adquirido ningún árbol -->
                <?php if (empty($arboles_adquiridos)): ?>
                    <p class="text-center">No has adquirido árboles aún.</p>
                <?php else: ?>
                    <!-- Cargar los árboles adquiridos en cards -->
                    <?php foreach ($arboles_adquiridos as $arbol): ?>
                        <?php
                        foreach ($arbol as $id_arbol):
                            $detalle_arbol = cargarInfoArbol((int) $id_arbol);
                        ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <!-- Imagen del árbol -->
                                    <img src="<?php echo htmlspecialchars($detalle_arbol['RUTA_FOTO_ARBOL']); ?>" class="card-img-top" alt="Imagen de árbol">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($detalle_arbol['ESPECIE']); ?></h5>
                                        <!-- Descripción del árbol -->
                                        <p class="card-text">
                                            <strong>Ubicación:</strong> <?php echo htmlspecialchars($detalle_arbol['UBICACION']); ?><br>
                                            <strong>Precio de Compra:</strong> $<?php echo number_format($detalle_arbol['PRECIO'], 2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Añade el footer a la vista -->
    <div id="footer-container"></div>
    <script>
        fetch('/pantallas/shared/footer.html')
            .then(response => response.text())
            .then(data => document.getElementById('footer-container').innerHTML = data);
    </script>

    <script src="/utils/app.js"></script>
</body>

</html>