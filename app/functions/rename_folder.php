<?php
include 'functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el cuerpo de la solicitud JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar las claves requeridas
    if (!isset($input['folder_id'], $input['new_name']) || empty($input['new_name'])) {
        echo json_encode(['success' => false, 'error' => 'Nombre o ID de carpeta inválidos.']);
        exit;
    }

    $folder_id = intval($input['folder_id']);
    $new_name = trim($input['new_name']);

    $conn = conn();
    $stmt = $conn->prepare("UPDATE folders SET folder_name='$new_name' WHERE id = '$folder_id'");
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al renombrar la carpeta.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}

?>