@extends('admin.app')

@section('content')
    <div class="card card-primary h-100">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title text-center mb-0">
                <i class="fas fa-plus-circle me-2"></i>
                إضافة وحدة جديدة
            </h5>
        </div>

        <form method="POST" action="{{ route('units.store') }}" role="form" class="h-100 d-flex flex-column">
            @csrf
            <div class="card-body flex-grow-1">
                <div class="row">
                    {{-- الاسم --}}
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="name" class="form-label fw-semibold">
                                اسم الوحدة
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name"
                                   value="{{ old('name') }}" required
                                   placeholder="أدخل اسم الوحدة">
                            @error('name')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- الحالة --}}
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="active" class="form-label fw-semibold">
                                حالة الوحدة
                            </label>
                            <select class="form-control form-select form-control-lg" id="active" name="is_active">
                                <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>
                                    <span class="text-success">
                                        <i class="fas fa-check-circle me-2"></i>مفعل
                                    </span>
                                </option>
                                <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>
                                    <span class="text-danger">
                                        <i class="fas fa-times-circle me-2"></i>غير مفعل
                                    </span>
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- زر الإرسال --}}
            <div class="card-footer bg-light py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-1"></i>
                        رجوع للقائمة
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>
                        إضافة الوحدة
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
