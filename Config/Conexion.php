<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$host = 'bfromhyapic8gh9mkwcu-mysql.services.clever-cloud.com';
$databasename ='bfromhyapic8gh9mkwcu';
$user = 'uoovfcgryqtxbrhy';
$password = 'rYzoOJeJIGSI1SjIBbFw';

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