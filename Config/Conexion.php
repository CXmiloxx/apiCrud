<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$host = 'bq0vfnib8muottxijajb-mysql.services.clever-cloud.com';
$databasename ='bq0vfnib8muottxijajb';
$user = 'uxirala7zhifttwm';
$password = 'BUrCTq9L7CA48YCRM8OA';

try{
    $base_de_datos = new PDO("mysql:host=$host; dbname=$databasename", $user, $password);
    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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