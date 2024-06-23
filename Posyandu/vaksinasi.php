<?php
require 'koneksi.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$anak_list = [];
$vaksinasi_data = [];
$selected_anak_id = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['anak_id'])) {
    $selected_anak_id = mysqli_real_escape_string($conn, $_POST['anak_id']);

    $query_vaksinasi = "SELECT jenis_vaksin.jenis as jenis_vaksin, vaksin_anak.status, vaksin_anak.tanggal, posyandu.nama as posyandu_nama
                        FROM vaksin_anak 
                        LEFT JOIN jenis_vaksin ON vaksin_anak.id_jenis = jenis_vaksin.id
                        LEFT JOIN posyandu ON vaksin_anak.id_posyandu = posyandu.id
                        WHERE vaksin_anak.id_anak = '$selected_anak_id'";
    $result_vaksinasi = mysqli_query($conn, $query_vaksinasi);

    if ($result_vaksinasi && mysqli_num_rows($result_vaksinasi) > 0) {
        while ($row_vaksinasi = mysqli_fetch_assoc($result_vaksinasi)) {
            $vaksinasi_data[] = $row_vaksinasi;
        }
    }
}

$query_anak = "SELECT id, nama FROM anaks WHERE id_user = $user_id";
$result_anak = mysqli_query($conn, $query_anak);

if ($result_anak && mysqli_num_rows($result_anak) > 0) {
    while ($row_anak = mysqli_fetch_assoc($result_anak)) {
        $anak_list[] = $row_anak;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Vaksinasi Anak</title>
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
            margin-bottom: 20px;
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.1);
            text-align: center;
            color: #ff9ccc;
            margin-bottom: 100px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff9ccc;
            color: white;
            text-decoration: none;
            margin-top: 25px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 15px;
            border: none; /* Remove border */
        }

        .submit-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff9ccc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 15px;
            border: none; /* Remove border */
        }

        .back-button:hover, .submit-button:hover {
            background-color: #CF5F9B;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select {
            width: calc(100% - 5px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="%23FC98CC"><path d="M7 10l5 5 5-5z"/></svg>'), url('images/people-icon.png');
            background-repeat: no-repeat;
            background-position: right 10px top 50%, left 10px center;
            background-size: 16px, 35px;
            padding-left: 40px;
        }

        .select-container {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ff9ccc;
            border-radius: 8px;
            padding: 10px;
            background-color: #fff;
            margin-bottom: 40px; /* Increased margin-bottom */
            width: fit-content;
            margin: 0 auto;
        }

        .select-container select {
            flex: none;
            width: 400px;
            border: none;
            outline: none;
            font-size: 15px;
            color: #ff9ccc;
            padding-left: 50px;
        }

        .select-container select option {
            padding: 10px;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #ff9ccc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }

        .form-group button:hover {
            background-color: #CF5F9B;
        }

        .results {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px; /* Added margin-top */
        }

        .results .card {
            background-color: #fff; /* Warna default */
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
        }

        .results .card.sudah-vaksin {
            background-color: rgb(216,202,238, 0.5); /* Warna pink untuk status sudah */
        }

        .results .card h3 {
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 10px;
            color: #ff9ccc; /* Pink color for header */
        }

        .results .card p {
            margin: 0;
            line-height: 1.6;
            color: #000; /* Black color for default text */
        }

        .results .card p span.label {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff9ccc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
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
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            margin-bottom: 20px; /* Add margin-bottom */
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
        <h2>Vaksinasi</h2>
        <form method="POST">
            <div class="form-group select-container">
                <select id="anak_id" name="anak_id" required>
                    <option value="">Pilih Anak</option>
                    <?php foreach ($anak_list as $anak) { ?>
                        <option value="<?= $anak['id'] ?>" <?= $selected_anak_id == $anak['id'] ? 'selected' : '' ?>><?= htmlspecialchars($anak['nama']) ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="submit-button">Lihat</button>
            </div>
        </form>
        <?php if (!empty($vaksinasi_data)) { ?>
            <div class="results">
            <?php foreach ($vaksinasi_data as $vaksinasi) { ?>
                <div class="card <?php echo ($vaksinasi['status'] == 'sudah') ? 'sudah-vaksin' : ''; ?>">
                    <h3><?= htmlspecialchars($vaksinasi['jenis_vaksin']) ?></h3>
                    <p><span class="label">Status:</span> <?= htmlspecialchars($vaksinasi['status']) ?></p>
                    <p><span class="label">Tanggal:</span> <?= htmlspecialchars($vaksinasi['tanggal']) ?></p>
                </div>
            <?php } ?>

            </div>
        <?php } ?>
        <div class="back-container">
            <a class="back-button" href="home.php">Kembali</a>
        </div>
    </div>
    <div class="footer">
        <p>© ANAK 2024. All rights reserved.</p>
    </div>
    <script>
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            navbar.style.display = navbar.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
