<?php 
include 'config.php';
cek_akses('admin'); 

// 1. QUERY STATISTIK UTAMA
$count_barang    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM barang"))['total'];
$transaksi_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal) = CURDATE()"))['total'];
$omzet_today     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as total FROM transaksi WHERE DATE(tanggal) = CURDATE()"))['total'];

// 2. QUERY GRAFIK 7 HARI TERAKHIR
$sql_grafik = "SELECT DATE_FORMAT(tanggal, '%d %b') as tgl, SUM(total) as omzet 
               FROM transaksi 
               WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
               GROUP BY DATE(tanggal) 
               ORDER BY tanggal ASC";
$res_grafik = mysqli_query($conn, $sql_grafik);
$labels = []; $data_omzet = [];
while($r = mysqli_fetch_assoc($res_grafik)){ 
    $labels[] = $r['tgl']; 
    $data_omzet[] = $r['omzet']; 
}

// 3. QUERY STOK MENIPIS (Kritis jika <= 10)
$sql_stok_low = "SELECT nama_barang, stok FROM barang WHERE stok <= 10 ORDER BY stok ASC";
$res_stok_low = mysqli_query($conn, $sql_stok_low);
$jumlah_kritis = mysqli_num_rows($res_stok_low);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            body { 
        background-color: #f8f9fa; 
        /* Urutan font: Apple System -> Inter -> Sans Serif */
        font-family: -apple-system, BlinkMacSystemFont, "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased; /* Biar font lebih halus di layar */
        letter-spacing: -0.022em; /* Khas tipografi Apple yang agak rapat */
    }

    /* Membuat Sidebar & Judul lebih tegas ala iOS */
    .sidebar h5, h4, h2, .nav-link { 
        font-weight: 600; 
        letter-spacing: -0.03em;
    }
        .sidebar { min-height: 100vh; background: #212529; color: white; padding-top: 20px; }
        .nav-link { color: rgba(255,255,255,0.8); margin-bottom: 5px; border-radius: 8px; padding: 10px 20px; }
        .nav-link:hover, .nav-link.active { background: #0d6efd; color: white; }
        .badge-mode { background-color: #198754; color: white; font-size: 0.7rem; padding: 3px 10px; border-radius: 20px; font-weight: bold; display: inline-block; margin-top: 5px; }
        .welcome-banner { 
            background: #0099ff; color: white; border-radius: 15px; padding: 40px; 
            position: relative; overflow: hidden; box-shadow: 0 4px 15px rgba(0,153,255,0.3);
        }
        .banner-icon { position: absolute; right: 20px; bottom: -10px; font-size: 8rem; opacity: 0.2; }
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
       <div class="col-md-2 d-none d-md-block sidebar text-center shadow">
    <div class="mb-4">
        <i class="fas fa-store fa-3x mb-2 text-white"></i>
        <h5 class="fw-bold d-block mb-0">SEMBAKO ALIEF</h5>
        <span class="badge-mode text-uppercase">Admin Mode</span>
    </div>
            <nav class="nav flex-column text-start px-2">
                <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-th-large me-2"></i> Dashboard</a>
                <a class="nav-link" href="tambah_barang.php"><i class="fas fa-box-open me-2"></i> Tambah Barang</a>
                <a class="nav-link" href="daftar_barang.php"><i class="fas fa-list me-2"></i> Daftar Barang</a>
                <a class="nav-link" href="admin_riwayat.php"><i class="fas fa-file-invoice-dollar me-2"></i> Laporan Keuangan</a>
                <hr class="bg-secondary">
                <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a>
            </nav>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <h4 class="fw-bold text-dark">Ringkasan Bisnis</h4>
                <div class="text-end">
                    <small class="text-muted d-block"><?= date('l, d F Y') ?></small>
                    <span class="fw-bold text-primary">Petugas: <?= $_SESSION['username'] ?></span>
                </div>
            </div>

            <div class="welcome-banner mb-4">
                <h1 class="fw-bold">Selamat Bekerja, <?= $_SESSION['username'] ?> !</h1>
                <p class="fs-5 mb-0">Pantau pergerakan stok dan performa toko Anda secara real-time.</p>
                <i class="fas fa-user-shield banner-icon"></i>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white p-3 rounded-circle me-3"><i class="fas fa-boxes fa-2x"></i></div>
                            <div><small class="text-muted">Total Produk</small><h2 class="fw-bold mb-0"><?= $count_barang ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white p-3 rounded-circle me-3"><i class="fas fa-shopping-cart fa-2x"></i></div>
                            <div><small class="text-muted">Transaksi Hari Ini</small><h2 class="fw-bold mb-0"><?= $transaksi_today ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card p-4 text-nowrap">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-dark p-3 rounded-circle me-3"><i class="fas fa-wallet fa-2x"></i></div>
                            <div><small class="text-muted">Omzet Hari Ini</small><h4 class="fw-bold mb-0"><?= rupiah($omzet_today ?? 0) ?></h4></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stat-card p-4 mb-4">
                <h5 class="fw-bold text-muted mb-4"><i class="fas fa-chart-line me-2 text-primary"></i>Grafik Penjualan 7 Hari Terakhir</h5>
                <canvas id="salesChart" height="80"></canvas>
            </div>

            <div class="card stat-card border-0 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-danger mb-0"><i class="fas fa-exclamation-circle me-2"></i>Stok Hampir Habis</h5>
                    <a href="daftar_barang.php" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Produk</th><th>Sisa</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php if($jumlah_kritis > 0): ?>
                                <?php while($s = mysqli_fetch_assoc($res_stok_low)): ?>
                                <tr>
                                    <td><?= $s['nama_barang'] ?></td>
                                    <td class="text-danger fw-bold"><?= $s['stok'] ?></td>
                                    <td><span class="badge bg-danger">Restock Segera!</span></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">Semua stok aman.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Omzet',
                data: <?= json_encode($data_omzet); ?>,
                borderColor: '#0099ff',
                backgroundColor: 'rgba(0, 153, 255, 0.1)',
                fill: true, tension: 0.4, borderWidth: 4
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>

</body>
</html>