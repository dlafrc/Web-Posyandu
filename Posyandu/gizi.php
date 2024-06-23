<?php
require 'koneksi.php';

session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    // Ambil ID user dari sesi
    $user_id = $_SESSION['user_id'];

    // Query untuk mendapatkan data anak berdasarkan ID pengguna
    $query_anak = "SELECT id, nama FROM anaks WHERE id_user = $user_id";
    $result_anak = mysqli_query($conn, $query_anak);

    // Inisialisasi array untuk menyimpan data anak
    $anak_data = array();

    // Ambil data anak dan masukkan ke dalam array
    if ($result_anak && mysqli_num_rows($result_anak) > 0) {
        while ($row_anak = mysqli_fetch_assoc($result_anak)) {
            $anak_data[] = $row_anak;
        }
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
    <title>Gizi Anak - Home Posyandu</title>
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
            text-align: left;
            max-width: 550px;
            margin: 10px auto;
        }

        .profile-box {
            background-color: #fff;
            text-size-adjust: 10px;
            border: 1px solid #ddd;
            border-color: #ff9ccc;
            border-radius: 8px;
            padding: 10px 25px;
            margin-bottom: 10px;
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

        .profile-heading {
            text-align: center;
            margin-bottom: 10px;
            color: #ff9ccc;
            font-size: 20px;
        }

        .profile-info label {
            font-weight: bold;
            color: #ff9ccc;
            font-size: 7px;
        }

        .back-button, .edit-button, .add-button {
            padding: 10px 20px;
            color: white;
            background-color: #ff9ccc;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            transition: background-color 0.3s;
            width: calc(20% - 10px);
            box-sizing: border-box;
        }

        .back-button:hover, .edit-button:hover, .add-button:hover {
            background-color: #CF5F9B;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
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
            padding: 8px;
            background-color: #fff;
            margin-bottom: 20px;
        }

        .select-container img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .select-container select {
            flex: 1;
            border: none;
            outline: none;
            font-size: 15px;
            color: #ff9ccc;
        }

        .select-container select option {
            padding: 10px;
        }

        .no-data {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60px;
            border: 1px solid #ff9ccc;
            border-radius: 8px;
            color: #ff9ccc;
            margin-bottom: 20px;
            flex-direction: column;
        }

        .gizi-box label {
            font-weight: bold;
            color: #ff9ccc;
            font-size: 14px;
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
        <h2 class="profile-heading">Gizi Anak</h2>
        <div class="profile-info">
            <div class="select-container">
                <img src="images/people-icon.png" alt="Icon">
                <select id="select_anak">
                    <option value="">Pilih Anak</option>
                    <?php foreach ($anak_data as $anak) : ?>
                        <option value="<?php echo $anak['id']; ?>"><?php echo htmlspecialchars($anak['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <div id="anak_gizi">
            <!-- Informasi gizi anak akan ditampilkan di sini -->
        </div>
        <div class="button-container">
             <a class="back-button" href="home.php">Kembali</a>
            </div>
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

    document.addEventListener("DOMContentLoaded", function() {
        var selectAnak = document.getElementById('select_anak');
        var anakGizi = document.getElementById('anak_gizi');

        selectAnak.addEventListener('change', function() {
            var selectedAnakId = selectAnak.value;
            if (selectedAnakId !== '') {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_gizi.php?id=' + selectedAnakId, true);
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        anakGizi.innerHTML = xhr.responseText;
                    } else {
                        console.error('Error: ' + xhr.status);
                    }
                };
                xhr.onerror = function() {
                    console.error('Request failed');
                };
                xhr.send();
            } else {
                anakGizi.innerHTML = '';
            }
        });
    });
</script>
</body>
</html>

