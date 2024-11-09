<?php
include './Config/Conexion.php';

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $controlador = explode('/', $uri);
    $idUsuario = isset($controlador[3]) && is_numeric($controlador[3]) ? $controlador[3] : null;
    $metodo = $_SERVER['REQUEST_METHOD'];

    if($metodo == 'GET'){
        try{
            if($idUsuario){
                $consulta = $base_de_datos->prepare('SELECT * FROM habitos WHERE idUsuario = ?');
                $consulta->execute([$idUsuario]);
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

                if($resultado){
                    $respuesta = formatearRespuesta(true, "Informacion de usuario encontrado exitosamente.", ['habitos' => $resultado]);
                
                }else{
                    $respuesta = formatearRespuesta(false, "No se encontró ningún usuario con el ID especificado.");
                }

            }else{
                $respuesta = formatearRespuesta(false, "Debes de dar un Id para buscar la informacion");

            }
        }catch(Exception $e){
            $respuesta = formatearRespuesta(false, "Error al ejecutar la consulta: ". $e->getMessage());
        }
    }else{
        $respuesta = formatearRespuesta(false, "Método no soportado. Se esperaba metodo GET");
    }
    
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Methods: GET, POST,PUT, DELELTE, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json');
echo json_encode($respuesta);
?>