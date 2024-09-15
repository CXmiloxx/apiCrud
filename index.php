<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: text/html; charset=UTF-8");
$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = isset($request[0]) ? $request[0] : null;
$action = isset($request[1]) ? $request[1] : $_SERVER['REQUEST_METHOD'];

if (empty($resource)) {
    echo '
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API CRUD</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }

        p {
            margin-bottom: 20px;
        }

        .endpoints {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .endpoint {
            flex: 1 1 calc(50% - 20px);
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .endpoint:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .endpoint h3 {
            color: #2980b9;
            margin-bottom: 10px;
        }

        .endpoint p {
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .endpoint ul {
            list-style-type: none;
        }

        .endpoint li {
            padding: 10px 0;
        }

        code {
            background-color: #d0d0d0;
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: #888;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .endpoints {
                flex-direction: column;
            }

            .endpoint {
                flex: 1 1 100%;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a la API CRUD</h1>
        <p>Esta API permite realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre usuarios. A continuación se detallan los métodos disponibles y sus respectivas rutas:</p>
        
        <div class="endpoints">
            <div class="endpoint">
                <h3>GET</h3>
                <p>Obtener recursos o datos</p>
                <ul>
                    <li><code>/usuarios/GET</code> - Obtiene todos los usuarios registrados</li>
                    <li><code>/usuarios/GET/{id}</code> - Obtiene un usuario específico por su ID</li>
                    <li><strong>Ruta completa:</strong> <code>https://apicrud.cleverapps.io/usuarios/GET</code></li>
                </ul>
            </div>
            <div class="endpoint">
                <h3>POST</h3>
                <p>Crear nuevos recursos</p>
                <ul>
                    <li><code>/usuarios/POST</code> - Crea un nuevo usuario con los datos proporcionados</li>
                    <li><strong>Ruta completa:</strong> <code>https://apicrud.cleverapps.io/usuarios/POST</code></li>
                </ul>
            </div>
            <div class="endpoint">
                <h3>PUT</h3>
                <p>Actualizar recursos existentes</p>
                <ul>
                    <li><code>/usuarios/PUT/{id}</code> - Actualiza un usuario existente con el ID especificado</li>
                    <li><strong>Ruta completa:</strong> <code>https://apicrud.cleverapps.io/usuarios/PUT/{id}</code></li>
                </ul>
            </div>
            <div class="endpoint">
                <h3>DELETE</h3>
                <p>Eliminar recursos existentes</p>
                <ul>
                    <li><code>/usuarios/DELETE/{id}</code> - Elimina un usuario existente con el ID especificado</li>
                    <li><strong>Ruta completa:</strong> <code>https://apicrud.cleverapps.io/usuarios/DELETE/{id}</code></li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>&copy; Camilo Guapacha</p>
        </div>
    </div>
</body>
</html>

    ';
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
