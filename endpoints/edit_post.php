<?php
$user_id = $_SESSION['user_id'];
?>
<div class="col-lg-4 col-md-6 col-sm-12 pt-4">
    <form action="utils/create_folder.php" method="POST">
        <label for="name" class="form-label">Título</label>
        <input type="text" class="form-control mb-2" name="name">
        <label for="content" class="form-label">Contenido</label>
        <textarea name="content" id="content" cols="30" rows="10" class="form-control mb-2"></textarea>
        <label for="folders" class="form-label">Carpeta</label>
            <?php
                $consult = mysqli_query($conn, "SELECT * FROM folders WHERE id_owner='$user_id'");
                if($consult && $results = mysqli_fetch_all($consult, MYSQLI_ASSOC)){
                    echo '<select name="folders" id="folders" class="form-select mb-4">
                    <option value="null"></option>';
                    foreach($results as $result){
                        echo '<option>'.$result['folder_name'].'</option>';
                    }
                    echo '</select>';
                }
            ?>  
        <input type="submit" class="btn btn-primary" value="Crear Escrito">
    </form>
</div>