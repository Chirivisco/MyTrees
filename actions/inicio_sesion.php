<?php
session_start();

require '../utils/functions.php';

// Verificar si la solicitud es un POST y si se han enviado los campos requeridos.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['contrasena'])) {
    
    // Comprobar que los campos no estén vacíos
    if ($_REQUEST['email'] !== "" && $_REQUEST['contrasena'] !== "") {
        $user['email'] = $_REQUEST['email'];
        $user['contrasena'] = $_REQUEST['contrasena'];

        // Intentar autenticar al usuario usando la función login proveniente de 'functions.php'.
        $resultado = login($user);

        // Validar si el login fue exitoso
        if ($resultado['success']) {

            $tipo_usuario = $resultado['tipo'];

            // guarda la info del usuario en la sesión.
            $_SESSION['email'] = $user['email'];
            $_SESSION['tipo'] = $tipo_usuario;

            // Redirige al menú correspondiente.
            if ($tipo_usuario === 'Admin') {
                header("Location: ../pantallas/menu_admin.php");
            } else {
                header("Location: ../pantallas/menu_usuario.php");
            }
            exit();
        } else {
            header("Location: ../index.php?error=invalid_credentials");
            exit();
        }
    } else {
        header("Location: ../index.php?error=empty_fields");
        exit();
    }
} else {
    header("Location: ../index.php?error=invalid_request");
    exit();
}
