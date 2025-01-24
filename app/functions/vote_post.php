<?php
session_start();
$id_user = $_SESSION['id_user'];
include 'functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = conn();
    $input = json_decode(file_get_contents('php://input'), true);

    $post_id = $input['post_id'] ?? null;

    // Validar entrada
    if (!$post_id) {
        echo json_encode(['success' => false, 'error' => 'ID del post no válido.']);
        exit;
    }

    $votes = getPostVotes($post_id);
    $stmt = $conn->prepare("SELECT * FROM votes_users WHERE id_owner = ? AND id_post = ?");
    $stmt->bind_param("ss", $id_user, $post_id);
    $stmt->execute();
    $userVoted = $stmt->get_result()->fetch_assoc();

    if ($votes) {
        $numVotes = $votes['votes'] + ($userVoted ? -1 : 1);

        // Actualizar votos
        $stmt = $conn->prepare("UPDATE votes SET votes = ? WHERE id_post = ?");
        $stmt->bind_param("is", $numVotes, $post_id);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar los votos.']);
            exit;
        }

        // Manejar relación usuario-post
        if ($userVoted) {
            $stmt = $conn->prepare("DELETE FROM votes_users WHERE id_owner = ? AND id_post = ?");
            $stmt->bind_param("ss", $id_user, $post_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO votes_users (id_owner, id_post) VALUES (?, ?)");
            $stmt->bind_param("ss", $id_user, $post_id);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => $userVoted ? 'Voto eliminado.' : 'Voto añadido.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al manejar la relación usuario-post.']);
        }
    } else {
        // Crear registro de votos y relación usuario-post
        $numVotes = 1;
        $stmt = $conn->prepare("INSERT INTO votes (id_post, votes) VALUES (?, ?)");
        $stmt->bind_param("si", $post_id, $numVotes);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("INSERT INTO votes_users (id_owner, id_post) VALUES (?, ?)");
            $stmt->bind_param("ss", $id_user, $post_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Voto registrado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al crear la relación usuario-post.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar el primer voto.']);
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
?>
