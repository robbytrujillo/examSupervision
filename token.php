<?php
session_start();
include 'config/koneksi.php';
$exam_id = $_GET['id'];
$error = '';

if(isset($_POST['submit'])){
    $token = $_POST['token'];
    $cek = mysqli_query($conn, "SELECT * FROM exam_list WHERE id='$exam_id' AND token='$token'");

    if(mysqli_num_rows($cek) > 0){
        $_SESSION['exam_id'] = $exam_id;
        header('Location: exam.php');
    } else {
        $error = "Token salah";
    }
}
?>