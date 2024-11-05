<?php
session_start();
require '../utils/functions.php';

// Validar si la sesión está activa y que sea un amigo.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Amigo') {
    header("Location: ../index.php");
    exit();
}

$arboles = obtenerArboles($_SESSION['tipo']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_menu_amigo.css">
    <link rel="stylesheet" href="/stylesheets/stylesheet_offcanvas.css"> <!-- Agregado: stylesheet para el offcanvas -->
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

    <!-- Offcanvas para el carrito de compras -->
    <div id="offcanvasCarrito" class="offcanvas">
        <div class="offcanvas-header">
            <h5>Carrito de Compras</h5>
            <button type="button" class="btn-close" id="closeOffcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <p>Tu carrito está vacío.</p>
            <!-- Aquí se cargará dinámicamente la lista de productos -->
        </div>
    </div>

    <main>
        <div class="container mt-5">
            <h1 class="text-center text-success mb-4">Tienda de Árboles</h1>
            <div class="row">

                <!-- Carga en las cards los distintos árboles disponibles -->
                <?php foreach ($arboles as $arbol): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <img src="<?php echo htmlspecialchars($arbol['RUTA_FOTO_ARBOL']); ?>" class="card-img-top" alt="Imagen de árbol">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($arbol['ESPECIE']); ?></h5>
                                <p class="card-text">
                                    <strong>Ubicación:</strong> <?php echo htmlspecialchars($arbol['UBICACION']); ?><br>
                                    <strong>Precio:</strong> $<?php echo number_format($arbol['PRECIO'], 2); ?>
                                </p>
                                <a href="#" class="btn btn-success">Comprar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

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