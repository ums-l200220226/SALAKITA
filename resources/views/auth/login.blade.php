<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SALAKITA</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ✅ Google Fonts: Outfit + Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f4f1ee 0%, #fff 100%);
            font-family: 'Outfit', sans-serif;
            color: #333;
        }

        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            max-width: 420px;
            width: 100%;
            padding: 50px 35px 40px 35px;
        }

        .login-logo {
            width: 200px;
            height: auto;
            margin: 0 auto 15px auto;
            display: block;
        }

        .form-label {
            font-weight: 500;
            color: #555;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 14px;
        }

        .btn-login {
            width: 100%;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            background-color: #85603f;
            border: none;
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #6c4a2f;
        }

        .form-text {
            text-align: center;
            margin-top: 18px;
            font-size: 0.95rem;
        }

        .form-text a {
            text-decoration: none;
            color: #85603f;
            font-weight: 600;
        }

        .form-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <img src="{{ asset('img/SalaKitaTanpaBGLogin.png') }}" alt="Logo SALAKITA" class="login-logo">

            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif
            
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan kata sandi"
                            required>

                        <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
                            <i id="eyeIcon" class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end mb-3">
                    <a href="{{ route('password.request') }}"
                        style="font-size: 0.9rem; color: #85603f; text-decoration: none; font-weight: 600;">
                            Lupa Password?
                    </a>
                </div>

                <button type="submit" class="btn btn-login mt-2">Masuk</button>

                <div class="form-text">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </div>
            </form>
        </div>
    </div>

<script>
    function togglePassword() {
        const password = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            password.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    }
</script>
</body>
</html>
