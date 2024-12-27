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

function jrMysqli($consulta, $id=null){
    $conn = conn();
    $stmt = $conn->prepare($consulta);
    if($id!==null){
        $stmt->bind_param('s', $id);
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
    ?>
    <div class="col-lg-9 col-md-9 pt-1">
        <div class="d-flex ps-2 pb-1 border-bottom">
            <p class="dash_location"><?php echo $location ?></p>
        </div>
        <div class="list-group">
            <?php
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
                                                <a href="#"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
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
                                            <a href="#"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
                                            <a href="#"><i class="bi bi-trash me-3 jr-list-icon"></i></a>
                                        </td>
                                    </tr>';
                    }
                }else{
                    echo '<p>Esta carpeta está vacía.</p>';
                }
            
                
?>
                </tbody>
            </table>            
        </div>
    </div>
<?php
}


