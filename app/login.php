<!DOCTYPE html>
<html lang="es">
<?php include 'commons/head.php'; ?>
    <body>
        <div class="jr-login-background"></div>
        <div class="container p-4" style="position:relative;z-index:1;">
            <a class="me-1 mb-0 p-0" href="https://letterwinds.com">
                <img src="commons/img/SVG/Letterwinds_logo.svg" class="mb-1 jr-login-logo" alt="Logo de Letterwinds"/>
            </a>
        </div>
        <div class="container" style="position:relative;z-index:10;">
            <div class="jr-login-container">
                <h1 class="mb-4">Inicia sesión para acceder</h1>
                <form action="functions/login_user.php" method="POST" class="mb-3" id="login-form">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" autocomplete required>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Contraseña</label>
                        <input type="password" class="form-control form-control-lg" id="pass" name="pass" autocomplete required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Acceder</button>
                    </div>
                </form>
                <?php
                if(isset($_GET['loc']) && $_GET['loc']=="error"){
                    echo '<div class="alert alert-danger mt-3" role="alert">
                    El email o la contraseña no son correctos. Inténtalo de nuevo.
                </div>';
                }
                ?>
                <div class="text-center mt-3">
                    <p class="d-inline">¿No tienes cuenta? </p><a href="/app/register.php">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </body>
</html>
