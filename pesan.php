<?php
include "koneksi.php";

// Inisialisasi variabel status dan pesan
$status = '';
$pesan = '';

// Proses formulir hanya ketika ada pengiriman POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan filter input
    $nama = filter_var($_POST["nama"], FILTER_SANITIZE_STRING);
    $no_wa = filter_var($_POST["no_wa"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $pesan = htmlspecialchars($_POST["pesan"], ENT_QUOTES, 'UTF-8');

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = 'error';
        $pesan = 'Email tidak valid.';
    } else {
        // Simpan pesan ke database
        $query = "INSERT INTO pesan_kontak (nama, no_wa, email, pesan) VALUES ('$nama', '$no_wa', '$email', '$pesan')";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            $status = 'success';
            $pesan = 'Pesan berhasil disimpan.';
        } else {
            $status = 'error';
            $pesan = 'Terjadi kesalahan saat menyimpan pesan.';
        }
    }

    
}
?>
