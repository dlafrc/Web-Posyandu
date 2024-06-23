<?php
session_start();
require 'koneksi.php';

$id_anak = isset($_GET['id_anak']) ? htmlspecialchars($_GET['id_anak']) : '';
$id_posyandu = $_SESSION['id_posyandu']; // Assuming id_posyandu is stored in the session on login

// Ensure id_anak is not empty before proceeding
if (!empty($id_anak)) {
    // Code to display the form or process immunization data based on id_anak
    // echo "ID Anak: " . $id_anak;

    // ... (other code to display/process immunization data)
} else {
    echo "ID Anak tidak ditemukan!";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedDate = $_POST['tanggal'];
    $selectedVaksin = $_POST['vaksin'];

    // Update the vaksin_anak table
    $updateQuery = "
        UPDATE vaksin_anak 
        SET id_posyandu = '$id_posyandu', status = 'sudah' 
        WHERE id_anak = '$id_anak' 
        AND id_jenis = '$selectedVaksin'
    ";

    if (mysqli_query($conn, $updateQuery)) {
        echo "Data updated successfully!";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
