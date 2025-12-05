<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<style>
    .password-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .password-header-icon {
        font-size: 40px;
        opacity: 0.9;
    }

    .password-header-content h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .password-header-content p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .form-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        max-width: 500px;
        margin: 0 auto 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #f5576c;
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        outline: none;
    }

    .password-requirements {
        background: #f8f9fa;
        border-left: 4px solid #f5576c;
        padding: 15px;
        border-radius: 6px;
        margin: 20px 0;
        font-size: 13px;
        color: #666;
    }

    .password-requirements ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }

    .password-requirements li {
        margin: 5px 0;
    }

    .password-strength {
        margin-top: 10px;
        padding: 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .strength-weak {
        background: #ffebee;
        color: #c62828;
    }

    .strength-medium {
        background: #fff3e0;
        color: #e65100;
    }

    .strength-strong {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .password-input-group {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 38px;
        cursor: pointer;
        color: #999;
        font-size: 16px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        flex: 1;
        justify-content: center;
    }

    .btn-save {
        background: #f5576c;
        color: white;
    }

    .btn-save:hover {
        background: #d63447;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #333;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .alert {
        border-radius: 6px;
        border: 1px solid;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .password-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 20px;
        }

        .button-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="password-header">
        <div class="password-header-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="password-header-content">
            <h1>Ubah Password</h1>
            <p>Perbarui password akun Anda untuk keamanan lebih baik</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div style="margin-bottom: 20px; margin-left: auto; margin-right: auto; max-width: 500px;">
        <div style="display: flex; gap: 15px; border-bottom: 2px solid #f0f0f0; flex-wrap: wrap;">
            <a href="<?= base_url('guru/profile') ?>" style="padding: 12px 0; color: #999; text-decoration: none; font-weight: 500; border-bottom: 3px solid transparent; transition: all 0.3s;">
                <i class="fas fa-user-circle"></i> Profil
            </a>
            <a href="<?= base_url('guru/profile/edit') ?>" style="padding: 12px 0; color: #999; text-decoration: none; font-weight: 500; border-bottom: 3px solid transparent; transition: all 0.3s;">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            <a href="<?= base_url('guru/profile/change-password') ?>" style="padding: 12px 0; color: #f5576c; text-decoration: none; font-weight: 500; border-bottom: 3px solid #f5576c;">
                <i class="fas fa-key"></i> Ubah Password
            </a>
        </div>
    </div>

    <form action="<?= base_url('guru/profile/update_password') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="form-card">
            <!-- Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-key"></i> Keamanan Akun
                </h3>

                <div class="form-group">
                    <label for="current_password"><i class="fas fa-lock"></i> Password Lama</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="current_password" 
                               name="current_password" required placeholder="Masukkan password lama">
                        <i class="toggle-password fas fa-eye" data-input="current_password"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password"><i class="fas fa-lock"></i> Password Baru</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="new_password" 
                               name="new_password" required placeholder="Masukkan password baru"
                               oninput="checkPasswordStrength(this.value)">
                        <i class="toggle-password fas fa-eye" data-input="new_password"></i>
                    </div>
                    <div id="strength" class="password-strength" style="display: none;"></div>
                </div>

                <div class="password-requirements">
                    <strong>Persyaratan Password:</strong>
                    <ul>
                        <li>Minimal 8 karakter</li>
                        <li>Mengandung huruf besar dan kecil</li>
                        <li>Mengandung angka</li>
                        <li>Berbeda dengan password lama</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Konfirmasi Password Baru</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="confirm_password" 
                               name="confirm_password" required placeholder="Konfirmasi password baru">
                        <i class="toggle-password fas fa-eye" data-input="confirm_password"></i>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> Ubah Password
                </button>
                <a href="<?= base_url('guru/profile') ?>" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function() {
        const inputId = this.dataset.input;
        const input = document.getElementById(inputId);
        
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
});

// Check password strength
function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('strength');
    let strength = 0;
    
    if (!password) {
        strengthDiv.style.display = 'none';
        return;
    }
    
    // Check length
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Check for uppercase
    if (/[A-Z]/.test(password)) strength++;
    
    // Check for lowercase
    if (/[a-z]/.test(password)) strength++;
    
    // Check for numbers
    if (/[0-9]/.test(password)) strength++;
    
    // Check for special characters
    if (/[!@#$%^&*]/.test(password)) strength++;
    
    strengthDiv.style.display = 'block';
    
    if (strength < 3) {
        strengthDiv.textContent = '⚠️ Password Lemah';
        strengthDiv.className = 'password-strength strength-weak';
    } else if (strength < 5) {
        strengthDiv.textContent = '✓ Password Sedang';
        strengthDiv.className = 'password-strength strength-medium';
    } else {
        strengthDiv.textContent = '✓✓ Password Kuat';
        strengthDiv.className = 'password-strength strength-strong';
    }
}

// Validate form on submit
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Password baru dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('Password minimal harus 8 karakter!');
        return false;
    }
});
</script>

<?= $this->endSection() ?>
