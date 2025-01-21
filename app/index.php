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
        <div class="container" style="min-height:75vh;">
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
                        echo '<div class="d-flex jr-main-app-container" id="jr-main-app-container">
                                    <div class="col-lg-2 col-md-3 sticky-top border" style="min-height:300px;height:300px;top:20px;"></div>
                                    <div id="wall_container" class="col-lg-4 col-md-4 col-sm-12">';
                        echo the_wall();
                        echo '</div>
                              <div id="reading_container" class="container pt-2 col-lg-6 col-md-5 col-sm-12"></div></div>';
                        break;
                }
            ?>            
        </div>
        <?php include 'commons/footer.php'; ?>
    </body>
</html>
