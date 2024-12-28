<?php
// Incluir el archivo de conexión a la base de datos
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
$query_check = "SELECT COUNT(*) as total FROM posts WHERE id_folder = ?";
$stmt_check = mysqli_prepare($conn, $query_check);

if ($stmt_check) {
    mysqli_stmt_bind_param($stmt_check, 's', $folder_id);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_check);

    // Si existen relaciones, eliminarlas
    if ($row['total'] > 0) {
        $query_delete_relations = "UPDATE FROM posts_folders WHERE id_folder = ?";
        $stmt_delete_relations = mysqli_prepare($conn, $query_delete_relations);
        if ($stmt_delete_relations) {
            mysqli_stmt_bind_param($stmt_delete_relations, 's', $folder_id);
            mysqli_stmt_execute($stmt_delete_relations);
            mysqli_stmt_close($stmt_delete_relations);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar relaciones de la carpeta.']);
            mysqli_close($conn);
            exit;
        }
    }
}

// Eliminar la carpeta de la tabla folders
$query_delete_folder = "DELETE FROM folders WHERE id = ?";
$stmt_delete_folder = mysqli_prepare($conn, $query_delete_folder);

if ($stmt_delete_folder) {
    mysqli_stmt_bind_param($stmt_delete_folder, 's', $folder_id);
    mysqli_stmt_execute($stmt_delete_folder);

    if (mysqli_stmt_affected_rows($stmt_delete_folder) > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró la carpeta para eliminar.']);
    }

    mysqli_stmt_close($stmt_delete_folder);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al eliminar la carpeta.']);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
