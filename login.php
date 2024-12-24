<div class="container">
    <p class="mb-3">Inicia sesión para acceder.</p>
    <form action="utils/login_user.php" method="POST" class="mb-2">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control mb-4" name="email">
            <label for="pass" class="form-label">Password</label>
            <input type="password" class="form-control mb-4" name="pass">
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

        <p class="me-2">No tienes cuenta?</p><a href="?loc=register">Regístrate aquí</a>
    </div>

</div>