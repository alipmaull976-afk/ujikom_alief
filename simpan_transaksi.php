<?php
include 'config.php';
$id_user = $_SESSION['id_user'];
$total   = $_POST['total'];
$bayar   = $_POST['uang_bayar'];
$tgl     = date('Y-m-d H:i:s');

if ($bayar < $total) {
    echo "<script>alert('Uang Kurang!'); window.history.back();</script>";
    exit;
}

// 1. Simpan ke Header Transaksi
mysqli_query($conn, "INSERT INTO transaksi (id_user, tanggal, total, status) VALUES ('$id_user', '$tgl', '$total', 'selesai')");
$id_t = mysqli_insert_id($conn);

// 2. Pindahkan dari Keranjang ke Detail & Potong Stok
$cart = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user = '$id_user'");
while($c = mysqli_fetch_assoc($cart)) {
    $id_b = $c['id_barang'];
    $jml  = $c['jumlah'];
    $sub  = $c['subtotal'];

    mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, subtotal) VALUES ('$id_t', '$id_b', '$jml', '$sub')");
    mysqli_query($conn, "UPDATE barang SET stok = stok - $jml WHERE id_barang = '$id_b'");
}

// 3. Kosongkan Keranjang
mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = '$id_user'");

header("Location: cetak_nota.php?id=$id_t&bayar=$bayar");
?>