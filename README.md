<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>README - Asociación de Amigos de Un Millón de Árboles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 2rem;
        }
        .section-title {
            color: #198754;
            margin-top: 1.5rem;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        .list-group-item {
            background-color: #e9ecef;
            border-color: #ced4da;
        }
        .code {
            font-family: monospace;
            background-color: #f1f1f1;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="text-center mb-4">
            <h1 class="display-5">Asociación de Amigos de Un Millón de Árboles</h1>
            <p class="lead">Aplicación web para la recaudación de fondos y gestión de árboles</p>
        </header>

        <section>
            <h2 class="section-title">Requerimientos</h2>
            <p>La Asociación de Amigos de Un Millón de Árboles necesita una aplicación web para apoyar sus iniciativas de reforestación mediante la recaudación de fondos. La aplicación deberá cumplir con los siguientes requerimientos funcionales y técnicos.</p>
        </section>

        <section>
            <h2 class="section-title">Requerimientos Funcionales</h2>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Roles de Usuarios:</strong> Existen dos tipos de usuarios: <span class="code">Administradores</span> y <span class="code">Amigos</span>.</li>
                <li class="list-group-item"><strong>Autenticación:</strong> Es necesario un usuario y una contraseña para ingresar al sistema.</li>
                <li class="list-group-item"><strong>Administrador Predeterminado:</strong> La aplicación tendrá un usuario administrador por defecto.</li>
            </ul>

            <h3 class="section-title">Perspectiva de Administrador</h3>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Acceso Restringido:</strong> Solo los administradores pueden acceder a esta sección; otros usuarios serán redireccionados a una página de acceso denegado.</li>
                <li class="list-group-item"><strong>Dashboard:</strong> La página de inicio muestra estadísticas como:
                    <ul>
                        <li>Cantidad de Amigos Registrados</li>
                        <li>Cantidad de Árboles Disponibles</li>
                        <li>Cantidad de Árboles Vendidos</li>
                    </ul>
                </li>
                <li class="list-group-item"><strong>Administración de Especies:</strong> CRUD de especies de árboles con datos de Nombre Comercial y Nombre Científico.</li>
                <li class="list-group-item"><strong>Administración de Árboles de Amigos:</strong> Ver, editar y registrar actualizaciones de árboles de usuarios amigos.</li>
                <li class="list-group-item"><strong>Administración de Árboles en Venta:</strong> Crear árboles con datos como especie, ubicación, estado, precio y foto.</li>
                <li class="list-group-item"><strong>Cerrar Sesión:</strong> Opción para que el administrador cierre sesión.</li>
            </ul>

            <h3 class="section-title">Perspectiva de Amigo</h3>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Registro de Amigo:</strong> Permite registrar amigos con datos como nombre, apellidos, teléfono, correo, dirección y país.</li>
                <li class="list-group-item"><strong>Lista de Árboles Disponibles:</strong> Los amigos pueden ver la lista de árboles en venta.</li>
                <li class="list-group-item"><strong>Compra de Árboles:</strong> Permite solicitar la compra de un árbol en estado "Disponible".</li>
                <li class="list-group-item"><strong>Listado de Árboles Propios:</strong> Los amigos pueden ver, pero no editar, la información de los árboles que han comprado.</li>
            </ul>
        </section>

        <section>
            <h2 class="section-title">Cronjob</h2>
            <p>Se desarrollará un script que enviará un correo al administrador si algún árbol no ha sido actualizado en más de un mes. El correo incluirá un listado de árboles pendientes de actualización.</p>
        </section>

        <section>
            <h2 class="section-title">Requerimientos Técnicos</h2>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Framework CSS:</strong> Utilizar un framework CSS como Bootstrap.</li>
                <li class="list-group-item"><strong>Base de Datos:</strong> Libre elección de la base de datos a utilizar.</li>
                <li class="list-group-item"><strong>Sin MVC:</strong> No se permite el uso de frameworks MVC.</li>
                <li class="list-group-item"><strong>Lenguaje del Servidor:</strong> Debe emplearse un lenguaje de programación del lado del servidor (ej., PHP).</li>
                <li class="list-group-item"><strong>Git:</strong> Uso de Git para el control de versiones, mostrando commits desde el primer día.</li>
                <li class="list-group-item"><strong>Validación de Formularios:</strong> Validar datos en todos los formularios del proyecto.</li>
                <li class="list-group-item"><strong>Buenas Prácticas:</strong> Asegurar un buen formato de código, nombres descriptivos y documentación adecuada.</li>
            </ul>
        </section>

        <footer class="text-center mt-5">
            <p class="small">&copy; 2024 Asociación de Amigos de Un Millón de Árboles</p>
        </footer>
    </div>
</body>
</html>
