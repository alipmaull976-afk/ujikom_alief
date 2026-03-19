<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Proses Upload Gambar
    $fitur_gambar = $_FILES['gambar']['name'];
    if ($fitur_gambar != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
        $x = explode('.', $fitur_gambar);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambar']['tmp_name'];
        
        // Memberi nama unik (contoh: 171500123_beras.jpg)
        $nama_gambar_baru = time() . '_' . $fitur_gambar;

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            // PINDAHKAN KE FOLDER YANG KAMU BUAT
            move_uploaded_file($file_tmp, 'assets/img/barang/' . $nama_gambar_baru);
        } else {
            echo "<script>alert('Ekstensi tidak diperbolehkan!'); window.location='tambah_barang.php';</script>";
            exit;
        }
    } else {
        $nama_gambar_baru = "default.png"; // Jika tidak upload, pakai gambar default
    }

    $query = "INSERT INTO barang (nama_barang, harga, stok, deskripsi, gambar) 
              VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$nama_gambar_baru')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang Berhasil Ditambah!'); window.location='daftar_barang.php';</script>";
    }
}
?>