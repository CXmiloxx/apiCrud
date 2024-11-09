<?php
include './Config/Conexion.php';

date_default_timezone_set('America/Bogota');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
    $contenido = trim(file_get_contents('php://input'));
    $uri = parse_url($_SERVER['REQUEST_METHOD'], PHP_URL_PATH);
    $controlador = explode('/', $uri);
    $idUsuario = isset($controlador[3]) && is_numeric($controlador[3]) ? $controlador[3] : null;
    $datos = json_decode($contenido, true);

    if (isset($datos['nombre'], $datos['descripcion'])) {
        $nombre = $datos['nombre'];
        $descripcion = $datos['descripcion'];

        try {
            $query = 'SELECT COUNT(*) FROM habitos WHERE idUsuario = :idU AND nombre = :nom';
            $consultaHabito = $base_de_datos->prepare($query);
            $consultaHabito->bindParam(':idU', $idUsuario);
            $consultaHabito->bindParam(':nom', $nombre);
            $consultaHabito->execute();

            if ($consultaHabito->fetchColumn() > 0) {
                $respuesta = formatearRespuesta(false, 'El hábito con ese nombre ya está registrado.');
            } else {
                $query = "INSERT INTO habitos (nombre, descripcion, fecha_creacion, idUsuario) 
                            VALUES (:nom, :des, NOW(), :idUsu)";
                $consulta = $base_de_datos->prepare($query);
                $consulta->bindParam(':nom', $nombre);
                $consulta->bindParam(':des', $descripcion);
                $consulta->bindParam(':idUsu', $idUsuario);

                if ($consulta->execute()) {
                    $respuesta = formatearRespuesta(true, "El hábito se ha registrado correctamente.");
                } else {
                    $respuesta = formatearRespuesta(false, "No se pudo registrar el hábito. Verifica los campos e inténtalo de nuevo.");
                }
            }
        } catch (PDOException $e) {
            $respuesta = formatearRespuesta(false, 'Error al intentar registrar el hábito: ' . $e->getMessage());
        }
    } else {
        $respuesta = formatearRespuesta(false, "Datos incompletos o inválidos. Asegúrate de enviar todos los campos requeridos.");
    }
} else {
    $respuesta = formatearRespuesta(false, 'Método no permitido, se esperaba POST');
}

echo json_encode($respuesta);
?>
