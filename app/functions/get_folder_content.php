<?php
include 'functions.php';
$folderId = filter_input(INPUT_POST, 'folderId', FILTER_SANITIZE_STRING);
$folderName = getFolderName($folderId);
if($folderId){
    $folderContent = jrMysqli("SELECT * FROM posts WHERE id_folder=?", $folderId);
    if($folderContent){
            echo '<table class="table table-hover jr-dash-table-posts-list">
            <thead>
                <tr>
                    <th><input type="checkbox" onchange="check(\'all\', this)"/></th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Carpeta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
            if(isMultidimensional($folderContent)===true){
                foreach($folderContent as $post){
                    $status_init=0;
                    $status = ($post['status']==$status_init) ? 'Draft' : 'Published';
                    echo   '<tr>
                                <td><input type="checkbox" class="post_checkbox" id="checkbox-'.$post['id'].'" onchange="check(\'this\', this)"></td>
                                <td class="jr-dash-posts-list-title"><a href="?action=readpost&idpost='.$post['id'].'" style="text-decoration:none!important;">'.$post['title'].'</a></td>
                                <td class="jr-dash-posts-list-meta">'.date("d/m/Y", strtotime($post['date_created'])).'</td>
                                <td class="jr-dash-posts-list-meta">'.$status.'</td>
                                <td class="jr-dash-posts-list-meta">'.$folderName.'</td>
                                <td>
                                    <a href="?action=editpost&idpost='.$post['id'].'"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
                                    <a href="#" onclick="deletePost('.$post.')"><i class="bi bi-trash me-3 jr-list-icon"></i></a>
                                </td>
                            </tr>';
                }
            }else{
                    $status_init=0;
                    $status = ($folderContent['status']==$status_init) ? 'Draft' : 'Published';
                    echo    '<tr>
                                <td><input type="checkbox" class="post_checkbox" id="checkbox-'.$folderContent['id'].'" onchange="check(\'this\', this)"></td>
                                <td class="jr-dash-posts-list-title"><a href="?action=readpost&idpost='.$folderContent['id'].'" style="text-decoration:none!important;">'.$folderContent['title'].'</a></td>
                                <td class="jr-dash-posts-list-meta">'.date("d/m/Y", strtotime($folderContent['date_created'])).'</td>
                                <td class="jr-dash-posts-list-meta">'.$status.'</td>
                                <td class="jr-dash-posts-list-meta">'.$folderName.'</td>
                                <td>
                                    <a href="?action=editpost&idpost='.$folderContent['id'].'"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
                                    <a href="#"><i class="bi bi-trash me-3 jr-list-icon"></i></a>
                                </td>
                            </tr>';
                }
            }else{
                echo '<div class="container p-2"><p>Esta carpeta está vacía.</p></div>';
            }
            echo '
            </tbody>
        </table>  ';
}else{
    echo '<p>ID de Carpeta no válido.</p>';
}

?>