<?php
include './Config/Conexion.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
    $contenido = trim(file_get_contents('php://input'));
    $datos = json_decode($contenido, true);

    if (isset($datos['idUsuario'], $datos['nombre'], $datos['telefono'], $datos['correo'], $datos['direccion'])) {
        $idUsuario = $datos['idUsuario'];
        $nombre = $datos['nombre'];
        $telefono = $datos['telefono'];
        $correo = $datos['correo'];
        $direccion = $datos['direccion'];

        try {
            $query = 'SELECT COUNT(*) FROM datos WHERE correo = :cor AND idUsuario = :idUsu';
            $consultaCorreo = $base_de_datos->prepare($query);
            $consultaCorreo->bindParam(':cor', $correo);
            $consultaCorreo->bindParam(':idUsu', $idUsuario);
            $consultaCorreo->execute();

            if ($consultaCorreo->fetchColumn() > 0) {
                $respuesta = formatearRespuesta(false, 'El correo ya está registrado para este usuario.');
            } else {
                $query = "INSERT INTO datos (nombre, telefono, correo, direccion, idUsuario) 
                            VALUES (:nom, :tel, :cor, :dir, :idUsu)";
                $consulta = $base_de_datos->prepare($query);
                $consulta->bindParam(':nom', $nombre);
                $consulta->bindParam(':tel', $telefono);
                $consulta->bindParam(':cor', $correo);
                $consulta->bindParam(':dir', $direccion);
                $consulta->bindParam(':idUsu', $idUsuario);

                if ($consulta->execute()) {
                    $respuesta = formatearRespuesta(true, "Los datos se han insertado correctamente.");
                } else {
                    $respuesta = formatearRespuesta(false, "No se pudo insertar los datos. Verifica los campos e inténtalo de nuevo.");
                }
            }
        } catch (PDOException $e) {
            $respuesta = formatearRespuesta(false, 'Error al intentar registrar los datos: ' . $e->getMessage());
        }
    } else {
        $respuesta = formatearRespuesta(false, "Datos incompletos o inválidos. Asegúrate de enviar todos los campos requeridos.");
    }
} else {
    $respuesta = formatearRespuesta(false, 'Método no permitido, se esperaba POST');
}

echo json_encode($respuesta);
?>