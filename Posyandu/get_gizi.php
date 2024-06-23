<?php
require 'koneksi.php';

function hitungUmurDetail($tanggal_lahir) {
    $birthDate = new DateTime($tanggal_lahir);
    $today = new DateTime("today");
    $diff = $today->diff($birthDate);

    $umur = array(
        'tahun' => $diff->y,
        'bulan' => $diff->m,
        'hari' => $diff->d
    );

    return $umur;
}

if (isset($_GET['id'])) {
    $anak_id = intval($_GET['id']);

    // Query untuk mendapatkan data anak berdasarkan ID
    $query_anak = "SELECT nama, berat, tanggal_lahir FROM anaks WHERE id = $anak_id";
    $result_anak = mysqli_query($conn, $query_anak);

    if ($result_anak && mysqli_num_rows($result_anak) > 0) {
        $row_anak = mysqli_fetch_assoc($result_anak);
        $nama = $row_anak['nama'];
        $berat = $row_anak['berat'];
        $tanggal_lahir = $row_anak['tanggal_lahir'];

        // Hitung umur detail berdasarkan tanggal lahir
        $umur_detail = hitungUmurDetail($tanggal_lahir);
        $umur_tahun = $umur_detail['tahun'];
        $umur_bulan = $umur_detail['bulan'];
        $umur_hari = $umur_detail['hari'];

        // Hitung kebutuhan kalori berdasarkan umur dan berat badan
        if ($umur_tahun == 0 && $umur_bulan <= 3) {
            $kalori = 110 * $berat; // Ambil nilai rata-rata antara 100-120 kkal/kg
        } elseif ($umur_tahun == 0 && $umur_bulan <= 6) {
            $kalori = 102.5 * $berat; // Ambil nilai rata-rata antara 95-110 kkal/kg
        } elseif ($umur_tahun == 0 && $umur_bulan <= 12) {
            $kalori = 90 * $berat; // Ambil nilai rata-rata antara 80-100 kkal/kg
        } elseif ($umur_tahun >= 1 && $umur_tahun <= 3) {
            $kalori = 80 * $berat;
        } elseif ($umur_tahun >= 4 && $umur_tahun <= 5) {
            $kalori = 70 * $berat;
        } else {
            $kalori = 0; // Set to 0 to avoid errors in further calculations
        }

        // Hitung kebutuhan protein
        $protein = 1 * $berat;

        // Hitung kebutuhan lemak (30% dari total kalori)
        $kalori_dari_lemak = 0.30 * $kalori;
        $lemak = $kalori_dari_lemak / 9;

        // Hitung kebutuhan serat berdasarkan usia
        $serat = $umur_tahun + 5;

        echo "<div class='profile-box'>";
        echo "<div class='profile-info'>";
        echo "<div class='gizi-box'>";
        echo "<p><label>Nama:</label><br>$nama</p>";
        echo "<p><label>Umur:</label><br>$umur_tahun tahun, $umur_bulan bulan, $umur_hari hari</p>";
        echo "<p><label>Berat Badan:</label><br>$berat kg</p>";
        echo "<p><label>Kebutuhan Kalori:</label><br>$kalori kkal per hari</p>";
        echo "<p><label>Kebutuhan Protein:</label><br>$protein gram per hari</p>";
        echo "<p><label>Kebutuhan Lemak:</label><br>$lemak gram per hari</p>";
        echo "<p><label>Kebutuhan Serat:</label><br>$serat gram per hari</p>";
        echo "</div>";
    } else {
        echo "<div class='no-data'><span>Data anak tidak ditemukan.</span></div>";
    }
} else {
    echo "<div class='no-data'><span>ID anak tidak valid.</span></div>";
}

// Tutup koneksi
mysqli_close($conn);
?>
