@extends('admin.layouts.auth')
@section('title', 'تعيين كلمة مرور جديدة')
@section('heading', 'تعيين كلمة مرور جديدة')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3">
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">كلمة المرور الجديدة</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>
        <div class="d-grid"><button type="submit" class="btn btn-primary">حفظ كلمة المرور</button></div>
    </form>
@endsection
