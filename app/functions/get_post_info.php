<?php
session_start();
include 'functions.php';
$conn = conn();
$user_id = $_SESSION['id_user'];
$postId = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_STRING);
if($postId){
    $post = getPostInfo($postId);
    $autor = getAutorInfo($post['id_owner']);
    $comments = getCommentsForPost($postId);
    $date = date("d M Y", strtotime($post['date_created']));   
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
    // $numComments = mysqli_query($conn, "SELECT * FROM comments WHERE id_post='$postId'");
    // $countComments = mysqli_num_rows($numComments);
    // $comments = jrMysqli("SELECT * FROM comments WHERE id_post=? ORDER BY date_created DESC", $postId);
    // var_dump($comments);
    if($comments[0]>0){
        $commentsIcon = '<i class="bi bi-chat-square-dots-fill me-1 voted-colored"></i>';
    }else{
        $commentsIcon = '<i class="bi bi-chat-square-dots me-1"></i>';
    };
    if($autor['meta_content']===''){
        $autorImg = 'img/users/01_placeholder_user.png';
    }else{
        $autorImg = $autor['meta_content'];
    }
    if($post){
        $content = nl2br(htmlspecialchars($post['content']));
        echo '<div class="m-p-cont rounded bg-light p-2">
                        <div class="m-p-header border-bottom d-flex pb-1 justify-content-between align-top">
                            <!-- Cabecera del post -->
                            <div class="d-flex justify-content-between" style="width:100%;">
                                <div class="d-flex">    
                                    <div class="m-p-user-img rounded-circle me-2">
                                        <img src="'.$autorImg.'"/>
                                    </div>
                                    <div class="m-p-meta">
                                        <p class="m-p-user-name">'.$autor['first_name'].' '.$autor['last_name'].'</p>
                                        <p class="m-p-post-date">'.$date.'</p>
                                    </div>
                                </div>
                                <div>
                                    <img src="assets/img/SVG/CC.svg" style="width:20px;"/>
                                    <img src="assets/img/SVG/CC_BY.svg" style="width:20px;"/>
                                    <img src="assets/img/SVG/CC_NC.svg" style="width:20px;"/>
                                    <img src="assets/img/SVG/CC_ND.svg" style="width:20px;"/>
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
                                 <div class="d-flex align-items-center me-3">
                                    '.$commentsIcon.'
                                    <span class="votes_quantity">'.$comments[0].'</span>
                                 </div> 
                                 <div class="d-flex me-2">
                                     <i class="bi bi-bookmark-plus"></i>
                                 </div> 
                             </div>
                         </div>
                        <!-- Lista de comentarios -->
                        <div class="m-p-comments pt-1 pb-1 border-top">
                            <form onsubmit="addComment(event, \''.$postId.'\')">
                                <input class="form-control post-comment-input" type="text" name="comentario" placeholder="Comentar">
                            </form>
                            <div class="comments-list-reader" id="comments_'.$postId.'">';
                            if($comments){
                                $mostrarTodos = '<div class="comments-list-options" id="comments-list-options">
                                                            <a href="#">Mostrar todos los comentarios</a>
                                                        </div>';
                            }else{
                                $mostrarTodos = '';
                            }
                            if (is_array($comments)) {
                                if (isMultidimensional($comments)) {
                                    echo $mostrarTodos;
                                    // Si es un array multidimensional, recorremos cada comentario
                                    foreach ($comments as $comment) {
                                        if($comment['meta_content']===''){
                                            $autorCommentImg = 'img/users/01_placeholder_user.png';
                                        }else{
                                            $autorCommentImg = $comment['meta_content'];
                                        }
                                        if (!empty($comment['content'])) { // Verificar si el comentario tiene contenido
                                            // $commentAutorImg = jrMysqli("SELECT meta_content FROM users_meta WHERE meta_type='1' && id_owner=?", $comment['id_owner']);
                                            // $commentAutorData = jrMysqli("SELECT * FROM users WHERE id=?", $comment['id_owner']);
                                            echo '<div class="comment-item d-flex justify-contents-between">
                                                    <div class="me-2 wall-post-comments-user-img-box" style="background-image: url('.$autorCommentImg.');width:35px;height:35px;background-size:cover;overflow:hidden;border-radius:18px;"></div>
                                                    <div>
                                                        <p><strong>'.$comment['first_name'].' '.$comment['last_name'].'</strong></p>
                                                        <p>'.$comment['content'].'</p>
                                                    </div>
                                                  </div>';
                                        }
                                    }
                                } else {
                                    if($comments['meta_content']===''){
                                        $autorCommentImg = 'img/users/01_placeholder_user.png';
                                    }else{
                                        $autorCommentImg = $comments['meta_content'];
                                    }
                                    echo $mostrarTodos;
                                    // Si no es multidimensional, significa que hay solo un comentario
                                    if (!empty($comments['content'])) { // Verificar si el comentario tiene contenido
                                        // $commentAutorImg = jrMysqli("SELECT meta_content FROM users_meta WHERE meta_type='1' && id_owner=?", $comment['id_owner']);
                                        // $commentAutorData = jrMysqli("SELECT * FROM users WHERE id=?", $comment['id_user']);
                                        echo '<div class="comment-item d-flex justify-contents-between">
                                                <div class="me-2 wall-post-comments-user-img-box" style="background-image: url('.$autorCommentImg.');width:35px;height:35px;background-size:cover;overflow:hidden;border-radius:18px;"></div>
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