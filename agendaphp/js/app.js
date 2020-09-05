const formularioContactos = document.querySelector('#contacto'),
    listadoContactos = document.querySelector('#listado-contactos tbody'),
    inputBuscador = document.querySelector('#buscar');

eventListeners();

function eventListeners() {
    //Cuando el formulario de crear o editar se ejecuta
    formularioContactos.addEventListener('submit', leerFormulario);

    // Listener para eliminar el contacto
    if (listadoContactos) {
        listadoContactos.addEventListener('click', eliminarContacto);
    }

    //buscador
    //'input' se refiere a cuando alguien escribe
    inputBuscador.addEventListener('input', buscarContactos);

    //La mando a llamar apenas carga la página para que me diga cuántos contactos hay en total
    numeroContactos();
}

//e: es el evento
function leerFormulario(e) {
    //Esto es para prevenir la acción por default, la cual es que al dar click al botón, va a intentar
    //buscar una página para redireccionar, pero no es eso lo que queremos que haga
    e.preventDefault();

    //Leer los datos de los inputs
    const nombre = document.querySelector('#nombre').value,
        empresa = document.querySelector('#empresa').value,
        telefono = document.querySelector('#telefono').value,
        accion = document.querySelector('#accion').value;

    if (nombre === '' || empresa === '' || telefono === '') {
        //2 parámetros: texto y clase
        mostrarNotificacion('Todos los campos son obligatorios', 'error');
    } else {
        //Pasa la validación, crear llamado a Ajax
        //FormData se utiliza para guardar valores de formularios
        const infoContacto = new FormData();
        infoContacto.append('nombre', nombre);
        infoContacto.append('empresa', empresa);
        infoContacto.append('telefono', telefono);
        infoContacto.append('accion', accion);

        //Verificamos que todo funcione correctamente hasta ahora, viendo que los valores se guarden en el arreglo
        //Los puntos crean una copia del objeto, porque si no, no podría verse
        //console.log(...infoContacto);

        if (accion === 'crear') {
            //crearemos un nuevo contacto
            //esta función toma toda la información del formulario
            insertarBD(infoContacto);
        } else {
            //editar el contacto
            //leer el ID
            const idRegistro = document.querySelector('#id').value;
            infoContacto.append('id', idRegistro);
            actualizarRegistro(infoContacto);
        }
    }
}


/** Inserta en la base de datos via Ajax **/
function insertarBD(datos) {
    //Llamado a Ajax


    //crear el objeto
    const xhr = new XMLHttpRequest();

    //abrir la conexión
    xhr.open('POST', 'inc/modelos/modelo-contactos.php', true);

    //pasar los datos
    xhr.onload = function() {
        if (this.status === 200) {
            //leemos la respuesta de PHP
            //JSON.parse se encarga de convertir el json en un objeto de JS
            const respuesta = JSON.parse(xhr.responseText);

            // console.log(respuesta);

            //inserta un nuevo elemento a la tabla
            const nuevoContacto = document.createElement('tr');
            //template strings: forma de ir concatenando. ${} esto quiere decir que es código JS
            nuevoContacto.innerHTML = `
                <td>${respuesta.datos.nombre}</td>
                <td>${respuesta.datos.empresa}</td>
                <td>${respuesta.datos.telefono}</td>
            `;

            // crear contenedor para los botones
            const contenedorAcciones = document.createElement('td');

            // crear el icono de Editar
            const iconoEditar = document.createElement('i');
            iconoEditar.classList.add('fas', 'fa-pen-square');

            // crea el enlace para editar
            const btnEditar = document.createElement('a');
            //appendChild es para agregar algo como hijo
            btnEditar.appendChild(iconoEditar);
            btnEditar.href = `editar.php?id=${respuesta.datos.id_insertado}`;
            btnEditar.classList.add('btn-editar', 'btn');

            // agregarlo al padre
            contenedorAcciones.appendChild(btnEditar);

            // crear el icono de Eliminar
            const iconoEliminar = document.createElement('i');
            iconoEliminar.classList.add('fas', 'fa-trash-alt');

            // crear el botón de eliminar
            const btnEliminar = document.createElement('button');
            btnEliminar.appendChild(iconoEliminar);
            btnEliminar.setAttribute('data-id', respuesta.datos.id_insertado);
            btnEliminar.classList.add('btn-borrar', 'btn');

            // agregarlo al padre
            contenedorAcciones.appendChild(btnEliminar);

            // agregarlo al tr
            nuevoContacto.appendChild(contenedorAcciones);

            // agregarlo con los contactos
            listadoContactos.appendChild(nuevoContacto);

            // Resetear el formulario
            document.querySelector('form').reset();

            // Mostrar la notificación
            mostrarNotificacion('Contacto creado correctamente', 'correcto');

            // Actualizar el número de contactos total
            numeroContactos();
        }
    }

    //enviar los datos
    //cuando se usa POST, la variable se entrega aquí
    xhr.send(datos);
}


//EDITAR UN CONTACTO
function actualizarRegistro(datos) {
    //comprobamos que guarde los datos correctamente
    //console.log(...datos);

    // Ajax

    //crear el objeto
    const xhr = new XMLHttpRequest();

    //abrir la conexión
    xhr.open('POST', 'inc/modelos/modelo-contactos.php', true);

    //leer la respuesta
    xhr.onload = function() {
        if (this.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);

            //console.log(respuesta);
            if (respuesta.respuesta === 'correcto') {
                //mostrar notificación de Correcto
                mostrarNotificacion('Contacto editado correctamente', 'correcto');
            } else {
                //hubo un error
                mostrarNotificacion('Hubo un error', 'error');
            }

            //Después de 3 segundos, redireccionar
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 3000);
        }
    }

    //enviar la petición
    //Debido a que es un POST, se envían aquí
    xhr.send(datos);
}


//BORRAR UN CONTACTO
function eliminarContacto(e) {
    //esto nos muestra a qué le dimos click específicamente
    // console.log(e.target);
    // console.log(e.target.parentElement.classList.contains('btn-borrar'));

    if (e.target.parentElement.classList.contains('btn-borrar')) {
        // tomar el ID
        const id = e.target.parentElement.getAttribute('data-id');
        //console.log(id);

        // preguntar al usuario si está seguro. Confirm: es nativo del navegador.
        const respuesta = confirm('¿Estás seguro(a) de que quieres eliminar este contacto?');

        if (respuesta) {
            // llamado a Ajax
            // crear el objeto
            const xhr = new XMLHttpRequest();

            // abrir la conexión
            // cuando se usa GET, la variable se manda en la URL (?id=${id})
            // se usan template strings porque se envía una variable de JS
            xhr.open('GET', `inc/modelos/modelo-contactos.php?id=${id}&accion=borrar`, true);

            // leer la respuesta
            xhr.onload = function() {
                if (this.status === 200) {
                    const resultado = JSON.parse(xhr.responseText);

                    console.log(resultado);

                    if (resultado.respuesta === 'correcto') {
                        //Eliminar el registro del DOM
                        //console.log(e.target.parentElement.parentElement.parentElement);
                        e.target.parentElement.parentElement.parentElement.remove();

                        //Mostrar notificación
                        mostrarNotificacion('Contacto eliminado', 'correcto');

                        //Actualizar el número de contactos total
                        numeroContactos();
                    } else {
                        // Mostramos una notificación
                        mostrarNotificacion('Hubo un error', 'error');
                    }
                }
            }

            // enviar la petición
            xhr.send();
        }
    }
}


//NOTIFICACIONES EN PANTALLA
function mostrarNotificacion(mensaje, clase) {
    const notificacion = document.createElement('div');
    notificacion.classList.add(clase, 'notificacion', 'sombra');
    notificacion.textContent = mensaje;

    //Formulario
    //insertBefore toma primero lo que se va a insertar, y luego dónde se va a insertar.
    formularioContactos.insertBefore(notificacion, document.querySelector('form legend'));

    //Ocultar y mostrar la notificación
    //Esta es una función que espera cierto tiempo para ejecutar un código
    setTimeout(() => {
        notificacion.classList.add('visible');

        setTimeout(() => {
            notificacion.classList.remove('visible');

            setTimeout(() => {
                notificacion.remove();
            }, 500);
        }, 3000);
    }, 100);
}


//BUSCAR CONTACTOS
function buscarContactos(e) {
    //Esto me muestra el evento que está sucediendo
    //console.log(e.target.value);

    //La "i" quiere decir key insensitive, es decir, que no diferencia entre mayúsculas y minúsculas
    const expresion = new RegExp(e.target.value, "i"),
        registros = document.querySelectorAll('tbody tr');

    registros.forEach(registro => {
        registro.style.display = 'none';

        //Esto me filtra por nombre
        //console.log(registro.childNodes[1]);
        //-1 es igual a false, o sea que no hay coincidencias
        if (registro.childNodes[1].textContent.replace(/\s/g, " ").search(expresion) != -1) {
            registro.style.display = 'table-row';
        }
        numeroContactos();
    });
}

//Muestra el número de contactos
function numeroContactos() {
    const totalContactos = document.querySelectorAll('tbody tr'),
        contenedorNumero = document.querySelector('.total-contactos span');
    //console.log(totalContactos.length);

    let total = 0;

    totalContactos.forEach(contacto => {
        // console.log(contacto.style.display);
        if (contacto.style.display === '' || contacto.style.display === 'table-row') {
            total++;
        }
    });
    //console.log(total);
    contenedorNumero.textContent = total;
}