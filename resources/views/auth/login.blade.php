<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-lime-300 to-emerald-500 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-sm font-semibold tracking-[0.35em] text-emerald-600 uppercase">Login</h1>
                    <div class="mt-2 h-[2px] w-14 bg-emerald-500 mx-auto rounded"></div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="text-sm text-slate-600">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               placeholder="you@example.com"
                               class="mt-2 w-full border-0 border-b-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 px-0 bg-transparent">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="text-sm text-slate-600">Password</label>
                        <input id="password" name="password" type="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="mt-2 w-full border-0 border-b-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 px-0 bg-transparent">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500"
                                   name="remember">
                            <span>Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-emerald-700 hover:underline" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-full bg-gradient-to-r from-emerald-500 to-lime-500 text-white font-semibold shadow-lg shadow-emerald-500/30 hover:opacity-95 transition">
                        Login
                    </button>

                    <p class="text-center text-sm text-slate-600 pt-2">
                        Don’t have account yet?
                        <a href="{{ route('register') }}" class="text-emerald-700 hover:underline font-semibold">Register</a>
                    </p>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">or</span>
                    </div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('auth.google') }}"
                   class="w-full flex items-center justify-center py-3 px-4 rounded-full border border-gray-300 bg-white text-gray-700 font-semibold shadow hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Login with Google
                </a>
            </div>

            <p class="text-center text-xs text-white/90 mt-6">
                © {{ date('Y') }} Kaslyn. All rights reserved.
            </p>
        </div>
    </div>
</x-guest-layout>
