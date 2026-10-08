<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "my_db_apd"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jadwal = $_POST['id_jadwal'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tanggal = $_POST['tanggal'];
    $nama_brg = $_POST['nama_brg'];
    $jumlah_brg = $_POST['jumlah_brg'];
    $harga_satuan = $_POST['harga_satuan'];
    $total_harga = $_POST['total_harga'];
    $keterangan = $_POST['keterangan'];
    $created_by = $_SESSION['role'];

    // Upload foto barang
    $foto_brg = "";
    if (isset($_FILES['foto_brg']) && $_FILES['foto_brg']['error'] == 0) {
        $target_dir = "../uploads/";
        $filename_barang = time() . '_barang_' . basename($_FILES["foto_brg"]["name"]);
        $foto_brg = $target_dir . $filename_barang;
        move_uploaded_file($_FILES["foto_brg"]["tmp_name"], $foto_brg);
    }

    // Upload foto struk
    $foto_struk = "";
    if (isset($_FILES['foto_struk']) && $_FILES['foto_struk']['error'] == 0) {
        $target_dir = "../uploads/";
        $filename_struk = time() . '_struk_' . basename($_FILES["foto_struk"]["name"]);
        $foto_struk = $target_dir . $filename_struk;
        move_uploaded_file($_FILES["foto_struk"]["tmp_name"], $foto_struk);
    }

    // Simpan ke database
    $sql = "INSERT INTO apd_barang (id_jadwal, nama_lengkap, tanggal, nama_brg, jumlah_brg, harga_satuan, total_harga, foto_brg, foto_struk, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $message = "Gagal menyiapkan query: " . $conn->error;
    } else {
        $stmt->bind_param("isssiiisss", $id_jadwal, $nama_lengkap, $tanggal, $nama_brg, $jumlah_brg, $harga_satuan, $total_harga, $foto_brg, $foto_struk, $created_by);
        if ($stmt->execute()) {
            $message = "Data pembelian berhasil disimpan.";
        } else {
            $message = "Gagal menyimpan data: " . $stmt->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buat Laporan Pengeluaran</title>
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
        <a class="navbar-brand">Barang</a>

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

<div class="container mt-5">
    <h2 class="pb-2 border-bottom text-center">Buat Pembelian Barang</h2>

    <form method="POST" enctype="multipart/form-data">
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

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
            <label for="namaBarang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="namaBarang" name="nama_brg" required>
        </div>

        <div class="mb-3">
            <label for="jumlahBarang" class="form-label">Jumlah Barang</label>
            <input type="number" class="form-control" id="jumlahBarang" name="jumlah_brg" required>
        </div>

        <div class="mb-3">
            <label for="hargaSatuan" class="form-label">Harga Satuan</label>
            <input type="number" class="form-control" id="hargaSatuan" name="harga_satuan" required>
        </div>

        <div class="mb-3">
            <label for="totalHarga" class="form-label">Total Harga</label>
            <input type="number" class="form-control" id="totalHarga" name="total_harga" required>
        </div>

        <div class="mb-3">
            <label for="fotoStruk" class="form-label">Upload Foto Struk</label>
            <input type="file" class="form-control" id="fotoStruk" name="foto_struk" required>
        </div>

        <div class="mb-3">
            <label for="fotoBarang" class="form-label">Upload Foto Barang</label>
            <input type="file" class="form-control" id="fotoBarang" name="foto_brg" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-secondary" onclick="window.location.href='pengajuan-barang.php'">Kembali</button>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function logout() {
        window.location.href = "../auth/logout.php";
    }
</script>

</body>
</html>
