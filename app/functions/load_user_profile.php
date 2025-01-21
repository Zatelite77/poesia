<?php
session_start();
include 'functions.php';
$userId = $_SESSION['id_user'];
$userImg = jrMysqli("SELECT meta_content FROM users_meta WHERE meta_type='1' && id_owner=?", $userId);
$user = getAutorInfo($userId);
?>
<div class="container">
    <div class="container mb-4">
        <h1 class="fs-4">Perfil de Usuario</h1>
    </div>
    <div class="container d-flex">
        <div class="col col-lg-3 col-md-4 border-end">
            <div class="jr-user-profile-img">
                <img src="<?php echo $userImg ?>" alt="user profile image">
                <p><?php echo $user['first_name'] ?></p>
                <p><?php echo $user['last_name'] ?></p>
            </div>
        </div>
        <div class="col col-lg-9 col-md-8">Columna Derecha</div>
    </div>
</div>