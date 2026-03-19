<?php 
include 'config.php'; cek_akses('admin');
$tgl_mulai = $_GET['tgl_mulai'] ?? date('Y-m-d');
$tgl_selesai = $_GET['tgl_selesai'] ?? date('Y-m-d');

$q = mysqli_query($conn, "SELECT t.*, u.username FROM transaksi t JOIN user u ON t.id_user = u.id_user WHERE DATE(t.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_selesai' ORDER BY t.tanggal DESC");
$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as sm FROM transaksi WHERE DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_selesai'"))['sm'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Keuangan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Laporan Penjualan</h4>
            <a href="admin_dashboard.php" class="btn btn-sm btn-secondary">Tutup</a>
        </div>
        
        <form class="row g-3 mb-4">
            <div class="col-md-4"><input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>"></div>
            <div class="col-md-4"><input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>"></div>
            <div class="col-md-4"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
        </form>

        <div class="alert alert-success">Total Pemasukan Periode Ini: <strong><?= rupiah($total_masuk) ?></strong></div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>ID Nota</th><th>Tanggal</th><th>Kasir</th><th>Total Bayar</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td>#TRX-<?= $r['id_transaksi'] ?></td>
                    <td><?= $r['tanggal'] ?></td>
                    <td><?= $r['username'] ?></td>
                    <td><?= rupiah($r['total']) ?></td>
                    <td><a href="cetak_nota.php?id=<?= $r['id_transaksi'] ?>" target="_blank" class="btn btn-sm btn-info">Struk</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>