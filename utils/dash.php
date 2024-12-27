    <?php
    // include 'functions/functions.php';
    // $conn = conn();
    ?>
    <div class="col-lg-3 col-md-3 border-end pt-4">
        <h5>Carpetas</h5>
        <?php
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
                            <i class="ms-2 bi bi-three-dots" onclick="folder_options(this, '.$result['id'].');"></i>
                            <div class="folder-options-menu" id="folder-options-'.$result['id'].'" style="display: none;"></div>
                            </div><br>';
                }
            };
        ?>
    </div>
    <?php 
    echo posts_list();
    ?>