<?php
$user_id = $_SESSION['user_id'];
$id_post = $_GET['idpost'];
$datos = getPostInfo($id_post);
echo '
<div class="col-lg-4 col-md-6 col-sm-12 pt-4">
    <form method="POST">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control mb-2" name="title" id="title" value="'.$datos['title'].'">
        <label for="content" class="form-label">Contenido</label>
        <textarea name="content" id="content" cols="30" rows="10" class="form-control mb-2">'.$datos['content'].'</textarea>
        <label for="folders" class="form-label">Carpeta</label>';
                $consult = jrMysqli("SELECT * FROM folders WHERE id_owner=?", $user_id);
                if($consult){
                    echo '<select name="folders" id="folders" class="form-select mb-4">
                    <option value="null"></option>';
                    if(isMultidimensional($consult)===true){
                        foreach($consult as $result){
                            $selected = $result['id']===$datos['id_folder'] ? 'selected' : '';
                            echo '<option value="'.$result['id'].'" '.$selected.'>'.$result['folder_name'].'</option>';
                        }
                    }else{
                        $selected = $consult['id']===$datos['id_folder'] ? 'selected' : '';
                        echo '<option value="'.$consult['id'].'" '.$selected.'>'.$consult['folder_name'].'</option>';
                    }                                        
                    echo '</select>';
                }
        echo '
        <button class="btn btn-primary" onclick="savePost(\'d\')">Actualizar Escrito</button>
        <button class="btn btn-danger" onclick="savePost(\'p\')">Cancelar</button>
    </form>
</div>';