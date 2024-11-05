<?php

session_start();

require '../utils/functions.php';

// Valida que la sesión iniciada lleve un email y no esté vacío.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Amigo') {

    // Redirige al login si no hay una sesión iniciada.
    header("Location: ../index.php");
    exit();
} else {
    // Verifica si la solicitud es un POST y si contiene el tipo de acción
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        
        $accion = $_POST['accion'];

        switch ($accion) {
            // accion para agregar al carrito de compras.
            case 'agregar':
                // validar los datos del POST
                if(isset($_POST['id_arbol'], $_SESSION['id_usuario']) && !empty($_POST['id_arbol']) && !empty($_SESSION['id_usuario'])) {
                    $id_usuario = $_SESSION['id_usuario'];
                    $id_arbol = $_POST['id_arbol'];

                    $id_carrito = crearCarritoCompras($id_usuario);
                    // valida que se haya creado el nuevo carrito
                    if ($id_carrito > 0) {
                        if (agregarDetalleCarrito($id_carrito, $id_arbol)) {
                            // Acá debe actualizar el offcanvas para mostrar allí el arbol...
                            // se me ocurre que esto se haga por medio de AJAX o algo para no tener que recargar toda la página y solo el offcanvas
                            
                        }
                    }
                } else {
                    header("Location: ../pantallas/menu_amigo.php?error=no_data");
                    exit();
                }

                break;
            // accion para borrar un item del carrito de compras.
            case 'borrar':
                // validar los datos del POST
                
                break;
            // accion erronea
            default:
                header("Location: ../pantallas/menu_amigo.php?error=accion_invalida");
                exit();
        }
    } else {
        // Redirige si la solicitud no es válida
        header("Location: ../pantallas/menu_amigo.php?error=invalid_request");
        exit();
    }
}