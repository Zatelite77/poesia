<!DOCTYPE html>
<html lang="en">
<?php include 'commons/head.php'; ?>
    <body>
        <div class="jr-login-background"></div>
        <div class="container p-4" style="position:relative;z-index:1!important;">
            <a class="ms-2 mb-0 p-0" href="https://letterwinds.com"><img src="commons/img/SVG/Letterwinds_logo.svg" style="width:150px;" class="mb-1"/></a>
        </div>
        <div class="container" style="z-index:10;position:relative;">
            <div class="jr-login-container">
                <h1 class="mb-3 fs-4">Crear cuenta.</h1>
                <form action="functions/register_user.php" class="mb-2" method="POST">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label for="first_name" class="form-label">Nombre</label>
                        <input type="text" class="form-control mb-4" name="first_name" required>
                        <label for="last_name" class="form-label">Apellidos</label>
                        <input type="text" class="form-control mb-4" name="last_name" required>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control mb-4" name="email" required>
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" class="form-control mb-4" name="pass" required>
                        <input type="submit" class="btn btn-primary btn-sm" value="Crear Cuenta">
                    </div>
                </form>
                <div class="d-flex">
                    <p class="me-2">Ya tienes una cuenta?</p><a href="/app/login.php ">Inicia sesión aquí</a>
                </div>
            </div>
        </div>
    </body>
</html>