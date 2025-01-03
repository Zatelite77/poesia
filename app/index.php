<?php
session_start();
if(!isset($_SESSION['id_user'])){
    header('Location: https://letterwinds.com');
}
include 'functions/functions.php';
?>
<!DOCTYPE html>

<html>
    <?php
    include 'commons/head.php';
    ?>

    <body>
        <div class="container">
            <?php include 'commons/header.php'; ?>
            <?php
                $route = isset($_GET['route']) ? $_GET['route'] : 'home';
                //var_dump($route);
                switch ($route) {
                    case 'escritorio':
                        include 'endpoints/escritorio.php';
                        break;
                    case 'perfil':
                        include 'profile.php';
                        break;
                    default:
                        echo '<div class="d-flex"><div id="wall_container" class="col-lg-4 col-md-6 col-sm-12">';
                        echo the_wall();
                        echo '</div>
                              <div id="reading_container" class="col-lg-8 col-md-6 col-sm-12"></div></div>';
                        break;
                }
            ?>            
        </div>
        <?php include 'commons/footer.php'; ?>
    </body>
</html>
