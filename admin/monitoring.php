<?php
session_start();
include '../config/koneksi.php';
include '../auth/cek.php';
?>

<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

</head>

<body>

    <div class="container-fluid mt-4">

        <h3>Monitoring Peserta Ujian</h3>

        <table id="monitoringTable" class="table table-bordered table-striped">

            <thead>

                <tr>
                    <th>Nama</th>
                    <th>Ujian</th>
                    <th>Status</th>
                    <th>Pelanggaran</th>
                    <th>Last Ping</th>
                    <th>Aksi</th>
                </tr>

            </thead>

        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
    $(function() {

        $('#monitoringTable').DataTable({

            processing: true,
            serverSide: true,

            ajax: '../ajax/monitoring_data.php'

        });

    });
    </script>

</body>

</html>