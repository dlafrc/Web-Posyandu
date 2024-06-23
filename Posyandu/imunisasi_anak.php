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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imunisasi Anak</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<style>

:root {
  --blue: #97b9f9;
  --white: #fff;
  --gray: #f5f5f5;
  --black1: #222;
  --black2: #999;
}

.search {
  position: relative;
  width: 400px;
  margin: 0 10px;
}

.search label {
  position: relative;
  width: 100%;
}

.search label input {
  margin-top: 50px;
  width: 100%;
  height: 40px;
  border-radius: 40px;
  padding: 5px 20px;
  padding-left: 35px;
  font-size: 18px;
  outline: none;
  border: 1px solid var(--black2);
}

.search label ion-icon {
  margin-top: 50px;
  position: absolute;
  top: 0;
  left: 10px;
  font-size: 1.2rem;
}

    h1 {
        font-size: 2.5rem;
        color: var(--blue);
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
    }

    form label {
        font-size: 1rem;
        color: var(--black1);
        margin-bottom: 5px;
    }

    form input[type="date"],
    form select {
        width: 300px;
        padding: 10px;
        border: 1px solid var(--black2);
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 1rem;
    }

    form button[type="submit"] {
        padding: 10px 20px;
        background-color: var(--blue);
        color: var(--white);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
    }

    form button[type="submit"]:hover {
        background-color: darken(var(--blue), 10%);
    }

    table {
    margin-top: 20px; /* Tambahkan jarak dari tabel ke atasnya */
    margin-bottom: 20px; /* Tambahkan jarak dari tabel ke bawahnya */
    width: 100%;
    border-collapse: collapse;
    margin-left: 10px;
    margin-right: 10px;
    border: 3px solid var(--blue); /* Added outline */
    }

    table thead tr {
        background-color: var(--blue);
        color: var(--white);
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
        border-bottom: 3px solid var(--blue);
    }

    table tbody tr:hover {
        background-color: var(--gray);
    }

    /* Additional spacing */
    .content {
        padding: 20px;
        margin-top: 70px;
        margin-left: 30px;
        margin-right: 30px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .button {
    padding: 10px 15px;
    background-color: var(--blue);
    color: var(--white);
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1rem;
    margin-right: 10px;
    display: inline-block;
    text-align: center;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #0056b3; /* A lighter shade of blue for hover effect */
    color: var(--white); /* Ensures text color remains white */
}

.back-button {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: var(--blue);
    color: var(--white);
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    width: 150px;
    transition: background-color 0.3s;
}

.back-button:hover {
    background-color: #0056b3; /* Lighter shade of blue for hover effect */
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

        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div>
                    <div></div>
                    <form method="post">
                        <div class="search">
                            <label for="searchInput">
                                <input type="text" id="searchInput" name="search" placeholder="Nama Lengkap - tanggal lahir - nama ibu">
                            </label>
                        </div>
                    </form>
                </div>
                <div id="searchResults">
                        <!-- Hasil pencarian akan ditampilkan di sini -->
                </div>
            </div>

            <div class="content">
                <?php
                    if (isset($_POST['search'])) {
                        $search = mysqli_real_escape_string($conn, $_POST['search']);
                        $sql = "SELECT anaks.*, user_account.nama AS nama_ibu 
                                FROM anaks 
                                INNER JOIN user_account ON anaks.id_user = user_account.id 
                                WHERE anaks.nama LIKE '%$search%' 
                                OR anaks.tanggal_lahir LIKE '%$search%' 
                                OR user_account.nama LIKE '%$search%'";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            echo "<table>";
                            echo "<thead>";
                            echo "<tr><th>Nama Anak</th><th>Tanggal Lahir</th><th>Nama Ibu</th><th>Action</th></tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['tanggal_lahir']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_ibu']) . "</td>";
                                echo "<td>";
                                echo "<a class='button' href='edit_anak_di_posyandu.php?id_anak=" . $row['id'] . "'>Update Data</a>";
                                echo "<a class='button' href='tambah_imunisasi.php?id_anak=" . $row['id'] . "'>Tambah Imunisasi</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "No results found.";
                        }
                    }
                ?>
                <a href="home_posyandu.php" class="back-button">Back</a>
            </div>

            <!-- JavaScript -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic.js"></script>
            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Select a vaksin",
                        allowClear: true
                    });
                });
            </script>
            <script src="js/main.js"></script>
        </div>
</body>
</html>
