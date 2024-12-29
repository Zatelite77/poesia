<?php
include 'functions/functions.php';
$conn = conn();
$email = $_POST['email'];
$pass = $_POST['pass'];
// var_dump($email);
// var_dump($pass);
$consulta = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' && pass='$pass'");
$resultado = mysqli_fetch_assoc($consulta);
if($resultado != null){
    //var_dump($resultado);
    session_start();
    $_SESSION['user_id'] = $resultado['id'];
    header("Location: http://localhost:8888/poesia/");
}else{
    header("Location: http://localhost:8888/poesia/?loc=error");
}
