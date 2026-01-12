<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Login - {{ config('app.name', 'KasirApp') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Background Atas (Hiasan) */
        .bg-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            z-index: -1;
        }

        /* Kartu Login */
        .card-login {
            border: none;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: white;
            padding: 30px 30px 10px 30px;
            text-align: center;
        }

        .app-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 15px auto;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.3);
        }

        .form-control-lg {
            background-color: #f8f9fa;
            border: 2px solid #f1f1f1;
            font-size: 0.95rem;
            padding: 25px 20px;
            border-radius: 15px;
            transition: all 0.3s;
        }

        .form-control-lg:focus {
            background-color: #fff;
            border-color: #764ba2;
            box-shadow: 0 0 0 4px rgba(118, 75, 162, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px;
            border-radius: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.3);
            transition: transform 0.2s;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>

    <div class="bg-accent"></div>

    <div class="container px-4">
        <div class="card card-login mx-auto animate__animated animate__fadeInUp">

            <div class="login-header">
                <div class="app-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h4 class="font-weight-bold text-dark mb-1">Selamat Datang!</h4>
                <p class="text-muted small">Silakan login untuk memulai aplikasi.</p>
            </div>

            <div class="card-body p-4 pt-2">

                {{-- Error Message --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-lg small shadow-sm mb-4">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted ml-2 mb-2">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control form-control-lg"
                                placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted ml-2 mb-2">PASSWORD</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control form-control-lg"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                            <label class="custom-control-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                        {{-- <a href="#" class="small text-primary font-weight-bold">Lupa Password?</a> --}}
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        MASUK APLIKASI <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>
            </div>

            <div class="text-center pb-4">
                <small class="text-muted opacity-50">&copy; {{ date('Y') }}
                    {{ config('app.name', 'KasirApp') }}</small>
            </div>
        </div>
    </div>

</body>

</html>
