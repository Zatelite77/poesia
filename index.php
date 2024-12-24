<?php
session_start();
?>
<!DOCTYPE html>

<html>
    <?php
    include 'head.php';
    ?>
    <body>
        <?php
        include 'header.php';
        echo '<div class="container pt-1">';
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != null){
            if(!isset($_GET['loc'])){
                include 'muro.php';
            }else if($_GET['loc']=='dash'){
                include 'escritorio.php';
            }           
        }else{
            if(!isset($_GET['loc']) || $_GET['loc']=='login' || $_GET['loc']=='error'){
                include 'login.php';
            }else{
                include 'register.php';
            }
            
        }
        echo '</div>';
        include 'footer.php';
        ?>
    </body>
</html>
