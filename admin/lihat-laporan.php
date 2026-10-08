<?php
// Mulai session
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Jika bukan admin, redirect ke halaman login
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_db_apd";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah tombol Hapus telah ditekan
if (isset($_POST['delete'])) {
    $id_laporan = $_POST['id_laporan'];

    // Query untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM apd_laporan WHERE id_laporan = ?";

    // Persiapkan statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_laporan); // "i" berarti integer
        if ($stmt->execute()) {
            // Redirect ke halaman yang sama setelah data dihapus
            header("Location: lihat-laporan.php");
            exit;
        } else {
            echo "Gagal menghapus data.";
        }
    } else {
        echo "Query tidak valid.";
    }
}

// Query untuk mengambil data laporan yang belum diverifikasi
$sql = "SELECT * FROM apd_laporan WHERE created_by = 'admin'";
$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Query gagal: " . $conn->error);
}

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
        <h2 class="pb-2 border-bottom text-center">Laporan Pengeluaran</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="buat-laporan.php" class="btn btn-primary">Buat Laporan</a>
        </div>

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
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Memeriksa apakah ada data yang ditemukan
                    if ($result->num_rows > 0) {
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
                            
                            // Menampilkan status verifikasi berdasarkan nilai
                            if ($row['verifikasi'] == 0) {
                                echo "<td>Belum Diverifikasi</td>";
                            } elseif ($row['verifikasi'] == 1) {
                                echo "<td>Diverifikasi</td>";
                            }

                            echo "<td>
                                    <a href='edit-laporan.php?id=" . $row['id_laporan'] . "' class='btn btn-warning'>Edit</a>
                                    <form method='POST' action='lihat-laporan.php' style='display:inline;'>
                                        <input type='hidden' name='id_laporan' value='" . $row['id_laporan'] . "'>
                                        <button type='submit' name='delete' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus jadwal ini?\")'>Hapus</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>Tidak ada laporan yang perlu diverifikasi</td></tr>";
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
