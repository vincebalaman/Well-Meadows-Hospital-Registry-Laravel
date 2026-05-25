@php
    $navBaseClasses = 'flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200';
    $navActiveClasses = 'bg-cyan-500/15 text-white shadow-[0_18px_40px_-28px_rgba(34,211,238,0.55)]';
    $navInactiveClasses = 'text-slate-300 hover:bg-white/5 hover:text-white';
@endphp

<nav x-data="{ open: false }" class="app-sidebar">
    <div class="flex items-center justify-between px-5 py-4 lg:px-6 lg:py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400/15 text-cyan-200">
                <x-application-logo class="block h-7 w-7 fill-current" />
            </div>
            <div>
                <p class="text-[0.65rem] font-semibold uppercase tracking-[0.35em] text-cyan-200/90">Well Meadows</p>
                <p class="text-base font-semibold text-white">Hospital Registry</p>
            </div>
        </a>

        <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 p-2 text-slate-200 lg:hidden">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:block">
        <div class="px-5 pb-5 lg:px-6">
            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                <p class="text-[0.65rem] uppercase tracking-[0.35em] text-slate-400">Signed in as</p>
                <p class="mt-3 text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                <p class="text-sm text-slate-300">{{ Auth::user()->email }}</p>
                <p class="mt-3 inline-flex rounded-full bg-cyan-500/10 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-cyan-100">
                    {{ ucfirst(Auth::user()->role) }}
                </p>
            </div>
        </div>

        <div class="px-3 pb-4 lg:px-4">
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('dashboard') ? $navActiveClasses : $navInactiveClasses }}">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">⌂</span>
                    <span>{{ __('Dashboard') }}</span>
                </a>

                @if(auth()->user()->isPatient())
                    @if(auth()->user()->patient)
                        @if(auth()->user()?->patient?->id)
                            <a href="{{ route('patients.comprehensiveRecord', auth()->user()->patient->id) }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patients.comprehensiveRecord') ? $navActiveClasses : $navInactiveClasses }}">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">⟡</span>
                                <span>{{ __('My Comprehensive Record') }}</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('patients.create') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patients.create') ? $navActiveClasses : $navInactiveClasses }}">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">+</span>
                            <span>{{ __('Complete Registration') }}</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('patients.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patients.index') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">◌</span>
                        <span>{{ __('Patient Directory') }}</span>
                    </a>
                @endif

                <a href="{{ route('patientbillings.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patientbillings.*') ? $navActiveClasses : $navInactiveClasses }}">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">₿</span>
                    <span>{{ __('Patient Billings') }}</span>
                </a>

                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ route('inpatientstays.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('inpatientstays.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">✚</span>
                        <span>{{ __('Inpatient Stays') }}</span>
                    </a>
                    <a href="{{ route('beds.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('beds.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">□</span>
                        <span>{{ __('Beds') }}</span>
                    </a>
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin']))
                        <a href="{{ route('staffs.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('staffs.*') ? $navActiveClasses : $navInactiveClasses }}">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">☻</span>
                            <span>{{ __('Staff') }}</span>
                        </a>
                    @endif
                    <a href="{{ route('staff-allocations.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('staff-allocations.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">⇄</span>
                        <span>{{ __('Staff Allocations') }}</span>
                    </a>
                    <a href="{{ route('wards.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('wards.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">⌂</span>
                        <span>{{ __('Wards') }}</span>
                    </a>
                    <a href="{{ route('appointments.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('appointments.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">✦</span>
                        <span>{{ __('Appointments') }}</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="border-t border-white/10 px-3 pb-4 pt-4 lg:px-4">
            <a href="{{ route('profile.edit') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('profile.edit') ? $navActiveClasses : $navInactiveClasses }}">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">⚙</span>
                <span>{{ __('Profile') }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="{{ $navBaseClasses }} w-full text-left text-slate-300 hover:bg-rose-500/10 hover:text-rose-100">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-sm">↩</span>
                    <span>{{ __('Log Out') }}</span>
                </button>
            </form>
        </div>
    </div>
</nav>
