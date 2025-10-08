@extends('admin.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">إضافة مستودع جديد</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('warehouses.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">اسم المستودع</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">كود المستودع</label>
                            <input type="text" name="code" id="code" class="form-control"
                                value="{{ old('code') }}" required>
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">هاتف المستودع</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">عنوان المستودع (عام)</label>
                    <textarea name="address" id="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <h4>تفاصيل العنوان</h4>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="line1">السطر الأول من العنوان</label>
                            <input type="text" name="line1" id="line1" class="form-control"
                                value="{{ old('line1') }}">
                            @error('line1')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="line2">السطر الثاني من العنوان</label>
                            <input type="text" name="line2" id="line2" class="form-control"
                                value="{{ old('line2') }}">
                            @error('line2')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">المدينة</label>
                            <input type="text" name="city" id="city" class="form-control"
                                value="{{ old('city') }}">
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="state">المنطقة/الولاية</label>
                            <input type="text" name="state" id="state" class="form-control"
                                value="{{ old('state') }}">
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="postal_code">الرمز البريدي</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control"
                                value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="country">البلد</label>
                    <input type="text" name="country" id="country" class="form-control"
                        value="{{ old('country', 'السعودية') }}">
                    @error('country')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <h4>تفاصيل الهاتف</h4>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_number">رقم الهاتف</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_type">نوع الهاتف</label>
                            <select name="ph2one_type" id="phone_type" class="form-control">
                                <option value="mobile" {{ old('phone_type') == 'mobile' ? 'selected' : '' }}>جوال</option>
                                <option value="landline" {{ old('phone_type') == 'landline' ? 'selected' : '' }}>هاتف أرضي
                                </option>
                                <option value="fax" {{ old('phone_type') == 'fax' ? 'selected' : '' }}>فاكس</option>
                            </select>
                            @error('phone_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="is_active" id="is_active" class="custom-control-input"
                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">مفعل</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">حفظ</button>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
