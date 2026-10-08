<?php
// Mulai session
session_start();

// Cek apakah admin sudah login
//if ($_SESSION['role'] != 'admin') {
    //header("Location: ../login.php"); // Jika bukan admin, redirect ke halaman login
    //exit();
//}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_db_apd";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa apakah ada error koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Verifikasi jika ada ID jadwal yang dikirimkan
if (isset($_GET['id'])) {
    $id_laporan = $_GET['id'];

    // Query untuk memverifikasi jadwal
    $sql = "UPDATE apd_laporan SET verifikasi = 1 WHERE id_laporan = $id_laporan";
    if ($conn->query($sql) === TRUE) {
        echo "Laporan berhasil diverifikasi!";
        // Redirect kembali ke halaman yang sama untuk menampilkan data terbaru
        header("Location: verifikasi-laporan.php"); // Refresh halaman
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Query untuk mengambil semua jadwal yang belum diverifikasi
$sql = "SELECT * FROM apd_laporan WHERE verifikasi = 0";
$result = $conn->query($sql);

// Menutup koneksi setelah query selesai
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #2c3e50;
            padding-top: 60px;
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

        .table th, .table td {
            text-align: center;
        }

        .table td a {
            display: inline-block;
            padding: 6px 12px;
            text-decoration: none;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .navbar-nav .nav-item .dropdown-menu button {
            width: 100%;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
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

        <div class="col-md-10 content">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
                <a class="navbar-brand">Laporan</a>
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

            <h2 class="mt-5 pb-2 border-bottom">Laporan Pengeluaran (Belum Diverifikasi)</h2>

            <!-- Tabel Jadwal yang belum diverifikasi -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Jadwal</th>
                            <th scope="col">Nama Teknisi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Jumlah Uang</th>
                            <th scope="col">Foto Struk</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Memeriksa apakah ada data yang ditemukan
                        if ($result->num_rows > 0) {
                            // Output data tiap baris
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id_laporan'] . "</td>";
                                echo "<td>" . $row['id_jadwal'] . "</td>";
                                echo "<td>" . $row['nama_lengkap'] . "</td>";
                                echo "<td>" . $row['tanggal'] . "</td>";
                                echo "<td>" . $row['kategori'] . "</td>";
                                echo "<td>" . $row['jumlah_uang'] . "</td>";
                                echo "<td>" . $row['keterangan'] . "</td>";
                                if (isset($row['foto_struk']) && !empty($row['foto_struk'])) {
                                    echo "<td><button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#fotoModal' data-bs-whatever='" . $row['foto_struk'] . "'>Lihat</button></td>";
                                } else {
                                    echo "<td>Tidak ada foto</td>";
                                }
                                // Tombol untuk memverifikasi jadwal
                                echo "<td><a href='verifikasi-laporan.php?id=" . $row['id_laporan'] . "' class='btn btn-success'>Verifikasi</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>Tidak ada jadwal yang perlu diverifikasi</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
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
