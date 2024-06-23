<?php
require 'koneksi.php';
session_start();

// Memeriksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

$jenis_vaksin_list = [];

// Mendapatkan jenis vaksin dari tabel jenis_vaksin
$query_jenis_vaksin = "SELECT id, jenis FROM jenis_vaksin";
$result_jenis_vaksin = mysqli_query($conn, $query_jenis_vaksin);

if ($result_jenis_vaksin && mysqli_num_rows($result_jenis_vaksin) > 0) {
    while ($row_jenis_vaksin = mysqli_fetch_assoc($result_jenis_vaksin)) {
        $jenis_vaksin_list[] = $row_jenis_vaksin;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Posyandu Terdekat</title>
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
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            max-width: 600px;
            margin: 10px auto;
        }

        .profile-box {
            background-color: #fff;
            color: #ff9ccc;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 20px;
            margin-bottom: 30px;
            margin-left: 20px;
            margin-right: 20px;
            position: relative;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-heading {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left; /* Align form groups to the left */
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #ff9ccc;
            font-size: 18px;
        }

        .form-group input, .form-group select {
            width: 100%; /* Set width to 100% for consistency */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #FC98CC;
            box-sizing: border-box; /* Ensure padding and border are included in the element's total width and height */
        }

        .form-buttons {
            display: flex;
            justify-content: space-between; /* Align buttons to opposite ends */
        }

        .form-group button, .back-button {
            padding: 10px 20px;
            background-color: #ff9ccc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
            text-decoration: none;
        }

        .form-group button:hover, .back-button:hover {
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
        <h2 class="profile-heading">Cari Posyandu Terdekat</h2>
        <form method="POST" action="hasil_pencarian.php">
            <div class="form-group">
                <label for="kelurahan">Kelurahan:</label>
                <input type="text" id="kelurahan" name="kelurahan" required>
            </div>
            <div class="form-group">
                <label for="jenis_vaksin">Jenis Vaksin:</label>
                <select id="jenis_vaksin" name="jenis_vaksin" required>
                    <option value="">Pilih Jenis Vaksin</option>
                    <?php foreach ($jenis_vaksin_list as $jenis_vaksin) : ?>
                        <option value="<?php echo $jenis_vaksin['id']; ?>">
                            <?php echo htmlspecialchars($jenis_vaksin['jenis']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-buttons">
                <a href="home.php" class="back-button">Kembali</a>
                <button type="submit" class="back-button">Cari</button>
            </div>
        </form>
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
