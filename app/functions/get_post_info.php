<?php
session_start();
include 'functions.php';
$conn = conn();
$user_id = $_SESSION['id_user'];
$postId = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_STRING);
if($postId){
    $post = getPostInfo($postId);
    $autor = getAutorInfo($post['id_owner']);
    $date = date("d M Y", strtotime($post['date_created']));
    $comments = jrMysqli("SELECT c.content, c.date_created, u.first_name, u.last_name 
                      FROM comments c 
                      JOIN users u ON c.id_user = u.id 
                      WHERE c.id_post = ? 
                      ORDER BY c.date_created DESC", $postId);
        //Reiniciar variables
        if($comments){
            $mostrarTodos = '<div class="comments-list-options" id="comments-list-options">
                                        <a href="#">Mostrar todos los comentarios</a>
                                    </div>';
        }else{
            $mostrarTodos = '';
        }
        $votes = 0;
        $voted = '<i class="bi bi-hand-thumbs-up me-1 voted-grey" onclick="vote(\''.$postId.'\')"></i>';
        //Consultar votos del post
        $consult_votes = jrMysqli("SELECT * FROM votes WHERE id_post=?", $postId);
        if($consult_votes){
            $votes = $consult_votes['votes'];
            //Consulto si el usuario ha votado
            $consult_user_voted = jrMysqli("SELECT * FROM votes_users WHERE id_owner=? && id_post=?", $user_id, $postId);
            if($consult_user_voted){
                $voted = '<i class="bi bi-hand-thumbs-up-fill me-1 voted-colored" onclick="vote(\''.$postId.'\')"></i>';
            }
        }
        //consultamos los comentarios que tiene el post
        $numComments = mysqli_query($conn, "SELECT * FROM comments WHERE id_post='$postId'");
        $countComments = mysqli_num_rows($numComments);
        $comments = jrMysqli("SELECT * FROM comments WHERE id_post=?", $postId);
        if($countComments>0){
            $commentsIcon = '<i class="bi bi-chat-square-dots-fill me-1 voted-colored"></i>';
        }else{
            $commentsIcon = '<i class="bi bi-chat-square-dots me-1"></i>';
        };
    if($post){
        $content = nl2br(htmlspecialchars($post['content']));
        echo '<div class="m-p-cont rounded bg-light p-2">
                        <div class="m-p-header border-bottom d-flex pb-1 justify-content-between align-top">
                            <!-- Cabecera del post -->
                            <div class="d-flex">
                                <div class="m-p-user-img rounded-circle me-2">
                                    <img src="img/users/jose.jpg"/>
                                </div>
                                <div class="m-p-meta">
                                    <p class="m-p-user-name">'.$autor['first_name'].' '.$autor['last_name'].'</p>
                                    <p class="m-p-post-date">'.$date.'</p>
                                </div>
                            </div>
                        </div>
                        <!-- Cuerpo del post -->
                        <div class="m-p-body pt-2 pb-2 jr-post-body">
                            <div class="m-p-content">
                                <p class="m-p-post-title">'.$post['title'].'</p>
                                <p class="m-p-post-content">'.$content.'</p>
                            </div>
                        </div>
                        <div class="m-p-footer border-top d-flex pt-1">
                             <div class="m-p-actions-container d-flex">
                                 <div class="d-flex align-items-center me-3">
                                     '.$voted.'
                                     <span class="votes_quantity">'.$votes.'</span>
                                 </div>
                                 <div class="d-flex me-2">
                                     <i class="bi bi-bookmark-heart me-2"></i>
                                 </div> 
                                 <div class="d-flex">
                                    '.$commentsIcon.'
                                    <span class="votes_quantity">'.$countComments.'</span>
                                 </div> 
                             </div>
                         </div>
                        <!-- Lista de comentarios -->
                        <div class="m-p-comments pt-1 pb-1 border-top">
                            <form onsubmit="addComment(event, \''.$postId.'\')">
                                <input class="form-control post-comment-input" type="text" name="comentario" placeholder="Comentar">
                            </form>
                            <div class="comments-list-reader" id="comments_'.$postId.'">';
                            if (is_array($comments)) {
                                if (isMultidimensional($comments)) {
                                    echo $mostrarTodos;
                                    // Si es un array multidimensional, recorremos cada comentario
                                    foreach ($comments as $comment) {
                                        if (!empty($comment['content'])) { // Verificar si el comentario tiene contenido
                                            $userImg = "img/users/jose.jpg";
                                            echo '<div class="comment-item d-flex justify-contents-between">
                                                    <div class="me-2 wall-post-comments-user-img-box" style="background-image: url('.$userImg.');width:35px;height:35px;background-size:cover;overflow:hidden;border-radius:18px;"></div>
                                                    <div>
                                                        <p><strong>'.$comment['first_name'].' '.$comment['last_name'].'</strong></p>
                                                        <p>'.$comment['content'].'</p>
                                                    </div>
                                                  </div>';
                                        }
                                    }
                                } else {
                                    echo $mostrarTodos;
                                    // Si no es multidimensional, significa que hay solo un comentario
                                    if (!empty($comments['content'])) { // Verificar si el comentario tiene contenido
                                        $userImg = "img/users/jose.jpg";
                                        echo '<div class="comment-item d-flex justify-contents-between">
                                                <div class="me-2 wall-post-comments-user-img-box" style="background-image: url('.$userImg.');width:35px;height:35px;background-size:cover;overflow:hidden;border-radius:18px;"></div>
                                                <div>
                                                    <p><strong>'.$comments['first_name'].' '.$comments['last_name'].'</strong></p>
                                                    <p>'.$comments['content'].'</p>
                                                </div>
                                              </div>';
                                    }
                                }
                            }                            
                            
        echo '              </div>
                        </div>
                    </div>';
        // echo '  <div class="container">
        //             <h3>'.htmlspecialchars($postInfo['title'], ENT_QUOTES, 'UTF-8').'</h3>
        //             <p>'.htmlspecialchars($postInfo['content'], ENT_QUOTES, 'UTF-8').'</p>
        //         </div>';
    }else{
        echo '<p>Post no encontrado.</p>';
    }
}else{
    echo '<p>ID de post no válido.</p>';
}

?>