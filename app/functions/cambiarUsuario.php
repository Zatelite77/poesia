<?php
include 'functions.php';
$conn = conn();
session_start();
$user = $_POST['selectorUsuario'];
$consulta = mysqli_query($conn, "SELECT * FROM users WHERE id='$user'");
$resultado = mysqli_fetch_assoc($consulta);
session_destroy();
session_start();
$_SESSION['id_user'] = $user;
$_SESSION['first_name'] = $resultado['first_name'];
$_SESSION['last_name'] = $resultado['last_name'];
header("Location: https://letterwinds.com/app");