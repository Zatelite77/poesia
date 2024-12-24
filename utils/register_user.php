<?php
include 'conn.php';
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
    $_SESSION['user_id'] = $id;
    header("Location: http://localhost:8888/poesia/");
}else{
    header("Location: http://localhost:8888/poesia/?loc=error");
}
