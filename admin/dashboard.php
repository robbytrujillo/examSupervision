<?php
include '../config/koneksi.php';
include '../auth/cek.php';

$total_siswa = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE role='siswa'"));
$total_ujian = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM exam_list"));
$total_pelanggaran = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM violations"));
$total_blokir = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE status='blokir'"));
?>