<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location:../dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        /* Overlay untuk meningkatkan keterbacaan */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        
        /* Animasi untuk form login */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        /* Efek glassmorphism */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom input focus */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        /* Shake animation untuk error */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .shake {
            animation: shake 0.6s ease;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative">
    <!-- Overlay untuk background -->
    <div class="overlay"></div>
    
    <!-- Container utama -->
    <div class="relative z-10 w-full max-w-md animate-fade-in-up">
        <!-- Card login -->
        <div class="glass-effect rounded-2xl overflow-hidden">
            <!-- Header card dengan gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-center text-white">
                <div class="flex justify-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-user-shield text-3xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold">Admin Login</h1>
                <p class="text-blue-100 mt-2">Sistem Dashboard Management</p>
            </div>
            
            <!-- Form login -->
            <div class="bg-white/90 p-8">
                <!-- Pesan error -->
                <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg shake">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span><?php echo $_SESSION['error']; ?></span>
                    </div>
                </div>
                <?php 
                    unset($_SESSION['error']);
                endif; 
                ?>
                
                <!-- Form -->
                <form action="simpan_login.php" method="POST" id="loginForm">
                    <!-- Input nama -->
                    <div class="mb-6">
                        <label for="nama" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Nama Admin
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                required
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none input-focus focus:border-blue-500 transition duration-300"
                                placeholder="Masukkan nama admin"
                                autocomplete="username"
                                autofocus
                            >
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-id-card"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Input password -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-gray-700 font-medium">
                                <i class="fas fa-lock text-blue-500 mr-2"></i>Password
                            </label>
                            <button type="button" id="togglePassword" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-eye mr-1"></i> Tampilkan
                            </button>
                        </div>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-lg focus:outline-none input-focus focus:border-blue-500 transition duration-300"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                            >
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol submit -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center"
                    >
                        <span id="buttonText">Login ke Dashboard</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>
                
                <!-- Informasi tambahan -->
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Pastikan kredensial yang Anda masukkan sudah benar.
                    </p>
                </div>
                
                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-gray-500 text-sm">
                        © <?php echo date('Y'); ?> Sistem Dashboard. Hak akses terbatas.
                    </p>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Dekorasi background shape -->
    <div class="absolute top-10 left-10 w-64 h-64 rounded-full bg-white/5 hidden md:block"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-white/5 hidden md:block"></div>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            const text = this.querySelector('span') || this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                if (this.textContent.includes('Tampilkan')) {
                    this.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Sembunyikan';
                }
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                if (this.textContent.includes('Sembunyikan')) {
                    this.innerHTML = '<i class="fas fa-eye mr-1"></i> Tampilkan';
                }
            }
        });
        
        // Form submission dengan loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Validasi sederhana
            const nama = document.getElementById('nama').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const buttonText = document.getElementById('buttonText');
            
            if (nama === '' || password === '') {
                e.preventDefault();
                return;
            }
            
            // Tampilkan loading state
            submitBtn.disabled = true;
            buttonText.innerHTML = 'Memproses...';
            submitBtn.querySelector('i').className = 'fas fa-spinner fa-spin ml-2';
            
            // Biarkan form submit setelah 1 detik (untuk efek visual)
            setTimeout(() => {
                // Form akan disubmit secara normal
            }, 1000);
        });
        
        // Fokus ke input nama saat halaman dimuat
        document.getElementById('nama').focus();
        
        // Animasi untuk card login
        document.addEventListener('DOMContentLoaded', function() {
            const loginCard = document.querySelector('.animate-fade-in-up');
            loginCard.style.opacity = '0';
            
            setTimeout(() => {
                loginCard.style.animation = 'fadeInUp 0.8s ease-out forwards';
            }, 100);
        });
    </script>
</body>
</html>