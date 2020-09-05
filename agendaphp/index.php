<?php
    include 'inc/funciones/funciones.php';
    include 'inc/layout/header.php'; 
?>

<div class="contenedor-barra">
    <h1>Agenda de contactos</h1>
</div>

<div class="bg-amarillo contenedor sombra">
    <!-- No le colocamos nada en ACTION porque el formulario no nos va
    a llevar a otro archivo php. Todo se carga en la misma página con AJAX -->
    <form action="#" id="contacto">
        <legend>Añada un contacto <span>Todos los campos son obligatorios</span></legend>

        <?php include 'inc/layout/formulario.php'; ?>
    </form>
</div>

<div class="bg-blanco contenedor sombra contactos">
    <div class="contenedor-contactos">
        <h2>Contactos</h2>

        <input type="text" id="buscar" class="buscador sombra" placeholder="Buscar contactos">

        <p class="total-contactos"><span></span> Contactos</p>

        <div class="contenedor-tabla">
            <table id="listado-contactos" class="listado-contactos">
                <!-- Estos son los nombres de los campos -->
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Estos son los contenidos de los campos -->
                <tbody>
                <?php
                    $contactos = obtenerContactos();
                    // var_dump($contactos);
                    //num_rows indica que hay datos y cuántos hay (filas)
                    if($contactos->num_rows) {
                        // ESTE BLOQUE DE CÓDIGO DEBERÍA IR DENTRO DEL <tr> DE PRIMERO
                            //<pre>
                            //<?php var_dump($contacto); 
                            //</pre>
                        foreach($contactos as $contacto) { ?>
                            <tr>
                                <td><?php echo $contacto['nombre']; ?></td>
                                <td><?php echo $contacto['empresa']; ?></td>
                                <td><?php echo $contacto['telefono']; ?></td>
                                <td>
                                    <a class="btn-editar btn" href="editar.php?id=<?php echo $contacto['id']; ?>">
                                        <i class="fas fa-pen-square"></i>
                                    </a>

                                    <!-- data-id es un atributo propio que creé -->
                                    <button data-id="<?php echo $contacto['id']; ?>" type="button" class="btn-borrar btn">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'inc/layout/footer.php'; ?>