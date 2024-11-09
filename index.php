<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Obtener la URI de la solicitud y dividirla en partes
$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = isset($request[0]) ? $request[0] : null;
$action = isset($request[1]) ? $request[1] : $_SERVER['REQUEST_METHOD'];

// Si no se pasa ningún recurso, mostrar información general de la API
if (empty($resource)) {
    echo json_encode([
        'status' => true,
        'message' => 'Bienvenido a la API',
        'description' => 'Esta es una API RESTful que permite la gestión de usuarios y otros recursos relacionados.',
        'version' => '1.0.0',
        'base_url' => 'https://habit-tracker.cleverapps.io/',
        'resources' => [
            'usuarios' => [
                'description' => 'Permite gestionar los usuarios del sistema.',
                'GetUser' => [
                    'url' => '/usuarios/GetUser',
                    'description' => 'Obtiene todos los usuarios o un usuario específico si se proporciona un ID.',
                    'example' => '/usuarios/1'
                ],
                'CreateUser' => [
                    'url' => '/usuarios/CreateUser',
                    'description' => 'Crea un nuevo usuario.',
                    'example' => '/usuarios (Con datos en el cuerpo de la solicitud)'
                ],
                'Login' => [
                    'url' => '/usuarios/LoginUser',
                    'description' => 'Actualiza la información de un usuario específico.',
                    'example' => '/usuarios/1 (Con datos en el cuerpo de la solicitud)'
                ],
            ],
        ],
        'note' => 'Para obtener más información sobre cómo interactuar con los recursos, consulta nuestra documentación completa.'
    ]);
    exit;
}

$filepath = "$resource/$action.php";
if (file_exists($filepath)) {
    require $filepath;
} else {
    echo json_encode([
        'status' => false,
        'message' => 'El archivo correspondiente a la acción y recurso solicitados no existe.',
        'details' => [
            'resource' => $resource,
            'action' => $action,
            'filepath' => $filepath
        ]
    ]);
}
?>
