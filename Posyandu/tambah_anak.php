<?php
require 'koneksi.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error_message = "";

$nama_terkirim = $jenis_kelamin_terkirim = $berat_terkirim = $tinggi_terkirim = $tanggal_lahir_terkirim = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['jenis_kelamin'], $_POST['berat'], $_POST['tinggi'], $_POST['tanggal_lahir'])) {

    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $user_id = $_SESSION['user_id'];

    $kalori = $berat * 70; 
    $protein = $berat * 1.2; 
    $karbohidrat = $berat * 4; 
    $serat = $berat * 0.1; 

    // Query untuk memasukkan data anak ke database
    $query_anak = "INSERT INTO anaks (nama, jenis_kelamin, berat, tinggi, tanggal_lahir, id_user) VALUES ('$nama', '$jenis_kelamin', '$berat', '$tinggi', '$tanggal_lahir', '$user_id')";
    
    if (mysqli_query($conn, $query_anak)) {
        // Dapatkan ID anak yang baru saja disimpan
        $id_anak = mysqli_insert_id($conn);
    } else {
        // Jika terjadi kesalahan dalam menyimpan data anak
        $error_message = "Terjadi kesalahan dalam penyimpanan data anak.";
    }

    // Simpan data yang sudah dikirimkan jika terjadi kesalahan
    $nama_terkirim = $_POST['nama'];
    $jenis_kelamin_terkirim = $_POST['jenis_kelamin'];
    $berat_terkirim = $_POST['berat'];
    $tinggi_terkirim = $_POST['tinggi'];
    $tanggal_lahir_terkirim = $_POST['tanggal_lahir'];

    header("Location: data_anak.php");
            exit();
}

// Tutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Anak - Home Posyandu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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

        .header {
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            padding: 3px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: 80px;
        }

        .header img {
            width: 60px;
            height: 60px;
            margin-top: 10px;
        }

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
            right: 20px;
            background-color: rgba(152, 188, 252, 0.8);
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

        .container {
            padding: 30px;
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px 40px;
            margin-bottom: 40px;
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

        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #ff9ccc;
        }

        .profile-info input[type="text"],
        .profile-info input[type="number"],
        .profile-info input[type="date"],
        .profile-info select {
            width: calc(100% - 5px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .profile-heading {
            text-align: center;
            margin-bottom: 20px;
            color: #ff9ccc;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        input[type="submit"], .back-button {
            background-color: #ff9ccc;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-right: 5px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
        }

        input[type="submit"]:hover, .back-button:hover {
            background-color: #CF5F9B;
        }

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

        .error-message {
            color: red;
            margin-top: 20px;
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
            <a href="profile.php">Profile</a>
            <a href="bantuan.php">Bantuan</a>
            <a href="informasi.php">Informasi</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="profile-box">
            <h2 class="profile-heading">Tambah Data Anak</h2>
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form action="tambah_anak.php" method="post">
                <div class="profile-info">
                    <label for="nama">Nama Anak:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama_terkirim); ?>" required>
                </div>
                <div class="profile-info">
                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?php if ($jenis_kelamin_terkirim === 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php if ($jenis_kelamin_terkirim === 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="profile-info">
                    <label for="berat">Berat Badan (kg):</label>
                    <input type="number" id="berat" name="berat" value="<?php echo htmlspecialchars($berat_terkirim); ?>" required>
                </div>
                <div class="profile-info">
                    <label for="tinggi">Tinggi Badan (cm):</label>
                    <input type="number" id="tinggi" name="tinggi" value="<?php echo htmlspecialchars($tinggi_terkirim); ?>" required>
                </div>
                <div class="profile-info">
                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir_terkirim); ?>" required>
                </div>
                <div class="button-container">
                    <a href="data_anak.php" class="back-button">Kembali</a>
                    <input type="submit" value="Simpan">
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Home Posyandu. All rights reserved.
    </div>
    <script>
        function toggleMenu() {
            var navbar = document.getElementById('navbar');
            if (navbar.style.display === 'flex') {
                navbar.style.display = 'none';
            } else {
                navbar.style.display = 'flex';
            }
        }
    </script>
</body>
</html>