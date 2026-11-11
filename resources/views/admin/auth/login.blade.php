@extends('admin.layouts.auth')
@section('title', 'تسجيل الدخول')
@section('heading', 'تسجيل دخول المشرفين')

@section('content')
    <p class="login-box-msg">لوحة إدارة المكتبة الأثرية</p>
    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf
        <div class="input-group mb-3 has-validation">
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="البريد الإلكتروني" autocomplete="username" required autofocus aria-label="البريد الإلكتروني">
            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="input-group mb-3 has-validation">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="كلمة المرور" autocomplete="current-password" required aria-label="كلمة المرور">
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                <label class="form-check-label" for="remember">تذكّرني</label>
            </div>
            <a href="{{ route('password.request') }}" class="small">نسيت كلمة المرور؟</a>
        </div>
        <div class="d-grid"><button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-left me-1"></i> دخول</button></div>
    </form>
@endsection
