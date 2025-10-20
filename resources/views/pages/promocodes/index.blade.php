@extends('admin.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">أكواد الخصم</h1>
                    <p class="text-gray-600">إدارة وعرض جميع أكواد الخصم في النظام</p>
                </div>
                <a href="{{ route('promocodes.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>إضافة كود جديد</span>
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الأكواد</p>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $data->count() }}</h3>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">أكواد نشطة</p>
                        <h3 class="text-xl font-semibold text-green-600">{{ $data->where('active', true)->count() }}</h3>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">أكواد منتهية</p>
                        <h3 class="text-xl font-semibold text-red-600">{{ $data->where('active', false)->count() }}</h3>
                    </div>
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Table Header -->
            

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">الكود</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">النوع</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">الخصم</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">الحد الأدنى</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">الانتهاء</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">الحالة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($data as $promo)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <!-- Code -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded text-sm border border-blue-200">
                                        {{ $promo->code }}
                                    </span>
                                </div>
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-3">
                                <div class="space-y-1">
                                    <span class="block text-sm text-gray-900">
                                        @if($promo->promo_cat == 'user') مستخدم واحد @else متعدد المستخدمين @endif
                                    </span>
                                    <span class="block text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        @if($promo->type == 'limited') محدود @else غير محدود @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Discount -->
                            <td class="px-4 py-3">
                                <div class="space-y-1">
                                    <span class="block text-sm font-medium text-gray-900">
                                        @if($promo->discount_type == 'percentage')
                                        {{ $promo->discount_percentage_value }}%
                                        @else
                                        {{ $promo->discount_cash_value }} ج
                                        @endif
                                    </span>
                                    <span class="block text-xs text-gray-500">
                                        @if($promo->discount_type == 'percentage') نسبة @else نقدي @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Min Order -->
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-900">{{ $promo->min_order_value }} ج</span>
                            </td>

                            <!-- Expiry -->
                            <td class="px-4 py-3">
                                <div class="space-y-1">
                                    <span class="block text-sm {{ $promo->expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $promo->expiry_date->format('d/m/Y') }}
                                    </span>
                                    @if($promo->expiry_date->isPast())
                                    <span class="block text-xs text-red-500">منتهي</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    @if($promo->active)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        نشط
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        غير نشط
                                    </span>
                                    @endif

                                    @if($promo->check_offer_rate)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        مع العروض
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3">
                                <div class="flex justify-center">
                                    @can('product.edit')
                                    <a href="{{ route('promocodes.edit', $promo->id) }}"
                                       class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                        تعديل
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if($data->isEmpty())
            <div class="text-center py-12">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد أكواد خصم</h3>
                <p class="text-gray-500 mb-4">لم يتم إضافة أي أكواد خصم حتى الآن</p>
                <a href="{{ route('promocodes.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>إضافة أول كود خصم</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
