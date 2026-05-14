<?php
session_start();
include 'config/koneksi.php';

$user_id = $_SESSION['user_id'];
$exam_id = $_SESSION['exam_id'];

$exam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM exam_list WHERE id='$exam_id'"));
$moodle_url = $exam['moodle_url'];

$ip = $_SERVER['REMOTE_ADDR'];
$device = $_SERVER['HTTP_USER_AGENT'];

mysqli_query($conn, "INSERT INTO exam_sessions(user_id,exam_id,ip_address,device_info,start_time)
VALUES('$user_id','$exam_id','$ip','$device',NOW())");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $exam['nama_ujian']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="assets/js/monitor.js"></script>
</head>

<body>
    <nav class="navbar navbar-dark bg-danger">
        <span class="navbar-brand"><?= $exam['nama_ujian']; ?> | Mode Ujian Aktif</span>
    </nav>

    <iframe src="<?= $moodle_url; ?>" width="100%" height="1000px" frameborder="0"></iframe>

    <script>
    document.documentElement.requestFullscreen();
    </script>
</body>

</html>