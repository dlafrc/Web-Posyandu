<?php
require 'koneksi.php';

session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

$error_message = "";
$nama = "";
$email = "";
$NIK = "";
$jenis_kelamin = "";
$tanggal_lahir = "";

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan informasi profil pengguna berdasarkan ID pengguna
$query_sql = "SELECT nama, email, NIK, jenis_kelamin, tanggal_lahir FROM user_account WHERE id = $user_id";
$result = mysqli_query($conn, $query_sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nama = $row["nama"];
    $email = $row["email"];
    $NIK = $row["NIK"];
    $jenis_kelamin = $row["jenis_kelamin"];
    $tanggal_lahir = $row["tanggal_lahir"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Home Posyandu</title>
    <style>
        /* Gaya untuk seluruh halaman */
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
            padding: 0px;
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            position: relative;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Gaya untuk header */
        .header {
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            padding: 3px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: 80px; /* Mengatur tinggi header */
        }

        .header img {
            width: 60px;
            height: 60px;
            margin-top: 10px; /* Mengatur jarak antara logo dan tepi header */
        }

        /* Navbar */
        .menu-icon {
            position: absolute;
            right: 40px;
            top: 25px;
            cursor: pointer;
            font-size: 30px;
        }

        .menu-icon .bar {
            display: block;
            width: 25px;
            height: 3px;
            margin: 5px auto;
            background-color: white;
            transition: 0.4s;
        }

        .navbar {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 60px;
            right: 50px;
            background-color: rgba(152, 188, 252, 0.8); /* Warna latar belakang dengan opacity */
            border-radius: 5px;
            overflow: hidden;
            z-index: 1;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar a {
            padding: 15px 20px;
            display: block;
            color: white;
            text-align: left;
            text-decoration: none;
            transition: background 0.3s;
        }

        .navbar a:hover {
            background-color: #FC98CC;
            color: white;
        }

        /* Gaya untuk kontainer */
        .container {
            padding: 40px;
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Gaya untuk kotak profil */
        .profile-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px 40px; /* Menambahkan ruang di dalam kotak profil */
            margin-bottom: 40px; /* Menambahkan ruang di bawah kotak profil */
            margin-left: 20px; /* Menambahkan ruang di sisi kiri */
            margin-right: 20px; /* Menambahkan ruang di sisi kanan */
            position: relative;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Gaya untuk info profil */
        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info label {
            font-weight: bold;
            color: #ff9ccc
        }

        /* Gaya untuk teks "Profile" */
        .profile-heading {
            text-align: center; /* Teks "Profile" menjadi rata tengah */
            margin-bottom: 10px; /* Jarak antara teks "Profile" dengan detail profil */
            color: #ff9ccc;
        }

        /* Gaya untuk tombol */
        .back-button, .edit-button {
            padding: 10px 20px; /* Menyesuaikan padding untuk konsistensi */
            display: inline-block;
            color: white;
            background-color: #ff9ccc;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
            border-radius: 10px;
            position: absolute;
            bottom: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        /* Atur posisi tombol Back */
        .back-button {
            left: 38px;
        }

        /* Atur posisi tombol Edit */
        .edit-button {
            right: 39px;
        }

        /* Hover */
        .back-button:hover, .edit-button:hover {
            background-color: #CF5F9B;
        }

        /* Hover */
        .back-button:hover, .edit-button:hover {
            background-color: #CF5F9B;
        }

        /* Gaya untuk footer */
        .footer {
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/logo-icon.png" alt="Logo">
        <div class="menu-icon" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="navbar" id="navbar">
            <a href="home.php">Home</a>
            <a href="bantuan.php">Bantuan</a>
            <a href="informasi.php">Informasi</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php else : ?>
            <div class="profile-box">
            <h2 class="profile-heading">Profil</h2>
                <div class="profile-info">
                    <label>Nama:</label>
                    <p><?php echo $nama; ?></p>
                </div>
                
                <div class="profile-info">
                    <label>Email:</label>
                    <p><?php echo $email; ?></p>
                </div>
                <div class="profile-info">
                    <label>NIK:</label>
                    <p><?php echo $NIK; ?></p>
                </div>
                <div class="profile-info">
                    <label>Jenis Kelamin:</label>
                    <p><?php echo $jenis_kelamin; ?></p>
                </div>
                <div class="profile-info">
                    <label>Tanggal Lahir:</label>
                    <p><?php echo $tanggal_lahir; ?></p>
                </div> <br> <br><br>
                <!-- Letakkan tombol di dalam profile-box -->
                <a class="edit-button" href="edit_profile.php">Edit</a>
                <a class="back-button" href="home.php">Kembali</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>&copy; ANAK 2024. All rights reserved.</p>
    </div>

    <script>
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            if (navbar.style.display === "flex") {
                navbar.style.display = "none";
            } else {
                navbar.style.display = "flex";
            }
        }

        // Close the menu when clicking outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.menu-icon') && !event.target.matches('.bar')) {
                var navbar = document.getElementById("navbar");
                if (navbar.style.display === "flex") {
                    navbar.style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
