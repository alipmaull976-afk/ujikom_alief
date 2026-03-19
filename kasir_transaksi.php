<?php include 'config.php'; cek_akses('kasir'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir Sembako - Toko Sembako Alief</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white">Tambah ke Daftar</div>
                <div class="card-body">
                    <form action="keranjang_tambah.php" method="POST">
                        <div class="mb-3">
                            <label>Barang</label>
                            <select name="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php 
                                $brg = mysqli_query($conn, "SELECT * FROM barang WHERE stok > 0");
                                while($b = mysqli_fetch_assoc($brg)) echo "<option value='{$b['id_barang']}'>{$b['nama_barang']} (".rupiah($b['harga']).")</option>";
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Tambah ke Daftar</button>
                    </form>
                </div>
            </div>
            <a href="kasir_dashboard.php" class="btn btn-secondary w-100">Kembali ke Dashboard</a>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $id_u = $_SESSION['id_user'];
                            $total = 0;
                            $q = mysqli_query($conn, "SELECT k.*, b.nama_barang, b.harga FROM keranjang k JOIN barang b ON k.id_barang = b.id_barang WHERE k.id_user = '$id_u'");
                            if(mysqli_num_rows($q) > 0) {
                                while($c = mysqli_fetch_assoc($q)): 
                                    $total += $c['subtotal'];
                            ?>
                            <tr>
                                <td><?= $c['nama_barang'] ?></td>
                                <td><?= number_format($c['harga']) ?></td>
                                <td><?= $c['jumlah'] ?></td>
                                <td><?= number_format($c['subtotal']) ?></td>
                                <td><a href="keranjang_hapus.php?id=<?= $c['id_keranjang'] ?>" class="text-danger"><i class="fas fa-trash"></i></a></td>
                            </tr>
                            <?php endwhile; } else { echo "<tr><td colspan='5' class='text-center'>Belum ada barang</td></tr>"; } ?>
                        </tbody>
                    </table>
                    
                    <hr>
                    <div class="bg-dark text-success p-3 rounded mb-3 text-end">
                        <h2 class="mb-0">TOTAL: <?= rupiah($total) ?></h2>
                    </div>

                    <form action="simpan_transaksi.php" method="POST">
                        <input type="hidden" name="total" id="total_belanja" value="<?= $total ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Uang Bayar</label>
                                <input type="number" name="uang_bayar" id="uang_bayar" class="form-control form-control-lg" oninput="hitungKembali()" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kembalian</label>
                                <input type="text" id="kembalian" class="form-control form-control-lg bg-light text-danger fw-bold" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" <?= $total == 0 ? 'disabled' : '' ?>>PROSES PEMBAYARAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungKembali() {
    let total = document.getElementById('total_belanja').value;
    let bayar = document.getElementById('uang_bayar').value;
    let kembali = bayar - total;
    if (kembali >= 0) {
        document.getElementById('kembalian').value = "Rp " + kembali.toLocaleString('id-ID');
    } else {
        document.getElementById('kembalian').value = "Uang Kurang";
    }
}
</script>
</body>
</html>