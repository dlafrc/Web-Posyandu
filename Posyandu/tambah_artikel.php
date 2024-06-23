<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $link = $_POST['link'];
    $status = 'pending';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO artikel (judul, pengarang, link, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $judul, $pengarang, $link, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil ditambahkan!'); window.location.href='artikel_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan artikel.'); window.location.href='tambah_artikel.php';</script>";
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
    <title>Tambah Artikel - Home Posyandu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        .main-content h1 {
            font-size: 2em;
            color: #97B9F9;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #97B9F9;
            color: white;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #82a0e4;
        }

        .btn-back {
            background-color: #f44336;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #d32f2f;
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
            <li class="menu-item"><i class="fas fa-tachometer-alt"></i><a href="home_admin.php">Dashboard</a></li>
            <li class="menu-item dropdown-btn"><i class="fas fa-database"></i>Database</li>
            <div class="dropdown-container">
                <a class="menu-item" href="posyandu_admin.php"><i class="fas fa-clinic-medical"></i> Posyandu</a>
                <a class="menu-item" href="artikel_admin.php"><i class="fas fa-file-alt"></i> Artikel</a>
            </div>
            <li class="menu-item"><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

    <div class="main-content">
        <h1>Tambah Artikel</h1>
        <div class="form-container">
            <form method="post" action="">
                <div class="form-group">
                    <label for="judul">Judul:</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                    <label for="pengarang">Pengarang:</label>
                    <input type="text" id="pengarang" name="pengarang" required>
                </div>
                <div class="form-group">
                    <label for="link">Link:</label>
                    <input type="url" id="link" name="link" required>
                </div>
                <button type="submit" class="btn">Tambah Artikel</button>
                <a href="artikel_admin.php" class="btn-back">Back</a>
            </form>
        </div>
    </div>
</body>
</html>