@extends('admin.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h5 class="text-center">إضافة عميل جديد</h5>
        </div>
        <form method="POST" action="{{ route('customers.store') }}" role="form">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="customer_type">نوع العميل</label>
                            <select class="form-control" id="customer_type" name="customer_type" required>
                                <option value="retail" {{ old('customer_type') == 'retail' ? 'selected' : '' }}>تجزئة
                                </option>
                                <option value="wholesale" {{ old('customer_type') == 'wholesale' ? 'selected' : '' }}>جملة
                                </option>
                            </select>
                            @error('customer_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="credit_limit">حد الائتمان</label>
                            <input type="number" step="0.01" class="form-control" id="credit_limit" name="credit_limit"
                                value="{{ old('credit_limit', 0) }}">
                            @error('credit_limit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="loyalty_points">نقاط المكافآت</label>
                            <input type="number" class="form-control" id="loyalty_points" name="loyalty_points"
                                value="{{ old('loyalty_points', 0) }}">
                            @error('loyalty_points')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">حالة التفعيل</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>مفعل</option>
                                <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>غير مفعل</option>
                            </select>
                            @error('active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">ملاحظات</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </div>
        </form>
    </div>
@endsection
