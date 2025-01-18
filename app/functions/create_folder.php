<?php
session_start();
include 'functions.php';
$conn = conn();
$user_id = $_SESSION['id_user'];
if($_POST['name']){
    $folder = $_POST['name'];
}else{
    echo 'error';
}
$consult = mysqli_query($conn, "INSERT INTO folders (id_owner, folder_name) VALUES ('$user_id', '$folder')");
if($consult){
    header("Location: https://letterwinds.com/app/escritorio");
}

