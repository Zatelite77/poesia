<?php
session_start();
include '../functions/functions.php';
$conn = conn();
$user_id=$_SESSION['user_id'];
if($_POST['name']){
    $folder = $_POST['name'];
}else{
    echo 'error';
}
$consult = mysqli_query($conn, "INSERT INTO folders (id_owner, folder_name) VALUES ('$user_id', '$folder')");
if($consult){
    header("Location: http://localhost:8888/poesia/?loc=dash");
}

