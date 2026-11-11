@extends('admin.layouts.auth')
@section('title', 'تأكيد كلمة المرور')
@section('heading', 'تأكيد كلمة المرور')

@section('content')
    <p class="login-box-msg">هذه منطقة محمية؛ أكّد كلمة المرور للمتابعة.</p>
    <form method="POST" action="{{ route('password.confirm.store') }}" novalidate>
        @csrf
        <div class="mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="كلمة المرور" required autocomplete="current-password" autofocus aria-label="كلمة المرور">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="d-grid"><button type="submit" class="btn btn-primary">تأكيد</button></div>
    </form>
@endsection
