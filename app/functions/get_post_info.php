<?php
include 'functions.php';
$postId = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_STRING);
if($postId){
    $postInfo = getPostInfo($postId);
    if($postInfo){
        echo '  <div class="container">
                    <h3>'.htmlspecialchars($postInfo['title'], ENT_QUOTES, 'UTF-8').'</h3>
                    <p>'.htmlspecialchars($postInfo['content'], ENT_QUOTES, 'UTF-8').'</p>
                </div>';
    }else{
        echo '<p>Post no encontrado.</p>';
    }
}else{
    echo '<p>ID de post no válido.</p>';
}

?>