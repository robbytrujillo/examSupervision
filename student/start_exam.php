<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$exam_id = isset($_GET['exam']) ? (int)$_GET['exam'] : 1;

$moodle_url = "https://elearning.ihbs.sch.id:2024/";

$ip_address = $_SERVER['REMOTE_ADDR'];

$device_info = mysqli_real_escape_string(
    $conn,
    $_SERVER['HTTP_USER_AGENT']
);

/* Cek apakah user diblokir */

$cek_block = mysqli_query($conn,"
SELECT status
FROM active_sessions
WHERE user_id='$user_id'
LIMIT 1
");

if(mysqli_num_rows($cek_block) > 0){

    $block_data = mysqli_fetch_assoc($cek_block);

    if($block_data['status'] == 'blocked'){
        header("Location: blocked.php");
        exit;
    }
}

/* Cek session aktif */

$cek_session = mysqli_query($conn,"
SELECT id
FROM active_sessions
WHERE user_id='$user_id'
AND exam_id='$exam_id'
LIMIT 1
");

if(mysqli_num_rows($cek_session) == 0){

    mysqli_query($conn,"
    INSERT INTO active_sessions(
        user_id,
        exam_id,
        ip_address,
        device_info,
        status,
        violation_count,
        login_time,
        last_ping
    )
    VALUES(
        '$user_id',
        '$exam_id',
        '$ip_address',
        '$device_info',
        'online',
        0,
        NOW(),
        NOW()
    )
    ");

}else{

    mysqli_query($conn,"
    UPDATE active_sessions
    SET
        last_ping = NOW(),
        status='online'
    WHERE user_id='$user_id'
    AND exam_id='$exam_id'
    ");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Monitoring Ujian</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
    body {
        background: #f5f7fb;
    }

    .card-monitor {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
    }

    .status-online {
        color: #28a745;
        font-weight: bold;
    }
    </style>

</head>

<body>

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-lg-6">

                <div class="card card-monitor">

                    <div class="card-body p-5">

                        <h3 class="text-center mb-4">
                            Monitoring Ujian Aktif
                        </h3>

                        <div class="text-center mb-4">

                            <h5 class="status-online">
                                ● Status Monitoring Aktif
                            </h5>

                            <p class="text-muted">
                                Sistem sedang memantau aktivitas ujian Anda.
                            </p>

                        </div>

                        <table class="table table-bordered">

                            <tr>
                                <th>User ID</th>
                                <td><?= $user_id ?></td>
                            </tr>

                            <tr>
                                <th>Exam ID</th>
                                <td><?= $exam_id ?></td>
                            </tr>

                            <tr>
                                <th>IP Address</th>
                                <td><?= $ip_address ?></td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-success">
                                        ONLINE
                                    </span>
                                </td>
                            </tr>

                        </table>

                        <button id="btnMulai" class="btn btn-primary btn-lg btn-block">

                            Mulai Ujian Moodle

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
    /* HEARTBEAT */

    function sendHeartbeat() {

        fetch('heartbeat.php')
            .then(response => response.json())
            .then(data => {

                if (data.status === 'blocked') {

                    window.location.href = 'blocked.php';

                }

            })
            .catch(error => {

                console.log(error);

            });

    }

    /* Kirim heartbeat tiap 30 detik */

    setInterval(sendHeartbeat, 30000);

    /* Jalankan sekali saat load */

    sendHeartbeat();

    /* Tombol buka Moodle */

    document.getElementById('btnMulai')
        .addEventListener('click', function() {

            window.open(
                '<?= $moodle_url ?>',
                '_blank'
            );

        });
    </script>

</body>

</html>