@extends('admin.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- رسائل التنبيه -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-red-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</p>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- رجوع -->
        <div class="mb-6">
            <a href="{{ route('warehouses.show', $warehouse->id) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة إلى تفاصيل المستودع
            </a>
        </div>

        <!-- العنوان الرئيسي -->
        <div class="mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                </div>
                <div class="mr-4">
                    <h1 class="text-2xl font-bold text-gray-800">تعديل المستودع</h1>
                    <p class="text-gray-600 mt-1">{{ $warehouse->name }}</p>
                </div>
            </div>
        </div>

        <!-- النموذج -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- المعلومات الأساسية -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-6 h-6 inline-block ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        المعلومات الأساسية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- اسم المستودع -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المستودع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- كود المستودع -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                كود المستودع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono"
                                   required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $warehouse->email) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   dir="ltr">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الهاتف الأساسي -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                هاتف المستودع
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $warehouse->phone) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   dir="ltr">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- العنوان العام -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-6 h-6 inline-block ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        العنوان العام
                    </h3>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            عنوان المستودع (عام)
                        </label>
                        <textarea name="address" id="address" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('address', $warehouse->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- تفاصيل العنوان -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        تفاصيل العنوان
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- السطر الأول من العنوان -->
                        <div>
                            <label for="line1" class="block text-sm font-medium text-gray-700 mb-2">
                                السطر الأول من العنوان
                            </label>
                            <input type="text" name="line1" id="line1"
                                   value="{{ old('line1', $warehouse->primaryAddress() ? $warehouse->primaryAddress()->line1 : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('line1')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- السطر الثاني من العنوان -->
                        <div>
                            <label for="line2" class="block text-sm font-medium text-gray-700 mb-2">
                                السطر الثاني من العنوان
                            </label>
                            <input type="text" name="line2" id="line2"
                                   value="{{ old('line2', $warehouse->primaryAddress() ? $warehouse->primaryAddress()->line2 : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('line2')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- المدينة -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                المدينة
                            </label>
                            <input type="text" name="city" id="city"
                                   value="{{ old('city', $warehouse->primaryAddress() ? $warehouse->primaryAddress()->city : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- المنطقة/الولاية -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                المنطقة/الولاية
                            </label>
                            <input type="text" name="state" id="state"
                                   value="{{ old('state', $warehouse->primaryAddress() ? $warehouse->primaryAddress()->state : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الرمز البريدي -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                الرمز البريدي
                            </label>
                            <input type="text" name="postal_code" id="postal_code"
                                   value="{{ old('postal_code', $warehouse->primaryAddress() ? $warehouse->primaryAddress()->postal_code : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- البلد -->
                    <div class="w-full md:w-1/2">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            البلد
                        </label>
                        <input type="text" name="country" id="country"
                               value="{{ old('country', $warehouse->primaryAddress() ? $warehouse->primaryAddress()->country : 'السعودية') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- تفاصيل الهاتف -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-6 h-6 inline-block ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        تفاصيل الهاتف
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- رقم الهاتف -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهاتف
                            </label>
                            <input type="text" name="phone_number" id="phone_number"
                                   value="{{ old('phone_number', $warehouse->primaryPhone() ? $warehouse->primaryPhone()->number : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   dir="ltr">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- نوع الهاتف -->
                        <div>
                            <label for="phone_type" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع الهاتف
                            </label>
                            <select name="phone_type" id="phone_type"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                                <option value="">اختر نوع الهاتف</option>
                                <option value="mobile" {{ old('phone_type', $warehouse->primaryPhone() ? $warehouse->primaryPhone()->type : '') == 'mobile' ? 'selected' : '' }}>جوال</option>
                                <option value="landline" {{ old('phone_type', $warehouse->primaryPhone() ? $warehouse->primaryPhone()->type : '') == 'landline' ? 'selected' : '' }}>هاتف أرضي</option>
                                <option value="fax" {{ old('phone_type', $warehouse->primaryPhone() ? $warehouse->primaryPhone()->type : '') == 'fax' ? 'selected' : '' }}>فاكس</option>
                            </select>
                            @error('phone_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الإعدادات -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-6 h-6 inline-block ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        الإعدادات
                    </h3>

                    <div class="space-y-6">
                        <!-- المستودع الافتراضي -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_default" id="is_default" value="1"
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ old('is_default', $warehouse->is_default) ? 'checked' : '' }}>
                            </div>
                            <div class="mr-3">
                                <label for="is_default" class="text-sm font-medium text-gray-700 cursor-pointer">
                                    تعيين كمستودع افتراضي
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    سيتم استخدام هذا المستودع كافتراضي في العمليات المختلفة.
                                    <span class="text-blue-600 font-medium">ملاحظة: عند تفعيل هذا الخيار، سيتم إلغاء المستودع الافتراضي الحالي.</span>
                                </p>
                            </div>
                        </div>
                        @error('is_default')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- تفعيل المستودع -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                            </div>
                            <div class="mr-3">
                                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                                    تفعيل المستودع
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    يمكن استخدام المستودع في العمليات المخزنية. إذا كان غير مفعل، فلن يظهر في القوائم المنسدلة.
                                </p>
                            </div>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- أزرار الإجراء -->
                <div class="flex items-center justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                    <a href="{{ route('warehouses.show', $warehouse->id) }}"
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تلميحات للخيارات
        const isDefaultCheckbox = document.getElementById('is_default');
        const isActiveCheckbox = document.getElementById('is_active');

        if (isDefaultCheckbox) {
            isDefaultCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    if (!confirm('سيتم تعيين هذا المستودع كافتراضي وسيتم إلغاء المستودع الافتراضي الحالي. هل أنت متأكد؟')) {
                        this.checked = false;
                    }
                }
            });
        }
    });
</script>
@endsection
