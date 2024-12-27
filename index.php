<?php
session_start();
?>
<!DOCTYPE html>

<html>
    <?php
    include 'commons/head.php';
    ?>
    <body>
        <?php
        include 'commons/header.php';
        echo '<div class="container pt-1">';
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != null){
            if(!isset($_GET['loc'])){
                include 'endpoints/muro.php';
            }else if($_GET['loc']=='dash'){
                include 'endpoints/escritorio.php';
            }           
        }else{
            if(!isset($_GET['loc']) || $_GET['loc']=='login' || $_GET['loc']=='error'){
                include 'login.php';
            }else{
                include 'register.php';
            }
            
        }
        echo '</div>';
        include 'commons/footer.php';
        ?>
    </body>
</html>
