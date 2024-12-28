<?php
if (isset($_GET['folder_id'])) {
    $folder_id = $_GET['folder_id']; // Sanitizar el ID

    echo '
    <a href="#" onclick="rename_folder(\''.$folder_id.'\')">Renombrar</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteFolderModal">Eliminar</a>
    ';
}
?>
