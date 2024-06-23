<!DOCTYPE html>
<html>
<head>
    <title>Home A.N.A.K</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            color: white;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            text-align: left;
            overflow-x: hidden;
            opacity: 0; /* Start hidden */
            transition: opacity 1s; /* Fade-in effect */
        }
        body.loaded {
            opacity: 1; /* Fade-in effect when loaded */
        }
        .container {
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 15px;
            margin-top: 20px;
            padding: 0 1px;
            box-sizing: border-box;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .logo h1 {
            font-size: 24px;
            margin: 0;
        }
        .content {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 30px;
        }
        .text {
            max-width: 600px;
            margin-right: 20px;
        }
        .text h2 {
            font-size: 60px; /* Ukuran lebih besar */
            margin-bottom: 0.3px;
        }
        .text p {
            font-size: 20px; /* Ukuran lebih besar */
            margin-bottom: 30px;
        }
        .buttons {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Rata kiri */
        }
        .buttons a {
            padding: 12px 24px; /* Ukuran lebih besar */
            margin: 5px 0;
            background-color: white;
            color: #ff9ccc;
            text-decoration: none;
            border-radius: 40px;
            font-weight: bold;
            text-align: center;
            box-sizing: border-box;
            transition: opacity 1s; /* Fade-out effect on click */
        }
        .buttons a:hover {
            background-color: #e0e0e0;
        }
        .large-logo {
            display: flex;
            justify-content: center;
            padding-top: 0px;
            width: 90%;
        }
        .large-logo img {
            max-width: 550px; /* Ukuran lebih besar */
            height: auto;
        }
        @media (min-width: 768px) {
            .buttons {
                flex-direction: row;
                justify-content: flex-start; /* Rata kiri */
                width: 100%;
            }
            .buttons a {
                margin: 0 20px 0 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="images/logo-icon.png" alt="A.N.A.K Logo">
                <h1>A.N.A.K</h1>
            </div>
        </div>
        <div class="content">
            <div class="text">
                <h2>ACUAN NUTRISI DAN AKSES KESEHATAN</h2>
                <p>Bersama Membangun Generasi Sehat, Nutrisi Tepat dan Akses Kesehatan Mudah</p>
                <div class="buttons">
                    <a href="login.php" class="fade-link">Masuk</a>
                    <a href="register.php" class="fade-link">Daftar</a>
                </div>
            </div>
            <div class="large-logo">
                <img src="images/logo-icon.png" alt="Large Logo">
            </div>
        </div>
    </div>

    <script>
        // Add fade-in effect when page loads
        document.addEventListener("DOMContentLoaded", function() {
            document.body.classList.add('loaded');
        });

        // Add fade-out effect when link is clicked
        document.querySelectorAll('.fade-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let targetUrl = this.getAttribute('href');
                document.body.style.opacity = 0; // Start fade-out
                setTimeout(function() {
                    window.location.href = targetUrl; // Navigate after fade-out
                }, 1000); // Match the transition duration
            });
        });
    </script>
</body>
</html>
