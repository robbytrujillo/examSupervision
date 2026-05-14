<?php
include 'config/koneksi.php';

if(isset($_POST['register'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    mysqli_query($conn, "INSERT INTO users(nama,email,password) VALUES('$nama','$email','$password')");
    header('Location: login.php');
}
?>