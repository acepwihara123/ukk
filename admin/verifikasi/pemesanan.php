<?php
include "../../koneksi.php";

// Proses Hapus Pemesanan
if (isset($_POST['delete_pemesanan'])) {
    $id = intval($_POST['delete_id']);
    
    // Hapus data dari tabel verifikasi terlebih dahulu (jika ada)
    $conn->query("DELETE FROM verifikasi WHERE pemesanan_id = $id");
    
    // Hapus data dari tabel pemesanan
    $hapus = $conn->query("DELETE FROM pemesanan WHERE id = $id");
    
    if ($hapus) {
        echo "<script>
            alert('Data pemesanan berhasil dihapus!');
            window.location.href = 'pemesanan.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . $conn->error . "');
            window.location.href = 'pemesanan.php';
        </script>";
        exit();
    }
}

// ambil data pemesanan dengan join ke tabel verifikasi
$data = $conn->query("
    SELECT p.*, d.total_denda, d.hari_terlambat
    FROM pemesanan p
    LEFT JOIN denda d ON d.pemesanan_id = p.id
    ORDER BY p.id DESC
");

$tarif_denda = 50000;

// ambil semua pemesanan
$q = $conn->query("SELECT * FROM pemesanan");

while($p = $q->fetch_assoc()){

    $tgl_kembali = strtotime($p['tanggal_kembali']);
$hari_ini = strtotime(date('Y-m-d'));

if($hari_ini > $tgl_kembali){

    $hari_telat = floor(($hari_ini - $tgl_kembali) / (60*60*24));
    $total_denda = $hari_telat * $tarif_denda;

    $cek = $conn->query("SELECT * FROM denda WHERE pemesanan_id=".$p['id']);

    if($cek->num_rows > 0){
        $conn->query("
            UPDATE denda 
            SET hari_terlambat=$hari_telat, total_denda=$total_denda 
            WHERE pemesanan_id=".$p['id']
        );
    } else {
        $conn->query("
            INSERT INTO denda (pemesanan_id,hari_terlambat,total_denda)
            VALUES (".$p['id'].",$hari_telat,$total_denda)
        ");
    }

} else {

            $hari_telat = floor(($hari_ini - $tgl_kembali) / (60*60*24));
            $total_denda = $hari_telat * $tarif_denda;

            // cek sudah ada denda atau belum
            $cek = $conn->query("SELECT * FROM denda WHERE pemesanan_id=".$p['id']);

            if($cek->num_rows > 0){
                // update
                $conn->query("
                    UPDATE denda 
                    SET hari_terlambat=$hari_telat, total_denda=$total_denda 
                    WHERE pemesanan_id=".$p['id']
                );
            } else {
                // insert
                $conn->query("
                    INSERT INTO denda (pemesanan_id,hari_terlambat,total_denda)
                    VALUES (".$p['id'].",$hari_telat,$total_denda)
                ");
            }

        }

    }

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pemesanan | Rental Kamera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        * {
            font-family: 'Inter', sans-serif;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-selesai {
            background-color: #d1fae5;
            color: #059669;
        }
        .status-proses {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-batal {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .table-row:hover {
            background-color: #eff6ff;
            transition: all 0.3s ease;
        }
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
        .filter-active {
            background-color: #3b82f6 !important;
            color: white !important;
        }
        
        /* Custom scrollbar untuk konten utama */
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
        
        .content-main {
            height: calc(100vh - 80px);
            overflow-y: auto;
        }
        
        .mobile-sidebar-full {
            height: 100vh;
            overflow-y: auto;
        }
        
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .contact-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }
        .contact-item i {
            width: 16px;
            color: #6b7280;
        }
        .date-badge {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .date-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }
        .date-item i {
            width: 16px;
            color: #6b7280;
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
                        <i class="fas fa-check-circle text-2xl text-primary"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Verifikasi Pemesanan</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 text-xl cursor-pointer hover:text-primary"></i>
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
<aside class="w-64 bg-white shadow-sm p-6 hidden md:block">

<ul class="space-y-2">

<li>
<a href="../dashboard.php"
class="flex items-center space-x-3 p-3 rounded-lg bg-blue-500 text-white">
<i class="fas fa-home"></i>
<span>Dashboard</span>
</a>
</li>

<li>
<a href="../list/kamera.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-camera"></i>
<span>Kamera</span>
</a>
</li>



<!-- MENU TRANSAKSI -->
<li>
<a href="../verifikasi/pemesanan.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-shopping-cart"></i>
<span>Transaksi</span>
</a>
</li>

<!-- ✅ MENU LAPORAN (BARU) -->
<li>
<a href="../laporan/laporan.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-chart-line"></i>
<span>Laporan</span>
</a>
</li>

</ul>

</aside>
            
            <!-- Main Content -->
            <main class="flex-1 p-6 content-main content-scrollable">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Pemesanan</h1>
                    <p class="text-gray-600">Kelola dan verifikasi semua pemesanan kamera</p>
                </div>

                <!-- Stats Cards -->
                <?php
                // Get statistics from verifikasi table
                $total_pemesanan = mysqli_num_rows($data);
                $total_nominal = 0;
                $status_counts = [
                    'selesai' => 0,
                    'proses' => 0,
                    'batal' => 0
                ];
                
                mysqli_data_seek($data, 0);
                while($row = mysqli_fetch_assoc($data)) {
                    $total_nominal += $row['total'];
                    // Ambil status dari verifikasi_status, default 'proses' jika belum ada
                    $status = strtolower($row['status'] ?? 'proses');
                    if (isset($status_counts[$status])) {
                        $status_counts[$status]++;
                    }
                }
                mysqli_data_seek($data, 0);
                ?>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-5 stat-card hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Pemesanan</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $total_pemesanan; ?></p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-shopping-cart text-primary text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-5 stat-card hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Pendapatan</p>
                                <p class="text-2xl font-bold text-green-600">Rp <?php echo number_format($total_nominal, 0, ',', '.'); ?>.</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-5 stat-card hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Selesai</p>
                                <p class="text-2xl font-bold text-green-600"><?php echo $status_counts['selesai']; ?></p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                   
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <!-- Search and Filter Bar -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row gap-4 justify-between">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text"
                                    id="searchInput"
                                    placeholder="Cari berdasarkan nama, email, telepon, atau alamat..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak & Alamat</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Sewa</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                     <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">aksi</th>
                                     <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">denda</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                                <?php if(mysqli_num_rows($data) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($data)): 
                                        // Ambil status dari verifikasi, default 'proses'
                                        $status = strtolower($row['status'] ?? 'proses');
                                    ?>
                                        <tr class="table-row transition duration-150" data-status="<?php echo $status; ?>">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #<?php echo $row['id']; ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['nama']); ?></div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-envelope mr-1"></i> <?php echo htmlspecialchars($row['email']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="contact-cell">
                                                    <div class="contact-item">
                                                        <i class="fas fa-phone-alt"></i>
                                                        <span class="text-gray-700"><?php echo htmlspecialchars($row['no_telepon'] ?? '-'); ?></span>
                                                    </div>
                                                    <div class="contact-item">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span class="text-gray-600 text-xs truncate max-w-[200px]" title="<?php echo htmlspecialchars($row['alamat'] ?? '-'); ?>">
                                                            <?php echo htmlspecialchars(substr($row['alamat'] ?? '-', 0, 50)); ?>
                                                            <?php echo strlen($row['alamat'] ?? '') > 50 ? '...' : ''; ?>
                                                        </span>
                                                    </div>
                                                    <?php if(!empty($row['tempat_tinggal'])): ?>
                                                    <div class="contact-item">
                                                        <i class="fas fa-home"></i>
                                                        <span class="text-gray-600 text-xs"><?php echo htmlspecialchars($row['tempat_tinggal']); ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="date-badge">
                                                    <div class="date-item">
                                                        <i class="fas fa-calendar-alt text-blue-500"></i>
                                                        <span class="text-gray-700">Mulai: <?php echo date('d/m/Y', strtotime($row['tanggal_mulai'] ?? 'now')); ?></span>
                                                    </div>
                                                    <div class="date-item">
                                                        <i class="fas fa-calendar-check text-green-500"></i>
                                                        <span class="text-gray-700">Kembali: 
                                                            <?php 
                                                            if(!empty($row['tanggal_kembali'])) {
                                                                echo date('d/m/Y', strtotime($row['tanggal_kembali']));
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <div class="date-item">
                                                        <i class="fas fa-clock text-yellow-500"></i>
                                                        <span class="text-gray-600 text-xs">Durasi: <?php echo $row['durasi']; ?> hari</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="font-bold text-primary">
                                                    Rp <?php echo number_format($row['total'], 0, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                <?php 
                                                $metode = strtolower($row['pembayaran'] ?? 'transfer');
                                                $metode_text = [
                                                    'transfer' => '  Transfer',
                                                    'cod' => 'Cash',
                                                    'qris' => '📱 QRIS',
                                                    'ewallet' => '💳 E-Wallet',
                                                    'cash' => '💵 Cash'
                                                ];
                                                $metode_icon = [
                                                    'transfer' => 'fa-university',
                                                    'cod' => 'fa-truck',
                                                    'qris' => 'fa-qrcode',
                                                    'ewallet' => 'fa-mobile-alt',
                                                    'cash' => 'fa-money-bill-wave'
                                                ];
                                                $icon = $metode_icon[$metode] ?? 'fa-credit-card';
                                                ?>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas <?php echo $icon; ?> text-gray-500"></i>
                                                    <span><?php echo $metode_text[$metode] ?? ucfirst($metode); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                $status_class = '';
                                                $status_text = '';
                                                
                                                if ($status == 'selesai') {
                                                    $status_class = 'status-selesai';
                                                    $status_text = 'Selesai';
                                                } elseif ($status == 'proses') {
                                                    $status_class = 'status-proses';
                                                    $status_text = 'Proses';
                                                    
                                                } else {
                                                    $status_class = 'status-batal';
                                                    $status_text = 'Batal';
                                                }
                                                ?>
                                                <span class="status-badge <?php echo $status_class; ?>">
                                                    <?php echo $status_text; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-2">
                                                    <button onclick="viewDetail(<?php echo $row['id']; ?>)" 
                                                            class="text-blue-600 hover:text-blue-800 transition" 
                                                            title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button onclick="updateStatus(<?php echo $row['id']; ?>, '<?php echo $status; ?>')" 
                                                            class="text-green-600 hover:text-green-800 transition" 
                                                            title="Update Status">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="deleteOrder(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nama']); ?>')" 
                                                            class="text-red-600 hover:text-red-800 transition" 
                                                            title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">

    <?php if($row['total_denda'] > 0): ?>
        
        <span class="text-red-600 font-bold">
            Rp <?= number_format($row['total_denda'],0,',','.') ?>
            (<?= $row['hari_terlambat'] ?? 0 ?> hari)
        </span>

        <br>

        <button onclick="bayarDenda(<?= $row['id'] ?>)"
        class="text-yellow-600 text-xs mt-1">
            <i class="fas fa-money-bill"></i> Bayar
        </button>

    <?php else: ?>
        -
    <?php endif; ?>

</td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                            <p>Belum ada data pemesanan</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                      
                                </td>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-chart-line mr-1 text-primary"></i>
                                Menampilkan <span class="font-semibold text-gray-800" id="totalData"><?php echo $total_pemesanan; ?></span> data pemesanan
                            </div>
                           
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
                    <p>© <?= date('Y') ?> Verifikasi Pemesanan. Sistem Manajemen Penyewaan Kamera.</p>
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
                        <a href="../verifikasi/pemesanan.php" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal Update Status -->
    <!-- Modal Update Status -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Update Status</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <input type="hidden" id="status_id">

        <label class="block mb-2 font-medium">Pilih Status</label>

        <select id="status_value"
            class="w-full border rounded-lg p-2 mb-4 focus:ring-2 focus:ring-primary">

            <option value="proses">Proses</option>
            <option value="selesai">Selesai</option>
            <option value="batal">Batal</option>

        </select>

        <button onclick="saveStatus()"
            class="w-full bg-primary text-white py-2 rounded-lg hover:bg-secondary transition">
            Simpan Status
        </button>

    </div>
</div>

<!-- FORM DELETE -->
<form id="deleteForm" method="POST">
    <input type="hidden" name="delete_id" id="delete_id">
    <input type="hidden" name="delete_pemesanan">
</form>



<script>

/* =========================
   SEARCH
========================= */
document.getElementById("searchInput").addEventListener("keyup", function(){
    let value = this.value.toLowerCase();
    document.querySelectorAll("#tableBody tr").forEach(row=>{
        row.style.display =
            row.innerText.toLowerCase().includes(value)
            ? "" : "none";
    });
});

/* =========================
   FILTER STATUS
========================= */
function filterStatus(status){

    document.querySelectorAll("#tableBody tr").forEach(row=>{
        if(status === "all"){
            row.style.display="";
        }else{
            row.style.display =
                row.dataset.status === status ? "" : "none";
        }
    });
}

/* =========================
   DELETE
========================= */
function deleteOrder(id,nama){

    if(confirm("Hapus pemesanan "+nama+" ?")){
        document.getElementById("delete_id").value=id;
        document.getElementById("deleteForm").submit();
    }
}

/* =========================
   MODAL STATUS
========================= */
function updateStatus(id,current){

    document.getElementById("status_id").value=id;
    document.getElementById("status_value").value=current;

    document.getElementById("statusModal")
        .classList.remove("hidden");
    document.getElementById("statusModal")
        .classList.add("flex");
}

function closeModal(){
    document.getElementById("statusModal")
        .classList.add("hidden");
}

/* =========================
   SAVE STATUS
========================= */
function saveStatus(){

    let id=document.getElementById("status_id").value;
    let status=document.getElementById("status_value").value;

    fetch("update_status.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"id="+id+"&status="+status
    })
    .then(()=>location.reload());
}

/* =========================
   MOBILE MENU
========================= */
document.getElementById("mobileMenuBtn")
?.addEventListener("click",()=>{
    document.getElementById("mobileSidebar")
        .classList.remove("hidden");
});

document.getElementById("closeMobileMenu")
?.addEventListener("click",()=>{
    document.getElementById("mobileSidebar")
        .classList.add("hidden");
});

</script>

</body>
</html>

   <!-- MODAL DETAIL -->
<div id="detailModal"
class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

<div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">

<div class="flex justify-between mb-4">
<h3 class="text-xl font-bold">Detail Pemesanan</h3>

<button onclick="closeDetailModal()">
<i class="fas fa-times text-xl"></i>
</button>
</div>

<div id="detailContent">
Loading...
</div>

</div>
</div>
<script>
function viewDetail(id){

const modal = document.getElementById('detailModal');
const content = document.getElementById('detailContent');

modal.classList.remove('hidden');
modal.classList.add('flex');

content.innerHTML = `
<div class="text-center py-6">
<i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i>
<p>Memuat data...</p>
</div>
`;

fetch("get_detail.php?id=" + id)
.then(res => res.json())
.then(res => {

if(res.status !== "success"){
content.innerHTML = "Data tidak ditemukan";
return;
}

let d = res.data;

content.innerHTML = `
<div class="grid grid-cols-2 gap-4 text-sm">

<div>
<p class="text-gray-500">ID</p>
<p class="font-semibold">${d.id}</p>
</div>

<div>
<p class="text-gray-500">Nama</p>
<p class="font-semibold">${d.nama}</p>
</div>

<div>
<p class="text-gray-500">No Telepon</p>
<p>${d.no_telepon}</p>
</div>

<div>
<p class="text-gray-500">Email</p>
<p>${d.email}</p>
</div>

<div>
<p class="text-gray-500">Tempat Tinggal</p>
<p>${d.tempat_tinggal}</p>
</div>

<div>
<p class="text-gray-500">Durasi</p>
<p>${d.durasi} Hari</p>
</div>

<div>
<p class="text-gray-500">Tanggal Mulai</p>
<p>${d.tanggal_mulai}</p>
</div>

<div>
<p class="text-gray-500">Tanggal Kembali</p>
<p>${d.tanggal_kembali}</p>
</div>

<div>
<p class="text-gray-500">Metode Pembayaran</p>
<p>${d.pembayaran}</p>
</div>

<div>
<p class="text-gray-500">Kamera ID</p>
<p>${d.kamera_id}</p>
</div>

<div class="col-span-2">
<p class="text-gray-500">Alamat</p>
<p>${d.alamat}</p>
</div>

<div class="col-span-2">
<p class="text-gray-500">Total</p>
<p class="font-bold text-blue-600">
Rp ${Number(d.total).toLocaleString('id-ID')}
</p>
</div>

<div class="col-span-2">
<p class="text-gray-500">Status</p>
<p class="font-bold capitalize text-green-600">
${d.status}
</p>
</div>

</div>
`;
});
}

function closeDetailModal(){
const modal=document.getElementById('detailModal');
modal.classList.add('hidden');
modal.classList.remove('flex');
}
</script>

    <!-- Modal Delete Confirmation -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-5xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus pemesanan ini?</p>
                <form method="POST" action="">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <input type="hidden" name="delete_pemesanan" value="1">
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2 rounded-lg hover:bg-red-700 transition">
                            Ya, Hapus
                        </button>
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-300 text-gray-700 font-bold py-2 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

   

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
        
        // Search Table Function
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("#tableBody tr");
            let visibleCount = 0;
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(value)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });
            
            document.getElementById("totalData").textContent = visibleCount;
        });

        // Filter by Status
        let currentFilter = 'all';

        function filterStatus(status) {
            currentFilter = status;
            let rows = document.querySelectorAll("#tableBody tr");
            let visibleCount = 0;
            
            // Update button styles
            const buttons = ['filterAll', 'filterSelesai', 'filterProses', 'filterBatal'];
            buttons.forEach(btn => {
                const element = document.getElementById(btn);
                if (element) {
                    element.classList.remove('bg-primary', 'text-white');
                    element.classList.add('bg-gray-200', 'text-gray-700');
                }
            });
            
            const activeButton = document.getElementById(`filter${status.charAt(0).toUpperCase() + status.slice(1)}`);
            if (activeButton && status !== 'all') {
                activeButton.classList.remove('bg-gray-200', 'text-gray-700');
                activeButton.classList.add('bg-primary', 'text-white');
            } else if (status === 'all') {
                document.getElementById('filterAll').classList.remove('bg-gray-200', 'text-gray-700');
                document.getElementById('filterAll').classList.add('bg-primary', 'text-white');
            }
            
            rows.forEach(row => {
                if (status === 'all' || row.getAttribute('data-status') === status) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });
            
            document.getElementById("totalData").textContent = visibleCount;
        }

        function viewDetail(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            content.innerHTML = `
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4 p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium">ID Pemesanan:</span>
                        <span>#${id}</span>
                    </div>
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-primary text-2xl"></i>
                        <p class="mt-2 text-gray-500">Memuat data detail...</p>
                    </div>
                </div>
            `;
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        function updateStatus(id, currentStatus) {
            document.getElementById('status_id').value = id;
            document.getElementById('pemesanan_id').value = id;
            document.getElementById('status_value').value = currentStatus;
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusModal').classList.add('flex');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusModal').classList.remove('flex');
        }

        function deleteOrder(id, name) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus pemesanan dari <strong>${name}</strong>?`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        function exportData() {
            alert("Fitur ekspor data akan segera tersedia");
        }
        
        function deleteOrder(id,nama){
    if(confirm("Hapus pemesanan "+nama+" ?")){
        document.getElementById("delete_id").value=id;
        document.getElementById("deleteForm").submit();
    }
}

function updateStatus(id,current){
    document.getElementById("status_id").value=id;
    document.getElementById("status_value").value=current;

    document.getElementById("statusModal").classList.remove("hidden");
    document.getElementById("statusModal").classList.add("flex");
}

function closeModal(){
    document.getElementById("statusModal").classList.add("hidden");
}

function saveStatus(){
    let id=document.getElementById("status_id").value;
    let status=document.getElementById("status_value").value;

    fetch("update_status.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"id="+id+"&status="+status
    })
    .then(()=>location.reload());
}

function bayarDenda(id){
    if(confirm("Bayar denda?")){
        fetch("bayar_denda.php?id="+id)
        .then(()=>location.reload());
    }
}

        // Close modals when clicking outside
        window.onclick = function(event) {
            const detailModal = document.getElementById('detailModal');
            const statusModal = document.getElementById('statusModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === detailModal) closeDetailModal();
            if (event.target === statusModal) closeStatusModal();
            if (event.target === deleteModal) closeDeleteModal();
        }
      
    </script>
</body>
</html>