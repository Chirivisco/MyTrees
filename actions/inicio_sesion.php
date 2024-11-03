<?php

require '../utils/functions.php';

// Verificar si la solicitud es un POST y si se han enviado los campos requeridos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['contrasena'])) {
    
    // Comprobar que los campos no estén vacíos
    if ($_REQUEST['email'] !== "" && $_REQUEST['contrasena'] !== "") {
        $user['email'] = $_REQUEST['email'];
        $user['contrasena'] = $_REQUEST['contrasena'];

        // Intentar autenticar al usuario usando la función login proveniente de 'functions.php'
        $resultado = login($user);

        // Validar si el login fue exitoso
        if ($resultado['success']) {

            // Obtiene el el tipo de usuario
            $tipo_usuario = $resultado['tipo'];
            
            if ($tipo_usuario === 'Admin') {
                // Login de usuario 'admin'
                header("Location: ../pantallas/menu_admin.php");
            } else {
                // Login de usuario 'amigo'
                header("Location: ../pantallas/menu_usuario.php");
            }
            exit();
        } else {
            // Redirigir de nuevo a la página de login con un mensaje de error
            header("Location: ../index.php?error=invalid_credentials");
            exit();
        }
    } else {
        // Si los campos están vacíos, redirigir con un mensaje de error
        header("Location: ../index.php?error=empty_fields");
        exit();
    }
} else {
    // Si la solicitud no es un POST, redirigir con un mensaje de error de solicitud inválida
    header("Location: ../index.php?error=invalid_request");
    exit();
}
