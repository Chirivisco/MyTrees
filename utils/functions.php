<?php

function getConnection(): bool|mysqli
{
    $connection = mysqli_connect('localhost', 'root', '', 'MyTrees');
    print_r(mysqli_connect_error());
    return $connection;
}

// Función para iniciar una nueva sesión con un usuario
function login($user)
{
    // Obtiene la conexión a la base de datos
    $connection = getConnection();

    // Consulta SQL para autenticación de usuario y obtener el tipo
    $query = "SELECT U.CONTRASENA, TU.TIPO AS TIPO_USUARIO FROM USUARIOS AS U INNER JOIN TIPOS_USUARIOS AS TU ON U.TIPO_USUARIO = TU.ID_TIPO WHERE U.USUARIO = ?;";

    $usuario = $user['email'];
    $contrasena = $user['contrasena'];

    try {
        // Prepara la consulta SQL
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error en la consulta SQL: " . $connection->error);
        } else {
            // Vincula los parámetros (usuario)
            $stmt->bind_param("s", $usuario);

            // Ejecuta la consulta
            if ($stmt->execute()) {

                // Obtiene el resultado de la consulta
                $result = $stmt->get_result();

                // Valida que el resultado tenga filas
                if ($result->num_rows > 0) {
                    // Obtiene la contraseña hasheada y el tipo de usuario
                    $row = $result->fetch_assoc();
                    $hashed_password = $row['CONTRASENA'];
                    $tipo_usuario = $row['TIPO_USUARIO'];

                    // Valida la contraseña hasheada
                    if (password_verify($contrasena, $hashed_password)) {
                        // Inicia una sesión
                        session_start();
                        $_SESSION["email"] = $usuario;
                        $_SESSION["tipo"] = $tipo_usuario;

                        // Retorna true y su rol de usuario
                        return ['success' => true, 'tipo' => $tipo_usuario];
                    } else {
                        // Retorna false y null si la autenticación falló
                        return ['success' => false, 'tipo' => null];
                    }
                } else {
                    // Retorna false y null si no hay registros o resultados del query
                    return ['success' => false, 'tipo' => null];
                }
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Redirige en caso de error y detiene la ejecución
        header("Location: ../index.php?error=database_error");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para verificar si un correo ya existe en la base de datos
function validar_correo($email)
{
    // Obtiene la conexión a la base de datos
    $connection = getConnection();

    $query = "SELECT 1 FROM USUARIOS WHERE USUARIO = ?";

    try {
        // Prepara la consulta SQL
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error en la consulta SQL: " . $connection->error);
        } else {
            // Vincula los parámetros (correo)
            $stmt->bind_param("s", $email);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                // Obtiene el resultado de la consulta
                $result = $stmt->get_result();

                // Valida si el correo ya está registrado
                if ($result->num_rows > 0) {
                    return true; // El correo ya existe
                } else {
                    return false; // El correo no existe
                }
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Redirige en caso de error y detiene la ejecución
        header("Location: ../pantallas/signup.php?error=database_error");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para registrar un nuevo usuario y retornar el ID generado
function signup($user): int
{
    // Obtiene la conexión a la base de datos
    $connection = getConnection();

    // Consulta SQL para insertar un nuevo usuario
    $query = "INSERT INTO USUARIOS (TIPO_USUARIO, USUARIO, CONTRASENA, NOMBRE, APELLIDOS, PAIS) VALUES (?, ?, ?, ?, ?, ?)";

    $tipo_usuario = 2;
    $usuario = $user['correo'];
    $contrasena = password_hash($user['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña
    $nombre = $user['nombre'];
    $apellidos = $user['apellidos'];
    $pais = $user['pais'];

    try {
        // Prepara la consulta SQL
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la consulta SQL: " . $connection->error);
        } else {
            // Vincula los parámetros
            $stmt->bind_param("isssss", $tipo_usuario, $usuario, $contrasena, $nombre, $apellidos, $pais);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                // Retorna el ID del último usuario insertado
                return $connection->insert_id;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Retorna 0 como ID del usuario indicando error
        return 0;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para insertar direcciones y teléfonos del usuario
function insertarDetalleUsuario($idUsuario, $direcciones, $telefonos)
{
    $connection = getConnection();

    try {
        // Query para insertar direcciones
        $queryDireccion = "INSERT INTO DIRECCIONES_USUARIOS (ID_USUARIO, DIRECCION) VALUES (?, ?)";
        $stmt1 = $connection->prepare($queryDireccion);
        if (!$stmt1) {
            throw new Exception("Error en la preparación de la consulta de direcciones: " . $connection->error);
        }

        // Agrega las direcciones
        foreach ($direcciones as $direccion) {
            if (!empty($direccion)) {
                $stmt1->bind_param("is", $idUsuario, $direccion);
                $stmt1->execute();
            }
        }

        // Query para insertar teléfonos
        $queryTelefono = "INSERT INTO TELEFONOS_USUARIOS (ID_USUARIO, TELEFONO) VALUES (?, ?)";
        $stmt2 = $connection->prepare($queryTelefono);
        if (!$stmt2) {
            throw new Exception("Error en la preparación de la consulta de teléfonos: " . $connection->error);
        }

        // Agrega los teléfonos
        foreach ($telefonos as $telefono) {
            if (!empty($telefono)) {
                $stmt2->bind_param("is", $idUsuario, $telefono);
                $stmt2->execute();
            }
        }

        return true;
    } catch (Exception $e) {
        header("Location: ../pantallas/signup.php?error=database_error");
        exit();
    } finally {
        if (isset($stmt1)) {
            $stmt1->close();
        }
        if (isset($stmt2)) {
            $stmt2->close();
        }
        mysqli_close($connection);
    }
}

// Función para obtener las estadísticas del dashboard principal del administrador (conteo de usuarios, arboles vendidos y disponibles)w
function obtener_estadisticas()
{
    $connection = getConnection();
    try {
        $estadisticas = [];

        // Query para contar Amigos
        $queryUsuarios = "SELECT COUNT(U.USUARIO) AS total_usuarios FROM USUARIOS AS U INNER JOIN TIPOS_USUARIOS AS TU ON U.TIPO_USUARIO = TU.ID_TIPO WHERE TU.TIPO = 'Amigo';";
        $stmt1 = $connection->prepare($queryUsuarios);
        if (!$stmt1) {
            throw new Exception("Error en la preparación de la consulta de usuarios: " . $connection->error);
        } else {
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $row = $result1->fetch_assoc();
            // Agrega los resultados al array 'estadisticas'
            $estadisticas['total_usuarios'] = $row['total_usuarios'];
        }

        // Query para contar árboles vendidos
        $queryAV = "SELECT COUNT(*) AS total_arboles_vendidos FROM ARBOLES_VENDIDOS";
        $stmt2 = $connection->prepare($queryAV);
        if (!$stmt2) {
            throw new Exception("Error en la preparación de la consulta de árboles vendidos: " . $connection->error);
        } else {
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row = $result2->fetch_assoc();
            // Agrega los resultados al array 'estadisticas'
            $estadisticas['total_arboles_vendidos'] = $row['total_arboles_vendidos'];
        }

        // Query para contar árboles disponibles
        $queryAD = "SELECT COUNT(*) AS total_arboles_disponibles FROM ARBOLES WHERE ID_ARBOL NOT IN (SELECT ID_ARBOL FROM ARBOLES_VENDIDOS)";
        $stmt3 = $connection->prepare($queryAD);
        if (!$stmt3) {
            throw new Exception("Error en la preparación de la consulta de árboles disponibles: " . $connection->error);
        } else {
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $row = $result3->fetch_assoc();
            // Agrega los resultados al array 'estadisticas'
            $estadisticas['total_arboles_disponibles'] = $row['total_arboles_disponibles'];
        }

        // Devuelve el array de estadísticas
        return $estadisticas;
    } catch (Exception $e) {
        header("Location: ../pantallas/dashboard.php?error=database_error");
        exit();
    } finally {
        if (isset($stmt1)) {
            $stmt1->close();
        }
        if (isset($stmt2)) {
            $stmt2->close();
        }
        if (isset($stmt3)) {
            $stmt3->close();
        }
        mysqli_close($connection);
    }
}

// Función para insertar una nueva especie de árbol
function insertarEspecie($nombreComercial, $nombreCientifico): bool
{
    $connection = getConnection();
    $query = "INSERT INTO ESPECIES_ARBOLES (NOMBRE_COMERCIAL, NOMBRE_CIENTIFICO) VALUES (?, ?)";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param("ss", $nombreComercial, $nombreCientifico);

            // Retorna true si la ejecución fue exitosa
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Retorna false en caso de error
        return false;
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para obtener el ID de una especie a partir del nombre comercial y científico
function obtenerIdEspecie($nombreComercial, $nombreCientifico): int
{
    $connection = getConnection();
    $query = "SELECT ID_ESPECIE FROM ESPECIES_ARBOLES WHERE NOMBRE_COMERCIAL = ? AND NOMBRE_CIENTIFICO = ?";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param("ss", $nombreComercial, $nombreCientifico);
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    return (int) $row['ID_ESPECIE'];
                } else {
                    return 0;
                }
                
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
        }
    } catch (Exception $e) {
        return 0;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para actualizar una especie existente
function actualizarEspecie($idEspecie, $nombreComercial, $nombreCientifico): bool
{
    $connection = getConnection();
    $query = "UPDATE ESPECIES_ARBOLES SET NOMBRE_COMERCIAL = ?, NOMBRE_CIENTIFICO = ? WHERE ID_ESPECIE = ?";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param("ssi", $nombreComercial, $nombreCientifico, $idEspecie);

            // Retorna true si la ejecución fue exitosa
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Retorna false en caso de error
        return false;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para eliminar una especie de árbol
function borrarEspecie($idEspecie): bool
{
    $connection = getConnection();
    $query = "DELETE FROM ESPECIES_ARBOLES WHERE ID_ESPECIE = ?";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param("i", $idEspecie);

            // Retorna true si la ejecución fue exitosa
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Retorna false en caso de error
        return false;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para cargar todas las especies de árboles
function cargarEspecies(): array
{
    $connection = getConnection();
    $especies = [];

    $query = "SELECT ID_ESPECIE, NOMBRE_COMERCIAL, NOMBRE_CIENTIFICO FROM ESPECIES_ARBOLES";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                // Guarda en un array las especies de la bd
                while ($row = $result->fetch_assoc()) {
                    $especies[] = $row;
                }
                // Retorna el array con las epsecies
                return $especies;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        return [];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}
