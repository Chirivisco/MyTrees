<?php
session_start();

require '../utils/functions.php';

// Valida que la sesión iniciada lleve un email y no esté vacío.
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['tipo'] !== 'Admin') {

    // Redirige al login si no hay una sesión iniciada.
    header("Location: ../index.php");
    exit();
    
} else {

    // Valida si la solicitud es un POST y si contiene el tipo de acción.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'crear':
                // Valida que los datos se encuentren en el POST.
                if (
                    isset($_POST['nombre_comercial'], $_POST['nombre_cientifico']) && !empty($_POST['nombre_comercial']) && !empty($_POST['nombre_cientifico'])
                ) {

                    $nombreComercial = $_POST['nombre_comercial'];
                    $nombreCientifico = $_POST['nombre_cientifico'];

                    // Llama al método para insertar una nueva especie.
                    if (insertarEspecie($nombreComercial, $nombreCientifico)) {
                        header("Location: ../pantallas/mantenimiento_especies.php");
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
                // Valida que los datos se encuentren en el POST.
                if (
                    isset($_POST['nombre_comercial'], $_POST['nombre_cientifico'], $_POST['current_nombre_comercial'], $_POST['current_nombre_cientifico']) &&
                    !empty($_POST['nombre_comercial']) && !empty($_POST['nombre_cientifico'])
                ) {

                    $nombreComercial = $_POST['nombre_comercial'];
                    $nombreCientifico = $_POST['nombre_cientifico'];

                    $nombreComercial_actual = $_POST['current_nombre_comercial'];
                    $nombreCientifico_actual = $_POST['current_nombre_cientifico'];

                    // Obtener el ID usando los nombres actuales
                    $idEspecie = obtenerIdEspecie($nombreComercial_actual, $nombreCientifico_actual);

                    // Actualizar la especie
                    if (actualizarEspecie($idEspecie, $nombreComercial, $nombreCientifico)) {
                        header("Location: ../pantallas/mantenimiento_especies.php");
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
                // Valida que el ID de la especie vaya en el POST.
                if (isset($_POST['id_especie']) && !empty($_POST['id_especie'])) {
                    $idEspecie = $_POST['id_especie'];

                    if (borrarEspecie($idEspecie)) {
                        header("Location: ../pantallas/mantenimiento_especies.php");
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
        // Redirige si la solicitud no es válida.
        header("Location: ../pantallas/mantenimiento_especies.php?error=invalid_request");
        exit();
    }
}
