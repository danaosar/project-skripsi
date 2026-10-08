<?php
// config/database.php
// File konfigurasi database untuk localhost dan hosting

// Deteksi environment (localhost vs hosting)
$isLocalhost = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

if ($isLocalhost) {
    // Konfigurasi untuk localhost
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "my_db_apd";
} else {
    // Konfigurasi untuk hosting (sesuaikan dengan hosting Anda)
    $servername = "localhost"; // Bisa diganti dengan nama host hosting
    $username = "your_hosting_username"; // Ganti dengan username database hosting
    $password = "your_hosting_password"; // Ganti dengan password database hosting
    $dbname = "your_hosting_dbname"; // Ganti dengan nama database hosting
}

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke utf8 untuk mendukung karakter khusus
$conn->set_charset("utf8");
?>
