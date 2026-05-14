<?php
session_start();
include '../config/koneksi.php';
include '../auth/cek.php';

if($_SESSION['role'] != 'admin'){
    header('Location: ../login.php');
    exit;
}

$id = intval($_GET['id']);

mysqli_query($conn, "
    DELETE FROM violations 
    WHERE id='$id'
");

header('Location: dashboard.php');
exit;
?>