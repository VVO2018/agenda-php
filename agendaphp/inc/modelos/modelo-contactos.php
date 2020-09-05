<?php
    //Esto es para evitar que xampp nos mande errores que realmente no
    //son errores sino anuncios
    error_reporting(E_ALL ^ E_NOTICE);

    if($_POST['accion'] == 'crear'){
        //creará un nuevo registro en la base de datos

        //aquí se importa la conexión a la BD
        require_once('../funciones/bd.php');

        //validar las entradas
        //Toma 2 parámetros: qué se quiere limpiar y el tipo de sanitización (validación) que se quiere hacer
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $empresa = filter_var($_POST['empresa'], FILTER_SANITIZE_STRING);
        $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);

        try {
            //Aquí se manda a ejecutar el código SQL con prepared statements
            $stmt = $conn->prepare("INSERT INTO contactos (nombre, empresa, telefono) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $empresa, $telefono);
            $stmt->execute();
            if($stmt->affected_rows == 1) {
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'datos' => array(
                        'nombre' => $nombre,
                        'empresa' => $empresa,
                        'telefono' => $telefono,
                        'id_insertado' => $stmt->insert_id
                    )
                );
            }
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }
        //ESTA LÍNEA ES IMPORTANTÍSIMA. SIN ESTO NO FUNCIONA PORQUE NO MANDA LA RESPUESTA.
        echo json_encode($respuesta);
    }
    //echo json_encode($_POST);

    if($_GET['accion'] == 'borrar') {
        //echo json_encode($_GET);
        require_once('../funciones/bd.php');

        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        
        try {
            $stmt = $conn->prepare("DELETE FROM contactos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if($stmt->affected_rows == 1) {
                $respuesta = array(
                    'respuesta' => 'correcto'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }
        //ESTA LÍNEA ES IMPORTANTÍSIMA. SIN ESTO NO FUNCIONA PORQUE NO MANDA LA RESPUESTA.
        echo json_encode($respuesta);
    }

    if ($_POST['accion'] == 'editar') {
        // echo json_encode($_POST);
        require_once('../funciones/bd.php');

        //validar las entradas
        //Toma 2 parámetros: qué se quiere limpiar y el tipo de sanitización (validación) que se quiere hacer
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $empresa = filter_var($_POST['empresa'], FILTER_SANITIZE_STRING);
        $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $stmt = $conn->prepare("UPDATE contactos SET nombre = ?, empresa = ?, telefono = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nombre, $empresa, $telefono, $id);
            $stmt->execute();
            //affected_rows = 1 quiere decir que se ha hecho un cambio
            if($stmt->affected_rows == 1) {
                $respuesta = array(
                    'respuesta' => 'correcto'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }
        //ESTA LÍNEA ES IMPORTANTÍSIMA. SIN ESTO NO FUNCIONA PORQUE NO MANDA LA RESPUESTA.
        echo json_encode($respuesta);
    }
?>