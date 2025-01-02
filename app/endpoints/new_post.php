<?php
$user_id = $_SESSION['id_user'];
?>
<div class="col-lg-4 col-md-6 col-sm-12 pt-4">
    <form method="POST">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control mb-2" name="title" id="title">
        <label for="content" class="form-label">Contenido</label>
        <textarea name="content" id="content" cols="30" rows="10" class="form-control mb-2"></textarea>
        <label for="folders" class="form-label">Carpeta</label>
            <?php
            $conn = conn();
                $consult = mysqli_query($conn, "SELECT * FROM folders WHERE id_owner='$user_id'");
                if($consult && $results = mysqli_fetch_all($consult, MYSQLI_ASSOC)){
                    echo '<select name="folders" id="folders" class="form-select mb-4">
                    <option value="null"></option>';
                    foreach($results as $result){
                        echo '<option value="'.$result['id'].'">'.$result['folder_name'].'</option>';
                    }
                    echo '</select>';
                }
            ?>  
        <button class="btn btn-primary" onclick="savePost('d')">Guardar borrador</button>
        <button class="btn btn-primary" onclick="savePost('p')">Publicar escrito</button>
    </form>
</div>