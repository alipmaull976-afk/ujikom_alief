<?php 
include 'config.php'; 
// Akses bisa dibuka untuk admin dan kasir
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

// Jika admin, bisa lihat semua. Jika kasir, hanya lihat milik sendiri.
if ($role == 'admin') {
    $query = "SELECT t.*, u.username FROM transaksi t 
              JOIN user u ON t.id_user = u.id_user 
              ORDER BY t.tanggal DESC";
} else {
    $query = "SELECT t.*, u.username FROM transaksi t 
              JOIN user u ON t.id_user = u.id_user 
              WHERE t.id_user = '$id_user' 
              ORDER BY t.tanggal DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi - Toko Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #212529; color: white; padding-top: 20px; }
        .nav-link { color: rgba(255,255,255,0.8); margin-bottom: 10px; border-radius: 5px; }
        .nav-link:hover, .nav-link.active { background: #0d6efd; color: white; }
        .card { border: none; border-radius: 15px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 d-none d-md-block sidebar">
            <div class="text-center mb-4">
                <i class="fas fa-history fa-3x mb-2"></i>
                <h5 class="fw-bold">RIWAYAT</h5>
            </div>
            <nav class="nav flex-column px-3">
                <a class="nav-link" href="<?= $role ?>_dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
                <?php if($role == 'kasir'): ?>
                    <a class="nav-link" href="kasir_transaksi.php"><i class="fas fa-shopping-cart me-2"></i> Transaksi Baru</a>
                <?php endif; ?>
                <a class="nav-link active" href="riwayat_transaksi.php"><i class="fas fa-list me-2"></i> Daftar Nota</a>
                <hr>
                <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a>
            </nav>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Riwayat Penjualan</h4>
                <button onclick="window.location.reload()" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Nota</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Kasir</th>
                                    <th>Total Bayar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">#TRX-<?= $row['id_transaksi'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                        <td><i class="fas fa-user-circle me-1 text-muted"></i> <?= $row['username'] ?></td>
                                        <td class="fw-bold"><?= rupiah($row['total']) ?></td>
                                        <td class="text-center">
                                            <a href="cetak_nota.php?id=<?= $row['id_transaksi'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-print me-1"></i> Cetak Struk
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data transaksi.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <p class="small text-muted mt-3">
                * Menampilkan riwayat transaksi terbaru berdasarkan tanggal.
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>