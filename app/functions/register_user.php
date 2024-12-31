<?php
include 'functions.php';
$conn = conn();
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$pass = $_POST['pass'];
// var_dump($email);
// var_dump($pass);
$consulta = mysqli_query($conn, "INSERT INTO users (email, pass, first_name, last_name) VALUES ('$email', '$pass', '$first_name', '$last_name')");
$id = mysqli_insert_id($conn);
if($consulta){
    //var_dump($resultado);
    session_start();
    $_SESSION['id_user'] = $id;
    header("Location: https://letterwinds.com/app");
}else{
    header("Location: https://letterwinds.com/app/?loc=error");
}
//Archivo restaurado