<?php
include 'functions.php';
session_start();

$postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_STRING);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
$userId = $_SESSION['id_user'];

if ($postId && $comment) {
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO comments (id_post, id_user, content, date_created) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $postId, $userId, $comment);

    if ($stmt->execute()) {
        // Obtener todos los comentarios actualizados
        $comments = jrMysqli("SELECT c.content, c.date_created, u.first_name, u.last_name 
                              FROM comments c 
                              JOIN users u ON c.id_user = u.id 
                              WHERE c.id_post = ? 
                              ORDER BY c.date_created DESC", $postId);

        $response = [];
        foreach ($comments as $comment) {
            $response[] = [
                'user' => $comment['first_name'].' '.$comment['last_name'],
                'date' => date("d M Y", strtotime($comment['date_created'])),
                'content' => htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')
            ];
        }

        echo json_encode(['success' => true, 'comments' => $response]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar el comentario.']);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
}
?>
