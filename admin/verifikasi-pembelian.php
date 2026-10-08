<?php
session_start();

// Jika ingin mengaktifkan pengecekan role
// if ($_SESSION['role'] != 'admin') {
//     header("Location: ../login.php");
//     exit();
// }

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "my_db_apd");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses verifikasi
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "UPDATE apd_barang SET verifikasi = 1 WHERE id_pembelian_brg = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: verifikasi-pembelian.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data yang belum diverifikasi
$sql = "SELECT * FROM apd_barang WHERE verifikasi = 0";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pengajuan Barang</title>
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
            font-size: 16px;
            color: white;
            display: block;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
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
        .sidebar .active {
            background-color: #1abc9c;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
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
        <a href="verifikasi-pembelian.php" class="active">Verifikasi Pengajuan</a>
    </div>
    <a href="#">Akun Pengguna</a>
</div>

<!-- Main Content -->
<div class="content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Barang</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="https://github.com/mdo.png" width="32" height="32" class="rounded-circle" alt="User">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <button class="dropdown-item" onclick="profile()">Profile</button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="logout()">Sign out</button>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Table Section -->
    <div class="mt-5 pt-4">
        <h2 class="pb-2 border-bottom">Pengajuan Pembelian (Belum Diverifikasi)</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Jadwal</th>
                        <th>Nama Teknisi</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                        <th>Foto Barang</th>
                        <th>Foto Struk</th>
                        <th>Keterangan</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id_pembelian_brg']; ?></td>
                                <td><?= $row['id_jadwal']; ?></td>
                                <td><?= $row['nama_lengkap']; ?></td>
                                <td><?= $row['tanggal']; ?></td>
                                <td><?= $row['nama_brg']; ?></td>
                                <td><?= $row['jumlah_brg']; ?></td>
                                <td><?= $row['harga_satuan']; ?></td>
                                <td><?= $row['total_harga']; ?></td>
                                <td>
                                    <?= $row['foto_brg'] ? "<button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#fotoModal' data-bs-whatever='{$row['foto_brg']}'>Lihat</button>" : "Tidak ada"; ?>
                                </td>
                                <td>
                                    <?= $row['foto_struk'] ? "<button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#fotoModal' data-bs-whatever='{$row['foto_struk']}'>Lihat</button>" : "Tidak ada"; ?>
                                </td>
                                <td><?= $row['keterangan']; ?></td>
                                <td>
                                    <a href="verifikasi-pembelian.php?id=<?= $row['id_pembelian_brg']; ?>" class="btn btn-success">Verifikasi</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="13" class="text-center">Tidak ada data yang perlu diverifikasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img src="" id="modalImage" class="img-fluid" alt="Foto">
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const fotoModal = document.getElementById('fotoModal');
    fotoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const fotoSrc = button.getAttribute('data-bs-whatever');
        document.getElementById('modalImage').src = fotoSrc;
    });

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
