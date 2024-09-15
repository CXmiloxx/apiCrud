<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");
$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = isset($request[0]) ? $request[0] : null;
$action = isset($request[1]) ? $request[1] : $_SERVER['REQUEST_METHOD'];

if (empty($resource)) {
    echo json_encode([
        'status' => true,
        'message' => 'Bienvenido a mi api ',
        'rutes' => [
            'usuarios' => [
                'GET' => 'Listado de usuarios',
                'POST' => 'Crear un nuevo usuario',
                'PUT' => 'Actualizar un usuario existente',
                'DELETE' => 'Borrar un usuario'
            ]
        ]
    ]);
    exit;
}

$filepath = "$resource/$action.php";
if (file_exists($filepath)) {
    require $filepath;
} else {
    echo json_encode([
        'status' => false,
        'message' => 'El archivo correspondiente a la accion y recurso solicitados no existe.',
        'details' => [
            'resource' => $resource,
            'action' => $action,
            'filepath' => $filepath
        ]
    ]);
}
