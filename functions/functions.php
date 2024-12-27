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
        $consulta_escritos = mysqli_query($conn, "SELECT * FROM posts WHERE id_owner='$user_id'");
        $result_escritos = mysqli_fetch_all($consulta_escritos);
    }else if($_GET['action']=="openfolder"){
        $folderid = $_GET['folderid'];
        $consulta_escritos = mysqli_query($conn, "SELECT * FROM posts INNER JOIN posts_folders ON posts.id = posts_folders.id_post INNER JOIN folders ON posts_folders.id_folder = folders.id WHERE folders.id = '$folderid'");
        $result_escritos = mysqli_fetch_all($consulta_escritos);
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
            if($result_escritos){
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
                foreach($result_escritos as $escrito){
                    $consulta_carpeta = mysqli_query($conn, "SELECT * FROM posts_folders WHERE id_post='$escrito[0]'");
                    if($consulta_carpeta){
                        $consulta_carpeta_result = mysqli_fetch_assoc($consulta_carpeta);
                        if($consulta_carpeta_result != null){
                            $folder = $consulta_carpeta_result['folder_name'];
                        }else {
                            $folder = '...';
                        }
                    }
                $status_init=0;
                $status = ($escrito['6']==$status_init) ? 'Draft' : 'Published';
                echo    '<tr>
                                <td>'.$escrito[3].'</td>
                                <td>'.date("d/m/Y", strtotime($escrito[5])).'</td>
                                <td>'.$status.'</td>
                                <td>'.$folder.'</td>
                                <td>
                                    <a href="#"><i class="bi bi-pencil me-3 jr-list-icon"></i></a>
                                    <a href="#"><i class="bi bi-trash me-3 jr-list-icon"></i></a>
                                </td>
                            </tr>';
                }
            }else{
                echo '<div class="p-2">Esta carpeta está vacía</div>';
            }
?>
                </tbody>
            </table>            
        </div>
    </div>
<?php
}


