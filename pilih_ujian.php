<?php
session_start();
include 'config/koneksi.php';
$data = mysqli_query($conn, "SELECT * FROM exam_list WHERE status='aktif' AND tanggal=CURDATE()");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pilih Ujian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-4">
        <h3>Pilih Ujian Aktif</h3>
        <div class="row">
            <?php while($d = mysqli_fetch_assoc($data)): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5><?= $d['nama_ujian']; ?></h5>
                        <p><?= $d['mapel']; ?> - <?= $d['kelas']; ?></p>
                        <a href="token.php?id=<?= $d['id']; ?>" class="btn btn-primary btn-block">Masuk Ujian</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>