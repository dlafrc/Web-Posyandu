<?php
require 'koneksi.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query_sql = "SELECT * FROM user_account WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query_sql);

    if (mysqli_num_rows($result) > 0) {
        // Ambil data pengguna dari hasil query
        $user_data = mysqli_fetch_assoc($result);
        $user_id = $user_data['id'];

        // Mulai sesi
        session_start();

        // Simpan ID pengguna dan username dalam sesi
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;

        // Periksa apakah username ada di tabel posyandu
        $query_posyandu = "SELECT * FROM posyandu WHERE nama = '$username'";
        $result_posyandu = mysqli_query($conn, $query_posyandu);
        if (mysqli_num_rows($result_posyandu) > 0) {
            // Jika ada, alihkan ke halaman home_posyandu
            header("Location: home_posyandu.php");
        } else if($username == 'admin' && $password== 'admin') {
            header("Location: home_admin.php");
        }
        else {
            // Jika tidak, alihkan ke halaman home
            header("Location: home.php");
        }
        exit();
    } else {
        $error_message = "Email atau password yang anda masukkan salah";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(90deg, #FC98CC, #8BBEFF);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
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

        .container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        .container input[type="text"],
        .container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .container input[type="submit"] {
            background-color: #ff9ccc;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
        }

        .container input[type="submit"]:hover {
            background-color: #CF5F9B;
        }

        .error-message {
            color: red;
            margin-top: 15px;
            text-align: center;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #ff9ccc;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Masuk</h1>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Masuk">
        </form>
        <?php
        if (!empty($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>
        <div class="back-link">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
    </div>
</body>
</html>
