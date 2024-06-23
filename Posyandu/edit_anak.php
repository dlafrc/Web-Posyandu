<?php
require 'koneksi.php';

session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error_message = "";
$nama = $jenis_kelamin = $berat = $tinggi = $tanggal_lahir = "";

// Cek apakah ID anak disertakan dalam URL
if (isset($_GET['id'])) {
    $id_anak = $_GET['id'];
    $_SESSION['id_anak'] = $id_anak;

    // Ambil data anak dari database
    $query_anak = "SELECT * FROM anaks WHERE id = '$id_anak' AND id_user = '{$_SESSION['user_id']}'";
    $result_anak = mysqli_query($conn, $query_anak);

    if ($result_anak && mysqli_num_rows($result_anak) > 0) {
        $row_anak = mysqli_fetch_assoc($result_anak);
        $nama = $row_anak['nama'];
        $jenis_kelamin = $row_anak['jenis_kelamin'];
        $berat = $row_anak['berat'];
        $tinggi = $row_anak['tinggi'];
        $tanggal_lahir = $row_anak['tanggal_lahir'];
    } else {
        $error_message = "Data anak tidak ditemukan atau Anda tidak memiliki akses.";
    }
} else {
    header("Location: data_anak.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['jenis_kelamin'], $_POST['berat'], $_POST['tinggi'], $_POST['tanggal_lahir'])) {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $tanggal_lahir = $_POST['tanggal_lahir'];

    // Rumus perkiraan kebutuhan gizi
    $kalori = $berat * 70; // Misalnya 70 kkal per kg berat badan
    $protein = $berat * 1.2; // Misalnya 1.2 g per kg berat badan
    $karbohidrat = $berat * 4; // Misalnya 4 g per kg berat badan
    $serat = $berat * 0.1; // Misalnya 0.1 g per kg berat badan

    // Update data anak
    $query_update_anak = "UPDATE anaks SET nama = '$nama', jenis_kelamin = '$jenis_kelamin', berat = '$berat', tinggi = '$tinggi', tanggal_lahir = '$tanggal_lahir' WHERE id = '$id_anak' AND id_user = '{$_SESSION['user_id']}'";
    
    if (mysqli_query($conn, $query_update_anak)) {
        // Update data gizi
        $query_update_gizi = "UPDATE gizi SET serat = '$serat', protein = '$protein', karbohidrat = '$karbohidrat', kalori = '$kalori' WHERE id_anak = '$id_anak'";
        
        if (mysqli_query($conn, $query_update_gizi)) {
            header("Location: data_anak.php");
            exit();
        } else {
            $error_message = "Terjadi kesalahan dalam penyimpanan data gizi.";
        }
    } else {
        $error_message = "Terjadi kesalahan dalam penyimpanan data anak.";
    }
}

// Tutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Anak - Home Posyandu</title>
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
        <h2 class="profile-heading">Edit Data Anak</h2>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="edit_anak.php?id=<?php echo $id_anak; ?>" method="POST">
                <div class="profile-info">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                </div>
                <div class="profile-info">
                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?php echo ($jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo ($jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="profile-info">
                    <label for="berat">Berat (kg):</label>
                    <input type="number" id="berat" name="berat" value="<?php echo htmlspecialchars($berat); ?>" required step="0.1">
                </div>
                <div class="profile-info">
                    <label for="tinggi">Tinggi (cm):</label>
                    <input type="number" id="tinggi" name="tinggi" value="<?php echo htmlspecialchars($tinggi); ?>" required step="0.1">
                </div>
                <div class="profile-info">
                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" required>
                </div>
                <div class="button-container">
                    <a href="data_anak.php" class="back-button">Kembali</a>
                    <input type="submit" value="Simpan">
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Home Posyandu
    </div>
    <script>
        function toggleMenu() {
            var navbar = document.getElementById('navbar');
            if (navbar.style.display === 'block') {
                navbar.style.display = 'none';
            } else {
                navbar.style.display = 'block';
            }
        }
    </script>
</body>
</html>

