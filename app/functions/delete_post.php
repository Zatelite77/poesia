<?php
include 'functions.php';
$input = json_decode(file_get_contents('php://input'), true);
$post_id = $input['postId'];
$conn = conn();
if($post_id){
    $delete = mysqli_prepare($conn, "DELETE FROM posts WHERE id=?");
    mysqli_stmt_bind_param($delete, 's', $post_id);
    
    if(mysqli_stmt_execute($delete)){
        echo json_encode(['success' => true]);
    }else{
        echo json_encode(['success' => false, 'error' => 'Hubo un error al tratar de borrar el escrito.']);
    }
}else{
    echo json_encode(['success' => false, 'error' => 'Hubo un error al pasar el ID del post.']);
}