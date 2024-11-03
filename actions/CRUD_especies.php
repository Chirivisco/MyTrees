<?php

require '../utils/functions.php';

// Verifica si la solicitud es un POST y si contiene el tipo de acción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'crear':
            // Verifica si los datos requeridos están presentes
            if (isset($_POST['nombre_comercial'], $_POST['nombre_cientifico']) &&
                !empty($_POST['nombre_comercial']) && !empty($_POST['nombre_cientifico'])) {
                
                $nombreComercial = $_POST['nombre_comercial'];
                $nombreCientifico = $_POST['nombre_cientifico'];

                // Llama al método para insertar una nueva especie
                if (insertarEspecie($nombreComercial, $nombreCientifico)) {
                    header("Location: ../pantallas/mantenimiento_especies.php?accion=creado_exitosamente");
                    exit();
                } else {
                    header("Location: ../pantallas/mantenimiento_especies.php?error=database_error");
                    exit();
                }
            } else {
                header("Location: ../pantallas/mantenimiento_especies.php?error=empty_fields");
                exit();
            }
            break;

        case 'actualizar':
            // Verifica si los datos requeridos están presentes
            if (isset($_POST['id_especie'], $_POST['nombre_comercial'], $_POST['nombre_cientifico']) &&
                !empty($_POST['id_especie']) && !empty($_POST['nombre_comercial']) && !empty($_POST['nombre_cientifico'])) {
                
                $idEspecie = (int) $_POST['id_especie'];
                $nombreComercial = $_POST['nombre_comercial'];
                $nombreCientifico = $_POST['nombre_cientifico'];

                // Llama al método para actualizar la especie
                if (actualizarEspecie($idEspecie, $nombreComercial, $nombreCientifico)) {
                    header("Location: ../pantallas/mantenimiento_especies.php?accion=actualizado_exitosamente");
                    exit();
                } else {
                    header("Location: ../pantallas/mantenimiento_especies.php?error=database_error");
                    exit();
                }
            } else {
                header("Location: ../pantallas/mantenimiento_especies.php?error=empty_fields");
                exit();
            }
            break;

        case 'borrar':
            // Verifica si el ID de la especie está presente
            if (isset($_POST['id_especie']) && !empty($_POST['id_especie'])) {
                $idEspecie = (int) $_POST['id_especie'];

                // Llama al método para borrar la especie
                if (borrarEspecie($idEspecie)) {
                    header("Location: ../pantallas/mantenimiento_especies.php?accion=borrado_exitosamente");
                    exit();
                } else {
                    header("Location: ../pantallas/mantenimiento_especies.php?error=database_error");
                    exit();
                }
            } else {
                header("Location: ../pantallas/mantenimiento_especies.php?error=empty_fields");
                exit();
            }
            break;

        default:
            header("Location: ../pantallas/mantenimiento_especies.php?error=accion_invalida");
            exit();
    }
} else {
    // Redirige si la solicitud no es válida
    header("Location: ../pantallas/mantenimiento_especies.php?error=invalid_request");
    exit();
}
?>
