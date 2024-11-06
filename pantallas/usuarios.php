<?php
require '../utils/functions.php';

session_start();

// Validar si la sesión está activa y que sea un administrador.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$usuarios = obtenerUsuarios();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - My Trees</title>
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
        <h1 class="text-center text-success mb-4">Lista de Usuarios</h1>

        <!-- Tabla de usuarios -->
        <table class="table table-bordered text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Agrega los usuarios -->
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['ID_USUARIO']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['NOMBRE_COMPLETO']); ?></td>
                            <td>
                                <form action="../actions/RU_arbol.php" method="POST">
                                    <input type="hidden" name="accion" value="ver_arboles">
                                    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario['ID_USUARIO']); ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Ver árboles</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

    <!-- Script para ver árboles del usuario -->
    <script>
        function verArbolesUsuario(idUsuario, nombreCompleto) {
            // Redirige a una página o abre un modal para ver los árboles del usuario
            window.location.href = `/pantallas/ver_arboles_usuario.php?id_usuario=${idUsuario}`;
        }
    </script>
</body>

</html>