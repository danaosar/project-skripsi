<?php
// Mulai session
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_db_apd";

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
    // Ambil data dari form
    $id_jadwal = $_POST['id_jadwal'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $jumlah_uang = $_POST['jumlah_uang'];
    $keterangan = $_POST['keterangan'];
    $created_by = $_SESSION['role'];
    $verifikasi = 0;

    $target_file = "";

    // Cek dan upload file terlebih dahulu
    if (isset($_FILES['foto_struk']) && $_FILES['foto_struk']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["foto_struk"]["name"]);

        // Upload file
        if (!move_uploaded_file($_FILES["foto_struk"]["tmp_name"], $target_file)) {
            $message = "Gagal mengunggah foto struk.";
        }
    } else {
        $message = "File struk tidak ditemukan atau gagal diupload.";
    }

    // Jika upload berhasil, lanjut insert
    if ($target_file !== "") {
        $sql = "INSERT INTO apd_laporan (id_jadwal, nama_lengkap, tanggal, kategori, jumlah_uang, foto_struk, keterangan, verifikasi, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $message = "Gagal mempersiapkan statement: " . $conn->error;
        } else {
            $stmt->bind_param("sssssssis", $id_jadwal, $nama_lengkap, $tanggal, $kategori, $jumlah_uang, $target_file, $keterangan, $verifikasi, $created_by);

            if ($stmt->execute()) {
                $message = "Laporan berhasil disimpan!";
            } else {
                $message = "Gagal menyimpan laporan: " . $stmt->error;
            }
        }
    }
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
        <a class="navbar-brand">Laporan</a>

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

<div class="container mt-5">
    <h2 class="pb-2 border-bottom text-center">Buat Laporan Pengeluaran</h2>

    <!-- Form untuk membuat laporan -->
    <form method="POST" enctype="multipart/form-data">
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Input untuk id_jadwal -->
        <div class="mb-3">
            <label for="idJadwal" class="form-label">Jadwal</label>
            <input type="text" class="form-control" id="idJadwal" name="id_jadwal" required>
        </div>

        <div class="mb-3">
            <label for="namaTeknisi" class="form-label">Nama Teknisi</label>
            <input type="text" class="form-control" id="namaTeknisi" name="nama_lengkap" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori Pengeluaran</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="" disabled selected>Pilih transportasi</option>
                        <option value="Makan">Makan</option>
                        <option value="Transportasi">Transportasi</option>
                        <option value="Dan Lain-lainnya">Dan Lain-lainnya</option>
                    </select>
                </div>

        <div class="mb-3">
            <label for="jumlahUang" class="form-label">Jumlah Uang</label>
            <input type="number" class="form-control" id="jumlahUang" name="jumlah_uang" required>
        </div>

        <div class="mb-3">
            <label for="fotoStruk" class="form-label">Upload Foto Struk</label>
            <input type="file" class="form-control" id="fotoStruk" name="foto_struk" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <!-- Tombol Kembali di sebelah kiri -->
            <button class="btn btn-secondary" onclick="window.location.href='pengajuan-laporan.php'">Kembali</button>
            <!-- Tombol Simpan Laporan di sebelah kanan -->
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>
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
