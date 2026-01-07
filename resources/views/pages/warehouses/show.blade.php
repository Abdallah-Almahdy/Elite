@extends('admin.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- رجوع -->
        <div class="mb-6">
            <a href="{{ route('warehouses.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة إلى قائمة المستودعات
            </a>
        </div>

        <!-- العنوان الرئيسي -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">تفاصيل المستودع</h1>
                    <div class="flex items-center mt-2">
                        @if($warehouse->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 mr-4">
                                <span class="w-2 h-2 ml-1 bg-green-500 rounded-full animate-pulse"></span>
                                نشط
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 mr-4">
                                <span class="w-2 h-2 ml-1 bg-red-500 rounded-full"></span>
                                غير نشط
                            </span>
                        @endif

                        @if($warehouse->is_default)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                افتراضي
                            </span>
                        @endif
                    </div>
                </div>

                <!-- أزرار الإجراء -->
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}"
                           class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            تعديل
                        </a>

                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('هل أنت متأكد من حذف المستودع \'{{ $warehouse->name }}\'؟')"
                                    class="inline-flex items-center px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- المعلومات الرئيسية -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- البطاقة الرئيسية -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mr-4">
                                <h2 class="text-xl font-bold text-gray-800">{{ $warehouse->name }}</h2>
                                <p class="text-gray-600 mt-1">كود:
                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded text-gray-800">
                                        {{ $warehouse->code }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- البريد الإلكتروني -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">البريد الإلكتروني</h3>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-800">{{ $warehouse->email ?? 'غير محدد' }}</span>
                                </div>
                            </div>

                            <!-- الهاتف الأساسي -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">الهاتف الأساسي</h3>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-gray-800">{{ $warehouse->phone ?? 'غير محدد' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات إضافية</h3>

                        <!-- تاريخ الإنشاء -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">تاريخ الإنشاء</h4>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-800">{{ $warehouse->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>

                        <!-- آخر تحديث -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">آخر تحديث</h4>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-800">{{ $warehouse->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- العنوان -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- العنوان العام -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        العنوان العام
                    </h3>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-800">{{ $warehouse->address ?? 'غير محدد' }}</p>
                    </div>
                </div>
            </div>

            <!-- تفاصيل العنوان -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">تفاصيل العنوان</h3>

                    <div class="space-y-3">
                        @if($warehouse->primaryAddress())
                            @php $address = $warehouse->primaryAddress(); @endphp
                            <div class="flex items-start">
                                <svg class="w-5 h-5 ml-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">العنوان</h4>
                                    <p class="text-gray-800">
                                        {{ $address->line1 }}{{ $address->line2 ? ', ' . $address->line2 : '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 ml-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">المدينة والمنطقة</h4>
                                    <p class="text-gray-800">{{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}</p>
                                </div>
                            </div>

                            @if($address->postal_code || $address->country)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 ml-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">الرمز البريدي والبلد</h4>
                                    <p class="text-gray-800">
                                        {{ $address->postal_code }}{{ $address->country ? ' - ' . $address->country : '' }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-center py-4">لا توجد تفاصيل عنوان متاحة</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل الهاتف -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    تفاصيل الهاتف
                </h3>

                @if($warehouse->primaryPhone())
                    @php $phone = $warehouse->primaryPhone(); @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">رقم الهاتف</h4>
                            <p class="text-gray-800 text-lg font-medium">{{ $phone->number }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">نوع الهاتف</h4>
                            <p class="text-gray-800">
                                @switch($phone->type)
                                    @case('mobile')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            جوال
                                        </span>
                                        @break
                                    @case('landline')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            هاتف أرضي
                                        </span>
                                        @break
                                    @case('fax')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            فاكس
                                        </span>
                                        @break
                                    @default
                                        {{ $phone->type }}
                                @endswitch
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا توجد تفاصيل هاتف متاحة</p>
                @endif
            </div>
        </div>

        <!-- ملخص المعلومات -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">ملخص المعلومات</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-blue-600 mb-2">حالة المستودع</h4>
                    <p class="text-blue-800">
                        @if($warehouse->is_active)
                            يمكن استخدام هذا المستودع في العمليات المخزنية
                        @else
                            هذا المستودع غير مفعل حاليًا
                        @endif
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-blue-600 mb-2">المستودع الافتراضي</h4>
                    <p class="text-blue-800">
                        @if($warehouse->is_default)
                            هذا هو المستودع الرئيسي الافتراضي للنظام
                        @else
                            هذا مستودع عادي وليس افتراضي
                        @endif
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-blue-600 mb-2">الكود المرجعي</h4>
                    <p class="text-blue-800 font-mono text-lg">{{ $warehouse->code }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
