    <?php
    session_start();
    ?>
    <div class="border-bottom container mb-2 ps-4 pe-4 pt-2 pb-2">
        <nav class="d-flex justify-content-between">
          <div class="d-flex" style="align-items:center!important;">
            <a class="me-5 mb-0 p-0" href="https://letterwinds.com"><img src="commons/img/SVG/Letterwinds_logo.svg" style="width:150px;" class="mb-1"/></a>
            <?php
            if(isset($_SESSION['id_user'])){
              echo '<a class="btn btn-light me-2 jr-menu-item" href="/app/escritorio"><i class="bi bi-feather"></i></a>
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
            <a class="btn btn-light me-2 jr-menu-item" href="#" id="userProfileButton"><i class="bi bi-person-circle"></i></a>
            <form class="me-3" action="functions/cambiarUsuario.php" method="POST">
              <select onchange="this.form.submit()" class="form-select" name="selectorUsuario">';
              $user = $_SESSION['id_user'];
              switch($user){
                case '0000000001':
                echo '<option value="0000000001" selected>Jose Roman</option>
                      <option value="0000000008">Francisco de Quevedo</option>
                      <option value="0000000007">Lope Felix De Vega Carpio</option>';
                      break;
                case '0000000008':
                echo '<option value="0000000001">Jose Roman</option>
                      <option value="0000000008" selected>Francisco de Quevedo</option>
                      <option value="0000000007">Lope Felix De Vega Carpio</option>';
                      break;
                case '0000000007':
                echo '<option value="0000000001">Jose Roman</option>
                      <option value="0000000008">Francisco de Quevedo</option>
                      <option value="0000000007" selected>Lope Felix De Vega Carpio</option>';
                      break;
              }           
              echo '
                
              </select>
            </form>
            <a class="btn btn-light jr-menu-item" href="functions/logout.php"><i class="bi bi-box-arrow-right"></i></a>
          </div>';
            }
            ?>
        </nav>
    </div>