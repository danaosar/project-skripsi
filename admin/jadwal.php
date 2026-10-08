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
$sql = "SELECT * FROM apd_jadwal";
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
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #2c3e50;
            padding-top: 60px; /* Increased padding-top to give space for navbar */
            position: fixed;
            overflow-y: auto;
        }

        .sidebar a, .dropdown-btn {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: block;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
        }

        .sidebar a:hover, .dropdown-btn:hover {
            background-color: #34495e;
        }

        .dropdown-container {
            display: none;
            background-color: #3b4a5a;
            padding-left: 20px;
        }

        .dropdown-container a {
            font-size: 14px;
        }

        .sidebar a.active, .sidebar .dropdown-btn.active {
            background-color: #1abc9c;
            color: white;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .navbar-brand {
            margin-left: 20px;
        }

        .table thead th {
            background-color: #f8f9fa;
        }
        
        .table tbody td {
            vertical-align: middle;
        }

        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand">Jadwal</a>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <button onclick="profile()">Profile</button>
                    <div class="dropdown-divider"></div>
                    <button onclick="logout()">Sign out</button>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="sidebar">
    <a href="dashboard.php">Home</a>
    <button class="dropdown-btn">Jadwal Perjalanan ▾</button>
    <div class="dropdown-container">
        <a href="jadwal.php">Lihat Jadwal</a>
        <a href="lihat-jadwal.php">Buat Jadwal</a>
        <a href="verifikasi-jadwal.php">Verifikasi Jadwal</a>
    </div>
    <button class="dropdown-btn">Laporan Pengeluaran ▾</button>
    <div class="dropdown-container">
        <a href="laporan.php">Lihat Laporan</a>
        <a href="lihat-laporan.php">Buat Laporan</a>
        <a href="verifikasi-laporan.php">Verifikasi Laporan</a>
    </div>
    <button class="dropdown-btn">Pembelian Barang ▾</button>
    <div class="dropdown-container">
        <a href="pembelian.php">Lihat Pengajuan</a>
        <a href="lihat-pembelian.php">Buat Pengajuan</a>
        <a href="verifikasi-pembelian.php">Verifikasi Pengajuan</a>
    </div>
    <a href="">Akun Pengguna</a>
</div>

<div class="content">
    <div class="container pt-5">
        <h2 class="pb-2 border-bottom text-center">Jadwal Perjalanan</h2>

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
</div>

<!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      function logout() {
        window.location.href = "../auth/logout.php";
      }

      const dropdowns = document.querySelectorAll(".dropdown-btn");
      dropdowns.forEach(btn => {
        btn.addEventListener("click", function () {
          this.classList.toggle("active");
          const dropdownContent = this.nextElementSibling;
          if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
            this.innerHTML = this.innerHTML.replace("▴", "▾");
          } else {
            dropdownContent.style.display = "block";
            this.innerHTML = this.innerHTML.replace("▾", "▴");
          }
        });
      });

    </script>
</body>
</html>
