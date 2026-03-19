<?php
include 'config.php';
$id = $_GET['id'];

// Ambil data transaksi & nama kasir
$head = mysqli_query($conn, "SELECT t.*, u.username FROM transaksi t JOIN user u ON t.id_user = u.id_user WHERE t.id_transaksi = '$id'");
$d = mysqli_fetch_assoc($head);

// Ambil rincian barang (JOIN tabel detail_transaksi dan barang)
$items = mysqli_query($conn, "SELECT dt.*, b.nama_barang, b.harga FROM detail_transaksi dt JOIN barang b ON dt.id_barang = b.id_barang WHERE dt.id_transaksi = '$id'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nota #<?= $id; ?></title>
    <style>
        body { font-family: 'Courier New', monospace; width: 220px; padding: 10px; font-size: 12px; line-height: 1.4; }
        .center { text-align: center; }
        .right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <strong>TOKO SEMBAKO ALIEF</strong><br>
        <small>Nota: #<?= $id; ?></small><br>
        <small><?= date('d/m/Y H:i', strtotime($d['tanggal'])); ?></small>
    </div>
    
    <div class="line"></div>
    
    <table>
        <?php while($row = mysqli_fetch_assoc($items)): ?>
        <tr>
            <td colspan="2"><?= strtoupper($row['nama_barang']); ?></td>
        </tr>
        <tr>
            <td><?= $row['jumlah']; ?> x <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td class="right"><?= number_format($row['subtotal'], 0, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <div class="line"></div>
    
    <table>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="right"><strong><?= rupiah($d['total']); ?></strong></td>
        </tr>
    </table>
    
    <div class="line"></div>
    <div class="center" style="margin-top:10px">
        KASIR: <?= strtoupper($d['username']); ?><br>
        *** TERIMA KASIH ***
    </div>

    <div style="margin-top: 20px;" class="center">
        <button onclick="window.location.href='kasir_transaksi.php'" style="cursor:pointer; padding: 5px 10px;">KEMBALI KE KASIR</button>
    </div>
    
    <style>
        @media print { button { display: none; } }
    </style>
</body>
</html>