function checkLoginInputs(e) {
    e.preventDefault();

    const correo = document.querySelector('#correo');
    const contrasena = document.querySelector('#contrasena');
    const mensajeError = document.querySelector('#error-msg');

    // Limpiar el mensaje de error previo
    mensajeError.textContent = '';
    mensajeError.style.display = 'none'; // Ocultar mensaje inicialmente

    // Variable que almacena el estado de la validación
    let estado = true;

    // Valida la existencia del correo
    if (correo.value.trim() === "") {
        mensajeError.textContent = "El correo no puede estar vacío.";
        mensajeError.style.display = 'block';
        estado = false;
    }

    // Valida la existencia de una contraseña
    if (contrasena.value.trim() === "") {
        mensajeError.textContent = "La contraseña no puede estar vacía.";
        mensajeError.style.display = 'block';
        estado = false;
    }

    // Permite el flujo del sistema si la validación pasa
    if (estado) {
        e.target.submit();
    }
}

// Función para añadir un nuevo campo de número telefónico con un botón de eliminación
function addTelefonoField() {
    const container = document.getElementById('form-group-telefono');

    // Crear contenedor div para input y botón de eliminación
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-button'); // Usa la clase para alinear elementos

    // Crear el input de teléfono
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'telefono[]';
    input.required = true;
    input.placeholder = 'Número telefónico adicional';
    input.classList.add('additional-input');
    input.style.marginTop = '10px';

    // Crear el botón de eliminación
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.textContent = '-';
    deleteBtn.className = 'delete-input-btn';
    deleteBtn.style.marginTop = '10px';

    deleteBtn.onclick = function () {
        inputGroup.remove();
    };

    // Añadir el input y el botón al contenedor
    inputGroup.appendChild(input);
    inputGroup.appendChild(deleteBtn);

    // Añadir el contenedor al DOM
    container.appendChild(inputGroup);
}

// Función para añadir un nuevo campo de dirección con un botón de eliminación
function addDireccionField() {
    const container = document.getElementById('form-group-direccion');

    // Crear contenedor div para input y botón de eliminación
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-button'); // Usa la clase para alinear elementos

    // Crear el input de dirección
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'direccion[]';
    input.required = true;
    input.placeholder = 'Dirección adicional';
    input.classList.add('additional-input');
    input.style.marginTop = '10px';

    // Crear el botón de eliminación
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.textContent = '-';
    deleteBtn.className = 'delete-input-btn';
    deleteBtn.style.marginTop = '10px';
    deleteBtn.onclick = function () {
        inputGroup.remove();
    };

    // Añadir el input y el botón al contenedor
    inputGroup.appendChild(input);
    inputGroup.appendChild(deleteBtn);

    // Añadir el contenedor al DOM
    container.appendChild(inputGroup);
}

// Función que se asocia a los eventos
function bindEvents() {
    const signupForm = document.getElementById('signupForm');

    if (signupForm) {
        const addTelefonoBtn = document.getElementById('add-telefono-btn');
        const addDireccionBtn = document.getElementById('add-direccion-btn');

        if (addTelefonoBtn) {
            addTelefonoBtn.addEventListener('click', addTelefonoField);
        }

        if (addDireccionBtn) {
            addDireccionBtn.addEventListener('click', addDireccionField);
        }
    }
}

document.addEventListener('DOMContentLoaded', bindEvents);
