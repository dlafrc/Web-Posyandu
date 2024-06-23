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
    LIMIT 30
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
</head>

<style>

    /* =========== Google Fonts ============ */
@import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

/* =============== Globals ============== */
* {
  font-family: "Ubuntu", sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --blue: #97b9f9;
  --white: #fff;
  --gray: #f5f5f5;
  --black1: #222;
  --black2: #999;
}

body {
  min-height: 100vh;
  overflow-x: hidden;
}

.container {
  position: relative;
  width: 100%;
}

/* =============== Navigation ================ */
.navigation {
  position: fixed;
  width: 300px;
  height: 100%;
  background: var(--blue);
  border-left: 10px solid var(--blue);
  transition: 0.5s;
  overflow: hidden;
}
.navigation.active {
  width: 80px;
}

.navigation ul {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
}

.navigation ul li {
  position: relative;
  width: 100%;
  list-style: none;
  border-top-left-radius: 30px;
  border-bottom-left-radius: 30px;
}

.navigation ul li:hover,
.navigation ul li.hovered {
  background-color: var(--white);
}

.navigation ul li:nth-child(1) {
  margin-bottom: 40px;
  pointer-events: none;
}

.navigation ul li a {
  position: relative;
  display: block;
  width: 100%;
  display: flex;
  text-decoration: none;
  color: var(--white);
}
.navigation ul li:hover a,
.navigation ul li.hovered a {
  color: var(--blue);
}

.navigation ul li a .icon {
  position: relative;
  display: block;
  min-width: 60px;
  height: 60px;
  line-height: 75px;
  text-align: center;
}
.navigation ul li a .icon ion-icon {
  font-size: 1.75rem;
}

.navigation ul li a .title {
  position: relative;
  display: block;
  padding: 0 10px;
  height: 60px;
  line-height: 60px;
  text-align: start;
  white-space: nowrap;
}

/* --------- curve outside ---------- */
.navigation ul li:hover a::before,
.navigation ul li.hovered a::before {
  content: "";
  position: absolute;
  right: 0;
  top: -50px;
  width: 50px;
  height: 50px;
  background-color: transparent;
  border-radius: 50%;
  box-shadow: 35px 35px 0 10px var(--white);
  pointer-events: none;
}
.navigation ul li:hover a::after,
.navigation ul li.hovered a::after {
  content: "";
  position: absolute;
  right: 0;
  bottom: -50px;
  width: 50px;
  height: 50px;
  background-color: transparent;
  border-radius: 50%;
  box-shadow: 35px -35px 0 10px var(--white);
  pointer-events: none;
}

/* ===================== Main ===================== */
.main {
  position: absolute;
  width: calc(100% - 300px);
  left: 300px;
  min-height: 100vh;
  background: var(--white);
  transition: 0.5s;
}
.main.active {
  width: calc(100% - 80px);
  left: 80px;
}

.topbar {
  width: 100%;
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10px;
}

.toggle {
  position: relative;
  width: 60px;
  height: 60px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 2.5rem;
  cursor: pointer;
}


/* ======================= Cards ====================== */
.cardBox {
  position: relative;
  width: 133%;
  padding: 20px;
}

.cardBox .card {
  position: relative;
  background: var(--white);
  padding: 30px;
  border-radius: 20px;
  display: flex;
  justify-content: space-between;
  cursor: pointer;
  box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
}

.cardBox .card .numbers {
  position: relative;
  font-weight: 500;
  font-size: 2.5rem;
  color: var(--blue);
}

.cardBox .card .cardName {
  color: var(--black2);
  font-size: 1.1rem;
  margin-top: 5px;
}

.cardBox .card .iconBx {
  font-size: 3.5rem;
  color: var(--black2);
}

.cardBox .card:hover {
  background: var(--blue);
}
.cardBox .card:hover .numbers,
.cardBox .card:hover .cardName,
.cardBox .card:hover .iconBx {
  color: var(--white);
}

/* ================== Order Details List ============== */
.details {
  position: relative;
  width: 100%;
  padding: 20px;
  margin-top: 100%; /* Remove margin-top to bring it up */
}

.details .recentOrders {
  position: relative;
  min-height: 1000px;
  background: var(--white);
  padding: 20px;
  box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
  border-radius: 20px;
}


.details .cardHeader {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0; /* Remove margin-bottom to reduce spacing */
}

.cardHeader h2 {
  font-weight: 600;
  color: var(--blue);
  margin: 0; /* Ensure there's no margin on the h2 */
}

.cardHeader .btn {
  position: relative;
  padding: 5px 10px;
  background: var(--blue);
  text-decoration: none;
  color: var(--white);
  border-radius: 6px;
}

.details table {
  width: 150%;
  border-collapse: collapse;
  margin-top: 10px; /* Add a small top margin if needed */
}

.details table thead td {
  font-weight: 600;
  padding: 8px; /* Adjust the padding as needed */
}

.details .recentOrders table tr {
  color: var(--black1);
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.details .recentOrders table tr:last-child {
  border-bottom: none;
}

.details .recentOrders table tbody tr:hover {
  background: var(--blue);
  color: var(--white);
}

.details .recentOrders table tr td {
  padding: 8px; /* Adjust the padding as needed */
}





/* ====================== Responsive Design ========================== */
@media (max-width: 991px) {
  .navigation {
    left: -300px;
  }
  .navigation.active {
    width: 300px;
    left: 0;
  }
  .main {
    width: 100%;
    left: 0;
  }
  .main.active {
    left: 300px;
  }
  .cardBox {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .details {
    grid-template-columns: 1fr;
  }
  .recentOrders {
    overflow-x: auto;
  }
  .status.inProgress {
    white-space: nowrap;
  }
}

@media (max-width: 480px) {
  .cardBox {
    grid-template-columns: repeat(1, 1fr);
  }
  .cardHeader h2 {
    font-size: 20px;
  }
  .user {
    min-width: 40px;
  }
  .navigation {
    width: 100%;
    left: -100%;
    z-index: 1000;
  }
  .navigation.active {
    width: 100%;
    left: 0;
  }
  .toggle {
    z-index: 10001;
  }
  .main.active .toggle {
    color: #fff;
    position: fixed;
    right: 0;
    left: initial;
  }
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

        <!-- ========================= Main ==================== -->
         <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

  


     <!-- ================ Order Details List ================= -->
                        <div class="details">
                            <div class="recentOrders">
                            <div class="cardHeader">
                                <h2>Baru Ditambahkan</h2>
                                <a href="home_posyandu.php" class="btn">Kembali</a>
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
                                        <td><?php echo htmlspecialchars($immunization['nama_anak']); ?></td>
                                        <td><?php echo htmlspecialchars($immunization['nama_ibu']); ?></td>
                                        <td><?php echo htmlspecialchars($immunization['jenis_vaksin']); ?></td>
                                        <td><?php echo htmlspecialchars($immunization['tanggal']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>     
</div>

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic.js"></script>
            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

