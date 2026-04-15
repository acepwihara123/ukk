<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: index.php");
    exit();
}

include "../koneksi.php";

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['register'])) { 
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi!";
    } else {
        // Check user in database
        $sql = "SELECT * FROM users WHERE email = '$email' AND status = 'active'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                // Redirect to dashboard
                header("Location: index.php");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak terdaftar atau akun tidak aktif!";
        }
    }
}

// Handle registration form submission
if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['reg_nama']);
    $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['reg_telepon']);
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['reg_confirm_password'];
    
    // Validate inputs
    if (empty($nama) || empty($email) || empty($telepon) || empty($password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert_sql = "INSERT INTO users (nama, email, telepon, password, role, status, created_at) 
                           VALUES ('$nama', '$email', '$telepon', '$hashed_password', 'user', 'active', NOW())";
            
            if (mysqli_query($conn, $insert_sql)) {
                $success = "Registrasi berhasil! Silakan login.";
                // Clear form
                $_POST = array();
            } else {
                $error = "Registrasi gagal: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registrasi | Rental Kamera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .hero-bg {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.95), rgba(185, 28, 28, 0.95)), url('https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
        }
        .input-focus:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        .tab-active {
            background-color: #dc2626;
            color: white;
        }
        .social-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <i class="fas fa-camera text-red-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Rental<span class="text-red-600">Kamera</span></span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="login.php" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-full transition duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Selamat Datang!</h1>
            <p class="text-xl max-w-2xl mx-auto">Login untuk mulai menyewa kamera favorit Anda atau daftar jika belum memiliki akun.</p>
        </div>
    </div>

    <!-- Login & Register Form -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-20">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Tabs -->
            <div class="flex border-b">
                <button onclick="showTab('login')" id="loginTab" class="flex-1 py-4 text-center font-semibold transition duration-300 tab-active">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
                <button onclick="showTab('register')" id="registerTab" class="flex-1 py-4 text-center font-semibold text-gray-600 hover:text-red-600 transition duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Daftar
                </button>
            </div>

            <!-- Alert Messages -->
            <?php if (!empty($error)): ?>
                <div class="mx-6 mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="mx-6 mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span><?php echo $success; ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <div id="loginForm" class="p-6 md:p-8">
                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Alamat Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="email" 
                                   name="email" 
                                   required
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="nama@email.com">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" 
                                   name="password" 
                                   required
                                   class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="Masukkan password"
                                   id="login_password">
                            <button type="button" onclick="togglePassword('login_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded text-red-600 focus:ring-red-500">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a href="forgot_password.php" class="text-sm text-red-600 hover:text-red-700">Lupa password?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </form>
                
                <!-- Social Login -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Atau login dengan</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <button onclick="socialLogin('google')" class="flex items-center justify-center gap-3 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition social-btn">
                            <i class="fab fa-google text-red-500 text-xl"></i>
                            <span class="text-gray-700">Google</span>
                        </button>
                        <button onclick="socialLogin('facebook')" class="flex items-center justify-center gap-3 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition social-btn">
                            <i class="fab fa-facebook text-blue-600 text-xl"></i>
                            <span class="text-gray-700">Facebook</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="p-6 md:p-8 hidden">
                <form method="POST" action="" class="space-y-5">
                    <input type="hidden" name="register" value="1">
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   name="reg_nama" 
                                   required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="email" 
                                   name="reg_email" 
                                   required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="nama@email.com">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">No. Telepon <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="tel" 
                                   name="reg_telepon" 
                                   required
                                   pattern="[0-9]{10,13}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="08xxxxxxxxxx">
                            <p class="text-xs text-gray-500 mt-1">Minimal 10 digit, maksimal 13 digit</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" 
                                   name="reg_password" 
                                   required
                                   minlength="6"
                                   class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="Minimal 6 karakter"
                                   id="reg_password">
                            <button type="button" onclick="togglePassword('reg_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" 
                                   name="reg_confirm_password" 
                                   required
                                   class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 input-focus"
                                   placeholder="Ketik ulang password"
                                   id="reg_confirm_password">
                            <button type="button" onclick="togglePassword('reg_confirm_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" class="mr-3 mt-1 rounded text-red-600 focus:ring-red-500" required>
                        <label for="terms" class="text-sm text-gray-600">
                            Saya setuju dengan <a href="#" class="text-red-600 hover:underline">Syarat dan Ketentuan</a> serta 
                            <a href="#" class="text-red-600 hover:underline">Kebijakan Privasi</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </form>
                
                <div class="mt-6 text-center text-sm text-gray-600">
                    Dengan mendaftar, Anda menyetujui bahwa data Anda akan digunakan sesuai dengan kebijakan privasi kami.
                </div>
            </div>
        </div>
        
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-xl shadow-md p-6 text-center card-hover">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-camera text-red-600 text-xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Kamera Berkualitas</h3>
                <p class="text-gray-600 text-sm">Pilihan kamera profesional dengan kondisi terawat</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center card-hover">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-red-600 text-xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600 text-sm">Harga sewa kompetitif dengan berbagai pilihan durasi</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center card-hover">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-red-600 text-xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Support 24/7</h3>
                <p class="text-gray-600 text-sm">Tim kami siap membantu Anda kapan saja</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-6">
                        <i class="fas fa-camera text-red-500 text-2xl mr-2"></i>
                        <span class="text-xl font-bold">Rental<span class="text-red-500">Kamera</span></span>
                    </div>
                    <p class="text-gray-400 mb-6">Solusi sewa kamera profesional terpercaya di Indonesia.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gray-800 hover:bg-red-600 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-red-600 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-red-600 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="index.php" class="text-gray-400 hover:text-red-400">Home</a></li>
                        <li><a href="kamera.php" class="text-gray-400 hover:text-red-400">Kamera</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-red-400">Tentang Kami</a></li>
                        <li><a href="kontak.php" class="text-gray-400 hover:text-red-400">Kontak</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Layanan</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-red-400">Sewa Harian</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-400">Sewa Mingguan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-400">Sewa Bulanan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-400">Paket Wedding</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Kontak</h4>
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                            <span class="text-gray-400">Jakarta, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-red-500 mr-3"></i>
                            <span class="text-gray-400">(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-red-500 mr-3"></i>
                            <span class="text-gray-400">info@rentalkamera.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-500">
                <p>&copy; 2023 RentalKamera. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab switching
        function showTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            
            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginTab.classList.add('tab-active');
                loginTab.classList.remove('text-gray-600');
                registerTab.classList.remove('tab-active');
                registerTab.classList.add('text-gray-600');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                registerTab.classList.add('tab-active');
                registerTab.classList.remove('text-gray-600');
                loginTab.classList.remove('tab-active');
                loginTab.classList.add('text-gray-600');
            }
        }
        
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Change eye icon
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Social login simulation
        function socialLogin(provider) {
            alert('Fitur ' + provider + ' login akan segera hadir!');
        }
        
        // Password match validation for registration
        document.querySelector('form[action=""]').addEventListener('submit', function(e) {
            if (this.querySelector('input[name="register"]')) {
                const password = document.getElementById('reg_password').value;
                const confirm = document.getElementById('reg_confirm_password').value;
                const terms = document.getElementById('terms');
                
                if (password !== confirm) {
                    e.preventDefault();
                    alert('Konfirmasi password tidak cocok!');
                    return false;
                }
                
                if (!terms.checked) {
                    e.preventDefault();
                    alert('Anda harus menyetujui Syarat dan Ketentuan!');
                    return false;
                }
            }
        });
        
        // Check URL parameters for tab selection
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'register') {
            showTab('register');
        }
        
        // Add floating effect to cards
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>