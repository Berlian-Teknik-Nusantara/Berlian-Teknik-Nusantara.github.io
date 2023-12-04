<?php
session_start();
include('koneksi.php');

// Inisialisasi variabel status dan pesan
$status = '';
$pesan = '';

// Proses formulir hanya ketika ada pengiriman POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan filter input
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

    // Cek keberadaan pengguna dalam database
    if ($username == 'admin' && $password == 'sukses2023') {
        $_SESSION['admin'] = true;
        $status = 'success';
        $pesan = 'Login berhasil.';
        header("Location: admin.php");
        exit;
    } else {
        $status = 'error';
        $pesan = 'Username atau password salah.';
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 16px;
            color: red;
        }

        @media only screen and (max-width: 600px) {
            form {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <form method="post" action="login.php">
        <h2>Login</h2>
        <?php
            if ($status == 'error') {
                echo '<div class="message">' . $pesan . '</div>';
            } elseif ($status == 'success') {
                echo '<div class="message" style="color: green;">' . $pesan . '</div>';
                
            }
        ?>
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</body>
</html>
