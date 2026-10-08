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

// Variabel untuk menyimpan pesan error atau sukses
$message = "";

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $tujuan = $_POST['tujuan'];
    $tgl_berangkat = $_POST['tgl_berangkat'];
    $tgl_pulang = $_POST['tgl_pulang'];
    $transportasi = $_POST['transportasi'];
    $keterangan = $_POST['keterangan'];
    $created_by = $_SESSION['role'];

    // Query untuk memasukkan data ke database
    $sql = "INSERT INTO apd_jadwal (nama_lengkap, tujuan, tgl_berangkat, tgl_pulang, transportasi, keterangan, verifikasi, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssis", $nama_lengkap, $tujuan, $tgl_berangkat, $tgl_pulang, $transportasi, $keterangan, $verifikasi, $created_by);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan'); window.location.href='jadwal.php';</script>";
    } else {
        echo "Gagal menyimpan: " . $stmt->error;
    }
}

// Menutup koneksi setelah query selesai
$conn->close();
?>

<!doctype html>
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
    <div class="row mb-3">
        <div class="col-md-8 mx-auto">
            <h2 class="pb-2 border-bottom text-center">Buat Jadwal</h2>

            <!-- Form untuk membuat jadwal -->
            <form method="POST">
                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="namaTeknisi" class="form-label">Nama Teknisi</label>
                    <input type="text" class="form-control" id="namaTeknisi" name="nama_lengkap" required>
                </div>

                <div class="mb-3">
                    <label for="tujuan" class="form-label">Tujuan Perjalanan</label>
                    <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="tanggalBerangkat" class="form-label">Tanggal Berangkat</label>
                        <input type="date" class="form-control" id="tanggalBerangkat" name="tgl_berangkat" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggalPulang" class="form-label">Tanggal Pulang</label>
                        <input type="date" class="form-control" id="tanggalPulang" name="tgl_pulang" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="transportasi" class="form-label">Transportasi</label>
                    <select class="form-select" id="transportasi" name="transportasi" required>
                        <option value="" disabled selected>Pilih transportasi</option>
                        <option value="Pesawat">Pesawat</option>
                        <option value="Kereta">Kereta</option>
                        <option value="Mobil">Mobil</option>
                        <option value="Bus">Bus</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <!-- Tombol Kembali di sebelah kiri -->
                    <button class="btn btn-secondary" onclick="window.location.href='lihat-jadwal.php'">Kembali</button>
                    <!-- Tombol Simpan Jadwal di sebelah kanan -->
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function logout() {
    window.location.href = "../auth/logout.php";
}
</script>

</body>
</html>
