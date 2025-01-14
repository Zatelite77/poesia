<!-- Modal -->
    <div class="modal fade" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="deleteFolderModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>
        <?php
        echo folders_list();
        $idUser = $_SESSION['id_user'];
        $posts = jrMysqli("SELECT * FROM posts WHERE id_owner=?", $idUser);
        if($posts){
            echo posts_list();
        }else{
            echo '<div class="p-4">
                    <div class="alert alert-primary" role="alert">
                        Aún no tienes ningún escrito!
                    </div>
                  </div>';
        }
        ?>