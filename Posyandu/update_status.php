<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jenis = $_POST['id_jenis'];
    $status = $_POST['status'];

    // Update the status in the vaksinasi table
    $updateQuery = "UPDATE vaksinasi SET status = '$status' WHERE id_jenis = '$id_jenis'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "Status updated successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
