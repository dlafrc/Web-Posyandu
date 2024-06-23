<?php
require 'koneksi.php';
session_start();

// Memeriksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

$error_message = "";
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan data anak berdasarkan user_id
$query_anak = "SELECT id, nama FROM anaks WHERE id_user = $user_id";
$result_anak = mysqli_query($conn, $query_anak);

$anak_data = [];
if ($result_anak && mysqli_num_rows($result_anak) > 0) {
    $anak_data = mysqli_fetch_all($result_anak, MYSQLI_ASSOC);
} else {
    $error_message = "Data anak tidak ditemukan.";
}

// Tutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anak - Home Posyandu</title>
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
    padding-bottom: 10px; /* Kurangi padding bawah */
    text-align: left;
    max-width: 600px;
    margin: 10px auto;
}

.profile-box {
    background-color: #fff;
    border: 1px solid #ff9ccc;
    border-radius: 8px;
    padding: 10px 20px; /* Kurangi padding atas dan bawah */
    margin-bottom: 30px; /* Kurangi margin bawah */
    margin-left: 20px;
    margin-right: 20px;
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
    font-size: 20px; /* Kurangi ukuran font untuk heading */
}

.profile-info label {
    font-weight: bold;
    color: #ff9ccc;
    font-size: 18px; /* Kurangi ukuran font untuk label */
}

.back-button, .edit-button, .add-button {
    padding: 10px 20px; /* Menyamakan padding tombol */
    color: white;
    background-color: #ff9ccc;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 10px;
    font-size: 14px; /* Menyamakan ukuran font */
    transition: background-color 0.3s;
    width: calc(20% - 10px); /* Menambah lebar yang konsisten */
    box-sizing: border-box; /* Memastikan padding dihitung dalam lebar */
}

.back-button:hover, .edit-button:hover, .add-button:hover {
    background-color: #CF5F9B;
}


.button-container {
    display: flex;
    justify-content: space-between; /* Mengatur jarak antara tombol */
    align-items: center; /* Menyelaraskan tombol secara vertikal */
    margin-top: 10px;
    gap: 10px; /* Memberikan jarak antar tombol */
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
    padding: 10px; /* Kurangi padding */
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
    font-size: 15px; /* Kurangi ukuran font */
    color: #ff9ccc;
}

.select-container select option {
    padding: 10px;
}

.no-data {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80px; /* Kurangi tinggi kontainer no-data */
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
        <h2 class="profile-heading">Data Anak</h2>
        <?php if (!empty($error_message)) : ?>
            <div class="no-data"><?php echo $error_message; ?></div>
        <?php else : ?>
            <?php if (count($anak_data) > 0) : ?>
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
                </div>
                <div id="anak_detail"></div>
            <?php else : ?>
                <div class="no-data">
                    <span>Data anak tidak ditemukan.</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- Kontainer untuk tombol-tombol -->
        <div class="button-container">
            <a class="back-button" href="home.php" id="back-button">Kembali</a>
            <button id="edit-button" class="edit-button" style="display: none;" disabled>Edit</button>
            <a href="tambah_anak.php" class="add-button" id="add-button">Tambah</a>
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
        var anakDetail = document.getElementById('anak_detail');
        var editButton = document.getElementById('edit-button');
        var addButton = document.getElementById('add-button');
        var backButton = document.getElementById('back-button');

        selectAnak.addEventListener('change', function() {
            var anakId = selectAnak.value;
            if (anakId !== '') {
                fetch('get_anak_detail.php?id=' + anakId)
                    .then(response => response.text())
                    .then(data => {
                        anakDetail.innerHTML = data;
                        editButton.style.display = 'inline-block';
                        editButton.removeAttribute('disabled');
                        addButton.style.display = 'none'; // Sembunyikan tombol Tambah Data Anak
                        backButton.href = 'data_anak.php'; // Atur href tombol kembali ke data_anak.php
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                anakDetail.innerHTML = '';
                editButton.style.display = 'none';
                editButton.setAttribute('disabled', 'disabled');
                addButton.style.display = 'inline-block'; // Tampilkan kembali tombol Tambah Data Anak
                backButton.href = 'home.php'; // Atur href tombol kembali ke home.php
            }
        });

        editButton.addEventListener('click', function() {
            var selectedAnakId = selectAnak.value;
            if (selectedAnakId !== '') {
                window.location.href = 'edit_anak.php?id=' + selectedAnakId;
            }
        });

        backButton.addEventListener('click', function(event) {
            event.preventDefault();
            if (selectAnak.value !== '') {
                window.location.href = 'data_anak.php'; // Pastikan href tetap ke data_anak.php jika detail anak ditampilkan
            } else {
                window.location.href = 'home.php'; // Kembali ke home.php jika tidak ada anak yang dipilih
            }
        });
    });
</script>
</body>
</html>



