<?php
session_start();
require 'koneksi.php';

// Cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM posyandu";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .main-content h1 {
            font-size: 2em;
            color: #97B9F9;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 80%;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #97B9F9;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                width: calc(100% - 200px);
            }

            .card {
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

            .card {
                width: 100%;
                margin-bottom: 20px;
            }
        }

        .add-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #97B9F9;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .add-button:hover {
            background-color: #82a0e4;
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
                <a class="menu-item" href="posyandu_admin.php"><i class="fas fa-clinic-medical"></i> Posyandu</a>
                <a class="menu-item" href="artikel_admin.php"><i class="fas fa-file-alt"></i> Artikel</a>
            </div>
            <li class="menu-item"><i class="fa fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Data Posyandu</h1>
            <a href="tambah_posyandu.php" class="add-button">Tambah Data</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kecamatan</th>
                    <th>Kelurahan</th>
                    <th>Alamat</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['kecamatan'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['kelurahan'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['link'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Link</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>