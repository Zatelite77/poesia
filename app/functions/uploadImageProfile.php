<?php
session_start();
include 'functions.php'; // Archivo de conexión a la base de datos

// Obtener el ID del usuario como un string (asegurándonos de que se conserven los ceros a la izquierda)
$userId = str_pad($_SESSION['id_user'], 10, "0", STR_PAD_LEFT); // Asegura que el ID tiene 12 caracteres
var_dump($userId);
var_dump($_FILES);
// Verificar si el archivo ha sido subido
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imgTmp = $_FILES['imagen']['tmp_name']; // Archivo temporal
    $imgName = $_FILES['imagen']['name']; // Nombre original de la imagen
    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION)); // Extensión de la imagen

    // Aceptar solo imágenes JPEG, PNG y GIF
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imgExt, $validExtensions)) {
        die('Formato de archivo no permitido');
    }

    // Verificar si la carpeta existe, y si no, crearla
    $dir = "img/users/$userId/";

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true); // Crear la carpeta con permisos adecuados
        echo "Carpeta creada: $dir"; // Para depuración
    } else {
        echo "La carpeta ya existe: $dir"; // Para depuración
    }

    // Comprobar si ya existe una imagen con el mismo nombre
    $newImageName = $imgName;
    $counter = 1;

    // Si ya existe la imagen, renombrarla
    while (file_exists($dir . $newImageName)) {
        $newImageName = pathinfo($imgName, PATHINFO_FILENAME) . "_$counter." . $imgExt;
        echo "Nombre de imagen final: $newImageName"; // Para depuración
        $counter++;
    }

    // Mover la imagen al directorio del usuario
    // $newImagePath = $dir . $newImageName;
    $imagePathToMove = $_SERVER['DOCUMENT_ROOT'] . "/app/img/users/$userId/$newImageName";
    $imagePathToDB = "img/users/".$userId."/".$newImageName;
    // Verificar si el archivo temporal existe y moverlo
    if (move_uploaded_file($imgTmp, $imagePathToMove)) {
        echo "Archivo movido correctamente a: $imagePathToMove";
    } else {
        echo "Error al mover el archivo a: $imagePathToMove";
    }

    // Optimización de la imagen (convertir a JPG y reducir tamaño)
    optimize_image($imagePathToMove);
    $conn = conn();
    // Verificar si ya existe un registro de tipo 1 para el usuario
    $stmt = $conn->prepare("SELECT * FROM users_meta WHERE id_owner = ? AND meta_type = 1");
    $stmt->bind_param('s', $userId); // Usamos 's' para que se trate como string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si ya existe, actualizar el registro
        $stmt = $conn->prepare("UPDATE users_meta SET meta_content = ? WHERE id_owner = ? AND meta_type = 1");
        $stmt->bind_param('ss', $imagePathToDB, $userId); // Usamos 'ss' para tratar ambos como string
    } else {
        // Si no existe, insertar un nuevo registro
        $stmt = $conn->prepare("INSERT INTO users_meta (id_owner, meta_type, meta_content) VALUES (?, 1, ?)");
        $stmt->bind_param('ss', $userId, $imagePathToDB); // Usamos 'ss' para tratar ambos como string
    }

    $stmt->execute();
    $stmt->close();

    echo "Imagen subida y optimizada con éxito!";
} else {
    echo "Error al subir la imagen.";
}

// Función para optimizar la imagen
function optimize_image($imagePath) {
    // Cargar la imagen
    $img = null;
    $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

    if ($ext == 'jpeg' || $ext == 'jpg') {
        $img = imagecreatefromjpeg($imagePath);
    } elseif ($ext == 'png') {
        $img = imagecreatefrompng($imagePath);
    } elseif ($ext == 'gif') {
        $img = imagecreatefromgif($imagePath);
    }

    if ($img !== null) {
        // Convertir la imagen a JPG con calidad 80 (puedes ajustarlo)
        imagejpeg($img, $imagePath, 80);
        imagedestroy($img);
    }
}
?>
