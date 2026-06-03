<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$exam_id = isset($_GET['exam']) ? (int)$_GET['exam'] : 1;

$ip = $_SERVER['REMOTE_ADDR'];
$device = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']);

/* cek session aktif */
$cek = mysqli_query($conn,"
SELECT id
FROM active_sessions
WHERE user_id='$user_id'
AND exam_id='$exam_id'
");

if(mysqli_num_rows($cek)==0){

    mysqli_query($conn,"
    INSERT INTO active_sessions(
        user_id,
        exam_id,
        ip_address,
        device_info,
        status,
        login_time,
        last_ping
    )
    VALUES(
        '$user_id',
        '$exam_id',
        '$ip',
        '$device',
        'online',
        NOW(),
        NOW()
    )
    ");

}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Mulai Ujian</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">

        <div class="card shadow">

            <div class="card-body text-center">

                <h3>Monitoring Ujian Aktif</h3>

                <p>
                    Sistem sedang memverifikasi sesi ujian Anda.
                </p>

                <button id="btnMulai" class="btn btn-primary btn-lg">
                    Mulai Ujian
                </button>

            </div>

        </div>

    </div>

    <script>
    /* heartbeat setiap 30 detik */

    setInterval(function() {

        fetch('heartbeat.php')
            .then(response => response.json())
            .then(data => {

                if (data.status == 'blocked') {
                    window.location.href = 'blocked.php';
                }

            });

    }, 30000);

    /* buka moodle */

    document.getElementById('btnMulai')
        .addEventListener('click', function() {

            window.open(
                'https://elearning.ihbs.sch.id:2024/',
                '_blank'
            );

        });
    </script>

</body>

</html>