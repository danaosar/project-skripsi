<?php
// Mulai session
session_start();

// Koneksi ke database
//$servername = "localhost";
//$username = "root"; // Ganti dengan username MySQL Anda
//$password = ""; // Ganti dengan password MySQL Anda
//$dbname = "my_db_apd"; // Ganti dengan nama database Anda

// Membuat koneksi
//$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
//if ($conn->connect_error) {
    //die("Koneksi gagal: " . $conn->connect_error);
//}
?>

<!DOCTYPE html>
<html lang="en">
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
        .container {
            margin-bottom: 50px; /* Sesuaikan nilai ini sesuai kebutuhan */
        }
        .logo-container {
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100px; /* atur tinggi background */
        }
        .logo-big {
          width: 500px;  /* atur sesuai kebutuhan */
          height: auto;
        }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand">Dashboard</a>

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

    <main role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="bg-light p-5 rounded mt-5 logo-container">
        <div class="container text-center">
          <img src="../uploads/logo_eec.png" alt="Logo" class="mb-1 logo-big">
        </div>
      </div>

      <div class="container">
        <!-- Example row of columns -->
        <div class="row">
          <div class="col-md-4">
            <h2>Jadwal Perjalanan</h2>
            <p>Fitur ini memungkinkan pengguna untuk melihat dan membuat Jadwal Perjalanan. </p>
            <p><a class="btn btn-secondary" href="jadwal.php" role="button">View details &raquo;</a></p>
          </div>
          <div class="col-md-4">
            <h2>Laporan Pengeluaran</h2>
            <p>Fitur ini digunakan untuk membuat Laporan Pengeluaran Keuangan, yang berfungsi untuk mencatat dan merinci penggunaan dana. </p>
            <p><a class="btn btn-secondary" href="pengeluaran.php" role="button">View details &raquo;</a></p>
          </div>
          <div class="col-md-4">
            <h2>Pembelian Barang</h2>
            <p>Fitur ini digunakan untuk membuat laporan mengenai barang-barang yang sudah dibeli. </p>
            <p><a class="btn btn-secondary" href="pembelian.php" role="button">View details &raquo;</a></p>
          </div>
        </div>

        <hr>

      </div> <!-- /container -->

    </main>

    <footer class="container">
      <p>&copy; Company 2025</p>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function logout() {
      window.location.href = "../auth/logout.php";
    }
    </script>

  </body>
</html>
