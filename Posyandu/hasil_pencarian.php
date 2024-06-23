<?php
require 'koneksi.php';
session_start();

// Memeriksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

$hasil_pencarian = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kelurahan = $_POST['kelurahan'];
    $jenis_vaksin = $_POST['jenis_vaksin'];

    $query = "
    SELECT p.nama, p.alamat, p.link, j.jenis AS jenis_vaksin, v.status 
    FROM posyandu p
    JOIN vaksinasi v ON p.id = v.id_posyandu
    JOIN jenis_vaksin j ON v.id_jenis = j.id
    WHERE p.kelurahan = ? 
    AND j.id = ? 
    AND v.status = 'available'";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $kelurahan, $jenis_vaksin);
    $stmt->execute();
    $result =     $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hasil_pencarian[] = $row;
        }
    } else {
        $error_message = "Tidak ada posyandu yang ditemukan untuk kriteria yang diberikan.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Posyandu</title>
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
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .header {
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            padding: 3px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            right: 50px;
            background-color: rgba(152, 188, 252, 0.8);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            padding: 15px 50px;
            text-align: left;
            max-width: 600px;
            margin: 10px auto;
        }

        .profile-box {
            background-color: #fff;
            border: 2px solid #ff9ccc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
            animation: fadeInUp 1s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 15px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-heading {
            text-align: center;
            margin-bottom: 20px;
            color: #ff9ccc;
            font-size: 22px;
        }

        .profile-info label {
            font-weight: bold;
            color: #ff9ccc;
            font-size: 16px;
        }

        .back-button {
            padding: 10px 20px;
            color: white;
            background-color: #ff9ccc;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            transition: background-color 0.3s;
            width: auto;
            min-width: 100px;
            box-sizing: border-box;
        }

        .back-button:hover {
            background-color: #CF5F9B;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 10px;
        }

        .location-button {
            padding: 10px 20px;
            color: white;
            background-color: #ff9ccc;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            transition: background-color 0.3s;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .location-button:hover {
            background-color: #CF5F9B;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .select-container {
            display: flex;
            align-items: center;
            border: 1px solid #ff9ccc;
            border-radius: 8px;
            padding: 10px;
            background-color: #fff;
            margin-bottom: 20px;
            position: relative;
        }

        .select-container img {
            width: 35px;
            height: 35px;
            margin-right: 20px;
            margin-left: 10px; 
        }

        .select-container select {
            flex: 1;
            border: none;
            outline: none;
            font-size: 10px;
            color: #ff9ccc;
        }

        .select-container select option {
            padding: 10px;
        }

        .no-data {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
            border: 1px solid #ff9ccc;
            border-radius: 8px;
            color: #ff9ccc;
            margin-bottom: 20px;
            flex-direction: column;
        }

        .footer {
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
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
    <div class="profile-box">
        <h2 class="profile-heading">Hasil Pencarian Posyandu</h2>
        <?php if (!empty($error_message)) : ?>
            <div class="no-data"><?php echo $error_message; ?></div>
        <?php else : ?>
            <?php if (count($hasil_pencarian) > 0) : ?>
                <div class="profile-info">
                    <?php foreach ($hasil_pencarian as $posyandu) : ?>
                        <div class="select-container">
                            <img src="images/lokasi-icon.png" alt="Icon">
                            <div>
                                <label>Nama: </label>
                                <span><?php echo htmlspecialchars($posyandu['nama']); ?></span><br>
                                <label>Alamat: </label>
                                <span><?php echo htmlspecialchars($posyandu['alamat']); ?></span><br>
                                <label>Jenis Vaksin: </label>
                                <span><?php echo htmlspecialchars($posyandu['jenis_vaksin']); ?></span><br>
                            </div>
                            <a href="<?php echo htmlspecialchars($posyandu['link']); ?>" target="_blank" class="location-button">Lokasi</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="no-data">
                    <span>Tidak ada posyandu yang ditemukan.</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="button-container">
            <a class="back-button" href="cari_posyandu.php">Kembali</a>
        </div>
    </div>
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

