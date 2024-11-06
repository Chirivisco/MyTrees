<?php

session_start();

require '../utils/functions.php';

// Valida que la sesión iniciada lleve un email y no esté vacío.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {

    // Redirige al login si no hay una sesión iniciada.
    header("Location: ../index.php");
    exit();
} else {
    // valida si la solicitud es un POST
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'], $_POST['accion'])
        && !empty($_POST['id_usuario']) && !empty($_POST['accion'])
    ) {
        $accion = $_POST['accion'];

        switch ($accion) {
            
            // acción para cargar el response con los árboles del usuario
            case 'ver_arboles':

                $id_usuario = $_POST['id_usuario'];

                // obtiene los IDs de los árboles adquiridos por un usuario 
                $arboles_adquiridos = obtenerMisArboles($id_usuario);
                
                // valida que el usuario tenga árboles adquiridos
                if (!empty($arboles_adquiridos)) {
                    header("Location: ../pantallas/arbol_usuario.php");
                    exit();
                } else {
                    // redirige con un mensaje de error indicando que el usuario no posee árboles adquiridos
                    header("Location: ../pantallas/arbol_usuario.php?error=no_trees_to_view");
                    exit();
                }

            default:
                header("Location: ../pantallas/usuarios.php?error=accion_invalida");
                exit();
        }
    } else {
        // Redirige si la solicitud no es válida
        header("Location: ../pantallas/usuarios.php?error=invalid_request");
        exit();
    }
}
