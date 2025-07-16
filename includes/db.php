<?php
$host = "localhost";       // Ganti jika Anda pakai hosting eksternal
$username = "root";        // Username default XAMPP / local server
$password = "";            // Kosong jika pakai XAMPP, isi jika ada password MySQL
$database = "surya_enrollment";  // Nama database yang Anda buat di MySQL

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
