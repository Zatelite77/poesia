<?php
include 'functions/functions.php';
$conn = conn();
$user_id = $_SESSION['user_id'];
?>
<div class="container d-flex align-items-center border-bottom">
    <a href="?loc=dash&action=dash" class="me-4 fs-4 text-decoration-none">Escritorio</a>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="navbar-nav">
            <a class="me-2 ms-2 btn btn-light" href="?loc=dash&action=newfolder"><i class="bi bi-folder-plus me-1"></i>Crear Carpeta</a>
            <a class="me-2 ms-2 btn btn-light" href="?loc=dash&action=newpost"><i class="bi bi-file-earmark-plus me-1"></i>Crear Escrito</a>
            
        </div>
    </nav>
</div>
<div class="container d-flex">
    <?php
    if(!isset($_GET['action']) || $_GET['action']=='dash' || $_GET['action']=='openfolder'){
        include 'utils/dash.php';
    }else if($_GET['action']=="newfolder"){
        include 'utils/new_folder.php';
    }else if($_GET['action']=="newpost"){
        include 'utils/new_post.php';
    }else if($_GET['action']=="editpost"){
        include 'utils/edit_post.php';
    }
    ?>
</div>