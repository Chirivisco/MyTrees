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
    $query = "SELECT U.CONTRASENA, TU.TIPO AS TIPO_USUARIO, U.ID_USUARIO FROM USUARIOS AS U INNER JOIN TIPOS_USUARIOS AS TU ON U.TIPO_USUARIO = TU.ID_TIPO WHERE U.USUARIO = ?;";

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
                    $id_usuario = $row['ID_USUARIO'];

                    // Valida la contraseña hasheada
                    if (password_verify($contrasena, $hashed_password)) {
                        // Inicia una sesión
                        session_start();
                        $_SESSION["email"] = $usuario;
                        $_SESSION["tipo"] = $tipo_usuario;

                        // Retorna true y su rol de usuario
                        return ['success' => true, 'tipo' => $tipo_usuario, 'id_usuario' => $id_usuario];
                    } else {
                        // Retorna false y null si la autenticación falló
                        return ['success' => false, 'tipo' => null, 'id_usuario' => 0, 'error' => 1];
                    }
                } else {
                    // Retorna false y null si no hay registros o resultados del query
                    return ['success' => false, 'tipo' => null, 'id_usuario' => 0, 'error' => 1];
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

// Función que carga los usuarios del sistema.
function obtenerUsuarios()
{
    // Obtiene la conexión a la base de datos
    $connection = getConnection();
    $usuarios = [];
    $query = "SELECT U.ID_USUARIOS, TU.TIPO, U.USUARIO, CONCAT(NOMBRE, ' ', APELLIDOS) as NOMBRE_COMPLETO FROM USUARIOS AS U INNER JOIN TIPOS_USUARIOS AS TU ON U.TIPO_USUARIO = TU.ID_TIPO;";

    try {
        // Prepara la consulta SQL
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error en la consulta SQL: " . $connection->error);
        } else {

            // Ejecuta la consulta
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                // Guarda en un array los usuarios de la bd
                while ($row = $result->fetch_assoc()) {
                    $usuarios[] = $row;
                }
                // Retorna el array con las usuarios
                return $usuarios;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Redirige en caso de error y detiene la ejecución
        return $usuarios;
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

// Función para obtener todos los arboles de la bd
function obtenerArboles($tipo_usuario)
{
    // Obtiene la conexión a la base de datos
    $connection = getConnection();
    $arboles = [];
    $query = "";

    if ($tipo_usuario == "Admin") {
        $query = "SELECT * FROM vista_arboles_admin;";
    } else {
        $query = "SELECT * FROM vista_arboles_disponibles;";
    }


    try {
        // Prepara la consulta SQL
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error en la consulta SQL: " . $connection->error);
        } else {

            // Ejecuta la consulta
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                // Guarda en un array los árboles
                while ($row = $result->fetch_assoc()) {
                    $arboles[] = $row;
                }
                // Retorna el array con los árboles
                return $arboles;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        // Redirige en caso de error y detiene la ejecución
        return $arboles;
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para insertar un nuevo árbol utilizando un array de datos
function insertarArbol(array $arbol): bool
{
    $connection = getConnection();
    $query = "INSERT INTO ARBOLES (ESPECIE, UBICACION, PRECIO, FOTO_ARBOL, ESTADO) VALUES (?, ?, ?, ?, ?)";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param(
                "isdsi",
                $arbol['especie'],
                $arbol['ubicacion'],
                $arbol['precio'],
                $arbol['fotoArbol'],
                $arbol['estado']
            );

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

// Función para actualizar un árbol utilizando un array de datos
function actualizarArbol(array $arbol): bool
{
    $connection = getConnection();
    $query = "UPDATE ARBOLES SET ESPECIE = ?, UBICACION = ?, PRECIO = ?, FOTO_ARBOL = ?, ESTADO = ? WHERE ID_ARBOL = ?";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param(
                "isdsii",
                $arbol['especie'],
                $arbol['ubicacion'],
                $arbol['precio'],
                $arbol['fotoArbol'],
                $arbol['estado'],
                $arbol['idArbol']
            );

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

// Función para cargar la información de un árbol específico
function cargarInfoArbol(int $idArbol): array
{
    $connection = getConnection();
    $arbol = [];

    $query = "SELECT * FROM VISTA_ARBOL_INFO WHERE ID_ARBOL = ?";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param("i", $idArbol);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $arbol = $result->fetch_assoc();
                }
                return $arbol;
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

// Función para cargar todos los estados de usuarios
function cargarEstados(): array
{
    $connection = getConnection();
    $estados = [];

    $query = "SELECT * FROM ESTADOS;";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $estados[] = $row;
                }
                return $estados;
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

// Función para crear un nuevo carrito de compras o verificar si ya existe 
function crearCarritoCompras($idUsuario): int
{
    $connection = getConnection();

    try {
        // valida si el usuario ya tiene un carrito
        $queryVerificar = "SELECT ID_CARRITO FROM CARRITO_COMPRAS WHERE USUARIO = ?";
        $stmtVerificar = $connection->prepare($queryVerificar);

        if (!$stmtVerificar) {
            throw new Exception("Error en el statement de la consulta de verificación de carrito: " . $connection->error);
        }

        $stmtVerificar->bind_param("i", $idUsuario);
        $stmtVerificar->execute();
        $stmtVerificar->store_result();

        if ($stmtVerificar->num_rows > 0) {
            // Obtener el ID del carrito existente
            $stmtVerificar->bind_result($idCarritoExistente);
            $stmtVerificar->fetch();
            return $idCarritoExistente;
        }

        // Cerrar la consulta de verificación
        $stmtVerificar->close();

        // inserta un nuevo carrito para el usuario
        $queryCarrito = "INSERT INTO CARRITO_COMPRAS (USUARIO) VALUES (?)";
        $stmt = $connection->prepare($queryCarrito);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de carrito: " . $connection->error);
        }

        $stmt->bind_param("i", $idUsuario);

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar el carrito: " . $stmt->error);
        }

        // Retorna el ID del carrito creado
        return $connection->insert_id;
    } catch (Exception $e) {
        // Retorna 0 en caso de error
        return 0;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para agregar  de compra al carrito
function agregarDetalleCarrito($idCarrito, $idArbol): bool
{
    $connection = getConnection();

    try {
        $queryDetalle = "INSERT INTO DETALLE_CARRITO_COMPRAS (CARRITO, ARBOL) VALUES (?, ?)";
        $stmt = $connection->prepare($queryDetalle);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de detalle de carrito: " . $connection->error);
        } else {
            $stmt->bind_param("ii", $idCarrito, $idArbol);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar detalle de carrito: " . $stmt->error);
            } else {
                return true;
            }
        }
    } catch (Exception $e) {
        return false;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}

// Función para cargar todos los árboles en el carrito de un usuario
function cargarArbolesCarrito($idUsuario): array
{
    $connection = getConnection();
    $arboles = [];

    $query = "SELECT ARBOL FROM VISTA_ARBOLES_CARRITO WHERE USUARIO = ?;";

    try {
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta SQL: " . $connection->error);
        } else {
            $stmt->bind_param("i", $idUsuario);
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $arboles[] = $row;
                }
                return $arboles;
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

// Función para confirmar la compra de un árbol
function agregarVenta($idArbol, $idDueno): bool
{
    $connection = getConnection();

    try {
        $queryVenta = "INSERT INTO ARBOLES_VENDIDOS (ID_ARBOL, ID_DUENO) VALUES (?, ?)";
        $stmt = $connection->prepare($queryVenta);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de venta: " . $connection->error);
        } else {
            $stmt->bind_param("ii", $idArbol, $idDueno);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar la venta: " . $stmt->error);
            } else {
                return true;
            }
        }
    } catch (Exception $e) {
        return false;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        mysqli_close($connection);
    }
}