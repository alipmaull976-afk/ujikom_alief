<?php 
include 'config.php';
cek_akses('admin');

// Ambil ID dari URL
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika ID tidak ditemukan
if (!$data) {
    header("Location: daftar_barang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang - Toko Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Data Barang</h5>
                </div>
                <div class="card-body p-4">
                    <form action="proses_edit_barang.php" method="POST">
                        <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">

                        <div class="mb-3">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="<?= $data['nama_barang']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Barang</label>
                            <input type="text" name="jenis_barang" class="form-control" value="<?= $data['jenis_barang']; ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Harga Jual</label>
                                <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Stok</label>
                                <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="<?= $data['satuan']; ?>" placeholder="Kg/Pcs">
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= $data['deskripsi']; ?></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                            <a href="daftar_barang.php" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>