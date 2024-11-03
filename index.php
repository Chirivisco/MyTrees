<?php
// Manejo de errores (lado servidor)
$error = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'empty_fields') {
        $error = "Todos los campos son obligatorios.";
    } elseif ($_GET['error'] === 'invalid_credentials') {
        $error = "Error al autenticar el usuario.";
    } elseif ($_GET['error'] === 'invalid_request') {
        $error = "Solicitud no válida.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/stylesheets/stylesheet_login.css">
    <link rel="icon" href="/imagenes/app_icon.png" type="image/x-icon"> 
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Muestra el error del servidor -->
        <?php if ($error): ?>
            <div id="php-error-box">
                <p id="php-error-msg"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="/actions/inicio_sesion.php">
            <div class = "form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class = "form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <p>No tiene cuenta? <a href="/pantallas/signup.php">Registrese</a></p>
            <button type="submit">Login</button>
        </form>
    </div>
    
</body>
</html>
