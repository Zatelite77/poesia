<?php
include 'functions.php';
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
    $_SESSION['id_user'] = $resultado['id'];
    $_SESSION['first_name'] = $resultado['first_name'];
    $_SESSION['last_name'] = $resultado['last_name'];
    if(!$_SESSION['id_user'] || $_SESSION['id_user']==null){
        echo 'No se inicializó la variable de sesión';
    }else{
        header("Location: https://letterwinds.com/app");
    }
}else{
    header("Location: https://letterwinds.com/app/?loc=error");
}
//Archivo restaurado