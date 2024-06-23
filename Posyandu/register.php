<?php
require 'koneksi.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query_sql = "INSERT INTO user_account (username, email, password) VALUES ('$username', '$email', '$password')";

    if (mysqli_query($conn, $query_sql)) {
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Pendaftaran Gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        /* Gaya umum untuk seluruh halaman */
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

        /* Gaya untuk container form */
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

        /* Gaya untuk judul form */
        .container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        /* Gaya untuk input dan textarea */
        .container input[type="text"],
        .container input[type="email"],
        .container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        /* Gaya untuk tombol submit */
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

        /* Gaya untuk pesan error */
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
        <h1>Daftar</h1>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Daftar">
        </form>
        <?php
        if (!empty($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>
        <div class="back-link">
            Sudah punya akun? <a href="login.php">Masuk</a>
        </div>
    </div>
</body>
</html>
