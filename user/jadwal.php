<?php
// Mulai session
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "my_db_apd"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data jadwal
$sql = "SELECT * FROM apd_jadwal WHERE verifikasi IN (1)";
$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
}

// Menutup koneksi setelah query selesai
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jadwal Perjalanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 25px; /* untuk memberi ruang di bawah navbar */
        }
        .navbar-brand {
            margin-left: 20px; /* Sesuaikan nilai ini jika perlu */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand">Jadwal</a>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <!-- Navbar dengan item yang diletakkan di kanan -->
            <ul class="navbar-nav ms-auto"> <!-- Ganti ml-auto dengan ms-auto di Bootstrap 5 -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown"> <!-- Ganti dropdown-menu-right dengan dropdown-menu-end -->
                        <button onclick="profile()">Profile</button>
                        <div class="dropdown-divider"></div>
                        <button onclick="logout()">Sign out</button>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container pt-5">
        <h2 class="pb-2 border-bottom text-center">Jadwal Perjalanan</h2>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Kembali</button>
            <a href="pengajuan-jadwal.php" class="btn btn-primary">Pengajuan Jadwal</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode Teknisi</th>
                        <th scope="col">Nama Teknisi</th>
                        <th scope="col">Tujuan</th>
                        <th scope="col">Tanggal Berangkat</th>
                        <th scope="col">Tanggal Pulang</th>
                        <th scope="col">Transportasi</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Memeriksa apakah ada data yang diambil
                    if ($result->num_rows > 0) {
                        // Output data tiap baris
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id_jadwal'] . "</td>";
                            echo "<td>" . $row['id_user'] . "</td>";
                            echo "<td>" . $row['nama_lengkap'] . "</td>";
                            echo "<td>" . $row['tujuan'] . "</td>";
                            echo "<td>" . $row['tgl_berangkat'] . "</td>";
                            echo "<td>" . $row['tgl_pulang'] . "</td>";
                            echo "<td>" . $row['transportasi'] . "</td>";
                            echo "<td>" . $row['keterangan'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>Data tidak ditemukan</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function logout() {
        window.location.href = "../auth/logout.php";
    }
    </script>
</body>

</html>
