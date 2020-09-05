<?php
    //credenciales de la base de datos
    //Siempre que se tengan constantes, deben ser en mayúsculas
    define('DB_USUARIO', 'root');
    define('DB_PASSWORD', '');
    //si la BD está en un servidor diferente, aquí debe ir la IP
    define('DB_HOST', 'localhost');
    define('DB_NOMBRE', 'agendaphp');

    //toma 4 parámetros obligatorios: primero el host, luego el usuario, contraseña y nombre de la BD
    //el 5to es opcional: el puerto.
    $conn = new mysqli(DB_HOST, DB_USUARIO, DB_PASSWORD, DB_NOMBRE, 3310);

    //ping es una forma de comprobar si hace conexión a la BD. 1: Sí. 0:No.
    //echo $conn->ping();
?>