<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register SALAKITA</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f4f1ee 0%, #fff 100%);
            font-family: 'Outfit', sans-serif;
            color: #333;
        }

        .register-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            max-width: 450px;
            width: 100%;
            padding: 40px 35px;
        }

        .register-title {
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 25px;
            color: #2c2c2c;
        }

        .form-label {
            font-weight: 500;
            color: #555;
        }

        .form-control, textarea, select {
            border-radius: 12px;
            padding: 10px 14px;
        }

        textarea {
            resize: none;
            height: 90px;
        }

        .btn-register {
            width: 100%;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            background-color: #85603f;
            border: none;
            color: #fff;
            transition: all 0.3s ease;
            padding: 10px 14px;
        }

        .btn-register:hover {
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

        /* Toko field - hidden by default */
        #tokoField {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .toko-badge {
            display: inline-block;
            background: #4a7c2c;
            color: white;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 8px;
            margin-left: 5px;
        }
    </style>
</head>
<body>

    <div class="register-wrapper">
        <div class="register-card">
            <h3 class="register-title">Daftar Akun SALAKITA</h3>

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Daftar sebagai</label>
                    <select name="role" id="roleSelect" class="form-control" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="pembeli">Pembeli</option>
                        <option value="petani">Petani</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="nameInput" class="form-control" required>
                </div>

                <!-- Field Nama Toko - Muncul jika role = petani -->
                <div class="mb-3" id="tokoField">
                    <label class="form-label">
                        Nama Toko
                        <span class="toko-badge">Khusus Petani</span>
                    </label>
                    <input type="text" name="nama_toko" id="namaTokoInput" class="form-control" placeholder="Contoh: Kebun Segar Pak Budi">
                    <small class="text-muted">Nama toko yang akan dilihat pembeli. Bisa diubah nanti.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-register mt-2">Daftar</button>

                <div class="form-text">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show/hide nama toko field based on role
        const roleSelect = document.getElementById('roleSelect');
        const tokoField = document.getElementById('tokoField');
        const namaTokoInput = document.getElementById('namaTokoInput');
        const nameInput = document.getElementById('nameInput');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'petani') {
                tokoField.style.display = 'block';
                namaTokoInput.required = true;

                // Auto-fill nama toko dari nama lengkap
                if (nameInput.value) {
                    namaTokoInput.value = 'Toko ' + nameInput.value;
                }
            } else {
                tokoField.style.display = 'none';
                namaTokoInput.required = false;
                namaTokoInput.value = '';
            }
        });

        // Auto-update nama toko saat nama lengkap diubah (jika role petani)
        nameInput.addEventListener('input', function() {
            if (roleSelect.value === 'petani' && !namaTokoInput.value.includes('Toko')) {
                namaTokoInput.value = 'Toko ' + this.value;
            }
        });
    </script>
</body>
</html>
