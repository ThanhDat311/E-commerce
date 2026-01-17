@extends('layouts.master')

@section('title', 'Register Account - Electro')

@section('content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Register</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">Register</li>
    </ol>
</div>

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0 fw-bold text-white">CREATE AN ACCOUNT</h4>
                    </div>
                    <div class="card-body p-5">
                        
                        {{-- Hiển thị thông báo lỗi chung --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control py-2" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Your Full Name">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control py-2" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control py-2" id="password" name="password" required autocomplete="new-password" placeholder="Min 8 characters">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                                <input type="password" class="form-control py-2" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter password">
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                <label class="form-check-label text-muted small" for="flexCheckDefault">
                                  I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> & <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary text-white py-2 fw-bold rounded-pill shadow-sm">
                                    REGISTER NOW
                                </button>
                            </div>

                            <hr class="my-4">

                            <div class="text-center">
                                <span class="text-muted">Already have an account? </span>
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Log In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection