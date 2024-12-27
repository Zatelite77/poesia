<?php
include 'functions/functions.php';
$conn = conn();
//Obtener los posts
$consulta = mysqli_query($conn, "SELECT * FROM posts");
$results  = mysqli_fetch_all($consulta, MYSQLI_ASSOC);

foreach($results as $row){
    $post_id = $row['id'];
    $date = date("d M Y", strtotime($row['date_created']));
    // Reiniciar variables
    $votes = 0;
    $voted = '<i class="bi bi-hand-thumbs-up me-1" style="color:gray;"></i>';
    //Consultar votos del post
    $consult_votes = mysqli_query($conn, "SELECT * FROM votes WHERE id_post='$post_id'");
    if($consult_votes && $result_votes = mysqli_fetch_assoc($consult_votes)){
        $votes = $result_votes['votes'];
        //Consulto si el usuario ha votado
        $user_id = $_SESSION['user_id'];
        $consult_user_voted = mysqli_query($conn, "SELECT * FROM votes_users WHERE id_owner='$user_id' && id_post='$post_id'");
        if($consult_user_voted && $result_user_voted = mysqli_fetch_assoc($consult_user_voted)){
            $voted = '<i class="bi bi-hand-thumbs-up-fill me-1" style="color:green;"></i>';
        }
    }
    echo '<div class="container pt-4 pb-4">
            <div id="muro" class="muro">
                <div class="m-p-cont col-lg-4 col-md-6 col-sm-12 rounded bg-light p-2">
                    <div class="m-p-header border-bottom d-flex pb-1">
                        <div class="m-p-user-img rounded-circle me-2">
                            <img src="img/users/jose.jpg"/>
                        </div>
                        <div class="m-p-meta">
                            <p class="m-p-user-name">Jose Román</p>
                            <p class="m-p-post-date">'.$date.'</p>
                        </div>
                    </div>
                    <div class="m-p-body pt-2 pb-2">
                        <div class="m-p-content">
                            <p class="mb-1"><strong>'.$row['title'].'</strong></p>
                            <p>'.$row['content'].'</p>
                        </div>
                    </div>
                    <div class="m-p-comments pt-1 pb-1 border-top">
                            <form>
                                <input class="form-control post-comment-input" type="text" name="comentario" placeholder="Comentar">
                            </form>
                        </div>
                    <div class="m-p-footer border-top d-flex pt-1">
                        <div class="m-p-actions-container d-flex">
                            <div class="d-flex align-items-center me-3">
                                '.$voted.'
                                <span class="votes_quantity">'.$votes.'</span>
                            </div>
                            <div class="d-flex">
                                <i class="bi bi-bookmark-heart me-2"></i>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}
