<?php
session_start();
session_destroy();
header("Location: login.php"); // Sesuaikan dengan halaman utama Anda
exit;
?>
