<?php
session_start();
include 'config/koneksi.php';

$user_id = $_SESSION['user_id'];
$type = $_POST['type'];

$session = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM exam_sessions WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1"));

$session_id = $session['id'];

mysqli_query($conn,
"INSERT INTO violations(session_id,violation_type,details)
VALUES('$session_id','$type','Pelanggaran otomatis terdeteksi')");

$total = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM violations WHERE session_id='$session_id'"));

if($total >= 3){
    mysqli_query($conn, "UPDATE users SET status='blokir' WHERE id='$user_id'");
    mysqli_query($conn, "UPDATE exam_sessions SET status='diblokir' WHERE id='$session_id'");
}
?>