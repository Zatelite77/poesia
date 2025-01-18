<?php
session_start();
include 'functions.php';

$conn = conn();
$user_id = $_SESSION['id_user'];

// Leer datos JSON desde el cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos válidos.']);
    exit;
}

// Validar entradas
$title = isset($input['title']) ? trim($input['title']) : null;
$content = isset($input['content']) ? trim($input['content']) : null;
$status = isset($input['status']) ? trim($input['status']) : null;
$folderid = isset($input['folder']) && $input['folder'] !== 'null' ? trim($input['folder']) : 0;

if (!$title || !$content || !$status) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
    exit;
}

// Preparar la consulta
$query = "INSERT INTO posts (id_owner, id_folder, title, content, status) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conn->error]);
    exit;
}

// Vincular parámetros
$stmt->bind_param("sssss", $user_id, $folderid, $title, $content, $status);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Escrito guardado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al ejecutar la consulta: ' . $stmt->error]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
