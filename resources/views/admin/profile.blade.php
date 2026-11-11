@extends('admin.layouts.app')
@section('title', 'الملف الشخصي')
@section('breadcrumb')<li class="breadcrumb-item active" aria-current="page">الملف الشخصي</li>@endsection

@section('content')
    @if (session('status') === 'profile-information-updated')
        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i> تم حفظ بياناتك.</div>
    @elseif (session('status') === 'password-updated')
        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i> تم تغيير كلمة المرور.</div>
    @endif

    <div class="row" style="max-width: 1200px">
        <div class="col-lg-6">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header"><h3 class="card-title">البيانات الشخصية</h3></div>
                <form method="POST" action="{{ route('user-profile-information.update') }}" novalidate>
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">الاسم</label>
                            <input id="name" name="name" class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name', 'updateProfileInformation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="email">البريد الإلكتروني</label>
                            <input id="email" type="email" name="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email', 'updateProfileInformation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="card-footer"><button class="btn btn-primary" type="submit">حفظ</button></div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-warning card-outline mb-4">
                <div class="card-header"><h3 class="card-title">تغيير كلمة المرور</h3></div>
                <form method="POST" action="{{ route('user-password.update') }}" novalidate>
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="current_password">كلمة المرور الحالية</label>
                            <input id="current_password" type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password" required>
                            @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">كلمة المرور الجديدة</label>
                            <input id="password" type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password" required>
                            @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                        </div>
                    </div>
                    <div class="card-footer"><button class="btn btn-warning" type="submit">تغيير كلمة المرور</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
