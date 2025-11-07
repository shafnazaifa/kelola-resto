<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Restaurant</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #EEE6CA;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(113, 90, 90, 0.08);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 380px;
            padding: 2.2rem;
        }

        .login-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(113, 90, 90, 0.12);
        }

        .login-header {
            text-align: center;
            color: #715A5A;
            margin-bottom: 1.8rem;
        }

        .login-header i {
            color: #E6CFA9;
        }

        .login-header h2 {
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #715A5A;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #E6CFA9;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #715A5A;
            box-shadow: 0 0 0 0.2rem rgba(113, 90, 90, 0.15);
        }

        .btn-login {
            background-color: #715A5A;
            color: #EEE6CA;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #5d4a4a;
            transform: translateY(-2px);
        }

        .input-group-text {
            background: #fff;
            border: 1.5px solid #E6CFA9;
            color: #715A5A;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .alert-danger {
            border-radius: 10px;
            background-color: #f8d7da;
            border: none;
            color: #842029;
            font-size: 0.9rem;
        }

        .footer-text {
            font-size: 0.85rem;
            text-align: center;
            margin-top: 15px;
            color: #715A5A;
            opacity: 0.8;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container fade-in">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-utensils fa-3x"></i>
                        <h2>Restaurant</h2>
                        <p class="text-muted fs-6">Sistem Kasir</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}" 
                                       required autofocus 
                                       placeholder="Masukkan username">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       placeholder="Masukkan password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </form>

                    <div class="footer-text mt-4">
                        &copy; {{ date('Y') }} Restaurant Management 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
