@extends('layouts.master')

@section('title', 'Login - Electro')

@section('content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Login</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">Login</li>
    </ol>
</div>

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0 fw-bold text-white">ACCOUNT LOGIN</h4>
                    </div>
                    <div class="card-body p-5">
                        
                        {{-- Hiển thị thông báo (ví dụ: Logout thành công) --}}
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Hiển thị lỗi validate --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control py-2" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control py-2" id="password" name="password" required placeholder="Enter your password">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                    <label class="form-check-label text-muted small" for="remember_me">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-primary text-decoration-none small fw-bold" href="{{ route('password.request') }}">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary text-white py-2 fw-bold rounded-pill shadow-sm">
                                    LOG IN
                                </button>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="text-center">
                                <span class="text-muted">Don't have an account? </span>
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Register Here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection