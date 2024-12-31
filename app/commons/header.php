    <?php
    session_start();
    ?>
    <div class="border-bottom container mb-4 ps-4 pe-4 pt-2 pb-2">
        <nav class="d-flex justify-content-between">
          <div class="d-flex" style="align-items:center!important;">
            <a class="me-5 mb-0 p-0" href="https://letterwinds.com"><img src="commons/img/SVG/Letterwinds_logo.svg" style="width:150px;" class="mb-1"/></a>
            <?php
            if(isset($_SESSION['id_user'])){
              echo '<a class="btn btn-light me-2 jr-menu-item" href="?loc=dash"><i class="bi bi-feather"></i></a>
            <a class="btn btn-light me-2 jr-menu-item" href="#"><i class="bi bi-book"></i></a>
            <form class="d-flex" role="search">
              <div class="input-group">
                <span class="input-group-text" style="background-color:#F8F9FA!important;">
                  <i class="bi bi-search"></i>
                </span>
                <input type="search" class="form-control">
              </div>
            </form>
          </div>
          <div class="d-flex">
            <a class="btn btn-light me-2 jr-menu-item" href="#"><i class="bi bi-bell"></i></a>
            <a class="btn btn-light me-2 jr-menu-item" href="#"><i class="bi bi-person-circle"></i></a>
            <a class="btn btn-light jr-menu-item" href="functions/logout.php"><i class="bi bi-box-arrow-right"></i></a>
          </div>';
            }
            ?>
        </nav>
    </div>