<?php 
include 'config.php';
cek_akses('admin');

// Fitur Pencarian (Opsional)
$keyword = "";
$query_sql = "SELECT * FROM barang";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
    $query_sql = "SELECT * FROM barang WHERE nama_barang LIKE '%$keyword%' OR jenis_barang LIKE '%$keyword%'";
}

$result = mysqli_query($conn, $query_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Barang - Toko Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .table-container { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .img-barang { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }
        .badge-stok { font-size: 0.85rem; padding: 5px 10px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 text-primary"><i class="fas fa-boxes me-2"></i> Stok Barang</h3>
            <p class="text-muted">Kelola semua ketersediaan produk sembako Anda.</p>
        </div>
        <div>
            <a href="admin_dashboard.php" class="btn btn-outline-secondary me-2">Dashboard</a>
            <a href="tambah_barang.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Barang</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <form action="" method="POST" class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Cari nama barang..." value="<?= $keyword; ?>">
                <button class="btn btn-dark" type="submit" name="cari"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) :
                        while($row = mysqli_fetch_assoc($result)) : 
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <?php 
                            $foto = (!empty($row['gambar'])) ? 'assets/img/barang/'.$row['gambar'] : 'https://via.placeholder.com/50?text=No+Img';
                            ?>
                            <img src="<?= $foto; ?>" class="img-barang" alt="foto">
                        </td>
                        <td>
                            <strong><?= $row['nama_barang']; ?></strong><br>
                            <small class="text-muted"><?= $row['deskripsi']; ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= $row['jenis_barang'] ?: '-'; ?></span></td>
                        <td><?= rupiah($row['harga']); ?></td>
                        <td>
                            <?php if($row['stok'] <= 5): ?>
                                <span class="badge bg-danger badge-stok">Sisa <?= $row['stok']; ?> <?= $row['satuan']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-success badge-stok"><?= $row['stok']; ?> <?= $row['satuan']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="edit_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus barang ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data barang.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>