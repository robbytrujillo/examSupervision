<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['user_id'])){
    exit;
}

$user_id = $_SESSION['user_id'];

mysqli_query($conn,"
UPDATE active_sessions
SET last_ping = NOW()
WHERE user_id='$user_id'
");

$q = mysqli_query($conn,"
SELECT status
FROM active_sessions
WHERE user_id='$user_id'
LIMIT 1
");

$data = mysqli_fetch_assoc($q);

echo json_encode([
    'status'=>$data['status']
]);