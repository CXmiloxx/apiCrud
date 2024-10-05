<?php

include './Config/Conexion.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controlador = explode('/', $uri);
$idUsuario = isset($controlador[3]) ? $controlador[3] : null;
$metodo = $_SERVER['REQUEST_METHOD'];

if($metodo == 'DELETE'){
    try {
        if ($idUsuario) {
            try {
                $query = 'DELETE FROM datos WHERE idDatos = ?';
                $consulta = $base_de_datos->prepare($query);
                $resultado = $consulta->execute([$idUsuario]);

                if ($resultado && $consulta->rowCount()) {
                    $respuesta = formatearRespuesta(true, "Usuario eliminado exitosamente.");
                } else {
                    $respuesta = formatearRespuesta(false, "No se pudo eliminar el usuario. Verifica el ID o si el usuario existe.");
                }
            } catch (PDOException $e) {
                $respuesta = formatearRespuesta(false, "Error al eliminar el usuario: " . $e->getMessage());
            }
        } else {
            $respuesta = formatearRespuesta(false, "Debes especificar un id de usuario para eliminar.");
        }
    } catch (PDOException $e) {
        $respuesta = formatearRespuesta(false, "Error al ejecutar la consulta: " . $e->getMessage());
    }
} else {
    $respuesta = formatearRespuesta(false, "Método de solicitud no permitido. Se esperaba DELETE.");
}

echo json_encode($respuesta);

?>
