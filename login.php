<?php
session_start();
include 'config/koneksi.php';
$error = '';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $user = mysqli_fetch_assoc($query);

    if($user){
        if($user['status'] == 'blokir'){
            header('Location: blocked.php');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == 'admin'){
            header('Location: admin/dashboard.php');
        } else {
            header('Location: pilih_ujian.php');
        }
    } else {
        $error = "Login gagal";
    }
}
?>