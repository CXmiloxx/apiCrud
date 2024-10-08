<?php
include './Config/Conexion.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$metodo = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controlador = explode('/', $uri);
$idDatos = isset($controlador[3]) && is_numeric($controlador[3]) ? $controlador[3] : null;

if ($metodo == 'PUT') {
    try {
        if ($idDatos) {
            $queryUsuarioExistente = 'SELECT COUNT(*) FROM datos WHERE idDatos = ?';
            $usuarioExistente = $base_de_datos->prepare($queryUsuarioExistente);
            $usuarioExistente->execute([$idDatos]);

            if ($usuarioExistente->fetchColumn() > 0) {
                $contenido = trim(file_get_contents('php://input'));
                $datos = json_decode($contenido, true);

                if (isset($datos['nombre'], $datos['telefono'], $datos['correo'], $datos['direccion'])) {
                    $nombre = $datos['nombre'];
                    $telefono = $datos['telefono'];
                    $correo = $datos['correo'];
                    $direccion = $datos['direccion'];

                    $query = "UPDATE datos SET nombre = :nom, telefono = :ape, correo = :cor, direccion = :dire WHERE idDatos = :id";
                    $consulta = $base_de_datos->prepare($query);
                    $consulta->bindParam(':nom', $nombre);
                    $consulta->bindParam(':ape', $telefono);
                    $consulta->bindParam(':cor', $correo);
                    $consulta->bindParam(':dire', $direccion);
                    $consulta->bindParam(':id', $idDatos);

                    $resultado = $consulta->execute();

                    if ($resultado && $consulta->rowCount() > 0) {
                        $respuesta = formatearRespuesta(true, "Datos de Usuario actualizado correctamente");
                    } else {
                        $respuesta = formatearRespuesta(false, "No se pudo actualizar el usuario. Verifica los datos y vuelve a intentarlo.");
                    }
                } else {
                    $respuesta = formatearRespuesta(false, "Faltan datos necesarios para actualizar el usuario");
                }
            } else {
                $respuesta = formatearRespuesta(false, "El usuario con el ID especificado no existe");
            }
        } else {
            $respuesta = formatearRespuesta(false, "Debes especificar un ID de usuario para actualizar");
        }
    } catch (Exception $e) {
        $respuesta = formatearRespuesta(false, "Error al procesar los datos: " . $e->getMessage());
    }
} else {
    $respuesta = formatearRespuesta(false, "Método no permitido, se esperaba el método PUT");
}

echo json_encode($respuesta);

?>
