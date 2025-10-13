@extends('admin.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-8 px-4">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">إدارة الشكاوي</h1>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-red-500 to-orange-600 rounded-full"></div>
            </div>
            <p class="text-gray-600 text-lg">عرض ومتابعة جميع شكاوي المستخدمين</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">إجمالي الشكاوي</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $complaints->count() }}</h3>
                    </div>
                    <div class="p-3 bg-red-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">شكاوي هذا الشهر</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ $complaints->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">شكاوي اليوم</p>
                        <h3 class="text-2xl font-bold text-green-600">{{ $complaints->where('created_at', '>=', today())->count() }}</h3>
                    </div>
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">مستخدمين نشطين</p>
                        <h3 class="text-2xl font-bold text-purple-600">{{ $complaints->groupBy('user_id')->count() }}</h3>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if($complaints->count() > 0)
        <!-- Main Table Card -->
       

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">#</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">المستخدم</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">السبب</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الرسالة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الحالة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">تاريخ الإنشاء</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($complaints as $complaint)
                        <tr class="hover:bg-red-50/30 transition-colors duration-200 group">
                            <!-- Number -->
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-gray-600">{{ $loop->iteration }}</span>
                            </td>

                            <!-- User -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($complaint->user ? $complaint->user->name : 'محذوف', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            {{ $complaint->user ? $complaint->user->name : 'مستخدم محذوف' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $complaint->user ? $complaint->user->email : 'بريد غير متوفر' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Reason -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    {{ $complaint->reason_id }}
                                </span>
                            </td>

                            <!-- Message -->
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-gray-700 line-clamp-2">{{ $complaint->message }}</p>
                                    <button class="text-blue-600 hover:text-blue-800 text-sm mt-1"
                                            onclick="showFullMessage('{{ $complaint->message }}')">
                                        عرض المزيد
                                    </button>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                    </span>
                                    قيد المراجعة
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">
                                        {{ $complaint->created_at->format('Y-m-d') }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $complaint->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex gap-2 justify-center">
                                    <button class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-105"
                                            onclick="showComplaintDetails({{ $complaint }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p class="text-sm text-gray-600">
                        عرض <span class="font-medium">{{ $complaints->count() }}</span> من <span class="font-medium">{{ $complaints->count() }}</span> شكوى
                    </p>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            السابق
                        </button>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            التالي
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div class="absolute -inset-4 bg-green-100 rounded-full blur-lg opacity-50"></div>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد شكاوي</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">لم يتم تقديم أي شكاوي حتى الآن. سيظهر هنا أي شكوى يقدمها المستخدمون.</p>

            <div class="flex justify-center gap-3">
                <button class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    تحديث الصفحة
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Script -->
@section('scripts')
<script>
function showFullMessage(message) {
    // يمكنك استخدام SweetAlert أو أي مكتبة modal هنا
    alert('الرسالة الكاملة:\n\n' + message);
}

function showComplaintDetails(complaint) {
    // عرض تفاصيل الشكوى في modal
    const details = `
        المستخدم: ${complaint.user ? complaint.user.name : 'مستخدم محذوف'}
        السبب: ${complaint.reason_id}
        الرسالة: ${complaint.message}
        التاريخ: ${complaint.created_at}
    `;
    alert('تفاصيل الشكوى:\n\n' + details);
}
</script>
@endsection
@section('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.animate-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style>
@endsection
@endsection
