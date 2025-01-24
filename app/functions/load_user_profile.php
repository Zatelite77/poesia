<?php
session_start();
include 'functions.php';
$userId = $_SESSION['id_user'];
$userImg = jrMysqli("SELECT meta_content FROM users_meta WHERE meta_type='1' && id_owner=?", $userId);
$user = getAutorInfo($userId);
$publishedPosts = getPublishedPostsByUser($userId);
$draftPosts = getDraftPostsByUser($userId);
$commentsMade = getCommentsMadeByUser($userId);
// $commentsReceived = getCommentsReceivedByUser($userId);
// $votesMade = getVotesMadeByUser($userId);
// $votesReceived = getVotesReceivedByUser($userId);
// $following = getFollowingByUser($userId);
// $followers = getFollowersByUser($userId);
?>
<div class="container">
    <div class="container mb-4">
        <h1 class="fs-4">Perfil de Usuario</h1>
    </div>
    <div class="container d-flex">
        <div class="row g-3 d-flex col-12">
            <div class="col col-lg-3 col-md-4 border-end">
                <div class="card">
                    <div class="card-header">
                        <h3 class="jr-uspfl-head-h3 mb-2">Resumen de actividad:</h3>
                    </div>
                    <div class="card-body">
                        <div class="jr-uspfl-actitem-box d-flex border border-light mb-1 justify-content-between">
                            <span class="jr-uspfl-actitem-box-lft-cont">
                                <span class="jr-uspfl-actitem-lft-cont-txt">Escritos Publicados</span>
                            </span>
                            <span class="jr-uspfl-actitem-box-right-cont">
                                <span class="jr-uspfl-actitem-box-right-cont-txt"><?php echo $publishedPosts ?></span>
                            </span>
                        </div>
                        <div class="jr-uspfl-actitem-box d-flex border border-light mb-1 justify-content-between">
                            <span class="jr-uspfl-actitem-box-lft-cont">
                                <span class="jr-uspfl-actitem-lft-cont-txt">Escritos en Borrador</span>
                            </span>
                            <span class="jr-uspfl-actitem-box-right-cont">
                                <span class="jr-uspfl-actitem-box-right-cont-txt"><?php echo $draftPosts ?></span>
                            </span>
                        </div>
                        <div class="jr-uspfl-actitem-box d-flex border border-light mb-1 justify-content-between">
                            <span class="jr-uspfl-actitem-box-lft-cont">
                                <span class="jr-uspfl-actitem-lft-cont-txt">Comentarios Realizados</span>
                            </span>
                            <span class="jr-uspfl-actitem-box-right-cont">
                                <span class="jr-uspfl-actitem-box-right-cont-txt"><?php echo count($commentsMade) ?></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-lg-9 col-md-8">
                <div class="jr-user-profile-img">
                    <img src="<?php echo $userImg ?>" alt="user profile image">
                    <p><?php echo $user['first_name'] ?> <?php echo $user['last_name'] ?></p>
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
        </div>
    </div>
</div>