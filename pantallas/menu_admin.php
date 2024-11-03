<?php
include '../utils/functions.php';

// Llama a la función para obtener las estadísticas
$estadisticas = obtener_estadisticas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_menu_admin.css">
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
        <h1 class="text-center text-success mb-4">Estadísticas</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-header bg-success text-white">Árboles Disponibles</div>
                    <div class="card-body">
                        <!-- Muestra los arboles disponibles -->
                        <h2 class="card-title"><?php echo $estadisticas['total_arboles_disponibles']; ?></h2>
                        <p class="card-text">Total de árboles disponibles para venta.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-header bg-success text-white">Árboles Vendidos</div>
                    <div class="card-body">
                        <!-- Muestra los arboles vendidos -->
                        <h2 class="card-title"><?php echo $estadisticas['total_arboles_vendidos']; ?></h2>
                        <p class="card-text">Total de árboles ya vendidos.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-header bg-success text-white">Amigos Registrados</div>
                    <div class="card-body">
                        <!-- Muestra la cantidad de usuarios -->
                        <h2 class="card-title"><?php echo $estadisticas['total_usuarios']; ?></h2>
                        <p class="card-text">Total de amigos registrados en la app.</p>
                    </div>
                </div>
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
</body>
</html>
