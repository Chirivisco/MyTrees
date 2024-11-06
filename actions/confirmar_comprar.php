 <?php

    session_start();

    require '../utils/functions.php';

    // Valida que la sesión iniciada lleve un email y no esté vacío.
    if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Amigo') {

        // Redirige al login si no hay una sesión iniciada.
        header("Location: ../index.php");
        exit();
    } else {
        // valida si la solicitud es un POST y tiene la lista de arboles del carrito de compras junto con el usuario
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['arboles'])
        ) {
            $arboles = $_POST['arboles'];
            $idDueno = $_SESSION['id_usuario'];

            foreach ($arboles as $idArbol) {
                $venta = agregarVenta($idArbol, $idDueno);
                
                if (!$venta) {
                    header("Location: ../pantallas/menu_amigo.php?error=failed_purchase");
                } else {
                    // borra el árbol del carrito después de la compra
                    $carritoEliminado = eliminarArbolDelCarrito($idArbol, $_SESSION['id_usuario']);

                    if (!$carritoEliminado) {
                        header("Location: ../pantallas/menu_amigo.php?error=failed_cart_removal");
                        exit();
                    } else {
                        // redirige si logró borrar el árbol del carrito de compras
                        header("Location: ../pantallas/menu_amigo.php");
                        exit();
                    }
                }
            }
        } else {
            // Redirige si la solicitud no es válida
            header("Location: ../pantallas/menu_amigo.php?error=invalid_request");
            exit();
        }
    }
    ?>