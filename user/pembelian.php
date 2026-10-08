<?php
// Mulai session
session_start();

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

// Query untuk mengambil semua laporan yang sudah diverifikasi
$sql = "SELECT * FROM apd_barang WHERE verifikasi IN (1)";
$result = $conn->query($sql);

// Menutup koneksi setelah query selesai
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pembelian Barang</title>
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
            <a class="navbar-brand">Pembelian</a>

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

        <<div class="container pt-5">
            <h2 class="pb-2 border-bottom text-center">Pembelian Barang</h2>

            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Kembali</button>
                <a href="pengajuan-barang.php" class="btn btn-primary">Pengajuan Pembelian</a>
            </div>

                <!-- Tabel Jadwal Perjalanan -->
                <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Jadwal</th>
                        <th scope="col">Nama Teknisi</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Jumlah Barang</th>
                        <th scope="col">Harga Satuan</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Foto Barang</th>
                        <th scope="col">Foto Struk</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Memeriksa apakah ada data yang diambil
                        if ($result->num_rows > 0) {
                            // Output data tiap baris
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id_pembelian_brg'] . "</td>";
                                echo "<td>" . $row['id_jadwal'] . "</td>";
                                echo "<td>" . $row['nama_lengkap'] . "</td>";
                                echo "<td>" . $row['tanggal'] . "</td>";
                                echo "<td>" . $row['nama_brg'] . "</td>";
                                echo "<td>" . $row['jumlah_brg'] . "</td>";
                                echo "<td>" . $row['harga_satuan'] . "</td>";
                                echo "<td>" . $row['total_harga'] . "</td>";
                                if (isset($row['foto_struk']) && !empty($row['foto_struk'])) {
                                    echo "<td><button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#fotoModal' data-bs-whatever='" . $row['foto_struk'] . "'>Lihat</button></td>";
                                } else {
                                    echo "<td>Tidak ada foto</td>";
                                }
                                if (isset($row['foto_brg']) && !empty($row['foto_brg'])) {
                                    echo "<td><button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#fotoModal' data-bs-whatever='" . $row['foto_brg'] . "'>Lihat</button></td>";
                                } else {
                                    echo "<td>Tidak ada foto</td>";
                                }
                                echo "<td>" . $row['keterangan'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>Data tidak ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
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
            function logout() {
                window.location.href = "../auth/logout.php";
            }
        </script>

    </body>
</html>