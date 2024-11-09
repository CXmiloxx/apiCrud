<?php
include './Config/Conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");


if ($metodo == 'POST') {
    $contenido = trim(file_get_contents('php://input'));
    $datos = json_decode($contenido, true);

    if ((isset($datos['email'], $datos['contra']))) {
        $email = $datos['email'];
        $contra = $datos['contra'];

        try {
            $query = 'SELECT * FROM usuarios WHERE email = :ema';
            $consulta = $base_de_datos->prepare($query);
            $consulta->bindParam(':ema', $email);
            $consulta->execute();
            $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

            if($usuario){
                if(password_verify($contra, $usuario['contra'])){
                    $respuesta = formatearRespuesta(true, 'Login exitoso', ['idUsuario' => $usuario['idUsuario']]);
                
                }else{
                    $respuesta = formatearRespuesta(false, 'Contraseña incorrecta');
                }
            }else{
                $respuesta = formatearRespuesta(false, 'El email no se encuentra registrado');
            }
        } catch (Exception $e) {
            $respuesta = formatearRespuesta(false, 'Error al procesar los datos: '. $e->getMessage());
        }
    }else{
        $respuesta = formatearRespuesta(false, 'Faltan datos necesarios para el login el correo o la contraseña');
    }

}else{
    $respuesta = formatearRespuesta(false, 'No se permite el método. Se esperaba '. $metodo);
}

echo json_encode($respuesta);

