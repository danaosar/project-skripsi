<?php
session_start();

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

$row = null;

// Ambil data berdasarkan ID laporan
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_laporan = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM apd_laporan WHERE id_laporan = ?");
    $stmt->bind_param("i", $id_laporan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Laporan tidak ditemukan.");
    }
    $stmt->close();
} else {
    die("ID laporan tidak valid.");
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_laporan    = $_POST['id_laporan'];
    $id_jadwal     = $_POST['id_jadwal'];
    $nama_lengkap  = $_POST['nama_lengkap'];
    $tanggal       = $_POST['tanggal'];
    $kategori      = $_POST['kategori'];
    $jumlah_uang   = $_POST['jumlah_uang'];
    $keterangan    = $_POST['keterangan'];

    // Cek apakah ada upload baru untuk struk
    $foto_struk = $row['foto_struk']; // default (tidak diubah)
    if (isset($_FILES['foto_struk']) && $_FILES['foto_struk']['error'] == 0) {
        $target_dir = "uploads/";
        $filename = time() . "_struk_" . basename($_FILES["foto_struk"]["name"]);
        $target_file = $target_dir . $filename;
        move_uploaded_file($_FILES["foto_struk"]["tmp_name"], $target_file);
        $foto_struk = $target_file;
    }

    // Query update dengan prepared statement
    $stmt = $conn->prepare("UPDATE apd_laporan SET id_jadwal = ?, nama_lengkap = ?, tanggal = ?, kategori = ?, jumlah_uang = ?, foto_struk = ?, keterangan = ? WHERE id_laporan = ?");
    $stmt->bind_param("issssssi", $id_jadwal, $nama_lengkap, $tanggal, $kategori, $jumlah_uang, $foto_struk, $keterangan, $id_laporan);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: lihat-laporan.php");
        exit();
    } else {
        echo "Gagal memperbarui laporan: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Laporan Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="pb-2 border-bottom">Edit Laporan Pengeluaran</h2>

    <!-- Form untuk mengedit laporan -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">

        <!-- Input Nama Lengkap -->
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Teknisi</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nama_lengkap']; ?>" required>
        </div>

        <!-- Input Jadwal -->
        <div class="mb-3">
            <label for="id_jadwal" class="form-label">Jadwal</label>
            <input type="text" class="form-control" id="id_jadwal" name="id_jadwal" value="<?php echo $row['id_jadwal']; ?>" required>
        </div>

        <!-- Input Tanggal -->
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" required>
        </div>

        <!-- Input Kategori -->
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="Makan" <?php echo ($row['kategori'] == 'Makan') ? 'selected' : ''; ?>>Makan</option>
                <option value="Transportasi" <?php echo ($row['kategori'] == 'Transportasi') ? 'selected' : ''; ?>>Transportasi</option>
                <option value="Dan Lain-lainnya" <?php echo ($row['kategori'] == 'Dan Lain-lainnya') ? 'selected' : ''; ?>>Dan Lain-lainnya</option>
            </select>
        </div>

        <!-- Input Jumlah Uang -->
        <div class="mb-3">
            <label for="jumlah_uang" class="form-label">Jumlah Uang</label>
            <input type="number" class="form-control" id="jumlah_uang" name="jumlah_uang" value="<?php echo $row['jumlah_uang']; ?>" required>
        </div>

        <!-- Input Foto Struk -->
        <div class="mb-3">
            <label for="foto_struk" class="form-label">Foto Struk</label>
            <input type="file" class="form-control" id="foto_struk" name="foto_struk">
            <small>Jika ingin mengganti foto struk, pilih file baru.</small>
            <?php if ($row['foto_struk']): ?>
                <div class="mt-2">
                    <img src="<?php echo $row['foto_struk']; ?>" alt="Foto Struk" class="img-fluid" style="max-height: 150px;">
                </div>
            <?php endif; ?>
        </div>

        <!-- Input Keterangan -->
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required><?php echo $row['keterangan']; ?></textarea>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <!-- Tombol Kembali di sebelah kiri -->
            <button class="btn btn-secondary" onclick="window.location.href='lihat-laporan.php'">Kembali</button>
            <!-- Tombol Simpan Jadwal di sebelah kanan -->
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
