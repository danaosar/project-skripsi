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

// Variabel untuk error message
$error_message = "";

// Periksa apakah form dikirimkan dengan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Query untuk mencari username
    $sql = "SELECT * FROM apd_login WHERE username = '$user'";
    $result = $conn->query($sql);

    // Cek apakah query berhasil dijalankan dan apakah data ditemukan
    if ($result->num_rows > 0) {
        // Ambil data dari result
        $row = $result->fetch_assoc();

        // Verifikasi password
        if ($pass == $row['password']) { // Ganti dengan password_verify() jika password di-hash
            // Simpan session
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect ke dashboard sesuai role
            if ($row['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");  // Ganti dengan path yang benar
                exit();
            } else {
                header("Location: ../user/dashboard.php");  // Ganti dengan path yang benar
                exit();
            }
        } else {
            $error_message = "Username atau password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
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
    <title>Login - Sistem Pengguna</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding-bottom: 50px;
        }

        main.form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
        }

        #error-message {
            color: red;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <main class="form-signin">
        <form id="form-signin" method="POST">
            <!-- Gambar Logo -->
            <img class="mb-4 mx-auto d-block" src="../uploads/LOGO EEC_page-0001.jpg" alt="Logo" width="72" height="72">

            <h1 class="h3 mb-3 fw-normal text-center">Silakan Masuk</h1>

            <!-- Input Username -->
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>

            <!-- Input Password -->
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <!-- Remember me Checkbox -->
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Ingat saya
                </label>
            </div>

            <!-- Sign In Button -->
            <button class="btn btn-primary w-100 py-2" type="submit">Masuk</button>

            <!-- Create Account Button -->
            <button class="btn btn-secondary w-100 py-2 mt-2" type="button" onclick="window.location.href='admin-create.html'">Buat Akun</button>

            <!-- Error Message -->
            <?php if (!empty($error_message)): ?>
                <p id="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <!-- Copyright -->
            <p class="mt-5 mb-3 text-body-secondary text-center">&copy; 2017–<script>document.write(new Date().getFullYear());</script></p>
        </form>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
