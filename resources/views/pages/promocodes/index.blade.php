@extends('admin.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-6 px-4">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">إدارة أكواد الخصم</h1>
                <p class="text-gray-600">إدارة وعرض جميع أكواد الخصم المتاحة في النظام</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <button class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>تقرير الأكواد</span>
                </button>

                <a href="{{ route('promocodes.create') }}" class="flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>إضافة كود جديد</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">إجمالي الأكواد</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $data->count() }}</h3>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">أكواد نشطة</p>
                        <h3 class="text-2xl font-bold text-green-600">{{ $data->where('active', true)->count() }}</h3>
                    </div>
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">أكواد منتهية</p>
                        <h3 class="text-2xl font-bold text-red-600">{{ $data->where('active', false)->count() }}</h3>
                    </div>
                    <div class="p-3 bg-red-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">متوسط الخصم</p>
                        <h3 class="text-2xl font-bold text-purple-600">25%</h3>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-xl font-semibold text-gray-800">قائمة أكواد الخصم</h2>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" placeholder="بحث في الأكواد..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الكود</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">نوع الكود</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">المستخدم</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">العدد</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">المتبقي</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الحد الأدنى</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">نوع الخصم</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">قيمة الخصم</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الانتهاء</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الحالة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">مع العروض</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($data as $product)
                        <tr class="hover:bg-blue-50/30 transition-colors duration-200 group">
                            <!-- Code -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded-lg text-sm">{{ $product->code }}</span>
                                </div>
                            </td>

                            <!-- Type -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium text-gray-800">
                                        @if($product->promo_cat == 'user') مستخدم واحد @else مستخدمين متعددين @endif
                                    </span>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        @if($product->type == 'limited') محدود @else غير محدود @endif
                                    </span>
                                </div>
                            </td>

                            <!-- User -->
                            <td class="px-6 py-4">
                                @if($product->promo_cat == 'all')
                                <span class="text-gray-400">-</span>
                                @else
                                <span class="font-medium text-gray-800">{{ str_replace(',', ' ', $product->user->name) }}</span>
                                @endif
                            </td>

                            <!-- Counts -->
                            <td class="px-6 py-4">
                                @if($product->promo_cat == 'user')
                                <span class="text-gray-400">-</span>
                                @else
                                <span class="font-medium">{{ $product->users_limit ?? '-' }}</span>
                                @endif
                            </td>

                            <!-- Remaining -->
                            <td class="px-6 py-4">
                                @if($product->promo_cat == 'user')
                                <span class="text-gray-400">-</span>
                                @else
                                <span class="font-medium {{ $product->available_codes <= 2 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $product->available_codes ?? '-' }}
                                </span>
                                @endif
                            </td>

                            <!-- Min Order -->
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ $product->min_order_value }} ج</span>
                            </td>

                            <!-- Discount Type -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                    {{ $product->discount_type == 'percentage' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }}">
                                    @if($product->discount_type == 'percentage')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    نسبة
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    نقدي
                                    @endif
                                </span>
                            </td>

                            <!-- Discount Value -->
                            <td class="px-6 py-4">
                                <span class="font-bold text-lg
                                    {{ $product->discount_type == 'percentage' ? 'text-purple-600' : 'text-orange-600' }}">
                                    @if($product->discount_type == 'percentage')
                                    {{ $product->discount_percentage_value }}%
                                    @else
                                    {{ $product->discount_cash_value }} ج
                                    @endif
                                </span>
                            </td>

                            <!-- Expiry -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium {{ $product->expiry_date->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $product->expiry_date->format('d-m-Y') }}
                                    </span>
                                    @if($product->expiry_date->isPast())
                                    <span class="text-xs text-red-500">منتهي</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <div class="relative group">
                                        @if(!$product->active)
                                        <div class="flex items-center gap-2 px-3 py-1 bg-red-100 rounded-full">
                                            <span class="relative flex size-2">
                                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                                <span class="relative inline-flex size-2 rounded-full bg-red-500"></span>
                                            </span>
                                            <span class="text-sm text-red-700">غير نشط</span>
                                        </div>
                                        @else
                                        <div class="flex items-center gap-2 px-3 py-1 bg-green-100 rounded-full">
                                            <span class="relative flex size-2">
                                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex size-2 rounded-full bg-green-500"></span>
                                            </span>
                                            <span class="text-sm text-green-700">نشط</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Offer Status -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @if(!$product->check_offer_rate)
                                    <div class="flex items-center gap-2 px-3 py-1 bg-red-100 rounded-full">
                                        <span class="relative flex size-2">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex size-2 rounded-full bg-red-500"></span>
                                        </span>
                                        <span class="text-sm text-red-700">غير مفعل</span>
                                    </div>
                                    @else
                                    <div class="flex items-center gap-2 px-3 py-1 bg-green-100 rounded-full">
                                        <span class="relative flex size-2">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex size-2 rounded-full bg-green-500"></span>
                                        </span>
                                        <span class="text-sm text-green-700">مفعل</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                @can('product.edit')
                                <div class="flex justify-center">
                                    <a href="{{ route('promocodes.edit', $product->id) }}"
                                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                        <span>تعديل</span>
                                    </a>
                                </div>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            @if($data->isEmpty())
            <div class="text-center py-12">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد أكواد خصم</h3>
                <p class="text-gray-500 mb-6">لم يتم إضافة أي أكواد خصم حتى الآن</p>
                <a href="{{ route('promocodes.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
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

@section('styles')
<style>
    .animate-ping {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    table {
        border-spacing: 0;
    }

    tbody tr:last-child {
        border-bottom: 0;
    }
</style>
@endsection
@endsection
