<!DOCTYPE html>
<html>
<head>
    <title>Informasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            animation: fadeIn 1s ease-in-out;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
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
            padding: 11px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .logo-container {
            display: flex;
            align-items: center;
            padding-left: 80px; /* Tambahkan padding kiri 40px */
        }

        .header img {
            width: 60px;
            height: 60px;
        }

        .header .logo-text {
            font-size: 1.4em;
            font-weight: 600;
            margin-left: 10px;
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

        .main {
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: space-around;
            padding: 0 50px;
            color: white;
        }

        .text-section {
            max-width: 50%;
            margin-top: -90px;
            padding-left: 30px;
        }

        .text-section h2 {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .text-section p {
            font-size: 1.3em;
            margin-bottom: 10px;
            text-align: justify;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 600px;
        }

        .logo-section img {
            max-width: 100%;
            height: auto;
        }

        .footer {
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            text-align: center;
            padding: 10px;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }

        .social-media {
            display: flex;
            flex-direction: row;
            margin-top: 20px;
            padding-right: 40px;
        }
        .social-media a {
            color: white;
            font-size: 20px;
            margin: 0 10px 0 0;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .social-media a:hover {
            color: #e0e0e0;
        }
        .social-media a span {
            font-size: 17px; /* Ukuran teks lebih kecil */
            margin-left: 5px; /* Memberi jarak antara ikon dan teks */
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="images/logo-icon.png" alt="Logo">
            <div class="logo-text">A.N.A.K</div>
        </div>
        <div class="menu-icon" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="navbar" id="navbar">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="bantuan.php">Bantuan</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="main">
        <div class="text-section">
            <h2>Tentang Kami</h2>
            <p>"A.N.A.K" hadir sebagai solusi inovatif untuk memudahkan orang tua dalam mengelola jadwal imunisasi anak-anak secara efisien sambil menyediakan informasi terpercaya tentang manfaat imunisasi.</p>
            <p>Website ini membantu meningkatkan kesadaran masyarakat akan pentingnya imunisasi dalam pencegahan penyakit anak.</p>
            <div class="social-media">
            <a href="https://www.instagram.com/avengersturu" target="_blank"><i class="fab fa-instagram"></i> <span>@anak.official</span></a>
                    <a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp"></i> <span>081234567890</span></a>
            </div>
        </div>
        <div class="logo-section">
            <img src="images/logo-icon.png" alt="Logo">
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
