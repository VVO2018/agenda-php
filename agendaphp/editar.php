<?php
    include 'inc/funciones/funciones.php';
    include 'inc/layout/header.php';

    //para volver el ID un entero, ya que está como string
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    //var_dump($id);

    if(!$id) {
        die('No es válido');
    }

    $resultado = obtenerContacto($id);
    $contacto = $resultado->fetch_assoc();
?>

<!-- <pre>
    <?php //var_dump($contacto); ?>
</pre> -->

<div class="contenedor-barra">
    <div class="contenedor barra">
        <a href="index.php" class="btn volver">Volver</a>
        <h1>Editar contacto</h1>
    </div>
</div>

<div class="bg-amarillo contenedor sombra">
    <form action="#" id="contacto">
        <legend>Edite el contacto</legend>
        
        <?php include 'inc/layout/formulario.php'; ?>
    </form>

</div>

<?php include 'inc/layout/footer.php'; ?>