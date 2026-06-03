<?php
session_start();
include '../config/koneksi.php';
include '../auth/cek.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

/* Statistik */
$total_siswa = mysqli_num_rows(
    mysqli_query($conn, "SELECT id FROM users WHERE role='siswa'")
);

$total_ujian = mysqli_num_rows(
    mysqli_query($conn, "SELECT id FROM exam_list")
);

$total_pelanggaran = mysqli_num_rows(
    mysqli_query($conn, "SELECT id FROM violations")
);

$total_blokir = mysqli_num_rows(
    mysqli_query($conn, "SELECT id FROM users WHERE status='blokir'")
);

/* Pelanggaran terbaru */
$violations = mysqli_query($conn, "
    SELECT 
        v.*,
        u.nama,
        e.nama_ujian
    FROM violations v
    LEFT JOIN exam_sessions s ON v.session_id = s.id
    LEFT JOIN users u ON s.user_id = u.id
    LEFT JOIN exam_list e ON s.exam_id = e.id
    ORDER BY v.timestamp DESC
    LIMIT 10
");

if (!$violations) {
    die('Query Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Exam Supervision</title>

    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tambahkan di <head> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    </style>

    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f7fb;
    }

    .sidebar {
        min-height: 100vh;
        background: linear-gradient(180deg, #1d4ed8, #1e3a8a);
        color: white;
    }

    .sidebar .nav-link {
        color: white;
        font-weight: 500;
        padding: 12px;
        border-radius: 10px;
    }

    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .main-content {
        padding: 30px;
    }

    .card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .table {
        background: white;
    }

    .logout-btn {
        border-radius: 30px;
    }

    @media(max-width:768px) {
        .sidebar {
            min-height: auto;
        }

        .main-content {
            padding: 15px;
        }
    }

    /* TAMBAHKAN DI STYLE */
    .dataTables_wrapper {
        width: 100%;
    }

    .table {
        width: 100% !important;
    }

    #mobileSidebar .nav-link {
        color: white;
        font-weight: 500;
    }

    #mobileSidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
    }

    @media(max-width:768px) {
        .sidebar {
            display: none;
        }

        .col-md-10.main-content {
            max-width: 100%;
            flex: 0 0 100%;
        }

        .card h2 {
            font-size: 1.5rem;
        }

        .card h6 {
            font-size: 0.85rem;
        }
    }
    </style>
</head>

<body>

    <!-- Tambahkan di dalam <body> sebelum container-fluid -->

    <nav class="navbar navbar-dark bg-primary d-md-none">
        <button class="btn btn-outline-light" type="button" data-toggle="collapse" data-target="#mobileSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
    </nav>

    <div class="collapse d-md-none" id="mobileSidebar">
        <div class="bg-primary p-3">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="ujian.php" class="nav-link text-white">
                        <i class="fas fa-file-alt mr-2"></i> Data Ujian
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="siswa.php" class="nav-link text-white">
                        <i class="fas fa-users mr-2"></i> Data Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="violations.php" class="nav-link text-white">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Pelanggaran
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <a href="../logout.php" class="btn btn-danger btn-block rounded-pill">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <!-- <div class="col-md-2 sidebar p-4"> -->
            <div class="col-md-2 sidebar p-4 d-none d-md-block">
                <h3 class="font-weight-bold mb-4">Admin Panel</h3>

                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="dashboard.php" class="nav-link">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="ujian.php" class="nav-link">
                            <i class="fas fa-file-alt mr-2"></i> Data Ujian
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="siswa.php" class="nav-link">
                            <i class="fas fa-users mr-2"></i> Data Siswa
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="violations.php" class="nav-link">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="monitoring.php" class="nav-link">
                            <i class="fas fa-desktop mr-2"></i>
                            Monitoring Real Time
                        </a>
                    </li>

                    <li class="nav-item mt-4">
                        <a href="../logout.php" class="btn btn-danger btn-block logout-btn">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">

                <h2 class="font-weight-bold mb-4">Dashboard Monitoring Ujian</h2>

                <!-- Statistik Cards -->
                <div class="row mb-4">

                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h6>Total Siswa</h6>
                                <h2><?= $total_siswa; ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h6>Total Ujian</h6>
                                <h2><?= $total_ujian; ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h6>Total Pelanggaran</h6>
                                <h2><?= $total_pelanggaran; ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h6>Akun Diblokir</h6>
                                <h2><?= $total_blokir; ?></h2>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Chart -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Statistik Monitoring</h5>
                        <div style="height:350px;">
                            <canvas id="dashboardChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Pelanggaran Terbaru -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Pelanggaran Terbaru</h5>

                        <div class="table-responsive">
                            <!-- <table class="table table-bordered table-hover"> -->
                            <table id="violationsTable" class="table table-bordered table-hover dt-responsive nowrap">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Ujian</th>
                                        <th>Jenis Pelanggaran</th>
                                        <th>Waktu</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <!-- <tbody>
                                    <?php if (mysqli_num_rows($violations) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($v = mysqli_fetch_assoc($violations)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $v['nama'] ? $v['nama'] : '-'; ?></td>
                                        <td><?= $v['nama_ujian'] ? $v['nama_ujian'] : '-'; ?></td>
                                        <td>
                                            <span class="badge badge-danger">
                                                <?= $v['violation_type']; ?>
                                            </span>
                                        </td>
                                        <td><?= $v['timestamp']; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Belum ada data pelanggaran.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody> -->

                                <tbody>
                                    <?php if(mysqli_num_rows($violations) > 0): ?>
                                    <?php $no=1; while($v = mysqli_fetch_assoc($violations)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $v['nama'] ?: '-'; ?></td>
                                        <td><?= $v['nama_ujian'] ?: '-'; ?></td>
                                        <td><?= $v['violation_type']; ?></td>
                                        <td><?= $v['timestamp']; ?></td>
                                        <td>
                                            <a href="detail_pelanggaran.php?id=<?= $v['id']; ?>"
                                                class="btn btn-info btn-sm mb-1">
                                                Detail
                                            </a>

                                            <a href="block_siswa.php?session_id=<?= $v['session_id']; ?>"
                                                class="btn btn-warning btn-sm mb-1"
                                                onclick="return confirm('Blokir siswa ini?')">
                                                Block
                                            </a>

                                            <a href="hapus_pelanggaran.php?id=<?= $v['id']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Hapus data pelanggaran ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('dashboardChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Siswa', 'Ujian', 'Pelanggaran', 'Diblokir'],
            datasets: [{
                label: 'Data Sistem',
                data: [
                    <?= $total_siswa ?>,
                    <?= $total_ujian ?>,
                    <?= $total_pelanggaran ?>,
                    <?= $total_blokir ?>
                ],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- TAMBAHKAN SEBELUM </body> -->
    <script>
    $(document).ready(function() {
        $('#violationsTable').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    });
    </script>
</body>

</html>