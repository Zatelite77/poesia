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
        $response = '';
        // Obtener todos los comentarios actualizados
        $comments = jrMysqli("SELECT c.content, c.date_created, u.first_name, u.last_name 
                              FROM comments c 
                              JOIN users u ON c.id_user = u.id 
                              WHERE c.id_post = ? 
                              ORDER BY c.date_created DESC LIMIT 2", $postId);
        if($comments){
            $mostrarTodos = '<div class="comments-list-options" id="comments-list-options">
                                        <a href="#">Mostrar todos los comentarios</a>
                                    </div>';
        }else{
            $mostrarTodos = '';
        }
        
        if (is_array($comments)) {
            if (isMultidimensional($comments)) {
                $response .= $mostrarTodos;
                // Si es un array multidimensional, recorremos cada comentario
                foreach ($comments as $comment) {
                    if (!empty($comment['content'])) { // Verificar si el comentario tiene contenido
                        $userImg = "img/users/jose.jpg";
                        $response .= '<div class="comment-item d-flex justify-contents-between">
                                <div class="me-2 wall-post-comments-user-img-box" style="background-image: url('.$userImg.');width:35px;height:35px;background-size:cover;overflow:hidden;border-radius:18px;"></div>
                                <div>
                                    <p><strong>'.$comment['first_name'].' '.$comment['last_name'].'</strong></p>
                                    <p>'.$comment['content'].'</p>
                                </div>
                              </div>';
                    }
                }
            } else {
                // Si no es multidimensional, significa que hay solo un comentario
                if (!empty($comments['content'])) { // Verificar si el comentario tiene contenido
                    $userImg = "img/users/jose.jpg";
                    $response .= $mostrarTodos;
                    $response .= '<div class="comment-item d-flex justify-contents-between">
                            <div class="me-2 wall-post-comments-user-img-box" style="background-image: url('.$userImg.');width:35px;height:35px;background-size:cover;overflow:hidden;border-radius:18px;"></div>
                            <div>
                                <p><strong>'.$comments['first_name'].' '.$comments['last_name'].'</strong></p>
                                <p>'.$comments['content'].'</p>
                            </div>
                          </div>';
                }
            }
        } 

        
        // foreach ($comments as $comment) {
        //     $response[] = [
        //         'user' => $comment['first_name'].' '.$comment['last_name'],
        //         'date' => date("d M Y", strtotime($comment['date_created'])),
        //         'content' => htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')
        //     ];
        // }
        // echo $response;
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
