    <?php
    session_start();
    ?>
    <div class="border-bottom container mb-4">
        <nav class="navbar navbar-expand-lg navbar-expand-md navbar-expand-sm navbar-light container d-flex justify-content-between">
          <a class="navbar-brand" href="https://letterwinds.com"><img src="commons/img/SVG/Letterwinds_logo.svg" style="width:150px;"/></a>
                <?php
                if(isset($_SESSION['id_user'])){
                  echo'<div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                          <a class="nav-link" href="?loc=dash">Escritorio</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="functions/logout.php">Cerrar Sesión</a>
                        </li>
                      </ul>
                    </div>';
                }
                ?>
        </nav>
    </div>