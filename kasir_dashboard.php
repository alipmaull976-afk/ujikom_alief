<?php 
include 'config.php'; 
cek_akses('kasir'); 

// Mengambil statistik ringkas untuk kasir
$id_user = $_SESSION['id_user'];
$hari_ini = date('Y-m-d');

// 1. Total transaksi kasir ini hari ini
$q_transaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE id_user = '$id_user' AND DATE(tanggal) = '$hari_ini'");
$row_t = mysqli_fetch_assoc($q_transaksi);

// 2. Total uang yang dikumpulkan kasir ini hari ini
$q_uang = mysqli_query($conn, "SELECT SUM(total) as total_uang FROM transaksi WHERE id_user = '$id_user' AND DATE(tanggal) = '$hari_ini'");
$row_u = mysqli_fetch_assoc($q_uang);
$omzet = $row_u['total_uang'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Dashboard - Toko Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-color: #0d6efd; --secondary-color: #6c757d; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background: #212529; color: white; padding-top: 20px; }
        .nav-link { color: rgba(255,255,255,0.8); margin-bottom: 10px; border-radius: 5px; }
        .nav-link:hover, .nav-link.active { background: var(--primary-color); color: white; }
        .stat-card { border: none; border-radius: 15px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .welcome-banner { background: linear-gradient(45deg, #0d6efd, #00d4ff); color: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 d-none d-md-block sidebar">
            <div class="text-center mb-4">
                <i class="fas fa-store fa-3x mb-2"></i>
                <h5 class="fw-bold">SEMBAKO ALIEF</h5>
                <span class="badge bg-success">KASIR MODE</span>
            </div>
            <nav class="nav flex-column px-3">
                <a class="nav-link active" href="kasir_dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
                <a class="nav-link" href="kasir_transaksi.php"><i class="fas fa-shopping-cart me-2"></i> Transaksi Baru</a>
                <a class="nav-link" href="riwayat_transaksi.php"><i class="fas fa-history me-2"></i> Riwayat Nota</a>
                <hr>
                <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a>
            </nav>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-secondary">Ringkasan Kerja</h4>
                <div class="text-end">
                    <small class="text-muted d-block"><?= date('l, d F Y') ?></small>
                    <span class="fw-bold text-primary">Petugas: <?= $_SESSION['username'] ?></span>
                </div>
            </div>

            <div class="welcome-banner shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold">Selamat Bekerja, <?= $_SESSION['username'] ?>!</h2>
                        <p class="mb-0">Pastikan pelayanan ramah dan stok barang selalu dicek sebelum transaksi.</p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="fas fa-user-clock fa-5x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white p-3 rounded-circle me-3">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                            <div>
                                <small class="text-muted">Transaksi Hari Ini</small>
                                <h3 class="fw-bold mb-0"><?= $row_t['total'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white p-3 rounded-circle me-3">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                            <div>
                                <small class="text-muted">Omzet Saya (Hari Ini)</small>
                                <h3 class="fw-bold mb-0"><?= number_format($omzet, 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm p-3 bg-warning text-dark border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="fw-bold mb-1">Layar Kasir</h5>
                                <p class="small mb-0">Klik untuk melayani pembeli</p>
                            </div>
                            <a href="kasir_transaksi.php" class="btn btn-dark btn-sm">Buka <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Info Stok Hampir Habis</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th>Sisa Stok</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $stok_tipis = mysqli_query($conn, "SELECT * FROM barang WHERE stok <= 5 LIMIT 5");
                                if(mysqli_num_rows($stok_tipis) > 0) {
                                    while($s = mysqli_fetch_assoc($stok_tipis)):
                                ?>
                                <tr>
                                    <td class="fw-bold"><?= $s['nama_barang'] ?></td>
                                    <td><span class="badge bg-info text-dark"><?= $s['jenis_barang'] ?></span></td>
                                    <td class="text-danger fw-bold"><?= $s['stok'] ?> <?= $s['satuan'] ?></td>
                                    <td><?= rupiah($s['harga']) ?></td>
                                </tr>
                                <?php endwhile; } else { ?>
                                <tr><td colspan="4" class="text-center text-muted">Semua stok masih aman.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>