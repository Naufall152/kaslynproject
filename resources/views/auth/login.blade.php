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
            </div>

            <p class="text-center text-xs text-white/90 mt-6">
                © {{ date('Y') }} Kaslyn. All rights reserved.
            </p>
        </div>
    </div>
</x-guest-layout>
