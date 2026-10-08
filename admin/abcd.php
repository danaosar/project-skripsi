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

<div class="container mt-5">
    <h2 class="pb-2 border-bottom">Jadwal Pengajuan (Belum Diverifikasi)</h2>

    <!-- Tabel Jadwal yang belum diverifikasi -->
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

<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fotoModalLabel">Foto Struk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" id="modalImage" class="img-fluid" alt="Foto Struk">
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Menangani event ketika tombol "Lihat" ditekan
    var fotoModal = document.getElementById('fotoModal');
    fotoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang dipencet
        var fotoStruk = button.getAttribute('data-bs-whatever'); // Ambil URL foto
        var modalImage = document.getElementById('modalImage');
        modalImage.src = fotoStruk; // Set src dengan URL gambar
    });
</script>
</body>
</html>
