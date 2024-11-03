<?php
// Manejo de errores (lado servidor)
$error = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'empty_fields') {
        $error = "Todos los campos son obligatorios.";
    } elseif ($_GET['error'] === 'invalid_email') {
        $error = "El correo electrónico no es válido.";
    } elseif ($_GET['error'] === 'email_exists') {
        $error = "El correo electrónico ya está registrado.";
    } elseif ($_GET['error'] === 'invalid_request') {
        $error = "Solicitud no válida.";
    } elseif($_GET['error'] === 'database_error') {
        $error = "Error al registrar el usuario. Inténtelo de nuevo.";
    } elseif($_GET['registro'] === 'exitoso') {
        $error = "Usuario registrado correctamente!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_signup.css">
    <link rel="icon" href="/imagenes/app_icon.png" type="image/x-icon"> 
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>

        <!-- Muestra el error del servidor -->
        <?php if ($error): ?>
            <div id="php-error-box">
                <p id="php-error-msg"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="/actions/registrar_usuario.php">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </div>

            <div id="form-group-telefono">
                <label for="telefono">Número telefónico</label>
                <div class="input-button">
                    <input type="text" id="telefono" name="telefono[]" required>
                    <button type="button" id="add-telefono-btn">+</button>
                </div>
            </div>

            <div class="form-group">
                <label for="correo">Email</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>

            <div id="form-group-direccion">
                <label for="direccion">Dirección</label>
                <div class="input-button">
                    <input type="text" id="direccion" name="direccion[]" required>
                    <button type="button" id="add-direccion-btn">+</button>
                </div>
            </div>

            <div class="form-group">
                <label for="pais">País</label>
                <select id="pais" name="pais" required>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Panama">Panamá</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Spain">Spain</option>
                    <option value="Germany">Germany</option>
                </select>
            </div>

            <p>Tienes una cuenta? <a href="/index.php">Inicia sesión</a></p>
            <button type="submit">Sign Up</button>
        </form>
    </div>
    <script src="/utils/app.js"></script>
</body>
</html>
