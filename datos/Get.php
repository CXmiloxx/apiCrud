<?php
include './Config/Conexion.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controlador = explode('/', $uri);
$idDatos = isset($controlador[3]) && is_numeric($controlador[3]) ? $controlador[3] : null;
$metodo = $_SERVER['REQUEST_METHOD'];

if($metodo == 'GET'){
    try{
        $contenido = trim(file_get_contents('php://input'));
        $datos = json_decode($contenido, true);

        if($idDatos && isset($datos['idUsuario'])){
            $idUsuario = $datos['idUsuario'];

            $consulta = $base_de_datos->prepare('SELECT * FROM datos WHERE idDatos = :idDatos AND idUsuario = :idUs');
            $consulta->bindParam(':idDatos', $idDatos, PDO::PARAM_INT);
            $consulta->bindParam(':idUs', $idUsuario, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            if($resultado){
                $respuesta = formatearRespuesta(true, "Información del usuario encontrada exitosamente.", ['usuario' => $resultado]);
            }else{
                $respuesta = formatearRespuesta(false, "No se encontró ningún usuario con el ID especificado.");
            }
        }else{
            $respuesta = formatearRespuesta(false, "Debe proporcionar el ID del usuario en los datos de la solicitud.");
        }
    }catch(Exception $e){
        $respuesta = formatearRespuesta(false, "Error al ejecutar la consulta: ". $e->getMessage());
    }
}else{
    $respuesta = formatearRespuesta(false, "Método no soportado. Se esperaba método GET");
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');
echo json_encode($respuesta);

?>
