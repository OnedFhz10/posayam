@extends('layouts.app')

@section('content')
    <style>
        /* Reset Body */
        body {
            background-color: #e3f2fd;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        /* Kartu Login Utama */
        .login-container {
            width: 100%;
            max-width: 380px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Bagian Header Hijau */
        .login-header {
            background-color: #1abc9c;
            /* Warna Teal */
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        /* Style Logo Brand */
        .brand-logo-container {
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .brand-logo-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .login-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }

        /* Bagian Form */
        .login-body {
            padding: 30px 25px;
        }

        /* Input Group */
        .input-group-custom {
            display: flex;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            transition: border-color 0.3s;
        }

        .input-group-custom:focus-within {
            border-color: #1abc9c;
        }

        .input-icon {
            background-color: #16a085;
            color: white;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .form-control-custom {
            border: none;
            padding: 12px 15px;
            width: 100%;
            outline: none;
            font-size: 1rem;
            color: #555;
        }

        /* Link Forgot */
        .forgot-link {
            display: block;
            color: #16a085;
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Tombol Login */
        .btn-login {
            width: 100%;
            background-color: #16a085;
            color: white;
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #149174;
        }
    </style>

    <div class="login-container">
        {{-- Header dengan Logo --}}
        <div class="login-header">

            {{-- AREA LOGO BRAND --}}
            <div class="brand-logo-container">
                {{-- Ganti src ini dengan logo Anda --}}
                <img src="https://cdn-icons-png.flaticon.com/512/2921/2921822.png" alt="Logo Brand" class="brand-logo-img">
            </div>

            <h3>Login Kasir</h3>
        </div>

        {{-- Isi Form --}}
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="input-group-custom">
                    <div class="input-icon"><i class="fas fa-user"></i></div>
                    <input type="email" name="email" class="form-control-custom" placeholder="Email Address" required
                        autofocus value="{{ old('email') }}">
                </div>
                @error('email')
                    <small class="text-danger d-block mb-3" style="margin-top:-15px;">{{ $message }}</small>
                @enderror

                {{-- Password --}}
                <div class="input-group-custom">
                    <div class="input-icon"><i class="fas fa-lock"></i></div>
                    <input type="password" name="password" class="form-control-custom" placeholder="Password" required>
                </div>
                @error('password')
                    <small class="text-danger d-block mb-3" style="margin-top:-15px;">{{ $message }}</small>
                @enderror

                {{-- Forgot Password (Opsional, akan hilang jika route tidak ada) --}}
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif

                <button type="submit" class="btn-login">Login</button>

            </form>
        </div>
    </div>
@endsection
