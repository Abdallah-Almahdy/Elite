<x-guest-layout>
    <div class=" flex items-center justify-center bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200">
        <div
            class="w-full max-w-md bg-white/80 backdrop-blur-md shadow-2xl rounded-2xl p-8 transition-all duration-300 hover:shadow-blue-200">
            <div class="text-center mb-6 " style="display: flex;justify-content: center;align-items: center;flex-direction: column;margin">
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">تسجيل الدخول إلى حسابك</h2>
                <p class="text-gray-500 text-sm mt-1">مرحبًا بعودتك 👋</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center text-green-600 font-medium" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" dir="rtl">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-1">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring focus:ring-blue-200 transition-all p-2.5 text-right" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-1">كلمة المرور</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring focus:ring-blue-200 transition-all p-2.5 text-right" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 transition-all focus:ring-2 focus:ring-blue-300">
                        تسجيل الدخول
                    </button>
                </div>

                <!-- Optional Links -->
                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">
                            إنشاء حساب جديد
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
