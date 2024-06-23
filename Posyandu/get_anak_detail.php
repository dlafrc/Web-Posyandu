<?php
require 'koneksi.php';

if (isset($_GET['id'])) {
    $anak_id = $_GET['id'];

    // Query untuk mendapatkan detail anak berdasarkan id
    $query_anak_detail = "SELECT * FROM anaks WHERE id = $anak_id";
    $result_anak_detail = mysqli_query($conn, $query_anak_detail);

    if ($result_anak_detail && mysqli_num_rows($result_anak_detail) > 0) {
        $anak = mysqli_fetch_assoc($result_anak_detail);
        // Hitung umur anak
        $tanggal_lahir = new DateTime($anak['tanggal_lahir']);
        $sekarang = new DateTime();
        $diff = $tanggal_lahir->diff($sekarang);
        $umur = $diff->y . " tahun, " . $diff->m . " bulan, " . $diff->d . " hari";

        // Tampilkan detail anak dengan format yang sesuai
        echo "<div class='profile-box'>";
        echo "<div class='profile-info'>";
        echo "<label>Nama:</label>";
        echo "<p>" . htmlspecialchars($anak['nama']) . "</p>";
        echo "</div>";
        echo "<div class='profile-info'>";
        echo "<label>Jenis Kelamin:</label>";
        echo "<p>" . htmlspecialchars($anak['jenis_kelamin']) . "</p>";
        echo "</div>";
        echo "<div class='profile-info'>";
        echo "<label>Berat Badan:</label>";
        echo "<p>" . htmlspecialchars($anak['berat']) . " kg</p>";
        echo "</div>";
        echo "<div class='profile-info'>";
        echo "<label>Tinggi Badan:</label>";
        echo "<p>" . htmlspecialchars($anak['tinggi']) . " cm</p>";
        echo "</div>";
        echo "<div class='profile-info'>";
        echo "<label>Umur:</label>";
        echo "<p>" . $umur . "</p>";
        echo "</div>";
        echo "</div>"; // Tutup profile-box
    } else {
        echo "Data anak tidak ditemukan.";
    }
} else {
    echo "ID anak tidak ditemukan.";
}

// Tutup koneksi
mysqli_close($conn);
?>

