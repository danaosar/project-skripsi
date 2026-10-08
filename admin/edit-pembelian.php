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
$target_dir = "uploads/";

// Ambil data berdasarkan ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pembelian_brg = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM apd_barang WHERE id_pembelian_brg = ?");
    $stmt->bind_param("i", $id_pembelian_brg);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Data pembelian tidak ditemukan.");
    }
    $stmt->close();
} else {
    die("ID tidak valid.");
}

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pembelian_brg = $_POST['id_pembelian_brg'];
    $id_jadwal = $_POST['id_jadwal'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tanggal = $_POST['tanggal'];
    $nama_brg = $_POST['nama_brg'];
    $jumlah_brg = $_POST['jumlah_brg'];
    $harga_satuan = $_POST['harga_satuan'];
    $total_harga = $_POST['total_harga'];
    $keterangan = $_POST['keterangan'];

    // Cek upload foto struk
    $foto_struk = $row['foto_struk'];
    if (isset($_FILES['foto_struk']) && $_FILES['foto_struk']['error'] === 0) {
        $struk_file = $target_dir . time() . "_struk_" . basename($_FILES["foto_struk"]["name"]);
        move_uploaded_file($_FILES["foto_struk"]["tmp_name"], $struk_file);
        $foto_struk = $struk_file;
    }

    // Cek upload foto barang
    $foto_brg = $row['foto_brg'];
    if (isset($_FILES['foto_brg']) && $_FILES['foto_brg']['error'] === 0) {
        $brg_file = $target_dir . time() . "_barang_" . basename($_FILES["foto_brg"]["name"]);
        move_uploaded_file($_FILES["foto_brg"]["tmp_name"], $brg_file);
        $foto_brg = $brg_file;
    }

    // Update dengan prepared statement
    $stmt = $conn->prepare("UPDATE apd_barang SET 
        id_jadwal = ?, nama_lengkap = ?, tanggal = ?, 
        nama_brg = ?, jumlah_brg = ?, harga_satuan = ?, 
        total_harga = ?, foto_struk = ?, foto_brg = ?, 
        keterangan = ? WHERE id_pembelian_brg = ?");

    $stmt->bind_param(
        "isssiiisssi",
        $id_jadwal, $nama_lengkap, $tanggal, $nama_brg, $jumlah_brg,
        $harga_satuan, $total_harga, $foto_struk, $foto_brg, $keterangan,
        $id_pembelian_brg
    );

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: lihat-pembelian.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Pembelian Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="pb-2 border-bottom">Edit Pembelian Barang</h2>

    <!-- Form untuk mengedit laporan -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pembelian_brg" value="<?php echo $row['id_pembelian_brg']; ?>">

        <div class="mb-3">
            <label for="idJadwal" class="form-label">Jadwal</label>
            <input type="text" class="form-control" id="idJadwal" name="id_jadwal" value="<?php echo $row['id_jadwal']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="namaTeknisi" class="form-label">Nama Teknisi</label>
            <input type="text" class="form-control" id="namaTeknisi" name="nama_lengkap" value="<?php echo $row['nama_lengkap']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="namaBarang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="namaBarang" name="nama_brg" value="<?php echo $row['nama_brg']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="jumlahBarang" class="form-label">Jumlah Barang</label>
            <input type="number" class="form-control" id="jumlahBarang" name="jumlah_brg" value="<?php echo $row['jumlah_brg']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="hargaSatuan" class="form-label">Harga Satuan</label>
            <input type="number" class="form-control" id="hargaSatuan" name="harga_satuan" value="<?php echo $row['harga_satuan']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="totalHarga" class="form-label">Total Harga</label>
            <input type="number" class="form-control" id="totalHarga" name="total_harga" value="<?php echo $row['total_harga']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="fotoStruk" class="form-label">Upload Foto Struk</label>
            <input type="file" class="form-control" id="fotoStruk" name="foto_struk">
            <img src="<?php echo $row['foto_struk']; ?>" width="100" height="100" alt="foto struk"> <!-- Menampilkan gambar -->
        </div>

        <div class="mb-3">
            <label for="fotoBarang" class="form-label">Upload Foto Barang</label>
            <input type="file" class="form-control" id="fotoBarang" name="foto_brg">
            <img src="<?php echo $row['foto_brg']; ?>" width="100" height="100" alt="foto barang"> <!-- Menampilkan gambar -->
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo $row['keterangan']; ?></textarea>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-secondary" onclick="window.location.href='lihat-pembelian.php'">Kembali</button>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
