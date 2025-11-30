<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Guru | Log in</title>

    <!-- Memuat Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memuat Font Awesome via CDN (untuk ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Memuat Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Menerapkan font Inter sebagai default */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">
        
        <!-- Header Logo dan Judul -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Jurnal<span class="text-blue-600">Guru</span>
            </h1>
            <p class="text-gray-500 mt-2">Aplikasi Jurnal Mengajar Guru</p>
            <p class="text-gray-500">MIN 2 TANGGAMUS</p>
        </div>

        <p class="text-center text-gray-600 mb-6">Silakan masuk untuk memulai sesi Anda</p>

        <!-- Menampilkan Pesan Error (jika ada) -->
        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Menampilkan Pesan Sukses (jika ada) -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form action="<?= base_url('auth/attemptLogin') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Input Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="fas fa-envelope text-gray-400"></span>
                    </div>
                    <input type="email" id="email" name="email"
                           class="w-full px-4 py-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent <?php if(session('errors.email')): ?>border-red-500 focus:ring-red-500<?php else: ?>border-gray-300 focus:ring-blue-500<?php endif ?>"
                           placeholder="email@anda.com" value="<?= old('email') ?>">
                </div>
                <?php if(session('errors.email')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('errors.email') ?></p>
                <?php endif ?>
            </div>

            <!-- Input Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="fas fa-lock text-gray-400"></span>
                    </div>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-3 pl-10 pr-10 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent <?php if(session('errors.password')): ?>border-red-500 focus:ring-red-500<?php else: ?>border-gray-300 focus:ring-blue-500<?php endif ?>"
                           placeholder="••••••••">
                    <!-- Tombol Toggle Password -->
                    <div id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <span id="passwordIcon" class="fas fa-eye text-gray-400 hover:text-gray-600"></span>
                    </div>
                </div>
                <?php if(session('errors.password')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('errors.password') ?></p>
                <?php endif ?>
            </div>

            <!-- Remember Me & Lupa Password -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>
                <div class="text-sm">
                    <a href="#" onclick="showModal(event)" class="font-medium text-blue-600 hover:text-blue-500">
                        Lupa password?
                    </a>
                </div>
            </div>

            <!-- Tombol Sign In -->
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                    Masuk
                </button>
            </div>
        </form>
        
    </div>

    <!-- Modal untuk "Lupa Password" (menggantikan alert) -->
    <div id="forgotPasswordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden p-4" onclick="closeModal()">
        <div class="relative mx-auto p-6 border w-full max-w-sm shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <i class="fas fa-info-circle fa-lg text-blue-600"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Lupa Password</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Silakan hubungi Administrator Anda untuk bantuan mengatur ulang kata sandi.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModalButton" onclick="closeModal()" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('forgotPasswordModal');

        function showModal(event) {
            event.preventDefault(); // Mencegah link '#' mengubah URL
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Menutup modal jika user mengklik di luar area modal
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // --- Script Toggle Password ---
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const passwordIcon = document.getElementById('passwordIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                // Toggle type
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle icon
                if (type === 'password') {
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                } else {
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                }
            });
        }
    </script>

</body>
</html>