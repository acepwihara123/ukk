<?php include "../../koneksi.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kit Kamera</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Kit Kamera</h1>
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
                        
                        <!-- MENU KIT KAMERA (ACTIVE) -->
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
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Data Kit Kamera</h2>
                            <p class="text-gray-600 mt-1">Kelola data kit kamera yang tersedia untuk disewa</p>
                        </div>
                        
                        <div class="mt-4 md:mt-0">
                            <a href="../tambah/kit_kamera.php" class="inline-flex items-center px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition hover-lift">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Kit Baru
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistik Ringkas -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-primary">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Total Kit</p>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <?php
                                        $total = $conn->query("SELECT COUNT(*) as total FROM kit_kamera")->fetch_assoc();
                                        echo $total['total'];
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-box-open text-primary"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-success">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Total Stok</p>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <?php
                                        $stok = $conn->query("SELECT SUM(stok) as total_stok FROM kit_kamera")->fetch_assoc();
                                        echo $stok['total_stok'] ?? 0;
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-box text-success"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-warning">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Merk Berbeda</p>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <?php
                                        $merk = $conn->query("SELECT COUNT(DISTINCT merk) as total_merk FROM kit_kamera")->fetch_assoc();
                                        echo $merk['total_merk'];
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-tag text-warning"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-info">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Rata-rata Harga</p>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <?php
                                        $harga = $conn->query("SELECT AVG(harga_sewa) as avg_harga FROM kit_kamera")->fetch_assoc();
                                        echo "Rp " . number_format($harga['avg_harga'] ?? 0, 0, ',', '.');
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Kit Kamera -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <h3 class="text-lg font-bold text-gray-800">Daftar Kit Kamera</h3>
                            
                            <div class="mt-2 md:mt-0">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                    <input type="text" id="searchInput" placeholder="Cari kit kamera..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary w-full md:w-64">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">ID</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Nama Kit</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Merk</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Isi Kit</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Stok</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Harga Sewa</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Status</th>
                                    <th class="text-left py-3 px-6 text-gray-600 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                $data = $conn->query("SELECT * FROM kit_kamera ORDER BY id DESC");
                                $no = 1;
                                
                                while ($row = $data->fetch_assoc()) :
                                ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition fade-in">
                                    <td class="py-4 px-6">
                                        <span class="font-medium text-gray-800"><?= $row['id'] ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-md bg-orange-100 flex items-center justify-center mr-3">
                                                <i class="fas fa-box-open text-orange-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800"><?= htmlspecialchars($row['nama_kit']) ?></p>
                                                <p class="text-xs text-gray-500"><?= htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) ?>...</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($row['merk']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="max-w-xs">
                                            <p class="text-gray-700 text-sm truncate" title="<?= htmlspecialchars($row['isi_kit']) ?>">
                                                <?= htmlspecialchars(substr($row['isi_kit'], 0, 60)) ?>
                                                <?= strlen($row['isi_kit']) > 60 ? '...' : '' ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <span class="font-medium <?= $row['stok'] > 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= $row['stok'] ?>
                                            </span>
                                            <?php if($row['stok'] > 0): ?>
                                                <span class="ml-2 text-xs text-success bg-green-50 px-2 py-1 rounded">Tersedia</span>
                                            <?php else: ?>
                                                <span class="ml-2 text-xs text-danger bg-red-50 px-2 py-1 rounded">Habis</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-gray-800">Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></span>
                                        <p class="text-xs text-gray-500">/hari</p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <?php 
                                        $status = $row['status'] ?? 'aktif';
                                        $status_class = $status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $status_class ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex space-x-2">
                                            <a href="../edit/kit_kamera.php?id=<?= $row['id'] ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-primary rounded-md hover:bg-blue-100 transition">
                                                <i class="fas fa-edit text-xs mr-1"></i>
                                                Edit
                                            </a>
                                            <a href="../hapus/kit_kamera.php?id=<?= $row['id'] ?>" 
                                               onclick="return confirm('Yakin ingin menghapus kit <?= htmlspecialchars(addslashes($row['nama_kit'])) ?>?')"
                                               class="inline-flex items-center px-3 py-1.5 bg-red-50 text-danger rounded-md hover:bg-red-100 transition">
                                                <i class="fas fa-trash text-xs mr-1"></i>
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                $no++;
                                endwhile; 
                                
                                if($no == 1): 
                                ?>
                                <tr>
                                    <td colspan="8" class="py-12 text-center">
                                        <div class="text-gray-400">
                                            <i class="fas fa-box-open text-4xl mb-3"></i>
                                            <p class="text-lg">Belum ada data kit kamera</p>
                                            <p class="text-sm mt-1">Mulai dengan menambahkan kit kamera baru</p>
                                            <a href="../tambah/kit_kamera.php" class="inline-flex items-center px-4 py-2 mt-4 bg-primary text-white rounded-lg hover:bg-secondary transition">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Kit Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer Tabel -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <p class="text-gray-600 text-sm">
                                Menampilkan <span class="font-medium"><?= ($no-1) ?></span> data kit kamera
                            </p>
                            <div class="mt-2 md:mt-0">
                                <button onclick="exportData()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <i class="fas fa-file-export mr-2"></i>
                                    Ekspor Data
                                </button>
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
                        <a href="../list/lensa.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-lens"></i>
                            <span>Lensa</span>
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
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Export function
        function exportData() {
            alert("Fitur ekspor data akan segera tersedia");
        }
        
        // Add animation to table rows
        const tableRows = document.querySelectorAll('#tableBody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    </script>
</body>
</html>