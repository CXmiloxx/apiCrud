<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$user = 'root';
$databasename ='';
$password = '';
$server = 'localhost';

try{
    $base_datos = new PDO("mysql:host=$server, bdname=$databasename", $password, $user);
    $base_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    echo json_encode(formatearRespuesta(false, "Error al conectar con la base de datos: " . $e->getMessage()));
}

function formatearRespuesta($status, $mensaje, $datos = null) {
    $respuesta = [
        'status' => $status,
        'message' => $mensaje
    ];
    if ($datos !== null) {
        $respuesta = array_merge($respuesta, $datos);
    }
    return $respuesta;
}

?>