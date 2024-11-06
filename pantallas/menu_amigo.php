<?php
session_start();
require '../utils/functions.php';

// Validar si la sesión está activa y que sea un amigo.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Amigo') {
    header("Location: ../index.php");
    exit();
}

$arboles = obtenerArboles($_SESSION['tipo']);
$arboles_carrito = cargarArbolesCarrito($_SESSION['id_usuario']);

// foreach ($arboles_carrito as $arbol) {

//     foreach ($arbol as $id_arbol) {
//         $detalle_arbol = cargarInfoArbol((int) $id_arbol);
//          $columnas = array_keys($detalle_arbol);
//          echo "<br>";
//          echo "id del arbol del array 'arbol': ".$id_arbol."<br>";
//          echo "ruta de la imagen: ".$detalle_arbol['RUTA_FOTO_ARBOL']."<br>";
//          echo "id del arbol: ".$detalle_arbol['ID_ARBOL'];
//          echo "<br>";
//     }
// }

// die();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - My Trees</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_menu_amigo.css">
    <link rel="stylesheet" href="/stylesheets/stylesheet_offcanvas.css">
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

            <!-- Valida si hay arboles en el carrito de compras-->
            <?php if (!empty($arboles_carrito)): ?>
                <!-- Inicia el formulario para la compra -->
                <form action="../actions/confirmar_comprar.php" method="POST">
                    <!-- Aquí aseguramos que $arboles_carrito contiene los árboles -->
                    <?php foreach ($arboles_carrito as $arbol): ?>
                        <?php
                        // Aseguramos que $arbol es un arreglo con los IDs de los árboles
                        foreach ($arbol as $id_arbol):
                            $detalle_arbol = cargarInfoArbol((int) $id_arbol);
                        ?>
                            <div class="card card-offcanvas mb-3" style="width: 18rem;" data-id-arbol="<?php echo htmlspecialchars($id_arbol); ?>">
                                <img class="card-img-top" src="<?php echo $detalle_arbol['RUTA_FOTO_ARBOL'] ?>" alt="Imagen de árbol">
                                <div class="card-body">
                                    <p class="card-text"><?php echo "<b>Especie:</b> " . $detalle_arbol['ESPECIE'] ?></p>
                                    <p class="card-text"><?php echo "<b>Precio:</b> " . $detalle_arbol['PRECIO'] ?></p>

                                    <!-- Agregar un campo hidden para el ID del árbol -->
                                    <input type="hidden" name="arboles[]" value="<?php echo $detalle_arbol['ID_ARBOL']; ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <!-- Botón de confirmar compra -->
                    <button type="submit" class="btn btn-primary w-100 mt-3">Confirmar Compra</button>
                </form>
            <?php else: ?>
                <p>No hay árboles en el carrito de compras.</p>
            <?php endif; ?>

        </div>
    </div>

    <main>
        <div class="container mt-5">
            <h1 class="text-center text-success mb-4">Tienda de Árboles</h1>
            <div class="row">
                <!-- Carga en las cards los árboles disponibles -->
                <?php foreach ($arboles as $arbol): ?>
                    <div class="col-md-4 mb-4">
                        <!-- Form para agregar al carrito de compras el árbol -->
                        <form action="../actions/carrito_compras.php" method="POST">
                            <input type="hidden" name="accion" value="agregar">
                            <div class="card shadow-sm h-100">
                                <!-- Imagen del árbol -->
                                <img src="<?php echo htmlspecialchars($arbol['RUTA_FOTO_ARBOL']); ?>" class="card-img-top" alt="Imagen de árbol">

                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($arbol['ESPECIE']); ?></h5>

                                    <!-- Descripción del árbol -->
                                    <p class="card-text">
                                        <strong>Ubicación:</strong> <?php echo htmlspecialchars($arbol['UBICACION']); ?><br>
                                        <strong>Precio:</strong> $<?php echo number_format($arbol['PRECIO'], 2); ?>
                                    </p>
                                    <input type="hidden" name="id_arbol" value="<?php echo htmlspecialchars($arbol['ID_ARBOL']); ?>">
                                    <button type="submit" class="btn btn-success">Comprar</button>
                                </div>
                            </div>
                        </form>
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