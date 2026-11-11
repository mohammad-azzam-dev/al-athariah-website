@extends('admin.layouts.auth')
@section('title', 'استعادة كلمة المرور')
@section('heading', 'استعادة كلمة المرور')

@section('content')
    <p class="login-box-msg">اكتب بريدك الإلكتروني وسنرسل لك رابطًا لتعيين كلمة مرور جديدة.</p>
    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf
        <div class="input-group mb-3 has-validation">
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="البريد الإلكتروني" autocomplete="username" required autofocus aria-label="البريد الإلكتروني">
            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="d-grid mb-3"><button type="submit" class="btn btn-primary">إرسال رابط الاستعادة</button></div>
        <p class="mb-0 text-center"><a href="{{ route('login') }}">العودة إلى تسجيل الدخول</a></p>
    </form>
@endsection
