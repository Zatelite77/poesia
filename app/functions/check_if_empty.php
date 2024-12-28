<?php
include 'functions.php';

// Establecer encabezado para devolver JSON
header('Content-Type: application/json; charset=utf-8');

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

// Leer los datos enviados en el cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

// Validar si el ID de la carpeta está presente
if (!isset($input['folder_id']) || empty($input['folder_id'])) {
    echo json_encode(['success' => false, 'error' => 'ID de carpeta inválido.']);
    exit;
}

// Obtener el ID de la carpeta
$folder_id = $input['folder_id'];

// Establecer conexión a la base de datos
$conn = conn();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Error al conectar con la base de datos.']);
    exit;
}

// Comprobar si hay relaciones en posts_folders
$query_check = jrMysqli("SELECT * FROM posts WHERE id_folder = ?", $folder_id);

if (count($query_check) > 0) {
    echo json_encode(['success' => false, 'message' => 'Hay registros.']);
    exit;
}else{
    echo json_encode(['success' => true, 'error' => 'No hay registros.']);
    exit;
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>