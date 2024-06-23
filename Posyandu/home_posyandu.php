<?php
// Start the session
require 'koneksi.php';
session_start();

// Check if the user session is set
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not set
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Query untuk mengambil id dan count dari tabel posyandu berdasarkan nama
$query_posyandu = "SELECT id, count FROM posyandu WHERE nama = '$username'";
$result_posyandu = mysqli_query($conn, $query_posyandu);

$vaccineCount = 0;

if ($result_posyandu && mysqli_num_rows($result_posyandu) > 0) {
    $posyandu_data = mysqli_fetch_assoc($result_posyandu);
    
    // Dapatkan id_posyandu dan count
    $id_posyandu = $posyandu_data['id'];
    $vaccineCount = $posyandu_data['count'];
} else {
    // Arahkan ke halaman login jika tidak ditemukan posyandu yang cocok
    header("Location: login.php");
    exit();
}

// Query to get the latest 10 immunizations
$query_recent_immunizations = "
    SELECT a.nama AS nama_anak, u.nama AS nama_ibu, jv.jenis AS jenis_vaksin, va.tanggal 
    FROM vaksin_anak va
    JOIN anaks a ON va.id_anak = a.id
    JOIN user_account u ON a.id_user = u.id
    JOIN jenis_vaksin jv ON va.id_jenis = jv.id
    WHERE va.id_posyandu = (SELECT id FROM posyandu WHERE nama = '$username' LIMIT 1)
    ORDER BY va.tanggal DESC
    LIMIT 23
";
$result_recent_immunizations = mysqli_query($conn, $query_recent_immunizations);

$recent_immunizations = [];
if ($result_recent_immunizations && mysqli_num_rows($result_recent_immunizations) > 0) {
    while ($row = mysqli_fetch_assoc($result_recent_immunizations)) {
        $recent_immunizations[] = $row;
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

.card .numbers .btn {
  position: relative;
  padding: 5px 5px;
  background: var(--blue);
  text-decoration: none;
  color: var(--white);
  border-radius: 6px;
  border: 1px solid color: var(--white);
}

.card .numbers .btn:hover {
  background: var(--white);
  color: var(--blue);
}

.scrollable-content .btn{
    position: relative;
  padding: 5px 10px;
  background: var(--blue);
  text-decoration: none;
  color: var(--white);
  border-radius: 6px;
  border: 4px color: var(--blue);
  margin-left: 100px;
}

.scrollable-content .btn:hover {
  background: var(--white);
  color: var(--blue);
}

.main .wellcome{
    position: relative;
  font-weight: 700;
  font-size: 2.0rem;
  color: var(--blue);
  margin-top: 50px;
  margin-bottom: 20px;
}

.cardBox .card .numb {
  position: relative;
  font-weight: 500;
  font-size: 2.5rem;
  color: var(--blue);
}

.cardBox .card:hover .numb {
  color: var(--white);
}

.icon .logo{
    width: 70px; /* Set lebar gambar */
  height: 70px; /* Set tinggi gambar */
  margin-top: 10px;
}

.container .logo-title{
    margin-top: 40px;
    margin-left: 5px;
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
                <h2 class="wellcome" >Selamat datang Posyandu <?php echo htmlspecialchars($username); ?></h2>

                <div class="user">

                </div>
            </div>

            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numb"><?php echo $vaccineCount; ?></div>
                        <div class="cardName">Total Vaksinasi</div>
                    </div>
                    <div class="iconBx">
                         <ion-icon name="heart-circle-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">100</div>
                        <div class="cardName">Target</div>
                    </div>
                    <div class="iconBx">
                          <ion-icon name="trending-up-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers"> <button class="btn" onclick="window.location.href='imunisasi_anak.php'">Tambah Imunisasi</button></div>
                        <div class="cardName"></div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="Add-circle-outline"></ion-icon>
                    </div>
                </div>
            </div>

     <!-- ================ Order Details List ================= -->
                        <div class="details">
                            <div class="recentOrders">
                            <div class="cardHeader">
                                <h2>Baru Ditambahkan</h2>
                                <a href="data_imunisasi.php" class="btn">Lihat Semua</a>
                            </div>
                        <table>
                            <thead>
                                <tr>
                                    <td>Nama Anak</td>
                                    <td>Orang Tua</td>
                                    <td>Jenis Vaksin</td>
                                    <td>Tanggal</td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($recent_immunizations as $immunization): ?>
                                    <tr>
                                        <td data-label="Nama Anak"><?php echo htmlspecialchars($immunization['nama_anak']); ?></td>
                                        <td data-label="Orang Tua"><?php echo htmlspecialchars($immunization['nama_ibu']); ?></td>
                                        <td data-label="Jenis Vaksin"><?php echo htmlspecialchars($immunization['jenis_vaksin']); ?></td>
                                        <td data-label="Tanggal"><?php echo htmlspecialchars($immunization['tanggal']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

<!-- ================= Update Vaksinasi ================ -->
<div class="recentCustomers">
    <div class="cardHeader">
        <h2>Update Vaksinasi</h2>
    </div>

    <div class="scrollable-content">
        <table>
            <?php
            // Fetch vaksin options from the database
            $vaksinQuery = "SELECT id, jenis FROM jenis_vaksin";
            $vaksinResult = mysqli_query($conn, $vaksinQuery);

            // Loop through each vaksin
            while ($row = mysqli_fetch_assoc($vaksinResult)) {
                // Fetch the current status of the vaksin
                $statusQuery = "SELECT status FROM vaksinasi WHERE id_posyandu = '$id_posyandu' AND id_jenis = '" . $row['id'] . "'";
                $statusResult = mysqli_query($conn, $statusQuery);
                $status = mysqli_fetch_assoc($statusResult)['status'];

                // Display the vaksin with its status and button to update status
                echo '<tr>';
                echo '<td>';
                echo '<h4>' . htmlspecialchars($row['jenis']) . '</h4>';
                echo '<span id="status_' . $row['id'] . '">Status: ' . ($status == 'available' ? 'Available' : 'Unavailable') . '</span>';
                echo '<button id="button_' . $row['id'] . '" class="btn" onclick="updateStatus(' . $row['id'] . ', \'' . $status . '\')">' . ($status == 'available' ? 'Set Unavailable' : 'Set Available') . ' </button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>


    <!-- =========== Scripts =========  -->
    <script src="js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>



<script>
    function updateStatus(id, currentStatus) {
        var newStatus = currentStatus === "available" ? "unavailable" : "available";
        var statusElement = document.getElementById("status_" + id);
        var buttonElement = document.getElementById("button_" + id);

        // Update the status text immediately for better UX
        statusElement.innerText = "Status: " + (newStatus === 'available' ? 'Available' : 'Unavailable');
        buttonElement.innerText = newStatus === 'available' ? 'Set Unavailable' : 'Set Available';

        // Send AJAX request to update status in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Update the status for the next click
                buttonElement.setAttribute('onclick', 'updateStatus(' + id + ', "' + newStatus + '")');
                console.log(xhr.responseText); // Display the response message
            }
        };
        xhr.send("id_jenis=" + id + "&status=" + newStatus);
    }
</script>

