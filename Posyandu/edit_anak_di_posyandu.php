<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    // Redirect to login page if not set
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

$error_message = "";
$id_anak = isset($_GET['id_anak']) ? htmlspecialchars($_GET['id_anak']) : '';

if (!empty($id_anak)) {
    $query_anak_detail = "SELECT * FROM anaks WHERE id = $id_anak";
    $result_anak_detail = mysqli_query($conn, $query_anak_detail);

    if ($result_anak_detail && mysqli_num_rows($result_anak_detail) > 0) {
        $anak = mysqli_fetch_assoc($result_anak_detail);
        $nama_anak = $anak['nama']; // Asumsi bahwa kolom nama adalah 'nama'
    } else {
        $error_message = "Data anak tidak ditemukan.";
    }
} else {
    $error_message = "ID anak tidak ditemukan!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $berat = isset($_POST['berat']) ? htmlspecialchars($_POST['berat']) : '';
    $tinggi = isset($_POST['tinggi']) ? htmlspecialchars($_POST['tinggi']) : '';

    if (!empty($berat) && !empty($tinggi)) {
        $query_update_anak = "UPDATE anaks SET berat = '$berat', tinggi = '$tinggi' WHERE id = $id_anak";
        if (mysqli_query($conn, $query_update_anak)) {
            $success_message = "Data berhasil diperbarui!";
        } else {
            $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Harap lengkapi semua data!";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Anak | Posyandu</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<style>

:root {
  --blue: #97b9f9;
  --white: #fff;
  --gray: #f5f5f5;
  --black1: #222;
  --black2: #999;
}

    .main .wellcome {
        position: relative;
        font-weight: 700;
        font-size: 2.0rem;
        color: var(--blue);
        margin-top: 100px;
        margin-bottom: 30px;
    }

    .content {
        position: relative;
        margin-top: 50px;
        padding: 20px;
        margin-left: 30%;
        margin-right: 30%;
        border-radius: 5px;
        border-collapse: collapse;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--black2);
    }

    .content .btn {
        position: relative;
        padding: 5px 10px;
        background: var(--blue);
        text-decoration: none;
        color: var(--white);
        border-radius: 6px;
        display: inline-block;
    }

    .content .btnSi {
        position: relative;
        font-weight: 600px;
        font-size: 15px;
        margin-top: 20px;
        padding: 10px 20px;
        background: var(--blue);
        text-decoration: none;
        color: var(--white);
        border-radius: 6px;
        display: inline-block;
        border: 1px solid color: var(--white);
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 50px;
    }

    form label {
        font-size: 1rem;
        color: var(--black1);
        margin-bottom: 5px;
    }

    form input[type="text"],
    form input[type="date"],
    form select {
        width: 300px;
        padding: 10px;
        border: 1px solid var(--black2);
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .error-message {
        color: red;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .success-message {
        color: black1;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .button-link,
    .back-button {
        text-decoration: none;
        color: var(--white);
        background: var(--blue);
        padding: 10px 10px;
        border-radius: 5px;
        display: inline-block;
        margin-left: 185px;

    }

    .icon .logo{
    width: 70px; /* Set lebar gambar */
  height: 70px; /* Set tinggi gambar */
  margin-top: 20px;
}

.container .logo-title{
    margin-top: 40px;
    margin-left: 10px;
    font-size: 25px;
    font-weight: 10px;
}

</style>
<body>
      <!-- =============== Navigation ================ -->
      <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="home_posyandu.php">
                        <span class="icon">
                           <img class = "logo" src="images/logo-icon.png" style = "size : "alt="">
                        </span>
                        <span class="logo-title"><?php echo htmlspecialchars($username); ?></span>
                    </a>
                </li>

                <li>
                    <a href="home_posyandu.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="data_imunisasi.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Data Imunisasi</span>
                    </a>
                </li>

                <li>

                <li>
                    <a href="imunisasi_anak.php">
                        <span class="icon">
                              <ion-icon name="person-add-outline"></ion-icon>
                        </span>
                        <span class="title">Tambah Imunisasi</span>
                    </a>
                </li>

                <li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- ========================= Main ==================== -->
         <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <h1 class = "wellcome" >Edit Data Anak <?php echo !empty($nama_anak) ? $nama_anak : ''; ?></h1>
                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <div class="content">
                
                <?php if (!empty($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php elseif (!empty($success_message)) : ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php else : ?>
                    <form method="post" action="">
                        <input type="hidden" name="id_anak" value="<?php echo $id_anak; ?>">
                        <div>
                            <label for="berat">Berat Badan (kg):</label>
                            <input type="text" id="berat" name="berat" value="<?php echo $anak['berat']; ?>" required>
                        </div>
                        <div>
                            <label for="tinggi">Tinggi Badan (cm):</label>
                            <input type="text" id="tinggi" name="tinggi" value="<?php echo $anak['tinggi']; ?>" required>
                        </div>
                        <button class = "btnSi" type="submit">Simpan Perubahan</button>
                    </form>
                <?php endif; ?>
                <a href="imunisasi_anak.php" class="back-button">Kembali</a>
            </div>
        </div>
    </div>


</div>

<script src="js/main.js"></script>

<!-- ====== ionicons ======= -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
