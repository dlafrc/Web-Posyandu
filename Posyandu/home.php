<?php
require 'koneksi.php';

$query_sql = "SELECT Judul, link FROM artikel WHERE status = 'acc'";
$result = mysqli_query($conn, $query_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Posyandu</title>
    <style>
        /* Gaya untuk seluruh halaman */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            padding: 11px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header img {
            width: 60px;
            height: 60px;
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

        /* Gaya untuk konten utama */
        .main-content {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            margin: auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 7;
            margin-top: 30px;
            margin-bottom: 100px;
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

        /* Gaya untuk container */
        .container {
            text-align: center;
        }
        .container h2 {
            margin-top: 0;
            color: #FC98CC;
            font-weight: bold;
        }
        .menu {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #FC98CC;
            font-weight: bold;
        }
        .menu-item .icon {
            background-color: #FC98CC;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            transition: background 0.3s;
        }
        .menu-item img {
            width: 40px;
            height: 40px;
        }
        .menu-item span {
            margin-top: 10px;
        }
        .menu-item:hover .icon {
            background-color: #CF5F9B;
        }
        .articles {
            margin-top: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            align-items: stretch; /* Tambahkan ini untuk memastikan semua artikel memiliki tinggi yang sama */
        }

        .article-item {
            background: #8BBEFF;
            color: white;
            padding: 20px;
            border-radius: 20px;
            transition: transform 0.3s, background 0.3s;
            text-align: center; /* Menengah-align teks dalam artikel */
            cursor: pointer;
            flex: 1 1 calc(33.33% - 40px);
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Menempatkan konten di tengah secara vertikal */
            min-height: 85px; /* Tambahkan ini untuk memastikan tinggi minimum yang sama */
        }
        .article-item:hover {
            background: #739FD8;
            transform: translateY(-5px);
        }
        .article-item h3 {
            margin-bottom: 10px;
            text-align: center; /* Menengah-align teks judul artikel */
            flex: 1; /* Tambahkan ini agar h3 mengambil ruang yang tersedia */
            display: flex;
            align-items: center; /* Tambahkan ini untuk menengah-align teks secara vertikal */
            justify-content: center; /* Tambahkan ini untuk menengah-align teks secara horizontal */
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

        /* Media Queries */
        @media (max-width: 1024px) {
            .main-content {
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .article-item {
                flex: 1 1 calc(50% - 20px);
            }
            .menu {
                gap: 20px;
            }
            .menu-item .icon {
                width: 60px;
                height: 60px;
            }
            .menu-item img {
                width: 30px;
                height: 30px;
            }
        }

        @media (max-width: 480px) {
            .article-item {
                flex: 1 1 100%;
            }
            .menu-icon {
                top: 15px;
            }
            .menu {
                flex-direction: column;
                gap: 10px;
            }
            .menu-item .icon {
                width: 50px;
                height: 50px;
            }
            .menu-item img {
                width: 25px;
                height: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/logo-icon.png" alt="Home Posyandu Logo">
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
    <div class="main-content">
        <div class="container">
            <br>
            <h2>Menu</h2>
            <div class="menu">
                <a href="vaksinasi.php" class="menu-item">
                    <div class="icon">
                        <img src="images/vaksinasi-icon.png" alt="Vaksinasi">
                    </div>
                    <span>Vaksinasi</span>
                </a>
                <a href="gizi.php" class="menu-item">
                    <div class="icon">
                        <img src="images/gizi-icon.png" alt="Gizi">
                    </div>
                    <span>Gizi</span>
                </a>
                <a href="cari_posyandu.php" class="menu-item">
                    <div class="icon">
                        <img src="images/jarak-icon.png" alt="Cari Posyandu">
                    </div>
                    <span>Cari Posyandu</span>
                </a>
                <a href="data_anak.php" class="menu-item">
                    <div class="icon">
                        <img src="images/data-anak-icon.png" alt="Data Anak">
                    </div>
                    <span>Data Anak</span>
                </a>
            </div>

            <br><br><br>
            <h2 style="            color: #8BBEFF;">Artikel Terkini</h2>
            <div class="articles">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<a href='" . $row['link'] . "' class='article-item'>";
                        echo "<h3>" . $row['Judul'] . "</h3>";
                        echo "</a>";
                    }
                } else {
                    echo "<p>Tidak ada artikel yang tersedia.</p>";
                }
                ?>
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

        // Close the menu when clicking outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.menu-icon')) {
                var navbar = document.getElementById("navbar");
                if (navbar.style.display === "flex") {
                    navbar.style.display = "none";
                }
            }
        }
    </script>
</body>
</html>

