<?php
include 'config.php';
cek_akses('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id        = $_POST['id_barang'];
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis     = mysqli_real_escape_string($conn, $_POST['jenis_barang']);
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $satuan    = mysqli_real_escape_string($conn, $_POST['satuan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query = "UPDATE barang SET 
              nama_barang = '$nama', 
              jenis_barang = '$jenis', 
              harga = '$harga', 
              stok = '$stok', 
              satuan = '$satuan', 
              deskripsi = '$deskripsi' 
              WHERE id_barang = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='daftar_barang.php';</script>";
    } else {
        echo "Gagal Update: " . mysqli_error($conn);
    }
}
?>