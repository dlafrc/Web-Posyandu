<?php
session_start();
require 'koneksi.php';

// Cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: login.php");
    exit();
}

$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_posyandu = $_POST['nama_posyandu'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $alamat_posyandu = $_POST['alamat_posyandu'];
    $link_posyandu = $_POST['link_posyandu'];

    $query = "INSERT INTO posyandu (nama, kecamatan, kelurahan, alamat, link) VALUES ('$nama_posyandu', '$kecamatan', '$kelurahan', '$alamat_posyandu', '$link_posyandu')";
    if (mysqli_query($conn, $query)) {
        $success_message = "Data Posyandu berhasil ditambahkan!";
    } else {
        $success_message = "Error: " . $query . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Posyandu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #F9FAFB;
            color: #333;
        }
        
        .sidebar {
            width: 250px;
            background-color: #97B9F9;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu {
            list-style-type: none;
            width: 100%;
            padding: 0;
        }

        .menu-item {
            background-color: transparent;
            color: white;
            padding: 15px;
            margin-bottom: 10px;
            text-align: left;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .menu-item:hover {
            background-color: #d4e3fc;
            color: #333;
        }

        .menu-item a {
            text-decoration: none;
            color: inherit;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .menu-item i {
            margin-right: 10px;
        }

        .dropdown-container {
            display: none;
            padding-left: 15px;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #F9FAFB;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #97B9F9;
        }

        .form-container label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #97B9F9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #82a0e4;
        }

        .form-container .back-button {
            background-color: #f44336;
            margin-top: 10px;
        }

        .form-container .back-button:hover {
            background-color: #e53935;
        }

        .notification {
            display: none;
            background-color: #97B9F9; 
            color: white;
            padding: 15px;
            border-radius: 5px;
            position: fixed;
            top: 20px;
            right: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .notification.blue {
            background-color: #97B9F9;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
                width: calc(100% - 200px);
            }
            .form-container {
                width: 90%;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                justify-content: space-around;
                padding: 10px 0;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
            .form-container {
                width: 100%;
                margin-bottom: 20px;
            }
        }
        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="images/logo-icon.png" alt="Logo" height="80">
            A.N.A.K
        </div>
        <ul class="menu">
            <li class="menu-item"><i class="fa fa-tachometer-alt"></i><a href="home_admin.php">Dashboard</a></li>
            <li class="menu-item dropdown-btn"><i class="fa fa-database"></i>Database</li>
            <div class="dropdown-container">
                <a class="menu-item" href="posyandu_admin.php"><i class="fa fa-clinic-medical"></i> Posyandu</a>
                <a class="menu-item" href="artikel_admin.php"><i class="fa fa-file-alt"></i> Artikel</a>
            </div>
            <li class="menu-item"><i class="fa fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="form-container">
            <h1>Tambah Data Posyandu</h1>
            <form method="POST" action="">
                <label for="nama_posyandu">Nama</label>
                <input type="text" id="nama_posyandu" name="nama_posyandu" required>
                <label for="kecamatan">Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan" required>
                <label for="kelurahan">Kelurahan</label>
                <input type="text" id="kelurahan" name="kelurahan" required>
                <label for="alamat_posyandu">Alamat</label>
                <input type="text" id="alamat_posyandu" name="alamat_posyandu" required>
                <label for="link_posyandu">Link</label>
                <input type="text" id="link_posyandu" name="link_posyandu" required>
                <button type="submit">Tambah Data</button>
                <a href="home_admin.php" style="text-decoration: none;">
                    <button type="button" class="back-button">Back</button>
                </a>
            </form>
        </div>
        <div class="notification blue" id="notification">
            <?php echo $success_message; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successMessage = "<?php echo $success_message; ?>";
            if (successMessage) {
                var notification = document.getElementById("notification");
                notification.style.display = "block";
                setTimeout(function() {
                    notification.style.display = "none";
                }, 3000);
            }

            var dropdown = document.querySelectorAll(".dropdown-btn");
            dropdown.forEach(function(button) {
                button.addEventListener("click", function() {
                    this.classList.toggle("active");
                    var dropdownContent = this.nextElementSibling;
                    if (dropdownContent.style.display === "block") {
                        dropdownContent.style.display = "none";
                    } else {
                        dropdownContent.style.display = "block";
                    }
                });
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>