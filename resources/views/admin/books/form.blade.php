@extends('admin.layouts.app')
@php
    $editing = $book->exists;
    $books = config('athariyah.books');
    $maxMb = round($books['max_kb'] / 1024);
    $err = fn ($f) => $errors->has($f) ? ' is-invalid' : '';
@endphp
@section('title', $editing ? 'تعديل كتاب' : 'إضافة كتاب')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.books.index') }}">الكتب</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $editing ? 'تعديل' : 'إضافة' }}</li>
@endsection


@section('content')
<form method="POST" action="{{ $editing ? route('admin.books.update', $book) : route('admin.books.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header"><h3 class="card-title">بيانات الكتاب</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="title">العنوان <span class="text-danger">*</span></label>
                        <input id="title" name="title" class="form-control form-control-lg{{ $err('title') }}" value="{{ old('title', $book->title) }}" required maxlength="255">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="author">المؤلف</label>
                            <input id="author" name="author" class="form-control{{ $err('author') }}" value="{{ old('author', $book->author) }}" maxlength="255" placeholder="اتركه فارغًا إن لم يُذكر">
                            @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="field">المادة <span class="text-danger">*</span></label>
                            <input id="field" name="field" class="form-control{{ $err('field') }}" value="{{ old('field', $book->field) }}" required maxlength="120" placeholder="مثال: التوحيد، النحو، الحديث">
                            @error('field')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="group">المجال (للتصنيف والألوان) <span class="text-danger">*</span></label>
                            <select id="group" name="group" class="form-select{{ $err('group') }}" required>
                                @foreach ($groups as $id => $label)<option value="{{ $id }}" @selected(old('group', $book->group) === $id)>{{ $label }}</option>@endforeach
                            </select>
                            @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="kind">النوع <span class="text-danger">*</span></label>
                            <select id="kind" name="kind" class="form-select{{ $err('kind') }}" required>
                                @foreach ($kinds as $id => $label)<option value="{{ $id }}" @selected(old('kind', $book->kind) === $id)>{{ $label }}</option>@endforeach
                            </select>
                            @error('kind')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="programme">البرنامج <span class="text-danger">*</span></label>
                            <select id="programme" name="programme" class="form-select{{ $err('programme') }}" required>
                                @foreach ($programmes as $id => $label)<option value="{{ $id }}" @selected(old('programme', $book->programme) === $id)>{{ $label }}</option>@endforeach
                            </select>
                            @error('programme')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3" id="section-wrap">
                            <label class="form-label" for="section">القسم داخل المقرأة</label>
                            <select id="section" name="section" class="form-select{{ $err('section') }}">
                                <option value="">قسم القرآن / السنة</option>
                                <option value="matun" @selected(old('section', $book->section) === 'matun')>قسم المتون العلمية</option>
                            </select>
                            @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="level">المستوى / البرنامج الفرعي <span class="text-danger">*</span></label>
                            <input id="level" name="level" class="form-control{{ $err('level') }}" value="{{ old('level', $book->level) }}" required maxlength="255" placeholder="مثال: برنامج «درجات»، دبلوم العلوم الشرعية">
                            @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="method">طريقة الدراسة <span class="text-danger">*</span></label>
                            <input id="method" name="method" class="form-control{{ $err('method') }}" value="{{ old('method', $book->method) }}" required maxlength="120" list="methods" placeholder="تفاعلي، دراسة ذاتية، حفظ…">
                            <datalist id="methods"><option value="تفاعلي"><option value="دراسة ذاتية"><option value="حفظ"><option value="حفظ واختبار"><option value="حفظ مع اختبار في شروحها"><option value="اختبار تطبيقي"></datalist>
                            @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="note">ملاحظة</label>
                        <input id="note" name="note" class="form-control{{ $err('note') }}" value="{{ old('note', $book->note) }}" maxlength="500" placeholder="مثال: كاملًا، الكتاب ١–٧">
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="topics_text">المفردات (اختياري، كل مفردة في سطر)</label>
                        <textarea id="topics_text" name="topics_text" rows="4" class="form-control{{ $err('topics') }}">{{ old('topics_text', implode("\n", $book->topics ?? [])) }}</textarea>
                        @error('topics')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-warning card-outline mb-4">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-cloud-arrow-up me-1"></i> النسخة الرقمية</h3></div>
                <div class="card-body">
                    @if ($book->hasFile())
                        <div class="admin-file mb-3">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <div>
                                <a href="{{ route('library.book.file', $book) }}" target="_blank" rel="noopener" class="fw-semibold d-block text-break">{{ $book->file_name }}</a>
                                <span class="small text-secondary" dir="ltr">{{ $book->humanFileSize() }} · {{ strtoupper($book->file_disk) }}</span>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remove_file" value="1" id="remove_file">
                            <label class="form-check-label text-danger" for="remove_file">حذف الملف الحالي</label>
                        </div>
                    @endif
                    <label class="form-label" for="file">{{ $book->hasFile() ? 'استبدال الملف' : 'رفع ملف' }}</label>
                    <input id="file" type="file" name="file" class="form-control{{ $err('file') }}" accept=".{{ implode(',.', $books['mimes']) }}">
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">PDF أو EPUB أو Word، حتى {{ $maxMb }} ميغابايت. يُحفظ في Cloudflare R2 ويظهر للزوار زر «قراءة / تحميل».</div>
                </div>
            </div>

            <div class="card card-success card-outline mb-4">
                <div class="card-header"><h3 class="card-title">النشر</h3></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_published" value="1" id="is_published" @checked(old('is_published', $book->is_published))>
                        <label class="form-check-label" for="is_published">ظاهر في المكتبة العامة</label>
                    </div>
                    <label class="form-label" for="sort_order">الترتيب في الفهرس</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" class="form-control{{ $err('sort_order') }}" value="{{ old('sort_order', $book->sort_order) }}">
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">الأصغر يظهر أولًا.</div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-check2-circle me-1"></i> {{ $editing ? 'حفظ التعديلات' : 'إضافة الكتاب' }}</button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // The section choice only applies to المقرأة.
    (function () {
        var prog = document.getElementById('programme'), wrap = document.getElementById('section-wrap');
        function sync() { wrap.hidden = prog.value !== 'maqra'; }
        prog.addEventListener('change', sync); sync();
    })();
</script>
@endpush
