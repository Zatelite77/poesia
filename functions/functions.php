<?php

function conn(){
    $mysqli = new mysqli("localhost", "root", "root", "poesia");
    if ($mysqli->connect_errno) {
        return "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }else{
        return $mysqli;
    }
}

function isMultidimensional(array $array): bool {
    foreach ($array as $element) {
        if (is_array($element)) {
            return true; // Es multidimensional
        }
    }
    return false; // Es simple
}

function jrMysqli($consulta, $id1=null, $id2=null){
    $conn = conn();
    $stmt = $conn->prepare($consulta);
    if($id1!==null && $id2!==null){
        $stmt->bind_param('ss', $id1, $id2);
    }else if($id1!==null && $id2==null){
        $stmt->bind_param('s', $id1);
    };
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $rows = $result->fetch_assoc(); // Devolver una sola fila como array asociativo
        if(count($rows)===1){
            $row = reset($rows);
        }else{
            $row = $rows;
        }
    } else {
        $row = $result->fetch_all(MYSQLI_ASSOC); // Devolver todas las filas como array multidimensional
    }
    $stmt->close();
    $conn->close();
    return $row;
}

function getFolderName($folderId){
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM folders WHERE id = ?");
    $stmt->bind_param('s', $folderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row['folder_name'];
}

function folders_list(){
    $user_id = $_SESSION['user_id'];
    //Listado de Carpetas
    $consult = jrMysqli("SELECT * FROM folders WHERE id_owner=?", $user_id);
    if(isMultidimensional($consult)===false){
        if(!isset($_GET['action']) || $_GET['action']=='openfolder' || $_GET['action']=='dash'){
            if(isset($_GET['folderid']) && $_GET['folderid'] == $consult['id']){
                $icon = '<i class="bi bi-folder2-open"></i> ';
                $color = 'jr-btn-opened-folder';
            }else{
                $icon = '<i class="bi bi-folder"></i> ';
                $color = 'btn-light';
            }
        }
        echo '<div class="btn '.$color.' p-2 mb-1 cyan-100 align-middle">
                <a class="btn p-0" href="?loc=dash&action=openfolder&folderid='.$consult['id'].'">'.$icon.$consult['folder_name'].'</a>
                <i class="ms-2 bi bi-three-dots" onclick="folder_options(this, '.$consult['id'].');"></i>
                <div class="folder-options-menu" id="folder-options-'.$consult['id'].'" style="display: none;"></div>
                </div><br>';
    }else{
        foreach($consult as $result){
            if(!isset($_GET['action']) || $_GET['action']=='openfolder' || $_GET['action']=='dash'){
                if(isset($_GET['folderid']) && $_GET['folderid'] == $result['id']){
                    $icon = '<i class="bi bi-folder2-open"></i> ';
                    $color = 'jr-btn-opened-folder';
                }else{
                    $icon = '<i class="bi bi-folder"></i> ';
                    $color = 'btn-light';
                }
            }
            echo '<div class="btn '.$color.' p-2 mb-1 cyan-100 align-middle">
                    <a class="btn p-0" href="?loc=dash&action=openfolder&folderid='.$result['id'].'">'.$icon.$result['folder_name'].'</a>
                    <i class="ms-2 bi bi-three-dots" onclick="folder_options(this, \''.$result['id'].'\');"></i>
                    <div class="folder-options-menu" id="folder-options-'.$result['id'].'" style="display: none;"></div>
                    </div><br>';
        }
    };
}

function posts_list(){
    $conn = conn();
    $user_id = $_SESSION['user_id'];
    $base_path = '<a href="?loc=dash&action=dash">Escritorio</a> <i class="bi bi-chevron-double-right"></i>';
    if(!isset($_GET['action']) || $_GET['action']=='dash'){
        $location = $base_path." Todos los escritos";
        //Si estamos en el escritorio, recuperamos todos los escritos
        $consulta_escritos = jrMysqli("SELECT * FROM posts WHERE id_owner=?", $user_id);
        // $result_escritos = mysqli_fetch_all($consulta_escritos);
    }else if($_GET['action']=="openfolder"){
        //si estamos dentro de una carpeta, recuperamos los escritos que estan dentro de esa carpeta
        $folderid = $_GET['folderid'];
        $consulta_escritos = jrMysqli("SELECT * FROM posts WHERE id_folder=?", $folderid);
        $folder_name = getFolderName($folderid);
        //var_dump($folder_name);
        $location = $base_path." ".$folder_name;
    }
    echo '
    <div class="col-lg-9 col-md-9 pt-1">
        <div class="d-flex ps-2 pb-1 border-bottom">
            <p class="dash_location"><?php echo $location ?></p>
        </div>
        <div class="list-group">';

            if($consulta_escritos){
                echo '<table class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Carpeta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';
                if(isMultidimensional($consulta_escritos)===true){
                    foreach($consulta_escritos as $escrito){
                        if($escrito['id_folder']!=="0000000000"){
                            $consulta_carpeta = jrMysqli("SELECT folder_name FROM folders WHERE id=?", $escrito['id_folder']);
                            $folder = $consulta_carpeta;
                        }else{
                            $folder = '...';
                        }
                            $status_init=0;
                            $status = ($escrito['status']==$status_init) ? 'Draft' : 'Published';
                            echo    '<tr>
                                            <td>'.$escrito['title'].'</td>
                                            <td>'.date("d/m/Y", strtotime($escrito['date_created'])).'</td>
                                            <td>'.$status.'</td>
                                            <td>'.$folder.'</td>
                                            <td>
                                                <a href="?loc=dash&action=editpost&idpost='.$escrito['id'].'"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
                                                <a href="#"><i class="bi bi-trash me-3 jr-list-icon"></i></a>
                                            </td>
                                        </tr>';
                    }
                }else{
                    if($consulta_escritos['id_folder']!=="0000000000"){
                        $consulta_carpeta = jrMysqli("SELECT folder_name FROM folders WHERE id=?", $consulta_escritos['id_folder']);
                        $folder = $consulta_carpeta;
                    }else{
                        $folder = '...';
                    }
                        $status_init=0;
                        $status = ($consulta_escritos['status']==$status_init) ? 'Draft' : 'Published';
                        echo    '<tr>
                                        <td>'.$consulta_escritos['title'].'</td>
                                        <td>'.date("d/m/Y", strtotime($consulta_escritos['date_created'])).'</td>
                                        <td>'.$status.'</td>
                                        <td>'.$folder.'</td>
                                        <td>
                                            <a href="?loc=dash&action=editpost&idpost='.$escrito['id'].'"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
                                            <a href="#"><i class="bi bi-trash me-3 jr-list-icon"></i></a>
                                        </td>
                                    </tr>';
                    }
                }else{
                    echo '<p>Esta carpeta está vacía.</p>';
                }
                echo '
                

                </tbody>
            </table>            
        </div>
    </div>';
}

function the_wall(){
    $conn = conn();
    $user_id = $_SESSION['user_id'];
    //Obtener los posts
    $posts = jrMysqli("SELECT * FROM posts WHERE status='p'");

    foreach($posts as $post){
        $post_id = $post['id'];
        $date = date("d M Y", strtotime($post['date_created']));
        // Reiniciar variables
        $votes = 0;
        $voted = '<i class="bi bi-hand-thumbs-up me-1" style="color:gray;"></i>';
        //Consultar votos del post
        $consult_votes = jrMysqli("SELECT * FROM votes WHERE id_post=?", $post_id);
        if($consult_votes){
            $votes = $consult_votes['votes'];
            //Consulto si el usuario ha votado
            $consult_user_voted = jrMysqli("SELECT * FROM votes_users WHERE id_owner=? && id_post=?", $user_id, $post_id);
            if($consult_user_voted){
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
                                <p class="mb-1"><strong>'.$post['title'].'</strong></p>
                                <p>'.$post['content'].'</p>
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
}

function getPostInfo($id_post){
    $conn = conn();
    $datos = jrMysqli("SELECT * FROM posts WHERE id=?", $id_post);
    return $datos;
}