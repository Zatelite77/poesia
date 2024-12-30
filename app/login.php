<!DOCTYPE html>
<html lang="en">
<?php include 'commons/head.php'; ?>
    <body>
        <div class="jr-login-background"></div>
        <?php include 'commons/header.php'; ?>
        <div class="container" style="z-index:10;position:relative;">
            <div class="jr-login-container">
                <h1 class="mb-3 fs-4">Inicia sesión para acceder.</h1>
                <form action="functions/login_user.php" method="POST" class="mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control mb-4" name="email" autocomplete>
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" class="form-control mb-4" name="pass" autocomplete>
                        <input type="submit" class="btn btn-primary btn-sm" value="Acceder">
                    </div>
                </form>
                <?php
                if(isset($_GET['loc']) && $_GET['loc']=="error"){
                    echo '<div class="alert alert-danger" role="alert">
                    El email o la contraseña no son correctos. Inténtalo de nuevo.
                </div>';
                }
                ?>
                <div class="d-flex">
                    <p class="me-2">No tienes cuenta?</p><a href="/app/register.php">Regístrate aquí</a>
                </div>
            </div>
        </div>
</body>
</html>