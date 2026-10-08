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

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jadwal Perjalanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .sidebar {
      height: 100vh;
      width: 250px;
      background-color: #2c3e50;
      padding-top: 60px; /* Increased padding-top to give space for navbar */
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
      background-color: #1abc9c; /* Warna latar belakang untuk yang aktif */
      color: white;
    }

    .active {
      background-color: #1abc9c;
    }

    .content {
      margin-left: 260px;
      padding: 20px;
    }

    .navbar-brand {
      margin-left: 20px; /* Sesuaikan nilai ini jika perlu */
    }

    .icon-button {
      display: flex;
      align-items: center;
      padding: 10px;
      margin: 10px 0;
      background-color: #1abc9c;
      color: white;
      border: none;
      border-radius: 5px;
      width: 200px;
      cursor: pointer;
      text-align: left;
    }

    .icon-button:hover {
      background-color: #16a085;
    }

    .icon-button i {
      margin-right: 10px;
    }

    .icon-button-container {
      display: flex;
      justify-content: flex-start; /* Distribute buttons evenly */
      gap: 15px; /* Space between buttons */
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

    <div class="sidebar">
      <a href="dashboard.html">Home</a>
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
        <a href="verifikasi-pembelian.php">Verifikasi Pengajuan</a>
      </div>
      <a href="">Akun Pengguna</a>
    </div>
    <div class="content">
      <h1>Main Content</h1>
      
      <!-- Icon Buttons Section -->
      <div class="icon-button-container">
        <a href="lihat-jadwal.php" style="text-decoration: none;">
          <button class="icon-button">
            <i class="fa-solid fa-calendar"></i> Jadwal Perjalanan
          </button>
        <a href="lihat-laporan.php" style="text-decoration: none;">
          <button class="icon-button">
            <i class="fa-solid fa-file"></i> Laporan Pengeluaran
          </button>
        <a href="lihat-pembelian.php" style="text-decoration: none;">
          <button class="icon-button">
            <i class="fa-solid fa-cart-shopping"></i> Pembelian Barang
          </button>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      function logout() {
        window.location.href = "../auth/logout.php";
      }

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