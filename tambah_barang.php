<?php 
include 'config.php';
cek_akses('admin'); // Memastikan hanya admin yang bisa menambah barang
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Toko Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card-add { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .preview-img { width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 2px dashed #ddd; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-add">
                <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Tambah Stok Sembako</h5>
                    <a href="admin_dashboard.php" class="btn btn-sm btn-light">Kembali</a>
                </div>
                <div class="card-body p-4">
                    <form action="proses_tambah_barang.php" method="POST" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <img src="https://via.placeholder.com/150?text=Pilih+Foto" id="load_gambar" class="preview-img mb-2">
                            <div class="mt-2">
                                <label for="foto" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-camera me-1"></i> Pilih Foto Barang
                                </label>
                                <input type="file" name="gambar" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <small class="text-muted">Format: JPG, PNG, WebP (Maks 2MB)</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Gula Pasir 1kg" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis/Kategori</label>
                                <select name="jenis_barang" class="form-select">
                                    <option value="Sembako">Sembako</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Bumbu Dapur">Bumbu Dapur</option>
                                    <option value="Sabun & Deterjen">Sabun & Deterjen</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" name="stok" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control" placeholder="Kg / Pcs / Liter">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat (Opsional)</label>
                            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan tambahan barang..."></textarea>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Fungsi untuk melihat preview gambar sebelum diunggah

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('load_gambar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>