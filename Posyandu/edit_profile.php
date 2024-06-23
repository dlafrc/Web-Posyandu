<?php
require 'koneksi.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error_message = "";

$user_id = $_SESSION['user_id'];

$query_sql = "SELECT nama, email, NIK, jenis_kelamin, tanggal_lahir FROM user_account WHERE id = $user_id";
$result = mysqli_query($conn, $query_sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nama = $row["nama"];
    $email = $row["email"];
    $NIK = $row["NIK"];
    $jenis_kelamin = $row["jenis_kelamin"];
    $tanggal_lahir = $row["tanggal_lahir"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_baru = $_POST["nama"];
    $email_baru = $_POST["email"];
    $NIK_baru = $_POST["NIK"];
    $jenis_kelamin_baru = $_POST["jenis_kelamin"];
    $tanggal_lahir_baru = $_POST["tanggal_lahir"];

    $update_sql = "UPDATE user_account SET nama = '$nama_baru', email = '$email_baru', NIK = '$NIK_baru', jenis_kelamin = '$jenis_kelamin_baru', tanggal_lahir = '$tanggal_lahir_baru' WHERE id = $user_id";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: profile.php");
        exit();
    } else {
        $error_message = "Update Gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Home Posyandu</title>
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        }

        .profile-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px 40px;
            margin-bottom: 40px;
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

        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #ff9ccc;
        }

        .profile-info input[type="text"],
        .profile-info input[type="email"],
        .profile-info input[type="date"],
        .profile-info select {
            width: calc(100% - 5px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .profile-heading {
            text-align: center;
            margin-bottom: 20px;
            color: #ff9ccc;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        input[type="submit"], .back-button {
            background-color: #ff9ccc;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-right: 5px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
        }

        input[type="submit"]:hover, .back-button:hover {
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
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }

        .error-message {
            color: red;
            margin-top: 20px;
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
            <a href="profile.php">Profile</a>
            <a href="bantuan.php">Bantuan</a>
            <a href="informasi.php">Informasi</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="profile-box">
            <h2 class="profile-heading">Edit Profil</h2>
            <form action="" method="post">
                <div class="profile-info">
                    <label>Nama:</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                </div>
                <div class="profile-info">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="profile-info">
                    <label>NIK:</label>
                    <input type="text" name="NIK" value="<?php echo htmlspecialchars($NIK); ?>" required>
                </div>
                <div class="profile-info">
                    <label>Jenis Kelamin:</label>
                    <select name="jenis_kelamin" required>
                        <option value="Pria" <?php if ($jenis_kelamin == 'Pria') echo 'selected'; ?>>Pria</option>
                        <option value="Wanita" <?php if ($jenis_kelamin == 'Wanita') echo 'selected'; ?>>Wanita</option>
                    </select>
                </div>
                <div class="profile-info">
                    <label>Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" required>
                </div>
                <div class="button-container">
                    <a class="back-button" href="profile.php">Kembali</a>
                    <input type="submit" value="Simpan">
                </div>
            </form>
            <?php 
            if (!empty($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            }
            ?>
        </div>
    </div>
    <div class="footer">
        <p>&copy; ANAK 2024. All rights reserved.</p>
    </div>

    <script>
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            if (navbar.style.display === "block") {
                navbar.style.display = "none";
            } else {
                navbar.style.display = "block";
            }
        }
    </script>
</body>
</html>

