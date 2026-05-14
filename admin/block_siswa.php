<?php
session_start();
include '../config/koneksi.php';
include '../auth/cek.php';

if($_SESSION['role'] != 'admin'){
    header('Location: ../login.php');
    exit;
}

$session_id = intval($_GET['session_id']);

$get = mysqli_query($conn, "
    SELECT user_id 
    FROM exam_sessions 
    WHERE id='$session_id'
");

$data = mysqli_fetch_assoc($get);

if($data){
    $user_id = $data['user_id'];

    mysqli_query($conn, "
        UPDATE users 
        SET status='blokir' 
        WHERE id='$user_id'
    ");

    mysqli_query($conn, "
        UPDATE exam_sessions 
        SET status='diblokir' 
        WHERE id='$session_id'
    ");
}

header('Location: dashboard.php');
exit;
?>