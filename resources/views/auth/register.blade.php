<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-lime-300 to-emerald-500 px-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-sm font-semibold tracking-[0.35em] text-emerald-600 uppercase">Register</h1>
                    <div class="mt-2 h-[2px] w-14 bg-emerald-500 mx-auto rounded"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="text-sm text-slate-600">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                               required autofocus autocomplete="name"
                               placeholder="Nama UKM / Pemilik"
                               class="mt-2 w-full border-0 border-b-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 px-0 bg-transparent">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="text-sm text-slate-600">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               required autocomplete="username"
                               placeholder="you@example.com"
                               class="mt-2 w-full border-0 border-b-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 px-0 bg-transparent">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="text-sm text-slate-600">Password</label>
                        <input id="password" name="password" type="password"
                               required autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="mt-2 w-full border-0 border-b-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 px-0 bg-transparent">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-sm text-slate-600">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               required autocomplete="new-password"
                               placeholder="Ulangi password"
                               class="mt-2 w-full border-0 border-b-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 px-0 bg-transparent">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-full bg-gradient-to-r from-emerald-500 to-lime-500 text-white font-semibold shadow-lg shadow-emerald-500/30 hover:opacity-95 transition">
                        Register
                    </button>

                    <p class="text-center text-sm text-slate-600 pt-2">
                        Already registered?
                        <a href="{{ route('login') }}" class="text-emerald-700 hover:underline font-semibold">Login</a>
                    </p>
                </form>
            </div>

            <p class="text-center text-xs text-white/90 mt-6">
                © {{ date('Y') }} Kaslyn. All rights reserved.
            </p>
        </div>
    </div>
</x-guest-layout>
