<!-- /* The PHP code block you provided is handling user registration. Here's a breakdown of what it
does: */
<?php
include 'config/koneksi.php';

if(isset($_POST['register'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    mysqli_query($conn, "INSERT INTO users(nama,email,password) VALUES('$nama','$email','$password')");
    header('Location: login.php');
}
?> -->

<?php
session_start();
include 'config/koneksi.php';

$error = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    header('Location: pilih_ujian.php');
    exit;
}

if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $kelas    = mysqli_real_escape_string($conn, $_POST['kelas']);

    $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $insert = mysqli_query($conn, "
            INSERT INTO users (nama, email, password, kelas, role, status)
            VALUES (
                '$nama',
                '$email',
                '$password',
                '$kelas',
                'siswa',
                'aktif'
            )
        ");

        if ($insert) {
            $success = "Registrasi berhasil. Silakan login.";
        } else {
            $error = "Registrasi gagal.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Exam Supervision</title>

    <link rel="icon" type="image/x-icon" href="assets/img/logo.png">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
    body {
        background: linear-gradient(135deg, #2563eb, #1e3a8a);
        font-family: 'Segoe UI', sans-serif;
        min-height: 100vh;
    }

    .register-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .left-panel {
        background: rgba(255, 255, 255, 0.08);
        color: white;
        padding: 50px;
    }

    .right-panel {
        background: white;
        padding: 50px;
    }

    .form-control {
        border-radius: 30px;
        padding: 12px 18px;
    }

    .btn-custom {
        border-radius: 30px;
        padding: 12px;
        font-weight: bold;
    }

    @media(max-width:768px) {
        .left-panel {
            display: none;
        }

        .right-panel {
            padding: 30px;
        }
    }
    </style>
</head>

<body>

    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 register-card" style="max-width: 1000px;">

            <!-- Left Info -->
            <div class="col-md-6 left-panel d-flex flex-column justify-content-center">
                <h2 class="font-weight-bold mb-4">Daftar Akun Ujian</h2>
                <p class="lead">
                    Sistem monitoring ujian online modern untuk mendeteksi pelanggaran,
                    menjaga integritas akademik, dan memastikan keamanan peserta.
                </p>

                <ul class="mt-4">
                    <li>Multi ujian</li>
                    <li>Monitoring anti-cheat</li>
                    <li>Deteksi tab switching</li>
                    <li>Auto block system</li>
                </ul>
            </div>

            <!-- Register Form -->
            <div class="col-md-6 right-panel">

                <div class="text-center mb-4">
                    <h3 class="font-weight-bold">Registrasi Siswa</h3>
                    <p class="text-muted">Buat akun untuk mengikuti ujian</p>
                </div>

                <?php if($error): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <?php if($success): ?>
                <div class="alert alert-success"><?= $success; ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="Contoh: X IPA 1" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary btn-block btn-custom">
                        Daftar Sekarang
                    </button>

                </form>

                <div class="text-center mt-4">
                    <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
                </div>

            </div>

        </div>
    </div>

</body>

</html>