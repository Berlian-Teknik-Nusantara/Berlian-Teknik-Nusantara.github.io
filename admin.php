<?php
session_start();

include('koneksi.php');

// Memeriksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Menghapus data jika tombol "Hapus" diklik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = mysqli_real_escape_string($koneksi, $_POST['delete_id']);
    $deleteQuery = "DELETE FROM pesan_kontak WHERE id = '$deleteId'";

    if ($koneksi->query($deleteQuery) === TRUE) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}

// Mengambil data pesanan dari database setelah penghapusan (jika ada)
$sql = "SELECT * FROM pesan_kontak";
$result = $koneksi->query($sql);

// Menutup koneksi
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f2f2f2;
        }

        h1 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
            text-align: left;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        a {
            margin-top: 20px;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border-radius: 5px;
        }

        a:hover {
            background-color: #555;
        }
    </style>
    <title>Halaman Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>

<body>

    <h1>Data Pesan</h1>

    <?php
    // Menampilkan data pesanan
    if ($result->num_rows > 0) {
        echo "<table id='datapesanan' border='1'>
                <tr>
                    <th>Nama</th>
                    <th>No Whatsapp</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Waktu</th>
                    <th>Hapus Pesan</th> <!-- Kolom tambahan untuk tombol hapus -->
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["nama"] . "</td>
                    <td>" . $row["no_wa"] . "</td>
                    <td>" . $row["email"] . "</td>
                    <td>" . $row["pesan"] . "</td>
                    <td>" . $row["waktu_kirim"] . "</td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                            <button type='submit' style='background-color:tranparant; border:none; border-radius:5px; color:red; cursor:pointer;'>Hapus</button>
                        </form>
                    </td>
                </tr>";
        }

        echo "</table>";

        // Menampilkan tombol ekspor
        echo '<br>';
        ?>
        <button type="button" onclick="exportToExcel('datapesanan')">Export To Excel</button>
        <?php
    } else {
        echo "Tidak ada data pesanan.";
    }
    ?>

    <br>
    <a href="logout.php">Logout</a> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script>
        function exportToExcel(tableId) {
            const table = document.getElementById(tableId);

            
            const wb = XLSX.utils.table_to_book(table, { sheet: 'Sheet1', raw: true });

            
            const ws = wb.Sheets[wb.SheetNames[0]];

            
            ws['!cols'] = [
                { wch: 15 }, 
                { wch: 20 }, 
                { wch: 15 }, 
                { wch: 15 }, 
                { wch: 20 }, 
                { wch: 20 }, 
                { wch: 15 },
            ];

            
            const style = {
                font: { bold: true },
                fill: { fgColor: { rgb: 'FF0000' } },
                alignment: { horizontal: 'center' },
                border: {
                    top: { style: 'thin', color: { rgb: '000000' } },
                    bottom: { style: 'thin', color: { rgb: '000000' } },
                    left: { style: 'thin', color: { rgb: '000000' } },
                    right: { style: 'thin', color: { rgb: '000000' } },
                },
            };

            Object.keys(ws).filter(cell => cell !== '!ref').forEach(cell => ws[cell].s = style);

            
            XLSX.writeFile(wb, 'exported_data.xlsx');
        }
    </script>

</body>

</html>
