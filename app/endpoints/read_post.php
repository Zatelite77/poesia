<?php
$user_id = $_SESSION['id_user'];
$id_post = $_GET['idpost'];
$datos = getPostInfo($id_post);
echo '
<div class="col-lg-4 col-md-6 col-sm-12 pt-4">
<h3>'.$datos['title'].'</h3>
<p>'.$datos['content'].'</p>    
</div>';