<?php
session_start();
require 'koneksi.php';

$id_anak = isset($_GET['id_anak']) ? htmlspecialchars($_GET['id_anak']) : '';
$username = $_SESSION['username']; // Assuming id_posyandu is stored in the session on login

// Assuming you have a MySQLi connection established as $conn
$username = mysqli_real_escape_string($conn, $username);

// Query to get the posyandu details
$query_posyandu = "SELECT * FROM posyandu WHERE nama = '$username'";
$result_posyandu = mysqli_query($conn, $query_posyandu);

if ($result_posyandu) {
    // Fetch the posyandu data
    $posyandu_data = mysqli_fetch_assoc($result_posyandu);
    $id_posyandu = $posyandu_data['id'];
} else {
    echo "Error: " . mysqli_error($conn);
}

// Ensure id_anak is not empty before proceeding
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedDate = isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : '';
    $selectedVaksin = $_POST['vaksin'];

    // Update the vaksin_anak table
    $updateQuery = "
        UPDATE vaksin_anak 
        SET id_posyandu = '$id_posyandu', status = 'sudah', tanggal = '$selectedDate'
        WHERE id_anak = '$id_anak' 
        AND id_jenis = '$selectedVaksin'
    ";

    if (mysqli_query($conn, $updateQuery)) {
        // Increment the total vaccination count for the year
        $incrementQuery = "
            UPDATE posyandu 
            SET count = count + 1 
            WHERE id = '$id_posyandu'
        ";
        mysqli_query($conn, $incrementQuery);

        // Set the success message to a JavaScript variable
        echo "<script>alert('Berhasil menambahkan imunisasi!');</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posyandu Home</title>
    <!-- ======= Styles ====== -->
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

.main .wellcome{
    position: relative;
  font-weight: 700;
  font-size: 2.0rem;
  color: var(--blue);
  margin-top: 100px;
  margin-bottom: 50px;
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
        margin-bottom: 10px;
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

        form input[type="date"],
        form select {
            width: 300px;
            padding: 10px;
            border: 1px solid var(--black2);
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 1rem;
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
                <h2 class="wellcome" >Tambah Imunisasi <?php echo htmlspecialchars($nama_anak); ?></h2>

                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <div class="content">

                <form method="post" action="tambah_imunisasi.php?id_anak=<?php echo $id_anak; ?>">
                    <label for="tanggal">Select Date:</label>
                    <input type="date" id="tanggal" name="tanggal" required>

                    <label for="vaksin">Select Vaksin:</label>
                    <select id="vaksin" name="vaksin" class="select2" required>
                        <option value="">Select a Vaksin</option>
                        <?php
                        // Fetch vaksin options from the database
                        $vaksinQuery = "SELECT id, jenis FROM jenis_vaksin";
                        $vaksinResult = mysqli_query($conn, $vaksinQuery);
                        while ($row = mysqli_fetch_assoc($vaksinResult)) {
                            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['jenis']) . '</option>';
                        }
                        ?>
                    </select>

                    <button class = "btn" type="submit">Submit</button>
                    <button class="btn" onclick="window.location.href='imunisasi_anak.php'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>

   <!-- =========== Scripts =========  -->
   <script src="js/main.js"></script>

<!-- ====== ionicons ======= -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
