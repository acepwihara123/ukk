<?php
include "../../koneksi.php";

if(isset($_POST['simpan'])){

    // ambil data form
    $nama_kit = $_POST['nama_kit'];
    $merk = $_POST['merk'];
    $isi_kit = $_POST['isi_kit'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];
    
    // Hapus titik dan karakter non-numeric dari harga
    $harga_raw = $_POST['harga_sewa'];
    $harga = preg_replace('/[^0-9]/', '', $harga_raw); // Hanya ambil angka

    // upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // rename biar unik
    $namaGambar = time() . "_" . $gambar;

    move_uploaded_file($tmp, "../uploads/" . $namaGambar);

    // simpan ke database
    $conn->query("INSERT INTO kit_kamera 
    VALUES(NULL,
    '$nama_kit',
    '$merk',
    '$isi_kit',
    '$harga',
    '$stok',
    '$deskripsi',
    '$namaGambar',
    '$status',
    NOW()
    )");

    header("Location:../list/kit_kamera.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kit Kamera - Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        info: '#06b6d4',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        
        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Custom scrollbar */
        .content-scrollable::-webkit-scrollbar {
            width: 8px;
        }
        
        .content-scrollable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .content-scrollable::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .content-scrollable::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Untuk konten utama yang discroll */
        .content-main {
            height: calc(100vh - 80px);
            overflow-y: auto;
        }
        
        /* Sidebar mobile penuh layar */
        .mobile-sidebar-full {
            height: 100vh;
            overflow-y: auto;
        }
        
        /* Hover effects */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm flex-shrink-0">
            <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-box-open text-2xl text-primary"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Kit Kamera</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 text-xl cursor-pointer hover:text-primary transition"></i>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">AK</div>
                        <div>
                            <p class="font-medium text-gray-800">Admin User</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <a href="login/logout.php" class="ml-2 text-red-500 hover:text-red-600">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Kontainer utama dengan sidebar dan konten -->
        <div class="flex flex-1 overflow-hidden">
            <!-- SIDEBAR -->
            <aside class="w-64 bg-white shadow-sm p-6 hidden md:block flex-shrink-0" style="height: calc(100vh - 80px);">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="../dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="../list/kamera.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                                <i class="fas fa-camera"></i>
                                <span>Kamera</span>
                            </a>
                        </li>
                        <li>
                            <a href="../list/kit_kamera.php" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                                <i class="fas fa-box-open"></i>
                                <span>Kit Kamera</span>
                            </a>
                        </li>
                        <li>
                            <a href="../verifikasi/pemesanan.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Transaksi</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <main class="flex-1 p-6 content-main content-scrollable fade-in">
                <!-- Header Konten -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Tambah Kit Kamera Baru</h2>
                            <p class="text-gray-600 mt-1">Isi formulir di bawah untuk menambahkan kit kamera baru</p>
                        </div>
                        <a href="../list/kit_kamera.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
                
                <!-- Form Tambah Kit Kamera -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6" id="kitForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kolom Kiri -->
                            <div class="space-y-4">
                                <!-- Nama Kit -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Nama Kit <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_kit" required 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                                           placeholder="Contoh: Wedding Photography Kit">
                                </div>
                                
                                <!-- Merk -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Merk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="merk" required 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                                           placeholder="Contoh: Canon, Nikon, Sony">
                                </div>
                                
                                <!-- Isi Kit -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Isi Kit <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="isi_kit" required rows="4"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                                           placeholder="Contoh: 1 Kamera DSLR, 1 Lensa 24-70mm, 1 Battery, 1 Charger, 1 Tas Kamera"></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma atau baris baru</p>
                                </div>
                                
                                <!-- Deskripsi -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Deskripsi
                                    </label>
                                    <textarea name="deskripsi" rows="3"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                                           placeholder="Deskripsi lengkap tentang kit ini..."></textarea>
                                </div>
                            </div>
                            
                            <!-- Kolom Kanan -->
                            <div class="space-y-4">
                                <!-- Stok -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Stok <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="stok" required min="0"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                                           placeholder="0">
                                </div>
                                
                                <!-- Harga Sewa -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Harga Sewa per Hari <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500">Rp</span>
                                        </div>
                                        <input type="text" name="harga_sewa" required 
                                               id="harga_sewa"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                                               placeholder="150.000">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Masukkan angka (contoh: 150000 atau 150.000)</p>
                                </div>
                                
                                <!-- Status -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-3">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center space-x-2 border rounded-lg p-3 cursor-pointer hover:border-primary transition">
                                            <input type="radio" name="status" value="aktif" checked class="w-4 h-4 text-primary">
                                            <span class="text-gray-700">✅ Aktif</span>
                                        </label>
                                        <label class="flex items-center space-x-2 border rounded-lg p-3 cursor-pointer hover:border-primary transition">
                                            <input type="radio" name="status" value="nonaktif" class="w-4 h-4 text-primary">
                                            <span class="text-gray-700">❌ Nonaktif</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Gambar -->
                        <div class="border-t border-gray-200 pt-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2">
                                Gambar Kit <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                                <div class="space-y-1 text-center">
                                    <div class="mx-auto h-12 w-12 text-gray-400">
                                        <i class="fas fa-box-open text-3xl"></i>
                                    </div>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-secondary focus-within:outline-none">
                                            <span>Upload gambar</span>
                                            <input id="file-upload" name="gambar" type="file" required 
                                                   class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, JPEG maksimal 5MB
                                    </p>
                                    <div id="file-preview" class="mt-3 hidden">
                                        <img id="preview-img" src="#" alt="Preview" class="mx-auto h-24 w-24 object-cover rounded-lg">
                                    </div>
                                    <p id="file-name" class="text-sm text-primary font-medium mt-2"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="../list/kit_kamera.php" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" name="simpan" 
                                    class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition hover-lift">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Kit Kamera
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Informasi -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Kit kamera adalah paket yang berisi beberapa item (kamera, lensa, aksesori)</li>
                                    <li>Pastikan data yang dimasukkan sudah benar sebelum disimpan</li>
                                    <li>Gambar akan otomatis di-rename untuk menghindari duplikasi</li>
                                    <li>Semua field yang ditandai dengan <span class="text-red-500">*</span> wajib diisi</li>
                                    <li>Harga sewa bisa menggunakan format dengan atau tanpa titik (contoh: 150000 atau 150.000)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
                    <p>© <?= date('Y') ?> Dashboard Kit Kamera. Sistem Manajemen Penyewaan Kit Kamera.</p>
                </footer>
            </main>
        </div>
    </div>
    
    <!-- Mobile Menu Button -->
    <div class="md:hidden fixed bottom-6 right-6 z-10">
        <button id="mobileMenuBtn" class="h-14 w-14 rounded-full bg-primary text-white shadow-lg flex items-center justify-center hover-lift">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
    
    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden">
        <div class="absolute right-0 top-0 h-full w-64 bg-white shadow-lg p-6 mobile-sidebar-full">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-gray-800">Menu</h2>
                <button id="closeMobileMenu" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="../dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../list/kamera.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-camera"></i>
                            <span>Kamera</span>
                        </a>
                    </li>
                    <li>
                        <a href="../list/kit_kamera.php" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                            <i class="fas fa-box-open"></i>
                            <span>Kit Kamera</span>
                        </a>
                    </li>
                    <li>
                        <a href="../verifikasi/pemesanan.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <script>
        // Mobile menu functionality
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileSidebar').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        
        document.getElementById('closeMobileMenu').addEventListener('click', function() {
            document.getElementById('mobileSidebar').classList.add('hidden');
            document.body.style.overflow = '';
        });
        
        // Close mobile sidebar when clicking outside
        document.getElementById('mobileSidebar').addEventListener('click', function(e) {
            if (e.target.id === 'mobileSidebar') {
                document.getElementById('mobileSidebar').classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        // Show selected file name and preview
        document.getElementById('file-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileNameElement = document.getElementById('file-name');
            const previewContainer = document.getElementById('file-preview');
            const previewImg = document.getElementById('preview-img');
            
            if (file) {
                fileNameElement.textContent = `File terpilih: ${file.name}`;
                fileNameElement.classList.remove('hidden');
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                fileNameElement.textContent = '';
                fileNameElement.classList.add('hidden');
                previewContainer.classList.add('hidden');
            }
        });
        
        // Format harga input dengan titik
        const hargaInput = document.getElementById('harga_sewa');
        
        // Fungsi untuk memformat angka dengan titik
        function formatNumberWithDots(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Event saat user mengetik (hanya angka yang diperbolehkan)
        hargaInput.addEventListener('input', function(e) {
            let value = e.target.value;
            let numericValue = value.replace(/[^0-9]/g, '');
            e.target.value = numericValue;
        });
        
        // Event saat input kehilangan fokus (blur) - format dengan titik
        hargaInput.addEventListener('blur', function(e) {
            let value = e.target.value;
            if (value && !isNaN(value) && value !== '') {
                let numericValue = value.replace(/\./g, '');
                e.target.value = formatNumberWithDots(parseInt(numericValue));
            }
        });
        
        // Event saat input mendapatkan fokus - hapus titik untuk editing mudah
        hargaInput.addEventListener('focus', function(e) {
            let value = e.target.value;
            if (value) {
                e.target.value = value.replace(/\./g, '');
            }
        });
        
        // Validasi form sebelum submit
        document.getElementById('kitForm').addEventListener('submit', function(e) {
            const hargaField = document.getElementById('harga_sewa');
            let hargaValue = hargaField.value;
            
            let cleanValue = hargaValue.replace(/\./g, '');
            
            if (cleanValue === '' || isNaN(cleanValue)) {
                e.preventDefault();
                alert('Harga sewa harus diisi dengan angka yang valid!');
                return false;
            }
            
            hargaField.value = cleanValue;
            return true;
        });
        
        // Focus ke input nama kit saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="nama_kit"]').focus();
        });
    </script>
</body>
</html>