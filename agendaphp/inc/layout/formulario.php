<?php error_reporting(E_ALL ^ E_NOTICE); ?>

<div class="campos">
    <div class="campo">
        <label for="nombre">Nombre:</label>
        <input
            type="text"
            placeholder="Nombre del contacto"
            id="nombre"
            value="<?php echo ($contacto['nombre']) ? $contacto['nombre'] : ''; ?>"
        >
        <!--La línea anterior quiere decir que si existe un nombre, entonces lo muestre. Si no existe, que deje el campo vacío-->
    </div>
    <div class="campo">
        <label for="empresa">Empresa:</label>
        <input
            type="text"
            placeholder="Nombre de la empresa"
            id="empresa"
            value="<?php echo ($contacto['empresa']) ? $contacto['empresa'] : ''; ?>"
        >
    </div>
    <div class="campo">
        <label for="telefono">Teléfono:</label>
        <input
            type="tel"
            placeholder="Teléfono del contacto"
            id="telefono"
            value="<?php echo ($contacto['telefono']) ? $contacto['telefono'] : ''; ?>"
        >
    </div>
</div>
<div class="campo enviar">
    <?php
        //Si algún campo existe, entonces el nombre del botón deberá ser Guardar. De lo contrario, será Añadir.
        $textoBtn = ($contacto['telefono']) ? 'Guardar' : 'Añadir';
        $accion = ($contacto['telefono']) ? 'editar' : 'crear';
    ?>
    <input type="hidden" id="accion" value="<?php echo $accion; ?>">
    <?php if(isset( $contacto['id'] )) { ?>
        <input type="hidden" id="id" value="<?php echo $contacto['id']; ?>">
    <?php } ?>
    <input type="submit" value="<?php echo $textoBtn; ?>">
</div>