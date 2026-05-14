<?php
session_start();
include '../config/koneksi.php';
include '../auth/cek.php';

if($_SESSION['role'] != 'admin'){
    header('Location: ../login.php');
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT 
        v.*,
        u.nama,
        u.email,
        e.nama_ujian
    FROM violations v
    LEFT JOIN exam_sessions s ON v.session_id = s.id
    LEFT JOIN users u ON s.user_id = u.id
    LEFT JOIN exam_list e ON s.exam_id = e.id
    WHERE v.id='$id'
");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Pelanggaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="mb-4">Detail Pelanggaran</h3>

                <table class="table table-bordered">
                    <tr>
                        <th>Nama Siswa</th>
                        <td><?= $data['nama']; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $data['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Ujian</th>
                        <td><?= $data['nama_ujian']; ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Pelanggaran</th>
                        <td><?= $data['violation_type']; ?></td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td><?= $data['timestamp']; ?></td>
                    </tr>
                    <tr>
                        <th>Detail</th>
                        <td><?= $data['details']; ?></td>
                    </tr>
                </table>

                <a href="dashboard.php" class="btn btn-primary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

</body>

</html>