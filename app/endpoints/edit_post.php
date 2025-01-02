<?php
$user_id = $_SESSION['id_user'];
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
                            $folderSelected = $result['id']===$datos['id_folder'] ? 'selected' : '';
                            echo '<option value="'.$result['id'].'" '.$folderSelected.'>'.$result['folder_name'].'</option>';
                        }
                    }else{
                        $folderSelected = $consult['id']===$datos['id_folder'] ? 'selected' : '';
                        echo '<option value="'.$consult['id'].'" '.$folderSelected.'>'.$consult['folder_name'].'</option>';
                    }                                        
                    echo '</select>
                    <label for="folders" class="form-label">Estado</label>
                    <select name="status" id="status" class="form-select mb-4">';
                    if($consul['status']=='d'){
                        echo '  <option value="d" selected>Borrador</option>
                                <option value="p">Publicado</option>';
                    }else{
                        echo '  <option value="d">Borrador</option>
                                <option value="p" selected>Publicado</option>';
                    }                    
                    echo '</select>';
                }
        echo '
        <input class="visually-hidden" type="text" name="idpost" id="idpost" value="'.$id_post.'">
        <button class="btn btn-primary" onclick="updatePost()">Actualizar Escrito</button>
        <a href="?loc=dash&action=dash" class="btn btn-danger" style="color:white!important;">Cancelar</a>
    </form>
</div>';