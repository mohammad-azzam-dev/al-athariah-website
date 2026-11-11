@extends('admin.layouts.app')
@section('title', 'الرئيسية')

@php
    $fmt = function (int $bytes): string {
        $u = ['B', 'KB', 'MB', 'GB']; $i = 0; $s = (float) $bytes;
        while ($s >= 1024 && $i < 3) { $s /= 1024; $i++; }
        return round($s, $i ? 1 : 0).' '.$u[$i];
    };
@endphp

@section('content')
    @unless ($diskReady)
        <div class="callout callout-warning mb-4">
            <h5 class="mb-1"><i class="bi bi-cloud-slash me-1"></i> تخزين الملفات غير مُعدّ بعد</h5>
            <p class="mb-0">لرفع النسخ الرقمية إلى Cloudflare R2 أضف قيم <code>R2_ACCESS_KEY_ID</code> و<code>R2_SECRET_ACCESS_KEY</code> و<code>R2_BUCKET</code> و<code>R2_ENDPOINT</code> في ملف <code>.env</code>. يمكنك إضافة الكتب وتعديلها الآن، ورفع الملفات بعد الإعداد.</p>
        </div>
    @endunless

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box admin-box admin-box--green">
                <div class="inner"><h3 data-count="{{ $stats['total'] }}">{{ $stats['total'] }}</h3><p>كتاب في المكتبة</p></div>
                <i class="small-box-icon bi bi-journal-bookmark-fill"></i>
                <a href="{{ route('admin.books.index') }}" class="small-box-footer link-light link-underline-opacity-0">عرض الكتب <i class="bi bi-arrow-left-circle"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box admin-box admin-box--gold">
                <div class="inner"><h3 data-count="{{ $stats['with_files'] }}">{{ $stats['with_files'] }}</h3><p>كتاب له نسخة رقمية</p></div>
                <i class="small-box-icon bi bi-file-earmark-pdf-fill"></i>
                <a href="{{ route('admin.books.index', ['file' => 'with']) }}" class="small-box-footer link-dark link-underline-opacity-0">عرض <i class="bi bi-arrow-left-circle"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box admin-box admin-box--teal">
                <div class="inner"><h3 data-count="{{ $stats['published'] }}">{{ $stats['published'] }}</h3><p>منشور في الموقع</p></div>
                <i class="small-box-icon bi bi-eye-fill"></i>
                <a href="{{ route('library') }}" target="_blank" rel="noopener" class="small-box-footer link-light link-underline-opacity-0">المكتبة العامة <i class="bi bi-arrow-left-circle"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box admin-box admin-box--ink">
                <div class="inner"><h3 dir="ltr">{{ $fmt($stats['storage']) }}</h3><p>حجم الملفات على {{ strtoupper($disk) }}</p></div>
                <i class="small-box-icon bi bi-cloud-fill"></i>
                <span class="small-box-footer">{{ $diskReady ? 'متصل' : 'غير مُعدّ' }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">الكتب حسب البرنامج</h3></div>
                <div class="card-body">
                    @foreach ($programmes as $id => $label)
                        @php($row = $byProgramme[$id] ?? null)
                        @php($total = $row->total ?? 0)
                        @php($files = $row->with_files ?? 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1"><strong>{{ $label }}</strong><span class="text-secondary small">{{ $total }} كتاب · {{ $files }} ملف</span></div>
                            <div class="progress" role="progressbar" aria-label="{{ $label }}" aria-valuenow="{{ $total }}" aria-valuemin="0" aria-valuemax="{{ max(1, $stats['total']) }}">
                                <div class="progress-bar admin-progress" style="width: {{ $stats['total'] ? round($total / $stats['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">آخر التعديلات</h3>
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg"></i> إضافة كتاب</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                        @foreach ($latest as $b)
                            <tr>
                                <td><a href="{{ route('admin.books.edit', $b) }}" class="fw-semibold">{{ $b->title }}</a><div class="small text-secondary">{{ $b->author ?: 'المؤلف غير مذكور' }}</div></td>
                                <td class="text-nowrap">@if ($b->hasFile())<span class="badge text-bg-warning"><i class="bi bi-file-earmark-arrow-down"></i> ملف</span>@endif</td>
                                <td class="text-nowrap small text-secondary">{{ $b->updated_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
