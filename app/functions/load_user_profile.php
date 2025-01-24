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
                <form id="form_imagen" action="functions/uploadImageProfile.php" method="post" enctype="multipart/form-data">
                    <label for="imagen">Selecciona tu imagen de perfil:</label>
                    <input type="file" name="imagen" id="imagen" accept="image/*" onchange="manejarCargaImagen(event)" required>
                    <br>
                    <div>
                        <img id="image" src="" alt="Vista previa" style="max-width:100%;">
                    </div>
                    <br>
                    <input type="submit" value="Subir Imagen">
                </form>
            </div>
        </div>
        <div class="col col-lg-9 col-md-8">Columna Derecha</div>
    </div>
</div>