<?php

require '../utils/functions.php';

// Verificar si la solicitud es un POST y si se han enviado los campos requeridos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellidos'], $_POST['telefono'], $_POST['correo'], $_POST['contrasena'], $_POST['direccion'], $_POST['pais'])) {
       
    // Comprobar que los campos no estén vacíos
    if (!empty($_POST['nombre']) && !empty($_POST['apellidos']) && !empty($_POST['telefono']) && !empty($_POST['correo']) && !empty($_POST['contrasena']) && !empty($_POST['direccion']) && !empty($_POST['pais'])) {
        
        // Crear lista con los datos del usuario
        $nuevoUsuario = [
            'nombre' => $_POST['nombre'],
            'apellidos' => $_POST['apellidos'],
            'telefono' => $_POST['telefono'],
            'correo' => $_POST['correo'],
            'contrasena' => $_POST['contrasena'],
            'direccion' => $_POST['direccion'],
            'pais' => $_POST['pais']
        ];

        // Verificar si el correo ya existe
        if (validar_correo($nuevoUsuario['correo'])) {
            header("Location: ../pantallas/signup.php?error=email_exists");
            exit();
        } else {

            $idUsuario = signup($nuevoUsuario);

            // Validar que el usuario se haya creado
            if ($idUsuario > 0) {
               // Llamar a la función para insertar las distintas direcciones y teléfonos
                insertarDetalleUsuario($idUsuario, $_POST['direccion'], $_POST['telefono']);

                header("Location: ../pantallas/signup.php?registro=exitoso");
                exit();
            } else {
                // Redirigir con un error de base de datos si el registro falla
                header("Location: ../pantallas/signup.php?error=database_error");
                exit();
            }
        }
    } else {
        // Si hay campos vacíos, redirigir con un mensaje de error
        header("Location: ../pantallas/signup.php?error=empty_fields");
        exit();
    }
} else {
    // Si la solicitud no es un POST, redirigir con un mensaje de error de solicitud inválida
    header("Location: ../pantallas/signup.php?error=invalid_request");
    exit();
}
?>
