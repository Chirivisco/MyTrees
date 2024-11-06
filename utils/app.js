function checkLoginInputs(e) {
    e.preventDefault();

    const correo = document.querySelector('#correo');
    const contrasena = document.querySelector('#contrasena');
    const mensajeError = document.querySelector('#error-msg');

    // Limpiar el mensaje de error
    mensajeError.textContent = '';
    mensajeError.style.display = 'none';

    // Almacena el estado de la validación
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

    container.appendChild(inputGroup);
}

function valoresActualesEspecie(id, nombreComercial, nombreCientifico) {
    // Carga en los campos ocultos del form, los valores actuales de la espeice
    document.getElementById('currentNombreComercial').value = nombreComercial;
    document.getElementById('currentNombreCientifico').value = nombreCientifico;

    // Carga en los campos visibles del form, los valores actuales de la espeice
    // estos campos son los que se actualizan.
    document.getElementById('updateNombreComercial').value = nombreComercial;
    document.getElementById('updateNombreCientifico').value = nombreCientifico;
}

function Eliminar(id, nombreComercial) {
    if (confirm(`¿Estás seguro de que deseas eliminar la especie "${nombreComercial}"?`)) {
        document.getElementById(`deleteForm${id}`).submit();
    }
}

function cargarArbol(id_arbol, especie, ubicacion, precio, ruta_foto_arbol, estado) {
    // Ingresa los datos actuales en los campos
    document.getElementById('updateArbolId').value = id_arbol;
    document.getElementById('updateUbicacion').value = ubicacion;
    document.getElementById('updatePrecio').value = precio;
    const estadoSelect = document.getElementById('updateEstado');
    for (let i = 0; i < estadoSelect.options.length; i++) {
        if (estadoSelect.options[i].text === estado) {
            estadoSelect.selectedIndex = i;
            break;
        }
    }

    const especieSelect = document.getElementById('updateEspecie');
    for (let i = 0; i < especieSelect.options.length; i++) {
        if (especieSelect.options[i].text === especie) {
            especieSelect.selectedIndex = i;
            break;
        }
    }

    // Mostrar la imagen del árbol
    const imgElement = document.getElementById('imagenPreview');
    if (imgElement) {
        imgElement.src = ruta_foto_arbol;
        imgElement.alt = `Foto del árbol con ID ${id_arbol}`;
    }
}

function mostrarOffcanvas(offcanvasElement) {
    offcanvasElement.style.display = 'block';
    offcanvasElement.classList.add('show');
}

function ocultarOffcanvas(offcanvasElement) {
    offcanvasElement.classList.remove('show');
    setTimeout(() => {
        offcanvasElement.style.display = 'none';
    }, 300); // Espera para que termine la animación
}

// Función para mostrar las cards en el offcanvas
function cargarCarritoEnOffcanvas(arboles) {
    arboles.forEach(arbol => {
        // Encuentra la card correspondiente por el atributo `data-id-arbol`
        const card = document.querySelector(`.card[data-id-arbol="${arbol.ID_ARBOL}"]`);

        if (card) {
            // Llena la información de la card
            card.querySelector('.card-img-top').src = arbol.RUTA_FOTO_ARBOL;
            card.querySelector('.card-img-top').alt = `Imagen de ${arbol.ESPECIE}`;
            card.querySelector('.card-text').innerHTML = `
                <strong>Especie:</strong> ${arbol.ESPECIE} <br>
                <strong>Precio:</strong> $${arbol.PRECIO.toFixed(2)}
            `;
        }
    });
}


// Función que se asocia a los eventos
function bindEvents() {
    const signupForm = document.getElementById('signupForm');

    const offcanvasForm = document.getElementById('offcanvasCarrito');
    const carritoIcon = document.getElementById('carrito-compras');
    const botonesComprar = document.querySelectorAll(".btn.btn-success");
    const closeButton = document.getElementById('closeOffcanvas');

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

    if (offcanvasForm) {
        // muestra el offcanvas al clickear en el carrito de compras
        if (carritoIcon) {
            carritoIcon.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // detiene el click
                mostrarOffcanvas(offcanvasForm);
            });
        }

        // muestra el offcanvas al clickear en un botón de comprar
        // botonesComprar.forEach(boton => {
        //     boton.addEventListener('click', function (e) {
        //         e.stopPropagation(); // detiene el click
        //         mostrarOffcanvas(offcanvasForm);
        //     });
        // });


        // Cierra el offcanvas en el botón de cerrar
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                ocultarOffcanvas(offcanvasForm);
            });
        }

        document.addEventListener('click', function (event) {
            // valida si el click fue fuera del offcanvas
            if (!offcanvasForm.contains(event.target) && event.target !== carritoIcon && !carritoIcon.contains(event.target)) {
                ocultarOffcanvas(offcanvasForm);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', bindEvents);
