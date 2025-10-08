@extends('admin.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h5 class="text-center">إضافة مورد جديد</h5>
        </div>
        <form method="POST" action="{{ route('suppliers.store') }}" role="form">
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
                            <label for="tax_id">الرقم الضريبي</label>
                            <input type="text" class="form-control" id="tax_id" name="tax_id"
                                value="{{ old('tax_id') }}">
                            @error('tax_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="account_number">رقم الحساب</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                value="{{ old('account_number') }}">
                            @error('account_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="payment_terms">شروط الدفع</label>
                            <input type="text" class="form-control" id="payment_terms" name="payment_terms"
                                value="{{ old('payment_terms') }}">
                            @error('payment_terms')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>
                <h5>أرقام الهواتف</h5>

                <div id="phones-container">
                    <div class="phone-entry row mb-2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="phones[0][number]">رقم الهاتف</label>
                                <input type="text" class="form-control" name="phones[0][number]" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="phones[0][type]">النوع</label>
                                <select class="form-control" name="phones[0][type]">
                                    <option value="mobile">جوال</option>
                                    <option value="work">عمل</option>
                                    <option value="home">منزل</option>
                                    <option value="fax">فاكس</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="phones[0][is_primary]">رئيسي</label>
                                <select class="form-control" name="phones[0][is_primary]">
                                    <option value="1">نعم</option>
                                    <option value="0">لا</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-phone" style="display: none;">حذف</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-phone" class="btn btn-secondary mb-3">إضافة رقم آخر</button>

                <hr>
                <h5>العناوين</h5>

                <div id="addresses-container">
                    <div class="address-entry row mb-2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="addresses[0][street]">الشارع</label>
                                <input type="text" class="form-control" name="addresses[0][street]" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="addresses[0][city]">المدينة</label>
                                <input type="text" class="form-control" name="addresses[0][city]" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="addresses[0][state]">المنطقة</label>
                                <input type="text" class="form-control" name="addresses[0][state]">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="addresses[0][postal_code]">الرمز البريدي</label>
                                <input type="text" class="form-control" name="addresses[0][postal_code]" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="addresses[0][type]">نوع العنوان</label>
                                <select class="form-control" name="addresses[0][type]">
                                    <option value="primary">رئيسي</option>
                                    <option value="billing">فوترة</option>
                                    <option value="shipping">شحن</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="addresses[0][is_primary]">رئيسي</label>
                                <select class="form-control" name="addresses[0][is_primary]">
                                    <option value="1">نعم</option>
                                    <option value="0">لا</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger remove-address"
                                style="display: none;">حذف</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-address" class="btn btn-secondary mb-3">إضافة عنوان آخر</button>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">حالة التفعيل</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1">مفعل</option>
                                <option value="0">غير مفعل</option>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Phone management
            let phoneIndex = 1;
            document.getElementById('add-phone').addEventListener('click', function() {
                const container = document.getElementById('phones-container');
                const template = document.querySelector('.phone-entry').cloneNode(true);

                // Update indices
                template.querySelectorAll('input, select').forEach(el => {
                    const name = el.getAttribute('name').replace('[0]', `[${phoneIndex}]`);
                    el.setAttribute('name', name);
                    el.value = '';
                });

                // Show remove button
                template.querySelector('.remove-phone').style.display = 'block';

                container.appendChild(template);
                phoneIndex++;
            });

            // Address management
            let addressIndex = 1;
            document.getElementById('add-address').addEventListener('click', function() {
                const container = document.getElementById('addresses-container');
                const template = document.querySelector('.address-entry').cloneNode(true);

                // Update indices
                template.querySelectorAll('input, select').forEach(el => {
                    const name = el.getAttribute('name').replace('[0]', `[${addressIndex}]`);
                    el.setAttribute('name', name);
                    el.value = '';
                });

                // Show remove button
                template.querySelector('.remove-address').style.display = 'block';

                container.appendChild(template);
                addressIndex++;
            });

            // Remove phone entry
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-phone')) {
                    e.target.closest('.phone-entry').remove();
                }

                if (e.target.classList.contains('remove-address')) {
                    e.target.closest('.address-entry').remove();
                }
            });
        });
    </script>
@endsection
