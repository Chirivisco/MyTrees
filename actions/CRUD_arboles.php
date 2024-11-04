<?php
session_start();

require '../utils/functions.php';

// Valida que la sesión iniciada lleve un email y no esté vacío.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {

    // Redirige al login si no hay una sesión iniciada.
    header("Location: ../index.php");
    exit();
} else {

    // Verifica si la solicitud es un POST y si contiene el tipo de acción
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'crear':
                // Valida que los datos del arbol vayan en el POST
                if (
                    isset($_POST['especie'], $_POST['ubicacion'], $_POST['precio']) &&
                    !empty($_POST['especie']) && !empty($_POST['ubicacion']) && !empty($_POST['precio'])
                ) {

                    // valida si hay una imagen
                    if (!empty($_FILES['imagen']['name'])) {

                        $target_dir = '../imagenes/fotos_arboles/'; // ruta donde se guarda la imagen
                        $target_file = $target_dir . basename($_FILES['imagen']['name']); //ruta actual de la imagen
                        $uploadOk = false;

                        // valida que sea una imagen el archivo
                        $check = getimagesize($_FILES['imagen']['tmp_name']);
                        if ($check !== false) {
                            // es imagen
                            $uploadOk = true;
                        } else {
                            // no es imagen
                            $uploadOk = false;
                        }

                        // valida que el archivo no pese más de 500kb
                        if ($_FILES['imagen']['size'] > 500000) {
                            $uploadOk = false;
                        }

                        // agrega la imagen a la ruta indicada
                        if ($uploadOk == true) {
                            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                                // si no hay exito en agregar la imagen, redirige con un error
                                header("Location: ../pantallas/mantenimiento_arboles.php?error=image_upload_error");
                                exit();
                            }
                        } else {
                            header("Location: ../pantallas/mantenimiento_arboles.php?error=image_upload_error");
                            exit();
                        }
                    } else {
                        header("Location: ../pantallas/mantenimiento_arboles.php?error=no_image");
                        exit();
                    }

                    $arbol = [
                        'especie' => $_POST['especie'],
                        'ubicacion' => $_POST['ubicacion'],
                        'precio' => (float)$_POST['precio'],
                        'fotoArbol' => $target_file,
                        'estado' => 1 // estado: activo (default)
                    ];

                    // inserta el nuevo arbol
                    if (insertarArbol($arbol)) {
                        header("Location: ../pantallas/mantenimiento_arboles.php");
                        exit();
                    } else {
                        header("Location: ../pantallas/mantenimiento_arboles.php?error=database_error");
                        exit();
                    }
                } else {
                    header("Location: ../pantallas/mantenimiento_arboles.php?error=empty_fields");
                    exit();
                }
                break;

            case 'actualizar':

                // Verifica si los campos necesarios están presentes
                if (
                    isset($_POST['arbol_id'], $_POST['especie'], $_POST['ubicacion'], $_POST['precio']) &&
                    !empty($_POST['arbol_id']) && !empty($_POST['especie']) && !empty($_POST['ubicacion']) && !empty($_POST['precio'])
                ) {
                    $idArbol = (int)$_POST['arbol_id'];

                    // Verifica si se subió una imagen nueva
                    $fotoArbol = null;
                    if (!empty($_FILES['imagen']['name'])) {
                        $target_dir = '../imagenes/fotos_arboles/';
                        $target_file = $target_dir . basename($_FILES['imagen']['name']);
                        $uploadOk = false;

                        // valida que el archivo es una imagen
                        $check = getimagesize($_FILES['imagen']['tmp_name']);
                        if ($check !== false) {
                            $uploadOk = true;
                        } else {
                            $uploadOk = false;
                        }

                        // valida que el archivo no pese más de 500 KB
                        if ($_FILES['imagen']['size'] > 500000) {
                            $uploadOk = false;
                        }

                        // agrega la imagen a la ruta indicada
                        if ($uploadOk) {
                            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                                header("Location: ../pantallas/mantenimiento_arboles.php?error=image_upload_error");
                                exit();
                            } else {
                                $fotoArbol = $target_file;
                            }
                        } else {
                            header("Location: ../pantallas/mantenimiento_arboles.php?error=image_upload_error");
                            exit();
                        }
                    } else {
                        // si no se actualiza la imagen, se mantiene la misma
                        $arbolExistente = cargarInfoArbol($idArbol);
                        $fotoArbol = $arbolExistente['fotoArbol'];
                    }

                    $arbol = [
                        'idArbol' => $idArbol,
                        'especie' => $_POST['especie'],
                        'ubicacion' => $_POST['ubicacion'],
                        'precio' => (float)$_POST['precio'],
                        'fotoArbol' => $fotoArbol,
                        'estado' => $_POST['estado']
                    ];

                    // Llama al método para actualizar un árbol
                    if (actualizarArbol($arbol)) {
                        header("Location: ../pantallas/mantenimiento_arboles.php");
                        exit();
                    } else {
                        header("Location: ../pantallas/mantenimiento_arboles.php?error=database_error");
                        exit();
                    }
                } else {
                    header("Location: ../pantallas/mantenimiento_arboles.php?error=empty_fields");
                    exit();
                }
                break;

            default:
                header("Location: ../pantallas/mantenimiento_arboles.php?error=accion_invalida");
                exit();
        }
    } else {
        // Redirige si la solicitud no es válida
        header("Location: ../pantallas/mantenimiento_arboles.php?error=invalid_request");
        exit();
    }
}
