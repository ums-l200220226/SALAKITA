<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SALAKITA</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #f4f1ee 0%, #fff 100%);
            font-family: 'Outfit', sans-serif;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-reset {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            max-width: 450px;
            width: 100%;
            padding: 45px 40px;
        }

        .logo {
            width: 180px;
            display: block;
            margin: 0 auto 25px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .title-section h5 {
            font-weight: 700;
            color: #85603f;
            margin-bottom: 8px;
        }

        .title-section p {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1.5px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #85603f;
            box-shadow: 0 0 0 0.2rem rgba(133, 96, 63, 0.15);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #85603f;
        }

        .password-requirements {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .password-requirements .req-item {
            color: #6c757d;
            margin-bottom: 5px;
        }

        .password-requirements .req-item:last-child {
            margin-bottom: 0;
        }

        .password-requirements .req-item i {
            font-size: 0.8rem;
            margin-right: 6px;
        }

        .btn-reset {
            width: 100%;
            border-radius: 12px;
            background-color: #85603f;
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 14px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background-color: #6c4a2f;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(133, 96, 63, 0.3);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px;
        }

        .alert-danger {
            background-color: #fff5f5;
            color: #c53030;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #85603f;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .back-to-login a:hover {
            color: #6c4a2f;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="card-reset">

        <img src="{{ asset('img/SalaKitaTanpaBGLogin.png') }}" class="logo" alt="SALAKITA">

        <div class="title-section">
            <h5>Buat Password Baru</h5>
            <p>Password baru harus berbeda dari password sebelumnya</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- token WAJIB -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="bi bi-envelope"></i> Email
                </label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ request()->email ?? old('email') }}"
                       placeholder="Masukkan email Anda"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password baru -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="bi bi-lock"></i> Password Baru
                </label>
                <div class="password-wrapper">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 6 karakter"
                           required>
                    <i class="bi bi-eye toggle-password" id="togglePassword"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                <div class="password-requirements">
                    <div class="req-item">
                        <i class="bi bi-check-circle"></i> Minimal 6 karakter
                    </div>
                    <div class="req-item">
                        <i class="bi bi-check-circle"></i> Kombinasi huruf dan angka lebih aman
                    </div>
                </div>
            </div>

            <!-- Konfirmasi -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="bi bi-shield-check"></i> Konfirmasi Password
                </label>
                <div class="password-wrapper">
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-control"
                           placeholder="Ketik ulang password baru"
                           required>
                    <i class="bi bi-eye toggle-password" id="togglePasswordConfirm"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-reset">
                <i class="bi bi-check-circle"></i> Reset Password
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>

    </div>
</div>

<script>
    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirm = document.getElementById('password_confirmation');

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>
