<?php
include "../../koneksi.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM kamera WHERE id=$id");
$row = $data->fetch_assoc();

if(isset($_POST['update'])){

$conn->query("UPDATE kamera SET
nama='$_POST[nama]',
merk='$_POST[merk]',
baterai='$_POST[baterai]',
stok='$_POST[stok]',
tipe='$_POST[tipe]',
resolusi='$_POST[resolusi]',
berat='$_POST[berat]',
harga_sewa='$_POST[harga_sewa]'
WHERE id=$id");

header("Location:../list/kamera.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamera - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Kamera</h1>
                    <p class="text-gray-600 mt-1">ID Kamera: #<?= $row['id'] ?></p>
                </div>
                <a href="../list/kamera.php" class="inline-flex items-center px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
            
            <!-- Info Kamera -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                        <i class="fas fa-camera text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></h3>
                        <p class="text-sm text-gray-600">Merk: <?= htmlspecialchars($row['merk']) ?> | Tipe: <?= htmlspecialchars($row['tipe']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-semibold text-gray-800">Form Edit Data Kamera</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi kamera di bawah ini</p>
            </div>

            <!-- Form Content -->
            <form method="POST" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-5">
                        <!-- Nama Kamera -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kamera <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-camera text-gray-400"></i>
                                </div>
                                <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Masukkan nama kamera">
                            </div>
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Merk <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="merk" value="<?= htmlspecialchars($row['merk']) ?>" required
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Contoh: Canon, Nikon, Sony">
                            </div>
                        </div>

                        <!-- Tipe -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Kamera <span class="text-red-500">*</span>
                            </label>
                            <select name="tipe" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white">
                                <option value="DSLR" <?= $row['tipe'] == 'DSLR' ? 'selected' : '' ?>>DSLR</option>
                                <option value="Mirrorless" <?= $row['tipe'] == 'Mirrorless' ? 'selected' : '' ?>>Mirrorless</option>
                                <option value="Point & Shoot" <?= $row['tipe'] == 'Point & Shoot' ? 'selected' : '' ?>>Point & Shoot</option>
                                <option value="Action Camera" <?= $row['tipe'] == 'Action Camera' ? 'selected' : '' ?>>Action Camera</option>
                                <option value="Medium Format" <?= $row['tipe'] == 'Medium Format' ? 'selected' : '' ?>>Medium Format</option>
                            </select>
                        </div>

                        <!-- Resolusi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Resolusi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-expand-arrows-alt text-gray-400"></i>
                                </div>
                                <input type="text" name="resolusi" value="<?= htmlspecialchars($row['resolusi']) ?>" required
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Contoh: 45 MP, 24 MP">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-5">
                        <!-- Baterai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Baterai
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-battery-full text-gray-400"></i>
                                </div>
                                <input type="text" name="baterai" value="<?= htmlspecialchars($row['baterai']) ?>"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Contoh: LP-E6NH">
                            </div>
                        </div>

                        <!-- Stok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-boxes text-gray-400"></i>
                                </div>
                                <input type="number" name="stok" value="<?= htmlspecialchars($row['stok']) ?>" required min="0"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Jumlah stok tersedia">
                            </div>
                        </div>

                        <!-- Berat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Berat (gram)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-weight text-gray-400"></i>
                                </div>
                                <input type="text" name="berat" value="<?= htmlspecialchars($row['berat']) ?>"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Contoh: 650">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">gram</span>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Sewa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Harga Sewa/Hari <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-money-bill-wave text-gray-400"></i>
                                </div>
                                <input type="number" name="harga_sewa" value="<?= htmlspecialchars($row['harga_sewa']) ?>" required min="0"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="Harga sewa per hari">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">/hari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Harga -->
                <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-800">Harga Sewa Saat Ini</p>
                            <p class="text-2xl font-bold text-green-900 mt-1">
                                Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> 
                                <span class="text-sm font-normal text-green-700">/hari</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-green-800">Total Nilai Stok</p>
                            <p class="text-xl font-bold text-green-900 mt-1">
                                Rp <?= number_format($row['harga_sewa'] * $row['stok'], 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="index.php" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                            <i class="fas fa-times mr-2"></i>
                            Batalkan
                        </a>
                        <button type="submit" name="update" 
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Catatan -->
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-400 mt-1"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Penting!</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Pastikan semua data yang diubah sudah benar sebelum disimpan</li>
                            <li>Perubahan akan langsung diterapkan ke sistem</li>
                            <li>Field dengan tanda <span class="text-red-500">*</span> wajib diisi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
            <p>© <?= date('Y') ?> Sistem Manajemen Kamera. Semua hak dilindungi.</p>
        </div>
    </div>

    <script>
        // Format input harga saat blur
        document.querySelector('input[name="harga_sewa"]').addEventListener('blur', function(e) {
            const value = e.target.value;
            if (value) {
                const formatted = new Intl.NumberFormat('id-ID').format(value);
                e.target.value = formatted;
            }
        });

        // Format kembali ke angka saat focus
        document.querySelector('input[name="harga_sewa"]').addEventListener('focus', function(e) {
            let value = e.target.value;
            value = value.replace(/[^0-9]/g, '');
            e.target.value = value;
        });

        // Update preview harga saat input berubah
        document.querySelector('input[name="harga_sewa"]').addEventListener('input', function(e) {
            const harga = parseInt(e.target.value.replace(/[^0-9]/g, '')) || 0;
            const stok = parseInt(document.querySelector('input[name="stok"]').value) || 0;
            
            const hargaPreview = document.querySelector('.text-2xl.font-bold.text-green-900');
            const totalStokPreview = document.querySelector('.text-xl.font-bold.text-green-900');
            
            if (hargaPreview && totalStokPreview) {
                hargaPreview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga) + ' /hari';
                totalStokPreview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga * stok);
            }
        });

        // Update preview total stok saat stok berubah
        document.querySelector('input[name="stok"]').addEventListener('input', function(e) {
            const stok = parseInt(e.target.value) || 0;
            const harga = parseInt(document.querySelector('input[name="harga_sewa"]').value.replace(/[^0-9]/g, '')) || 0;
            
            const totalStokPreview = document.querySelector('.text-xl.font-bold.text-green-900');
            if (totalStokPreview) {
                totalStokPreview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga * stok);
            }
        });

        // Confirmation sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Simpan perubahan data kamera?')) {
                e.preventDefault();
            }
        });

        // Auto focus ke field pertama
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="nama"]').focus();
        });
    </script>
</body>
</html>