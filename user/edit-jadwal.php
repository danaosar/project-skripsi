<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_db_apd";
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$row = null;
$id_jadwal = null;

// Ambil ID jadwal dari URL dan pastikan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_jadwal = $_GET['id'];

    // Query menggunakan prepared statement
    $stmt = $conn->prepare("SELECT * FROM apd_jadwal WHERE id_jadwal = ?");
    $stmt->bind_param("i", $id_jadwal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Data jadwal tidak ditemukan.");
    }
    $stmt->close();
} else {
    die("ID jadwal tidak valid.");
}

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap   = $_POST['nama_lengkap'];
    $tujuan         = $_POST['tujuan'];
    $tgl_berangkat  = $_POST['tgl_berangkat'];
    $tgl_pulang     = $_POST['tgl_pulang'];
    $transportasi   = $_POST['transportasi'];
    $keterangan     = $_POST['keterangan'];

    // Query update menggunakan prepared statement
    $sql_update = "UPDATE apd_jadwal SET 
                    nama_lengkap = ?, 
                    tujuan = ?, 
                    tgl_berangkat = ?, 
                    tgl_pulang = ?, 
                    transportasi = ?, 
                    keterangan = ? 
                    WHERE id_jadwal = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssssi", $nama_lengkap, $tujuan, $tgl_berangkat, $tgl_pulang, $transportasi, $keterangan, $id_jadwal);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: pengajuan-jadwal.php");
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
    <title>Edit Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="pb-2 border-bottom">Edit Jadwal Pengajuan</h2>

    <!-- Form untuk mengedit jadwal -->
    <form method="POST">
        <!-- Input Nama Lengkap -->
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Teknisi</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nama_lengkap']; ?>" required>
        </div>

        <!-- Input Tujuan -->
        <div class="mb-3">
            <label for="tujuan" class="form-label">Tujuan</label>
            <input type="text" class="form-control" id="tujuan" name="tujuan" value="<?php echo $row['tujuan']; ?>" required>
        </div>

        <!-- Input Tanggal Berangkat -->
        <div class="mb-3">
            <label for="tgl_berangkat" class="form-label">Tanggal Berangkat</label>
            <input type="date" class="form-control" id="tgl_berangkat" name="tgl_berangkat" value="<?php echo $row['tgl_berangkat']; ?>" required>
        </div>

        <!-- Input Tanggal Pulang -->
        <div class="mb-3">
            <label for="tgl_pulang" class="form-label">Tanggal Pulang</label>
            <input type="date" class="form-control" id="tgl_pulang" name="tgl_pulang" value="<?php echo $row['tgl_pulang']; ?>" required>
        </div>

        <!-- Input Transportasi -->
        <div class="mb-3">
            <label for="transportasi" class="form-label">Transportasi</label>
            <input type="text" class="form-control" id="transportasi" name="transportasi" value="<?php echo $row['transportasi']; ?>" required>
        </div>

        <!-- Input Keterangan -->
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required><?php echo $row['keterangan']; ?></textarea>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <!-- Tombol Kembali di sebelah kiri -->
            <button class="btn btn-secondary" onclick="window.location.href='pengajuan-jadwal.php'">Kembali</button>
            <!-- Tombol Simpan Jadwal di sebelah kanan -->
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
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
