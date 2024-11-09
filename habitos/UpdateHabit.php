<?php
include './Config/Conexion.php';

date_default_timezone_set('America/Bogota');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'PUT') {
    $contenido = trim(file_get_contents('php://input'));
    $datos = json_decode($contenido, true);

    if (isset($datos['idHabito'], $datos['progreso'])) {
        $idHabito = $datos['idHabito'];
        $progreso = $datos['progreso'];

        if ($progreso < 0 || $progreso > 100) {
            echo json_encode(formatearRespuesta(false, 'El progreso debe estar entre 0 y 100.'));
            exit;
        }

        try {
            $query = "INSERT INTO progreso_habitos (idHabito, fecha, progreso)
                        VALUES (:idH, NOW(), :prog) 
                        ON DUPLICATE KEY UPDATE progreso = :prog, fecha = NOW()";
            $consulta = $base_de_datos->prepare($query);
            $consulta->bindParam(':idH', $idHabito);
            $consulta->bindParam(':prog', $progreso);

            if ($consulta->execute()) {
                $respuesta = formatearRespuesta(true, "El progreso del hábito se ha actualizado correctamente.");
            } else {
                $respuesta = formatearRespuesta(false, "No se pudo actualizar el progreso. Verifica los datos e inténtalo de nuevo.");
            }
        } catch (PDOException $e) {
            $respuesta = formatearRespuesta(false, 'Error al intentar actualizar el progreso: ' . $e->getMessage());
        }
    } else {
        $respuesta = formatearRespuesta(false, "Datos incompletos o inválidos. Asegúrate de enviar el ID del hábito y el progreso.");
    }
} else {
    $respuesta = formatearRespuesta(false, 'Método no permitido, se esperaba PUT');
}

echo json_encode($respuesta);
