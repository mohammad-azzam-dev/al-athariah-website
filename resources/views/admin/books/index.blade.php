@extends('admin.layouts.app')
@section('title', 'الكتب')
@section('breadcrumb')<li class="breadcrumb-item active" aria-current="page">الكتب</li>@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <form class="row g-2 align-items-end" method="get" action="{{ route('admin.books.index') }}" role="search">
                <div class="col-md-5">
                    <label class="form-label small mb-1" for="q">بحث</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="العنوان أو المؤلف أو المادة">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1" for="programme">البرنامج</label>
                    <select id="programme" name="programme" class="form-select">
                        <option value="">الكل</option>
                        @foreach ($programmes as $id => $label)<option value="{{ $id }}" @selected(request('programme') === $id)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1" for="file">النسخة الرقمية</label>
                    <select id="file" name="file" class="form-select">
                        <option value="">الكل</option>
                        <option value="with" @selected(request('file') === 'with')>لها ملف</option>
                        <option value="without" @selected(request('file') === 'without')>بدون ملف</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary flex-fill" type="submit"><i class="bi bi-search"></i> تصفية</button>
                    @if (request()->hasAny(['q', 'programme', 'file']))<a class="btn btn-outline-secondary" href="{{ route('admin.books.index') }}" title="مسح"><i class="bi bi-x-lg"></i></a>@endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th style="width: 4rem">الترتيب</th>
                            <th>الكتاب</th>
                            <th>البرنامج / المستوى</th>
                            <th>المجال</th>
                            <th>النسخة الرقمية</th>
                            <th>الحالة</th>
                            <th class="text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td class="text-secondary">{{ $book->sort_order }}</td>
                            <td>
                                <a href="{{ route('admin.books.edit', $book) }}" class="fw-semibold">{{ $book->title }}</a>
                                <div class="small text-secondary">{{ $book->author ?: 'المؤلف غير مذكور' }} · {{ $book->field }}</div>
                            </td>
                            <td><span class="badge admin-badge-prog">{{ $programmes[$book->programme] ?? $book->programme }}</span><div class="small text-secondary mt-1">{{ $book->level }}</div></td>
                            <td class="small">{{ $groups[$book->group] ?? $book->group }}</td>
                            <td>
                                @if ($book->hasFile())
                                    <a href="{{ route('library.book.file', $book) }}" target="_blank" rel="noopener" class="badge text-bg-warning text-decoration-none" title="{{ $book->file_name }}">
                                        <i class="bi bi-file-earmark-arrow-down"></i> <span dir="ltr">{{ $book->humanFileSize() }}</span>
                                    </a>
                                @else
                                    <span class="text-secondary small">—</span>
                                @endif
                            </td>
                            <td>{!! $book->is_published ? '<span class="badge text-bg-success">منشور</span>' : '<span class="badge text-bg-secondary">مخفي</span>' !!}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="d-inline" data-confirm="حذف «{{ $book->title }}» وملفه نهائيًا؟">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="حذف {{ $book->title }}"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary py-5">لا توجد كتب مطابقة.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap align-items-center gap-2">
            <span class="text-secondary small">{{ $books->total() }} كتاب</span>
            <div class="ms-auto">{{ $books->onEachSide(1)->links() }}</div>
        </div>
    </div>
@endsection
