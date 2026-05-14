<?php
include '../config/koneksi.php';

if(isset($_POST['simpan'])){
    $nama = $_POST['nama_ujian'];
    $mapel = $_POST['mapel'];
    $kelas = $_POST['kelas'];
    $url = $_POST['moodle_url'];
    $token = $_POST['token'];
    $tanggal = $_POST['tanggal'];
    $durasi = $_POST['durasi'];

    mysqli_query($conn,"INSERT INTO exam_list(nama_ujian,mapel,kelas,moodle_url,token,tanggal,durasi)
    VALUES('$nama','$mapel','$kelas','$url','$token','$tanggal','$durasi')");
}
?>