<?php
include './Config/Conexion.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: GET, POST,PUT, DELELTE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
    $contenido = trim(file_get_contents('php://input'));
    $datos = json_decode($contenido, true);

        if ((isset($datos['nombre'], $datos['apellido'], $datos['email'], $datos['contra']))){;
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];
        $email = $datos['email'];
        $contra = password_hash($datos['contra'], PASSWORD_BCRYPT);
        try {
            $query = 'SELECT COUNT(*) FROM usuarios WHERE email = :ema';
            $consultaCorreo = $base_de_datos->prepare($query);
            $consultaCorreo->bindParam(':ema', $email);
            $consultaCorreo->execute();

                if ($consultaCorreo->fetchColumn() > 0) {
                    $respuesta = formatearRespuesta(false, 'El correo ya existe. Registrese con otro correo');

                } else {
                    $query = "INSERT INTO usuarios (nombre, apellido, email, contra) VALUES (:nom, :ape, :ema, :con)";
                    $consulta = $base_de_datos->prepare($query);
                    $consulta->bindParam(':nom', $nombre);
                    $consulta->bindParam(':ape', $apellido);
                    $consulta->bindParam(':ema', $email);
                    $consulta->bindParam(':con', $contra);

                    if ($consulta->execute()) {
                        $respuesta = formatearRespuesta(true, "El usuario se ha creado correctamente.");
                    } else {
                        $respuesta = formatearRespuesta(false, "No se pudo insertar el usuario. Verifica los datos y vuelve a intentarlo.");
                    }
                }
            } catch (PDOException $e) {
                $respuesta = formatearRespuesta(false, 'Error al intentar registrar el usuario: ' . $e->getMessage());
            }
        }else{
            $respuesta = formatearRespuesta(false, "Datos incompletos o inválidos. Asegúrate de enviar todos los campos requeridos.");
        }

        } else {
            $respuesta = formatearRespuesta(false, 'Método no permitido se esperaba POST');
}

echo json_encode($respuesta);

