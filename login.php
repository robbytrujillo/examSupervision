<?php
session_start();
include 'config/koneksi.php';
$error = '';

if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header('Location: admin/dashboard.php');
    } else {
        header('Location: pilih_ujian.php');
    }
    exit;
}

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $user = mysqli_fetch_assoc($query);

    if($user){
        if($user['status'] == 'blokir'){
            header('Location: blocked.php');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == 'admin'){
            header('Location: admin/dashboard.php');
        } else {
            header('Location: pilih_ujian.php');
        }
        exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Monitoring Ujian</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 shadow-lg rounded-lg overflow-hidden bg-white" style="max-width: 950px;">
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-center text-white p-5"
                style="background: linear-gradient(135deg, #2563eb, #1e3a8a);">
                <h2 class="font-weight-bold mb-3">Monitoring Ujian Online</h2>
                <p class="lead">Sistem pengawasan ujian Moodle dengan deteksi pelanggaran otomatis, dashboard admin, dan
                    keamanan modern untuk sekolah.</p>
                <ul class="mt-3 pl-3">
                    <li>Deteksi tab switching</li>
                    <li>Monitoring fullscreen</li>
                    <li>Blokir otomatis</li>
                    <li>Multi ujian & multi kelas</li>
                </ul>
            </div>

            <div class="col-md-6 p-5">
                <div class="text-center mb-4">
                    <h3 class="font-weight-bold">Masuk Sistem</h3>
                    <p class="text-muted">Silakan login untuk melanjutkan</p>
                </div>

                <?php if($error): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control rounded-pill" placeholder="Masukkan email"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control rounded-pill"
                            placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" name="login"
                        class="btn btn-primary btn-block rounded-pill py-2 font-weight-bold">
                        Login
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small>Belum punya akun? <a href="register.php">Daftar</a></small>
                </div>
            </div>
        </div>
    </div>
</body>

</html>