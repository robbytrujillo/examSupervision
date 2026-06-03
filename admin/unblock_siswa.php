<?php

include '../config/koneksi.php';

$id=(int)$_GET['id'];

mysqli_query($conn,"
UPDATE active_sessions
SET status='online'
WHERE user_id='$id'
");

header("Location: monitoring.php");