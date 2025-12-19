<nav x-data="{ open: false }" class="bg-white border-b border-emerald-100 shadow-sm">
    @php
        $plan = Auth::user()?->activePlan(); // basic|pro|null
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center gap-8">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold">
                        K
                    </div>
                    <span class="font-semibold text-lg text-slate-900">
                        Kaslyn
                    </span>
                </a>

                {{-- MENU DESKTOP --}}
                <div class="hidden sm:flex gap-6">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="text-slate-600 hover:text-emerald-700 font-medium">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link
                        :href="route('transactions.index')"
                        :active="request()->routeIs('transactions.*')"
                        class="text-slate-600 hover:text-emerald-700 font-medium">
                        Transaksi
                    </x-nav-link>

                    {{-- LANGGANAN --}}
                    <x-nav-link
                        :href="route('subscriptions.plans')"
                        :active="request()->routeIs('subscriptions.*')"
                        class="text-slate-600 hover:text-emerald-700 font-medium">
                        Langganan
                    </x-nav-link>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        {{-- ✅ Trigger + Badge Plan --}}
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 hover:bg-emerald-50 transition">
                            <div class="text-sm font-medium text-slate-700">
                                {{ Auth::user()->name }}
                            </div>

                            @if($plan)
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold
                                    {{ $plan === 'pro' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ strtoupper($plan) }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-yellow-100 text-yellow-800">
                                    FREE
                                </span>
                            @endif

                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-emerald-50">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="px-4 pt-4 pb-3 space-y-1 border-t border-emerald-100">

            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('transactions.index')"
                :active="request()->routeIs('transactions.*')">
                Transaksi
            </x-responsive-nav-link>

            {{-- LANGGANAN --}}
            <x-responsive-nav-link
                :href="route('subscriptions.plans')"
                :active="request()->routeIs('subscriptions.*')">
                Langganan
            </x-responsive-nav-link>
        </div>

        <div class="border-t border-gray-200 px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</div>

                {{-- ✅ Badge Plan Mobile --}}
                @if($plan)
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold
                        {{ $plan === 'pro' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                        {{ strtoupper($plan) }}
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-yellow-100 text-yellow-800">
                        FREE
                    </span>
                @endif
            </div>

            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
