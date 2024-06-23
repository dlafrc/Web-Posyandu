<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "dellarachmatikani@gmail.com";
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST["message"]);
    $subject = "Saran atau Pertanyaan dari Pengguna Posyandu";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<script>alert('Pesan Anda telah terkirim. Terima kasih!'); window.location.href = 'bantuan.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan. Pesan Anda tidak terkirim.'); window.location.href = 'bantuan.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - Home Posyandu</title>
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
            color: #ff9ccc
        }

        .form-container {
            background-color: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-container input[type="email"], .form-container textarea {
            width: calc(100% - 10px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container .button-container {
            display: flex;
            justify-content: space-between;
        }

        .form-container button, .back-button {
            background-color: #ff9ccc;
            color: white;
            padding: 10px 20px;
            margin-right: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
        }

        .form-container button {
            margin-left: auto;
        }

        .form-container button:hover, .back-button:hover {
            background-color: #CF5F9B;
        }

        .form-heading {
            text-align: center;
            color: #FC98CC;
            margin-bottom: 20px;
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
            <a href="profile.php">Profile</a>
            <a href="informasi.php">Informasi</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="form-container">
            <h2 class="form-heading">Bantuan</h2>
            <form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Pesan:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <div class="button-container">
                    <a href="home.php" class="back-button">Kembali</a>
                    <button type="submit">Kirim</button>
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
